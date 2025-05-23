<?php

namespace App\Http\Controllers\Api;

use App\Models\ClassroomAssignment;
use App\Models\Classroom;
use App\Models\ClassroomSubmission;
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

class ClassroomAssignmentController extends Controller
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
     *     path="/api/classrooms/{classroom_id}/assignments",
     *     summary="Get all assignments for a classroom with pagination",
     *     tags={"Classroom Assignments"},
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
     *         description="Search by assignment title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="due_date_from",
     *         in="query",
     *         description="Filter by due date (from)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="due_date_to",
     *         in="query",
     *         description="Filter by due date (to)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="create_by",
     *         in="query",
     *         description="Filter by creator ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="List of classroom assignments"),
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
        $query = ClassroomAssignment::where('classroom_id', $classroomId)
                                  ->whereNull('delete_at');

        // Filter by creator
        if ($request->filled('create_by')) {
            $query->where('create_by', $request->create_by);
        }

        // Filter by due date range
        if ($request->filled('due_date_from')) {
            $query->where('due_date', '>=', $request->due_date_from);
        }

        if ($request->filled('due_date_to')) {
            $query->where('due_date', '<=', $request->due_date_to . ' 23:59:59');
        }

        // Search by title
        if ($request->filled('_search')) {
            $searchTerm = $request->_search;
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $assignments = $query->with('creator')
                           ->orderBy('due_date', 'asc')
                           ->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $assignments->items(),
                'meta' => [
                    'current_page' => $assignments->currentPage(),
                    'last_page' => $assignments->lastPage(),
                    'per_page' => $assignments->perPage(),
                    'total' => $assignments->total(),
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        return view('classroom_assignments.index', compact('assignments', 'classroom'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/assignments",
     *     summary="Create a new classroom assignment",
     *     tags={"Classroom Assignments"},
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
     *                 required={"title", "description", "due_date"},
     *                 @OA\Property(property="title", type="string", description="Assignment title"),
     *                 @OA\Property(property="description", type="string", description="Assignment description"),
     *                 @OA\Property(property="due_date", type="string", format="date-time", description="Assignment due date"),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true, description="Assignment file")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Assignment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Assignment created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=403, description="Not authorized to create assignments"),
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
            return response()->json(['message' => 'You are not authorized to create assignments in this classroom'], Response::HTTP_FORBIDDEN);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:now',
            'file' => 'nullable|file|max:20480', // 20MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $assignment = new ClassroomAssignment([
                'classroom_id' => $classroomId,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'create_by' => $userId,
                'create_at' => now(),
                'update_at' => now(),
            ]);

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalFileName = $file->getClientOriginalName();
                
                // Store in a directory structure that prevents filename collisions
                $filePath = $file->storeAs(
                    "classroom_assignments/{$classroomId}", 
                    $originalFileName, 
                    'public'
                );
                
                $assignment->file = $filePath;
            }

            $assignment->save();
            
            DB::commit();
            return response()->json(['message' => 'Assignment created successfully', 'data' => $assignment], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating classroom assignment: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating classroom assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/{classroom_id}/assignments/{id}",
     *     tags={"Classroom Assignments"},
     *     summary="Get specific classroom assignment by ID",
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
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Assignment not found"
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

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $id)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->with(['creator', 'submissions'])
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Get current user's submission if exists
        $userSubmission = $assignment->submissions()
                                   ->where('user_id', $userId)
                                   ->first();

        // If this is an API request
        if (request()->is('api/*')) {
            $response = $assignment->toArray();
            $response['user_submission'] = $userSubmission;
            return response()->json($response, Response::HTTP_OK);
        }

        // For web view
        return view('classroom_assignments.show', compact('assignment', 'classroom', 'userSubmission'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/assignments/{id}",
     *     tags={"Classroom Assignments"},
     *     summary="Update an existing classroom assignment",
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
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="due_date", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Assignment updated successfully"),
     *     @OA\Response(response=404, description="Assignment not found"),
     *     @OA\Response(response=403, description="Not authorized to update"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $classroomId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $id)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is the assignment creator or a teacher
        $userId = Auth::id();
        $canUpdate = $assignment->create_by == $userId || 
                     $classroom->create_by == $userId ||
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();
        
        if (!$canUpdate) {
            return response()->json(['message' => 'You are not authorized to update this assignment'], Response::HTTP_FORBIDDEN);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'file' => 'nullable|file|max:20480', // 20MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $updateData = [];
            
            // Update basic fields if provided
            foreach(['title', 'description', 'due_date'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            // Always update the update_at timestamp
            $updateData['update_at'] = now();
            
            // Handle file upload if provided
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($assignment->file) {
                    Storage::disk('public')->delete($assignment->file);
                }
                
                $file = $request->file('file');
                $timestamp = Carbon::now()->format('Y-m-d_His');
                $fileName = "assignment_{$classroomId}_{$id}_{$timestamp}." . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('classroom_assignments', $fileName, 'public');
                $updateData['file'] = $filePath;
            }

            // Update the assignment
            $assignment->update($updateData);
            
            DB::commit();
            return response()->json(['message' => 'Assignment updated successfully', 'data' => $assignment], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating classroom assignment: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating classroom assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/classrooms/{classroom_id}/assignments/{id}",
     *     tags={"Classroom Assignments"},
     *     summary="Soft delete a classroom assignment",
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
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Assignment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Assignment not found"
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

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $id)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is the assignment creator or a teacher
        $userId = Auth::id();
        $canDelete = $assignment->create_by == $userId || 
                     $classroom->create_by == $userId ||
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();
        
        if (!$canDelete) {
            return response()->json(['message' => 'You are not authorized to delete this assignment'], Response::HTTP_FORBIDDEN);
        }

        // Soft delete the assignment
        $assignment->update(['delete_at' => now()]);
        
        return response()->json(['message' => 'Assignment deleted successfully'], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/assignments/{id}/restore",
     *     tags={"Classroom Assignments"},
     *     summary="Restore a soft-deleted classroom assignment",
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
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Assignment restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Assignment not found"
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

        // Find the soft-deleted assignment
        $assignment = ClassroomAssignment::where('id', $id)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNotNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Deleted assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a teacher
        $userId = Auth::id();
        $canRestore = $classroom->create_by == $userId ||
                      $classroom->members()
                              ->where('user_id', $userId)
                              ->where('role', 'teacher')
                              ->exists();
        
        if (!$canRestore) {
            return response()->json(['message' => 'You are not authorized to restore this assignment'], Response::HTTP_FORBIDDEN);
        }

        // Restore the assignment
        $assignment->update(['delete_at' => null]);
        
        return response()->json(['message' => 'Assignment restored successfully', 'data' => $assignment], Response::HTTP_OK);
    }

    /**
     * Display assignments for a student
     */
    public function indexForStudent($classroomId)
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
        
        // Get assignments
        $assignments = ClassroomAssignment::where('classroom_id', $classroomId)
                                        ->whereNull('delete_at')
                                        ->with('creator')
                                        ->orderBy('due_date', 'asc')
                                        ->get();
        
        // For each assignment, get the user's submission
        foreach ($assignments as $assignment) {
            $submission = ClassroomSubmission::where('assignment_id', $assignment->id)
                                        ->where('user_id', $userId)
                                        ->first();
            
            $assignment->user_submission = $submission;
        }
        
        return view('student.classrooms.assignments', compact('classroom', 'assignments'));
    }

    /**
     * Show assignment details for a student
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
        
        // Get the assignment
        $assignment = ClassroomAssignment::where('id', $id)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->with('creator')
                                       ->firstOrFail();
        
        // Get user's submission
        $userSubmission = ClassroomSubmission::where('assignment_id', $assignment->id)
                                          ->where('user_id', $userId)
                                          ->first();
        
        return view('student.classrooms.assignment-show', compact('classroom', 'assignment', 'userSubmission'));
    }

    /**
     * Download assignment file for a student
     *
     * @param int $classroomId
     * @param int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadForStudent($classroomId, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return redirect()->route('student.classrooms.index')
                ->with('error', 'Classroom not found');
        }

        // Verify user is a member of this classroom
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            return redirect()->route('student.classrooms.index')
                ->with('error', 'You are not a member of this classroom');
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $id)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return redirect()->route('student.classrooms.assignments.index', $classroomId)
                ->with('error', 'Assignment not found');
        }

        // Check if assignment has a file
        if (!$assignment->file || !Storage::disk('public')->exists($assignment->file)) {
            return redirect()->route('student.classrooms.assignments.show', [$classroomId, $id])
                ->with('error', 'No file available for download');
        }

        // Return the file as a download
        $fileName = basename($assignment->file);
        $path = Storage::disk('public')->path($assignment->file);
        
        return response()->download($path, $fileName);
    }
}