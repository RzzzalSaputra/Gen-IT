<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Submission;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class SubmissionController extends Controller
{
    public function __construct()
    {
        // Only set the Accept header for API requests
        if (request()->is('api/*')) {
            request()->headers->set('Accept', 'application/json');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/submissions",
     *     summary="Get all submissions with pagination",
     *     tags={"Submissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="_page",
     *         in="query",
     *         description="Current page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="_limit",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="_search",
     *         in="query",
     *         description="Search by title or content",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by submission type",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by submission status",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="List of submissions with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request): JsonResponse|View
    {
        // For API requests
        if (request()->is('api/*')) {
            $query = Submission::query();

            // Filter berdasarkan status dan type
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Pencarian berdasarkan title atau content
            if ($request->has('_search')) {
                $search = $request->_search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('content', 'like', "%$search%");
                });
            }

            // Pagination
            $perPage = $request->_limit ?? 10;
            $submissions = $query->paginate($perPage);

            return response()->json($submissions);
        }
        
        // For web requests
        $submissions = Submission::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('submission.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get submission types from options table - using 'type' instead of 'group'
        $types = Option::where('type', 'submission_type')->get();
        
        return view('submission.create', compact('types'));
    }

    /**
     * @OA\Post(
     *     path="/api/submissions",
     *     summary="Store a newly created submission",
     *     tags={"Submissions"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "type"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="link", type="string"),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="type", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Submission created successfully"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Error creating submission")
     * )
     */

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
        'file'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        'link'    => 'nullable|string|url',
        'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'type'    => 'required|exists:options,id',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();
    try {
        // Get the submission type option based on what was uploaded
        $submissionType = null;
        
        if ($request->hasFile('file') || $request->hasFile('img')) {
            // If document or image is uploaded, use 'file' type
            $submissionType = Option::where('type', 'submission_type')
                ->where('value', 'file')
                ->first();
        } else if ($request->filled('link') && str_contains(strtolower($request->link), 'youtube')) {
            // If it's a YouTube link, use 'video' type
            $submissionType = Option::where('type', 'submission_type')
                ->where('value', 'video')
                ->first();
        } else {
            // Otherwise use 'text' type
            $submissionType = Option::where('type', 'submission_type')
                ->where('value', 'text')
                ->first();
        }
        
        if (!$submissionType) {
            return redirect()->back()->with('error', 'Submission type not found in options.')->withInput();
        }

        // Get pending status
        $pendingStatus = Option::where('value', 'pending')
            ->where('type', 'submission_status')
            ->first();
        
        if (!$pendingStatus) {
            return redirect()->back()->with('error', 'Status "pending" not found in options.')->withInput();
        }

        $submission = Submission::create([
            'title'      => $request->title,
            'content'    => $request->content,
            'file'       => null,
            'link'       => $request->link,
            'img'        => null,
            'type'       => $submissionType->id, // Use the determined submission type
            'status'     => $pendingStatus->id,
            'created_by' => Auth::id(),
        ]);

        $timestamp = now()->format('Ymd_His');
        $random = mt_rand(100, 999);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('submissions/files', $filename, 'public');  // Menyimpan file di public storage
                $submission->file = 'submissions/files/' . $filename;  // Path relatif untuk disimpan ke DB
            }

            if ($request->hasFile('img')) {
                $img = $request->file('img');
                $originalName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $imgExt = $img->getClientOriginalExtension();
                $imgName = "{$random}_{$slugName}_{$timestamp}.{$imgExt}";
                $imgPath = $img->storeAs('submissions/images', $imgName, 'public');  // Menyimpan gambar di public storage
                $submission->img = 'submissions/images/' . $imgName;  // Path relatif untuk disimpan ke DB
            }

            $submission->save();
        DB::commit();
        
        return redirect()->route('submissions.index')->with('success', 'Your submission has been sent for approval.');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Error creating submission: ' . $e->getMessage())->withInput();
    }
}

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *     path="/api/submissions/{id}",
     *     summary="Get submission by ID",
     *     tags={"Submissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Submission details"),
     *     @OA\Response(response=404, description="Submission not found")
     * )
     */
    public function show($id)
    {
        $submission = Submission::findOrFail($id);
        
        if (request()->is('api/*')) {
            return response()->json(['data' => $submission]);
        }
        
        // For web view - only allow creators to view their own submissions
        if ($submission->created_by !== Auth::id()) {
            return redirect()->route('submissions.index')->with('error', 'You do not have permission to view this submission.');
        }
        
        return view('submission.show', compact('submission'));
    }

    /**
     * @OA\Post(
     *     path="/api/submissions/{id}",
     *     tags={"Submissions"},
     *     summary="Update an existing submission",
     *     description="Update a submission with optional file and image uploads.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", nullable=true),
     *                 @OA\Property(property="content", type="string", nullable=true),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="type", type="integer", nullable=true),
     *                 @OA\Property(property="status", type="integer", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Submission updated successfully"),
     *     @OA\Response(response=404, description="Submission not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'link'    => 'nullable|string',
            'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type'    => 'nullable|integer|exists:options,id',
            'status'  => 'nullable|integer|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $submission = Submission::find($id);
            if (!$submission) {
                return response()->json(['message' => 'Submission not found'], 404);
            }

            $submission->update([
                'title'      => $request->title ?? $submission->title,
                'content'    => $request->content ?? $submission->content,
                'link'       => $request->link ?? $submission->link,
                'type'       => $request->type ?? $submission->type,
                'status'     => $request->status ?? $submission->status,
            ]);

            $timestamp = now()->format('Ymd_His');
            $random = mt_rand(100, 999);

            // Handle file upload
            if ($request->hasFile('file')) {
                if (!empty($submission->file)) {
                    $oldFilePath = storage_path('app/public/' . $submission->file);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file = $request->file('file');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('gallery', $filename, 'public');
                $submission->update(['file' => 'gallery/' . $filename]);
            }

            // Handle image upload
            if ($request->hasFile('img')) {
                if (!empty($submission->img)) {
                    $oldImagePath = storage_path('app/public/' . $submission->img);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('img');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $imgExt = $image->getClientOriginalExtension();
                $imageName = "{$random}_{$slugName}_{$timestamp}.{$imgExt}";
                $imagePath = $image->storeAs('gallery', $imageName, 'public');
                $submission->update(['img' => 'gallery/' . $imageName]);
            }

            DB::commit();
            return response()->json(['message' => 'Submission updated successfully', 'data' => $submission], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating submission', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/submissions/{id}/approve",
     *     summary="Approve a submission",
     *     tags={"Submissions"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Submission approved successfully"),
     *     @OA\Response(response=404, description="Submission not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateApproval($id)
    {
        DB::beginTransaction();
        try {
            $submission = Submission::find($id);
            if (!$submission) {
                return response()->json(['message' => 'Submission not found'], 404);
            }

            $submission->update([
                'approve_at' => now(),
                'approve_by' => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Submission approved successfully', 'data' => $submission], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error approving submission', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/submissions/{id}",
     *     summary="Delete a submission",
     *     tags={"Submissions"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Submission deleted successfully"),
     *     @OA\Response(response=404, description="Submission not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $submission = Submission::find($id);
            if (!$submission) {
                return response()->json(['message' => 'Submission not found'], 404);
            }

            $submission->delete();
            DB::commit();
            return response()->json(['message' => 'Submission deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error deleting submission', 'error' => $e->getMessage()], 500);
        }
    }

    public function publicIndex(Request $request)
    {
        $query = Submission::whereHas('statusOption', function ($query) {
            $query->where('value', 'accepted');
        });
        
        // Handle search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('content', 'like', "%$search%");
            });
        }

        $submissions = $query->orderBy('created_at', 'desc')
                            ->paginate(10);

        // Process submissions to add file preview information
        foreach ($submissions as $submission) {
            // Check if submission has a file
            if ($submission->file) {
                $fileExtension = strtolower(pathinfo($submission->file, PATHINFO_EXTENSION));
                
                // Determine if file is previewable
                $submission->filePreviewable = in_array($fileExtension, ['pdf', 'doc', 'docx']);
                
                // Add file extension info for UI display
                $submission->fileExtension = strtoupper($fileExtension);
                $submission->fileName = basename($submission->file);
                
                // Add file type category for display logic
                if (in_array($fileExtension, ['pdf'])) {
                    $submission->fileType = 'pdf';
                } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                    $submission->fileType = 'word';
                } else {
                    $submission->fileType = 'other';
                }
            }
            
            // Check if submission has a YouTube video link
            if ($submission->link && str_contains(strtolower($submission->link), 'youtube')) {
                if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $submission->link, $match)) {
                    $submission->videoId = $match[1];
                }
            }
            
            // Set default icon for submissions without image
            if (!$submission->img) {
                // Determine placeholder icon based on submission type
                if ($submission->file) {
                    $fileExtension = strtolower(pathinfo($submission->file, PATHINFO_EXTENSION));
                    if (in_array($fileExtension, ['pdf'])) {
                        $submission->placeholderIcon = 'document-pdf';
                    } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                        $submission->placeholderIcon = 'document-word';
                    } else {
                        $submission->placeholderIcon = 'document-generic';
                    }
                } elseif ($submission->link && isset($submission->videoId)) {
                    $submission->placeholderIcon = 'video';
                } else {
                    $submission->placeholderIcon = 'text';
                }
            }
        }

        return view('submission.public', compact('submissions'));
    }
}