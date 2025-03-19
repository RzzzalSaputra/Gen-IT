<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class PreviewController extends Controller
{
    public function preview($id)
    {
        try {
            $submission = Submission::findOrFail($id);
            
            // Clean the file path
            $filePath = str_replace('storage/', '', $submission->file);
            $filePath = str_replace('/storage/', '', $filePath);
            $filePath = ltrim($filePath, '/');
            
            // More thorough check for file existence
            if (!Storage::disk('public')->exists($filePath)) {
                Log::warning('File not found for preview', ['path' => $filePath, 'submission_id' => $id]);
                abort(404, 'File not found or inaccessible');
            }
            
            $fullPath = Storage::disk('public')->path($filePath);
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            // Handle specific file types
            switch ($extension) {
                case 'docx':
                    return $this->convertDocxToPdf($fullPath);
                    
                case 'pdf':
                    return response()->file($fullPath, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
                    ]);
                
                // Add more file types if needed
                
                default:
                    // For other file types, check if they are viewable in browser
                    $mimeType = Storage::mimeType('public/' . $filePath);
                    $isBrowserViewable = in_array($mimeType, [
                        'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml',
                        'text/plain', 'text/html'
                    ]);
                    
                    if ($isBrowserViewable) {
                        return response()->file($fullPath, [
                            'Content-Type' => $mimeType,
                            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
                        ]);
                    }
                    
                    // For non-viewable files, offer download instead
                    return response()->download($fullPath, basename($filePath), [
                        'Content-Type' => $mimeType
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Preview error:', [
                'submission_id' => $id, 
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Could not generate preview: ' . $e->getMessage());
        }
    }

    private function convertDocxToPdf($inputFile)
    {
        try {
            // Create unique ID for temporary files
            $uniqueId = uniqid();
            
            // Ensure temp directory exists with proper permissions
            $tempDir = storage_path('app/public/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Set paths for temporary files
            $htmlPath = "{$tempDir}/{$uniqueId}.html";
            
            // Direct conversion using DomPDF instead of PhpWord's PDF renderer
            try {
                // Load DOCX file
                $phpWord = IOFactory::load($inputFile);
                
                // Save as HTML first with styling
                $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
                $htmlWriter->save($htmlPath);
                
                // Check if HTML was created successfully
                if (!file_exists($htmlPath)) {
                    throw new \Exception('Failed to create HTML from DOCX');
                }
                
                // Get HTML content with better encoding handling
                $html = file_get_contents($htmlPath);
                if (!$html) {
                    throw new \Exception('Empty HTML content generated');
                }
                
                // Apply additional styling for better rendering
                $styledHtml = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><style>
                    body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.5; }
                    table { border-collapse: collapse; width: 100%; margin-bottom: 10px; }
                    td, th { border: 1px solid #ddd; padding: 8px; }
                    img { max-width: 100%; height: auto; }
                    p { margin: 5px 0; }
                </style></head><body>' . $html . '</body></html>';
                
                // Use DomPDF directly with careful configuration
                $pdf = PDF::loadHTML($styledHtml);
                $pdf->setPaper('a4');
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isRemoteEnabled', true);
                $pdf->setOption('dpi', 120);  // Increase DPI for better quality
                $pdf->setOption('defaultFont', 'Arial');
                
                // Clean up temporary HTML file
                if (file_exists($htmlPath)) {
                    unlink($htmlPath);
                }
                
                // Stream the PDF with a meaningful name
                return $pdf->stream('document-preview.pdf');
                
            } catch (\Exception $innerException) {
                // Log specific error for HTML generation/PDF conversion
                Log::error('HTML/PDF processing error:', [
                    'error' => $innerException->getMessage(),
                    'trace' => $innerException->getTraceAsString()
                ]);
                
                // Instead of downloading, display original document in HTML iframe
                if (file_exists($inputFile)) {
                    // Create a temporary accessible copy in public storage
                    $tempDocxName = 'temp_' . uniqid() . '.docx';
                    $publicPath = 'public/temp/' . $tempDocxName;
                    
                    // Copy original file to public storage for direct access
                    Storage::put($publicPath, file_get_contents($inputFile));
                    
                    // Get public URL for the temporary file
                    $publicUrl = Storage::url($publicPath);
                    
                    // Use the existing show.blade.php with fallback parameters
                    return view('preview.show', [
                        'id' => null,
                        'fallbackMode' => true,
                        'docUrl' => url($publicUrl),
                        'filename' => basename($inputFile)
                    ]);
                }
                
                throw $innerException;
            }
            
        } catch (\Exception $e) {
            Log::error('DOCX to PDF conversion error:', [
                'file' => $inputFile,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Clean up any temporary files that might have been created
            if (isset($htmlPath) && file_exists($htmlPath)) {
                unlink($htmlPath);
            }
            
            // Instead of throwing exception, show a user-friendly error page
            return response()->view('errors.conversion-failed', [
                'message' => 'Could not preview this document format'
            ], 500);
        }
    }

    public function viewPreview($id)
    {
        return view('preview.show', ['id' => $id]);
    }
}