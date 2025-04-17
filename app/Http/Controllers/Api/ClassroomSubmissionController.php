<?php

namespace App\Http\Controllers\Api;

use App\Models\ClassroomSubmission;
use App\Models\ClassroomAssignment;
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

class ClassroomSubmissionController extends Controller
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
     *     path="/api/classrooms/{classroom_id}/assignments/{assignment_id}/submissions",
     *     summary="Get all submissions for an assignment with pagination",
     *     tags={"Classroom Submissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="assignment_id",
     *         in="path",
     *         required=true,
     *         description="Assignment ID",
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
     *         name="user_id",
     *         in="query",
     *         description="Filter by user ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="graded",
     *         in="query",
     *         description="Filter by graded status (true/false)",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response=200, description="List of assignment submissions"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Assignment not found")
     * )
     */
    public function index(Request $request, $classroomId, $assignmentId)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $assignmentId)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a member of this classroom
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            return response()->json(['message' => 'You are not a member of this classroom'], Response::HTTP_FORBIDDEN);
        }

        // Check if user is a teacher (for seeing all submissions) or a student (for seeing only their own)
        $isTeacher = $classroom->create_by == $userId || 
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();

        // Build query for submissions
        $query = ClassroomSubmission::where('assignment_id', $assignmentId);

        // Students can only see their own submissions
        if (!$isTeacher) {
            $query->where('user_id', $userId);
        } else {
            // Teachers can filter by user
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        // Filter by graded status
        if ($request->has('graded')) {
            $graded = $request->boolean('graded');
            $query->where('graded', $graded);
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $submissions = $query->with('user')
                           ->orderBy('submitted_at', 'desc')
                           ->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $submissions->items(),
                'meta' => [
                    'current_page' => $submissions->currentPage(),
                    'last_page' => $submissions->lastPage(),
                    'per_page' => $submissions->perPage(),
                    'total' => $submissions->total(),
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        return view('classroom_submissions.index', compact('submissions', 'classroom', 'assignment', 'isTeacher'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/assignments/{assignment_id}/submissions",
     *     summary="Create or update a submission for an assignment",
     *     tags={"Classroom Submissions"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="assignment_id",
     *         in="path",
     *         required=true,
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"content", "file"},
     *                 @OA\Property(property="content", type="string", description="Submission content/notes"),
     *                 @OA\Property(property="file", type="string", format="binary", description="Submission file")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Submission created/updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Submission successful"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=403, description="Unauthorized or past due date"),
     *     @OA\Response(response=404, description="Assignment not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(Request $request, $classroomId, $assignmentId)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $assignmentId)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a student in this classroom
        $userId = Auth::id();
        $isStudent = $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'student')
                             ->exists();
        
        if (!$isStudent) {
            return response()->json(['message' => 'Only students can submit assignments'], Response::HTTP_FORBIDDEN);
        }

        // Check if assignment is past due date
        $now = now();
        $dueDate = Carbon::parse($assignment->due_date);
        
        if ($now->isAfter($dueDate)) {
            return response()->json(['message' => 'Assignment is past due date'], Response::HTTP_FORBIDDEN);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'file' => 'nullable|file|max:10240', // 10MB max - now optional
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            // Check if submission already exists for this user and assignment
            $submission = ClassroomSubmission::where('assignment_id', $assignmentId)
                                         ->where('user_id', $userId)
                                         ->first();
            
            $isNew = !$submission;
            
            if ($isNew) {
                // Create new submission
                $submission = new ClassroomSubmission([
                    'assignment_id' => $assignmentId,
                    'user_id' => $userId,
                    'content' => $request->content,
                    'submitted_at' => now(),
                    'graded' => false,
                ]);
            } else {
                // Update existing submission
                $submission->content = $request->content;
                $submission->submitted_at = now();
                $submission->graded = false; // Reset graded status on resubmission
                $submission->grade = null; // Clear previous grade
            }

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if (!$isNew && $submission->file) {
                    Storage::disk('public')->delete($submission->file);
                }
                
                $file = $request->file('file');
                $originalFileName = $file->getClientOriginalName();
                
                // Store in a directory structure that prevents filename collisions
                $filePath = $file->storeAs(
                    "classroom_submissions/{$assignmentId}/{$userId}", 
                    $originalFileName, 
                    'public'
                );
                
                $submission->file = $filePath;
            }

            $submission->save();
            
            DB::commit();
            
            $successMessage = $isNew ? 'Submission created successfully' : 'Submission updated successfully';
            return response()->json(['message' => $successMessage, 'data' => $submission], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating/updating submission: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error with submission',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/{classroom_id}/assignments/{assignment_id}/submissions/{id}",
     *     tags={"Classroom Submissions"},
     *     summary="Get specific submission by ID",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="assignment_id",
     *         in="path",
     *         required=true,
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Submission not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to view"
     *     )
     * )
     */
    public function show($classroomId, $assignmentId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $assignmentId)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the submission
        $submission = ClassroomSubmission::where('id', $id)
                                     ->where('assignment_id', $assignmentId)
                                     ->with('user')
                                     ->first();
        
        if (!$submission) {
            return response()->json(['message' => 'Submission not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is authorized to view this submission
        $userId = Auth::id();
        
        // Allow if:
        // 1. User is the submission owner
        // 2. User is a teacher in the classroom
        $isOwner = $submission->user_id == $userId;
        $isTeacher = $classroom->create_by == $userId || 
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();
        
        if (!$isOwner && !$isTeacher) {
            return response()->json(['message' => 'You are not authorized to view this submission'], Response::HTTP_FORBIDDEN);
        }

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json($submission, Response::HTTP_OK);
        }

        // For web view
        return view('classroom_submissions.show', compact('submission', 'classroom', 'assignment', 'isTeacher'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/assignments/{assignment_id}/submissions/{id}/grade",
     *     tags={"Classroom Submissions"},
     *     summary="Grade a submission (teachers only)",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="assignment_id",
     *         in="path",
     *         required=true,
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"grade"},
     *                 @OA\Property(property="grade", type="integer", description="Grade value (0-100)"),
     *                 @OA\Property(property="feedback", type="string", description="Feedback for the submission")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Submission graded successfully"),
     *     @OA\Response(response=404, description="Submission not found"),
     *     @OA\Response(response=403, description="Not authorized to grade"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function grade(Request $request, $classroomId, $assignmentId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $assignmentId)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the submission
        $submission = ClassroomSubmission::where('id', $id)
                                     ->where('assignment_id', $assignmentId)
                                     ->first();
        
        if (!$submission) {
            return response()->json(['message' => 'Submission not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is a teacher in this classroom
        $userId = Auth::id();
        $isTeacher = $classroom->create_by == $userId || 
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();
        
        if (!$isTeacher) {
            return response()->json(['message' => 'Only teachers can grade submissions'], Response::HTTP_FORBIDDEN);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string' // Added feedback validation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            // Update submission with grade and feedback
            $submission->update([
                'graded' => true,
                'grade' => $request->grade,
                'feedback' => $request->feedback // Save feedback
            ]);
            
            DB::commit();
            return response()->json([
                'message' => 'Submission graded successfully',
                'data' => $submission
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error grading submission',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/{classroom_id}/assignments/{assignment_id}/submissions/download/{id}",
     *     tags={"Classroom Submissions"},
     *     summary="Download a submission file",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="assignment_id",
     *         in="path",
     *         required=true,
     *         description="Assignment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="File download"),
     *     @OA\Response(response=404, description="File not found"),
     *     @OA\Response(response=403, description="Not authorized to download")
     * )
     */
    public function download($classroomId, $assignmentId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $assignmentId)
                                       ->where('classroom_id', $classroomId)
                                       ->whereNull('delete_at')
                                       ->first();
        
        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the submission
        $submission = ClassroomSubmission::where('id', $id)
                                     ->where('assignment_id', $assignmentId)
                                     ->first();
        
        if (!$submission) {
            return response()->json(['message' => 'Submission not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify user is authorized to download this file
        $userId = Auth::id();
        
        // Allow if:
        // 1. User is the submission owner
        // 2. User is a teacher in the classroom
        $isOwner = $submission->user_id == $userId;
        $isTeacher = $classroom->create_by == $userId || 
                     $classroom->members()
                             ->where('user_id', $userId)
                             ->where('role', 'teacher')
                             ->exists();
        
        if (!$isOwner && !$isTeacher) {
            return response()->json(['message' => 'You are not authorized to download this file'], Response::HTTP_FORBIDDEN);
        }

        // Check if file exists
        if (!$submission->file || !Storage::disk('public')->exists($submission->file)) {
            return response()->json(['message' => 'File not found'], Response::HTTP_NOT_FOUND);
        }

        // Download the file
        $path = Storage::disk('public')->path($submission->file);
        $fileName = basename($path);
        
        return response()->download($path, $fileName);
    }

    /**
     * Store a student's submission for an assignment.
     */
    public function storeForStudent(Request $request, $classroomId, $assignmentId)
    {
        // Log the submission attempt for debugging
        Log::info('Student submission attempt', [
            'classroom_id' => $classroomId,
            'assignment_id' => $assignmentId,
            'user_id' => Auth::id()
        ]);
        
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return back()->with('error', 'Classroom not found.');
        }

        // Find the assignment
        $assignment = ClassroomAssignment::where('id', $assignmentId)
            ->where('classroom_id', $classroomId)
            ->first();
        
        if (!$assignment) {
            return back()->with('error', 'Assignment not found.');
        }

        // Verify user is a student in this classroom
        $userId = Auth::id();
        $isStudent = $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isStudent && $classroom->create_by != $userId) {
            return back()->with('error', 'You are not authorized to submit to this assignment.');
        }

        // Check if assignment is past due date
        $now = now();
        $dueDate = Carbon::parse($assignment->due_date);
        
        if ($now->isAfter($dueDate)) {
            return back()->with('error', 'This assignment is past the due date.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // 10MB max - optional
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Check if user already has a submission
            $submission = ClassroomSubmission::where('assignment_id', $assignmentId)
                ->where('user_id', $userId)
                ->first();
            
            $isNew = !$submission;
            if ($isNew) {
                $submission = new ClassroomSubmission();
                $submission->assignment_id = $assignmentId;
                $submission->user_id = $userId;
                $successMessage = 'Assignment submitted successfully!';
            } else {
                $successMessage = 'Your submission has been updated!';
            }
            
            // Update content
            if ($request->has('content')) {
                $submission->content = $request->content;
            }
            
            // Handle file removal if requested
            if ($request->has('remove_file') && $request->remove_file == "1" && !$isNew && $submission->file) {
                Storage::disk('public')->delete($submission->file);
                $submission->file = null;
                $successMessage = 'Your submission has been updated and file was removed!';
            }
            // Handle file upload (only if not removing)
            else if ($request->hasFile('file')) {
                // Delete old file if exists
                if (!$isNew && $submission->file) {
                    Storage::disk('public')->delete($submission->file);
                }
                
                $file = $request->file('file');
                $originalFileName = $file->getClientOriginalName();
                
                // Store with original filename
                $filePath = $file->storeAs(
                    "classroom_submissions/{$assignmentId}/{$userId}", 
                    $originalFileName, 
                    'public'
                );
                
                $submission->file = $filePath;
            }
            
            $submission->submitted_at = Carbon::now();
            $submission->graded = false; // Reset graded status on resubmission
            $submission->grade = null;   // Reset grade on resubmission
            $submission->save();
            
            DB::commit();
            
            Log::info('Submission successful', [
                'submission_id' => $submission->id,
                'user_id' => $userId,
                'assignment_id' => $assignmentId,
                'file_removed' => $request->has('remove_file') && $request->remove_file == "1"
            ]);
            
            return redirect()->route('student.classrooms.assignments.show', [
                'classroom_id' => $classroomId,
                'id' => $assignmentId
            ])->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Submission error: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $userId,
                'assignment_id' => $assignmentId
            ]);
            
            return back()->with('error', 'There was a problem with your submission. Please try again.');
        }
    }

    /**
     * Show submission for a student
     */
    public function showForStudent($classroomId, $assignmentId, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $classroom = Classroom::findOrFail($classroomId);
        $assignment = ClassroomAssignment::where('id', $assignmentId)
                                       ->where('classroom_id', $classroomId)
                                       ->firstOrFail();
        
        // Get the submission
        $submission = ClassroomSubmission::where('id', $id)
                                       ->where('assignment_id', $assignmentId)
                                       ->firstOrFail();
        
        // Check if this is the user's submission
        $userId = Auth::id();
        if ($submission->user_id != $userId) {
            return redirect()->route('student.classrooms.index')
                ->with('error', 'You can only view your own submissions.');
        }
        
        // Download the file
        if (Storage::disk('public')->exists($submission->file)) {
            $path = Storage::disk('public')->path($submission->file);
            $fileName = basename($path);
            return response()->download($path, $fileName);
        }
        
        return redirect()->back()->with('error', 'File not found.');
    }

    /**
     * Download a student's submission file
     */
    public function downloadForStudent($classroomId, $assignmentId, $id)
    {
        Log::info('Student attempting to download submission', [
            'classroom_id' => $classroomId,
            'assignment_id' => $assignmentId,
            'submission_id' => $id,
            'user_id' => Auth::id()
        ]);
        
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return back()->with('error', 'Classroom not found.');
        }

        // Find the submission
        $submission = ClassroomSubmission::where('id', $id)
            ->where('assignment_id', $assignmentId)
            ->first();
        
        if (!$submission) {
            return back()->with('error', 'Submission not found.');
        }

        // Verify user is authorized to download this submission
        $userId = Auth::id();
        $isOwner = $submission->user_id == $userId;
        $isTeacher = $classroom->create_by == $userId || 
                     $classroom->teachers()->where('user_id', $userId)->exists();
        
        if (!$isOwner && !$isTeacher) {
            return back()->with('error', 'You are not authorized to download this submission.');
        }

        // Check if file exists
        if (!$submission->file || !Storage::disk('public')->exists($submission->file)) {
            return back()->with('error', 'Submission file not found.');
        }

        return Storage::disk('public')->download($submission->file);
    }
}