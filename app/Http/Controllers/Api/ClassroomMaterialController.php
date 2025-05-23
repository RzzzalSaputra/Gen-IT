<?php

namespace App\Http\Controllers\Api;

use App\Models\ClassroomMaterial;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ClassroomMaterialController extends Controller
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
     *     path="/api/classrooms/{classroom_id}/materials",
     *     summary="Get all materials for a classroom with pagination",
     *     tags={"Classroom Materials"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
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
     *         description="Search by material title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by material type",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="create_by",
     *         in="query",
     *         description="Filter by creator ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of classroom materials"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Classroom not found")
     * )
     */
    public function index(Request $request, $classroomId)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a member of this classroom
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            return response()->json(['message' => 'You are not a member of this classroom'], Response::HTTP_FORBIDDEN);
        }

        // Build query
        $query = ClassroomMaterial::where('classroom_id', $classroomId)
                                  ->whereNull('delete_at');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by creator
        if ($request->filled('create_by')) {
            $query->where('create_by', $request->create_by);
        }

        // Search by title
        if ($request->filled('_search')) {
            $searchTerm = $request->_search;
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $materials = $query->with(['creator', 'typeOption'])
                         ->orderBy('create_at', 'desc')
                         ->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $materials->items(),
                'meta' => [
                    'current_page' => $materials->currentPage(),
                    'last_page' => $materials->lastPage(),
                    'per_page' => $materials->perPage(),
                    'total' => $materials->total(),
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        $materialTypes = \App\Models\Option::where('type', 'classroom_material_type')->get();
        return view('classroom_materials.index', compact('materials', 'classroom', 'materialTypes'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/materials",
     *     summary="Create a new classroom material",
     *     tags={"Classroom Materials"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "type"},
     *                 @OA\Property(property="title", type="string", description="Material title"),
     *                 @OA\Property(property="content", type="string", description="Material content"),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true, description="Material file"),
     *                 @OA\Property(property="link", type="string", nullable=true, description="External link"),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true, description="Material image"),
     *                 @OA\Property(property="type", type="integer", description="Material type option ID")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Material created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Material created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=403, description="Not authorized to add materials"),
     *     @OA\Response(response=404, description="Classroom not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(Request $request, $classroomId)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a teacher in this classroom
        $userId = Auth::id();
        $isTeacher = $classroom->create_by == $userId || 
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();
        
        if (!$isTeacher) {
            return response()->json(['message' => 'You are not authorized to add materials to this classroom'], Response::HTTP_FORBIDDEN);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'file' => 'nullable|file|max:20480', // 20MB max
            'link' => 'nullable|string|url',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'type' => 'required|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $material = new ClassroomMaterial([
                'classroom_id' => $classroomId,
                'title' => $request->title,
                'content' => $request->content,
                'link' => $request->link,
                'type' => $request->type,
                'create_by' => $userId,
                'create_at' => now(),
                'update_at' => now(),
            ]);

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $directory = 'classroom_materials/files';
                $fileName = $this->generateUniqueFilename($directory, $originalName);
                $filePath = $file->storeAs($directory, $fileName, 'public');
                $material->file = $filePath;
            }

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $originalName = $image->getClientOriginalName();
                $directory = 'classroom_materials/images';
                $imageName = $this->generateUniqueFilename($directory, $originalName);
                $imagePath = $image->storeAs($directory, $imageName, 'public');
                $material->img = $imagePath;
            }

            $material->save();
            
            DB::commit();
            return response()->json(['message' => 'Material created successfully', 'data' => $material], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating classroom material: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating classroom material',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/{classroom_id}/materials/{id}",
     *     tags={"Classroom Materials"},
     *     summary="Get specific classroom material by ID",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Material ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Material not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to view"
     *     )
     * )
     */
    public function show($classroomId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a member of this classroom
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            return response()->json(['message' => 'You are not a member of this classroom'], Response::HTTP_FORBIDDEN);
        }

        // Find the material
        $material = ClassroomMaterial::where('id', $id)
                                   ->where('classroom_id', $classroomId)
                                   ->whereNull('delete_at')
                                   ->with(['creator', 'typeOption'])
                                   ->first();
        
        if (!$material) {
            return response()->json(['message' => 'Material not found'], Response::HTTP_NOT_FOUND);
        }

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json($material, Response::HTTP_OK);
        }

        // For web view
        return view('classroom_materials.show', compact('material', 'classroom'));
    }

    /**
     * Show material details for a student
     */
    public function showForStudent($classroomId, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $classroom = Classroom::findOrFail($classroomId);
        
        // Check if user is a member
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
                    
        if (!$isMember) {
            return redirect()->route('student.classrooms.index')
                ->with('error', 'You are not a member of this classroom.');
        }
        
        // Get the material
        $material = ClassroomMaterial::where('id', $id)
                                   ->where('classroom_id', $classroomId)
                                   ->whereNull('delete_at')
                                   ->with('creator')
                                   ->firstOrFail();
        
        return view('student.classrooms.material-show', compact('classroom', 'material'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/materials/{id}",
     *     tags={"Classroom Materials"},
     *     summary="Update an existing classroom material",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Material ID",
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
     *                 @OA\Property(property="type", type="integer", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Material updated successfully"),
     *     @OA\Response(response=404, description="Material not found"),
     *     @OA\Response(response=403, description="Not authorized to update"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $classroomId, $id)
    {
        // Find the material
        $material = ClassroomMaterial::find($id);
        if (!$material) {
            return response()->json(['message' => 'Material not found'], Response::HTTP_NOT_FOUND);
        }

        // Check classroom ownership
        $classroom = Classroom::find($classroomId);
        if (!$classroom || $material->classroom_id != $classroomId) {
            return response()->json(['message' => 'Classroom not found or material does not belong to this classroom'], Response::HTTP_NOT_FOUND);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:20480', // 20MB max
            'link' => 'nullable|string|url',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'type' => 'nullable|exists:options,id',
            'remove_file' => 'nullable|boolean',
            'remove_img' => 'nullable|boolean',
            'remove_link' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $updateData = [];
            
            // Update basic fields if provided
            if ($request->has('title')) {
                $updateData['title'] = $request->title;
            }
            
            if ($request->has('content')) {
                $updateData['content'] = $request->content;
            }
            
            if ($request->has('type')) {
                $updateData['type'] = $request->type;
            }
            
            // Always update the update_at timestamp
            $updateData['update_at'] = now();
            
            // Handle file removal/replacement
            if ($request->boolean('remove_file')) {
                if ($material->file) {
                    Storage::disk('public')->delete($material->file);
                }
                $updateData['file'] = null;
            }
            
            // Handle image removal/replacement
            if ($request->boolean('remove_img')) {
                if ($material->img) {
                    Storage::disk('public')->delete($material->img);
                }
                $updateData['img'] = null;
            }
            
            // Handle link removal
            if ($request->boolean('remove_link')) {
                $updateData['link'] = null;
            } else if ($request->has('link')) {
                $updateData['link'] = $request->link;
            }

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($material->file) {
                    Storage::disk('public')->delete($material->file);
                }
                
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $directory = 'classroom_materials/files';
                $fileName = $this->generateUniqueFilename($directory, $originalName);
                $filePath = $file->storeAs($directory, $fileName, 'public');
                $updateData['file'] = $filePath;
            }

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                // Delete old image if exists
                if ($material->img) {
                    Storage::disk('public')->delete($material->img);
                }
                
                $image = $request->file('img');
                $originalName = $image->getClientOriginalName();
                $directory = 'classroom_materials/images';
                $imageName = $this->generateUniqueFilename($directory, $originalName);
                $imagePath = $image->storeAs($directory, $imageName, 'public');
                $updateData['img'] = $imagePath;
            }

            // Update the material
            $material->update($updateData);
            
            DB::commit();
            
            // For web route, redirect back with success message
            if (!$request->expectsJson()) {
                return redirect()
                    ->route('teacher.materials.show', ['classroom_id' => $classroomId, 'id' => $id])
                    ->with('success', 'Material updated successfully');
            }
            
            return response()->json(['message' => 'Material updated successfully', 'data' => $material], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating classroom material: ' . $e->getMessage());
            
            if (!$request->expectsJson()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Error updating material: ' . $e->getMessage());
            }
            
            return response()->json([
                'message' => 'Error updating classroom material',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/classrooms/{classroom_id}/materials/{id}",
     *     tags={"Classroom Materials"},
     *     summary="Soft delete a classroom material",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Material ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Material deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Material not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to delete"
     *     )
     * )
     */
    public function destroy($classroomId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the material
        $material = ClassroomMaterial::where('id', $id)
                                   ->where('classroom_id', $classroomId)
                                   ->whereNull('delete_at')
                                   ->first();
        
        if (!$material) {
            return response()->json(['message' => 'Material not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is the material creator or a teacher
        $userId = Auth::id();
        $canDelete = $material->create_by == $userId || 
                     $classroom->create_by == $userId ||
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();
        
        if (!$canDelete) {
            return response()->json(['message' => 'You are not authorized to delete this material'], Response::HTTP_FORBIDDEN);
        }

        // Soft delete the material
        $material->update(['delete_at' => now()]);
        
        return response()->json(['message' => 'Material deleted successfully'], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/materials/{id}/restore",
     *     tags={"Classroom Materials"},
     *     summary="Restore a soft-deleted classroom material",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Material ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Material restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Material not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to restore"
     *     )
     * )
     */
    public function restore($classroomId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the soft-deleted material
        $material = ClassroomMaterial::where('id', $id)
                                   ->where('classroom_id', $classroomId)
                                   ->whereNotNull('delete_at')
                                   ->first();
        
        if (!$material) {
            return response()->json(['message' => 'Deleted material not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a teacher
        $userId = Auth::id();
        $canRestore = $classroom->create_by == $userId ||
                      $classroom->members()
                              ->where('user_id', $userId)
                              ->where('role', 'teacher')
                              ->exists();
        
        if (!$canRestore) {
            return response()->json(['message' => 'You are not authorized to restore this material'], Response::HTTP_FORBIDDEN);
        }

        // Restore the material
        $material->update(['delete_at' => null]);
        
        return response()->json(['message' => 'Material restored successfully', 'data' => $material], Response::HTTP_OK);
    }

    /**
     * Download a classroom material file
     */
    public function download($classroomId, $id)
    {
        // Find the material
        $material = ClassroomMaterial::where('id', $id)
                                   ->where('classroom_id', $classroomId)
                                   ->whereNull('delete_at')
                                   ->first();
        
        if (!$material || !$material->file) {
            return response()->json(['message' => 'File not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a member of this classroom
        $classroom = Classroom::find($classroomId);
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            return response()->json(['message' => 'You are not authorized to download this file'], Response::HTTP_FORBIDDEN);
        }

        // Ensure the file path is restricted to classroom_materials/files/
        $filePath = $material->file;
        
        // Check if path already contains classroom_materials/files/
        if (strpos($filePath, 'classroom_materials/files/') === false) {
            // Extract filename from path
            $fileName = basename($filePath);
            // Construct proper path
            $filePath = 'classroom_materials/files/' . $fileName;
        }
        
        // Construct the full path
        $fullPath = storage_path('app/public/' . $filePath);
        
        // Security check: Ensure we're not accessing files outside intended directory
        $realPath = realpath($fullPath);
        $intendedDir = realpath(storage_path('app/public/classroom_materials/files'));
        
        if (!$realPath || strpos($realPath, $intendedDir) !== 0) {
            return response()->json(['message' => 'Invalid file path'], Response::HTTP_FORBIDDEN);
        }
        
        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'File not found on server'], Response::HTTP_NOT_FOUND);
        }

        return response()->download($fullPath);
    }

    /**
     * Generate a unique filename based on the original name
     * 
     * @param string $directory The storage directory path
     * @param string $originalName The original filename
     * @return string The unique filename
     */
    private function generateUniqueFilename($directory, $originalName)
    {
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fullPath = $directory . '/' . $filename . '.' . $extension;
        
        $counter = 1;
        // Check if file exists, if so add suffix
        while (Storage::disk('public')->exists($fullPath)) {
            $fullPath = $directory . '/' . $filename . '_(' . $counter . ').' . $extension;
            $counter++;
        }
        
        return basename($fullPath);
    }
}