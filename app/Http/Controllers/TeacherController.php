<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Classroom;
use App\Models\User;
use App\Models\ClassroomAssignment; // Update this import
use App\Models\ClassroomSubmission; // Update this import
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassroomMaterialController;
use App\Http\Controllers\Api\ClassroomAssignmentController;
use App\Http\Controllers\Api\ClassroomSubmissionController;

class TeacherController extends Controller
{
    protected $classroomController;
    protected $materialController;
    protected $assignmentController;
    protected $submissionController;

    public function __construct(
        ClassroomController $classroomController,
        ClassroomMaterialController $materialController,
        ClassroomAssignmentController $assignmentController,
        ClassroomSubmissionController $submissionController
    ) {
        $this->classroomController = $classroomController;
        $this->materialController = $materialController;
        $this->assignmentController = $assignmentController;
        $this->submissionController = $submissionController;
    }

    public function dashboard()
    {
        $teacher_id = Auth::id();
        
        // Get overview data
        $classrooms = Classroom::where('create_by', $teacher_id)
            ->with(['assignments.submissions' => function($query) {
                $query->where('graded', false);
            }])
            ->get();
            
        $classroomCount = $classrooms->count();
        $studentCount = 0;
        $pendingSubmissions = 0;
        
        foreach ($classrooms as $classroom) {
            $studentCount += $classroom->members()->where('role', 'student')->count();
            
            // Count pending submissions more directly using the eager loaded data
            $classroom_pending = 0;
            foreach ($classroom->assignments as $assignment) {
                $classroom_pending += $assignment->submissions->count();
            }
            
            // Add pending submission count to each classroom object
            $classroom->pending_submissions_count = $classroom_pending;
            
            // Update total pending submissions
            $pendingSubmissions += $classroom_pending;
        }
        
        // Get recent activities
        $recentSubmissions = ClassroomSubmission::whereHas('assignment.classroom', function($query) use ($teacher_id) {
                $query->where('create_by', $teacher_id);
            })
            ->with(['user', 'assignment'])
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();
        
        return view('teacher.dashboard', compact(
            'classroomCount', 
            'studentCount', 
            'pendingSubmissions', 
            'recentSubmissions',
            'classrooms'
        ));
    }

    // Classrooms methods
    public function classrooms()
    {
        $teacher_id = Auth::id();
        $classrooms = Classroom::where('create_by', $teacher_id)->get();
        
        return view('teacher.classrooms.index', compact('classrooms'));
    }
    
    public function createClassroom()
    {
        return view('teacher.classrooms.create');
    }
    
    public function storeClassroom(Request $request)
    {
        $request->merge(['create_by' => Auth::id()]);
        $response = $this->classroomController->store($request);
        
        if ($response->getStatusCode() === 201) {
            return redirect()->route('teacher.classrooms.index')
                             ->with('success', 'Classroom created successfully');
        }
        
        return back()->withErrors(['msg' => 'Failed to create classroom']);
    }
    
    public function showClassroom($id)
    {
        $classroom = Classroom::findOrFail($id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to view this classroom.');
        }
        
        // Load the materials with filter to exclude deleted ones
        $materials = $classroom->materials()->whereNull('delete_at')->get();
        
        // Load assignments with filter to exclude deleted ones
        $assignments = $classroom->assignments()->whereNull('delete_at')->get();
        
        // Properly load members with their user relationship
        $members = $classroom->members()->with('user')->get();
        
        return view('teacher.classrooms.show', compact('classroom', 'materials', 'assignments', 'members'));
    }
    
    public function updateClassroom(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to update this classroom.');
        }
        
        // Update the classroom
        $classroom->update($request->only(['name', 'description']));
        
        return redirect()->route('teacher.classrooms.show', $classroom->id)
            ->with('success', 'Classroom updated successfully.');
    }

    public function destroyClassroom($id)
    {
        $classroom = Classroom::findOrFail($id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to delete this classroom.');
        }
        
        // Begin a database transaction
        DB::beginTransaction();
        
        try {
            // Delete all related records first
            // Members
            $classroom->members()->delete();
            
            // Materials
            $classroom->materials()->delete();
            
            // For assignments, also delete submissions first
            foreach ($classroom->assignments as $assignment) {
                $assignment->submissions()->delete();
            }
            
            // Assignments
            $classroom->assignments()->delete();
            
            // Finally delete the classroom
            $classroom->delete();
            
            DB::commit();
            
            return redirect()->route('teacher.classrooms.index')
                ->with('success', 'Classroom deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to delete classroom: ' . $e->getMessage());
        }
    }
    
    // Add other methods for assignments, materials, submissions, etc...
    
    // Example of the grading functionality
    public function gradeSubmission(Request $request, $classroom_id, $assignment_id, $id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to grade submissions in this classroom.');
        }
        
        $response = $this->submissionController->grade($request, $classroom_id, $assignment_id, $id);
        
        if ($response->getStatusCode() === 200) {
            return redirect()->route('teacher.submissions.index', [$classroom_id, $assignment_id])
                             ->with('success', 'Submission graded successfully');
        }
        
        return back()->withErrors(['msg' => 'Failed to grade submission']);
    }
    
    public function showMaterial($classroom_id, $id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        $material = $classroom->materials()->findOrFail($id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to view this material.');
        }
        
        return view('teacher.materials.show', compact('classroom', 'material'));
    }

    public function showAssignment($classroom_id, $id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to view this assignment.');
        }
        
        $assignment = $classroom->assignments()->findOrFail($id);
        
        return view('teacher.assignments.show', compact('classroom', 'assignment'));
    }
    
    // Implement other methods as needed...

    public function storeMember(Request $request, $classroom_id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to add members to this classroom.');
        }
        
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:student,teacher',
        ]);
        
        // Find the user
        $user = User::where('email', $request->email)->first();
        
        // Check if user is already a member
        if ($classroom->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this classroom.');
        }
        
        // Add the member
        $classroom->members()->create([
            'user_id' => $user->id,
            'role' => $request->role,
            'joined_at' => now(),
        ]);
        
        return back()->with('success', 'Member added successfully.')
            ->with('active_tab', 'members');
    }

    public function updateMemberRole(Request $request, $classroom_id, $id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to update member roles in this classroom.');
        }
        
        // Validate the request
        $request->validate([
            'role' => 'required|in:student,teacher',
        ]);
        
        // Update the member's role
        $classroom->members()->where('id', $id)->update([
            'role' => $request->role,
        ]);
        
        return back()->with('success', 'Member role updated successfully.');
    }

    public function destroyMember($classroom_id, $id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to remove members from this classroom.');
        }
        
        // Remove the member
        $classroom->members()->where('id', $id)->delete();
        
        return back()->with('success', 'Member removed successfully.');
    }

    public function destroyMaterial($classroom_id, $id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to delete materials in this classroom.');
        }
        
        // Soft delete the material
        $material = $classroom->materials()->findOrFail($id);
        $material->delete_at = now();
        $material->save();
        
        return redirect()->route('teacher.classrooms.show', $classroom_id)
            ->with('success', 'Material deleted successfully.');
    }

    public function destroyAssignment($classroom_id, $id)
    {
        $classroom = Classroom::findOrFail($classroom_id);
        
        // Check if the authenticated user is the teacher of this classroom
        if ($classroom->create_by !== Auth::id()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'You do not have permission to delete assignments in this classroom.');
        }
        
        // Soft delete the assignment
        $assignment = $classroom->assignments()->findOrFail($id);
        $assignment->delete_at = now();
        $assignment->save();
        
        return redirect()->route('teacher.classrooms.show', $classroom_id)
            ->with('success', 'Assignment deleted successfully.');
    }

    /**
     * Store a newly created classroom material.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $classroom_id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function storeMaterial(Request $request, $classroom_id)
    {
        Log::debug('Starting material submission', ['classroom_id' => $classroom_id, 'user_id' => Auth::id()]);
        
        try {
            $classroom = Classroom::findOrFail($classroom_id);
            
            // Check if the authenticated user is the teacher of this classroom
            if ($classroom->create_by !== Auth::id()) {
                Log::warning('Permission denied: User is not classroom creator', [
                    'user_id' => Auth::id(),
                    'classroom_id' => $classroom_id
                ]);
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'You do not have permission to add materials to this classroom.');
            }
            
            Log::debug('Permissions verified, calling material controller');
            
            // Pass both the request and classroom_id to the store method
            $response = $this->materialController->store($request, $classroom_id);
            
            Log::info('Material controller response', [
                'status_code' => $response->getStatusCode(),
                'content' => json_decode($response->getContent(), true)
            ]);
            
            if ($response->getStatusCode() === 201) {
                return redirect()->route('teacher.classrooms.show', $classroom_id)
                    ->with('success', 'Material added successfully')
                    ->with('active_tab', 'materials');
            }
            
            Log::error('Material creation failed with non-201 status', [
                'status_code' => $response->getStatusCode(),
                'response' => $response->getContent()
            ]);
            
            return back()->withErrors(['msg' => 'Failed to add material: ' . $response->getContent()]);
        } catch (\Exception $e) {
            Log::error('Exception in storeMaterial', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'classroom_id' => $classroom_id
            ]);
            
            return back()->withErrors(['msg' => 'Error processing request: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a newly created classroom assignment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $classroom_id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function storeAssignment(Request $request, $classroom_id)
    {
        Log::debug('Starting assignment creation', ['classroom_id' => $classroom_id, 'user_id' => Auth::id()]);
        
        try {
            $classroom = Classroom::findOrFail($classroom_id);
            
            // Check if the authenticated user is the teacher of this classroom
            if ($classroom->create_by !== Auth::id()) {
                Log::warning('Permission denied: User is not classroom creator', [
                    'user_id' => Auth::id(),
                    'classroom_id' => $classroom_id
                ]);
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'You do not have permission to add assignments to this classroom.');
            }
            
            Log::debug('Permissions verified, calling assignment controller');
            
            // Pass both the request and classroom_id to the store method
            $response = $this->assignmentController->store($request, $classroom_id);
            
            Log::info('Assignment controller response', [
                'status_code' => $response->getStatusCode(),
                'content' => json_decode($response->getContent(), true)
            ]);
            
            if ($response->getStatusCode() === 201) {
                return redirect()->route('teacher.classrooms.show', $classroom_id)
                    ->with('success', 'Assignment added successfully')
                    ->with('active_tab', 'assignments');
            }
            
            Log::error('Assignment creation failed with non-201 status', [
                'status_code' => $response->getStatusCode(),
                'response' => $response->getContent()
            ]);
            
            return back()->withErrors(['msg' => 'Failed to add assignment: ' . $response->getContent()]);
        } catch (\Exception $e) {
            Log::error('Exception in storeAssignment', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'classroom_id' => $classroom_id
            ]);
            
            return back()->withErrors(['msg' => 'Error processing request: ' . $e->getMessage()]);
        }
    }

    /**
     * Update an existing classroom assignment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $classroom_id
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function updateAssignment(Request $request, $classroom_id, $id)
    {
        Log::debug('Starting assignment update', ['classroom_id' => $classroom_id, 'assignment_id' => $id, 'user_id' => Auth::id()]);
        
        try {
            $classroom = Classroom::findOrFail($classroom_id);
            
            // Check if the authenticated user is the teacher of this classroom
            if ($classroom->create_by !== Auth::id()) {
                Log::warning('Permission denied: User is not classroom creator', [
                    'user_id' => Auth::id(),
                    'classroom_id' => $classroom_id
                ]);
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'You do not have permission to update assignments in this classroom.');
            }
            
            // Check if assignment exists and belongs to this classroom
            $assignment = $classroom->assignments()->findOrFail($id);
            
            Log::debug('Permissions verified, calling assignment controller');
            
            // Pass request, classroom_id, and assignment_id to the update method
            $response = $this->assignmentController->update($request, $classroom_id, $id);
            
            Log::info('Assignment controller response', [
                'status_code' => $response->getStatusCode(),
                'content' => json_decode($response->getContent(), true)
            ]);
            
            if ($response->getStatusCode() === 200) {
                return redirect()->route('teacher.assignments.show', [$classroom_id, $id])
                    ->with('success', 'Assignment updated successfully');
            }
            
            Log::error('Assignment update failed with non-200 status', [
                'status_code' => $response->getStatusCode(),
                'response' => $response->getContent()
            ]);
            
            return back()->withErrors(['msg' => 'Failed to update assignment: ' . $response->getContent()]);
        } catch (\Exception $e) {
            Log::error('Exception in updateAssignment', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'classroom_id' => $classroom_id,
                'assignment_id' => $id
            ]);
            
            return back()->withErrors(['msg' => 'Error processing request: ' . $e->getMessage()]);
        }
    }

    /**
     * Update an existing classroom material.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $classroom_id
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function updateMaterial(Request $request, $classroom_id, $id)
    {
        Log::debug('Starting material update', ['classroom_id' => $classroom_id, 'material_id' => $id, 'user_id' => Auth::id()]);
        
        try {
            $classroom = Classroom::findOrFail($classroom_id);
            
            // Check if the authenticated user is the teacher of this classroom
            if ($classroom->create_by !== Auth::id()) {
                Log::warning('Permission denied: User is not classroom creator', [
                    'user_id' => Auth::id(),
                    'classroom_id' => $classroom_id
                ]);
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'You do not have permission to update materials in this classroom.');
            }
            
            // Check if material exists, belongs to this classroom, and is not soft deleted
            $material = $classroom->materials()
                ->whereNull('delete_at')
                ->findOrFail($id);
            
            // Remove the type field from the request to ensure it doesn't get changed
            $requestData = $request->except(['type']);
            
            // Create a new request with the filtered data
            $filteredRequest = new Request($requestData);
            
            Log::debug('Permissions verified, calling material controller with filtered data', [
                'filtered_fields' => array_keys($requestData)
            ]);
            
            // Pass filtered request, classroom_id, and material_id to the update method
            $response = $this->materialController->update($filteredRequest, $classroom_id, $id);
            
            Log::info('Material controller response', [
                'status_code' => $response->getStatusCode(),
                'content' => json_decode($response->getContent(), true)
            ]);
            
            if ($response->getStatusCode() === 200) {
                return redirect()->route('teacher.materials.show', [$classroom_id, $id])
                    ->with('success', 'Material updated successfully');
            }
            
            Log::error('Material update failed with non-200 status', [
                'status_code' => $response->getStatusCode(),
                'response' => $response->getContent()
            ]);
            
            return back()->withErrors(['msg' => 'Failed to update material: ' . $response->getContent()]);
        } catch (\Exception $e) {
            Log::error('Exception in updateMaterial', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'classroom_id' => $classroom_id,
                'material_id' => $id
            ]);
            
            return back()->withErrors(['msg' => 'Error processing request: ' . $e->getMessage()]);
        }
    }

    /**
     * Display all submissions for an assignment.
     *
     * @param  int  $classroom_id
     * @param  int  $assignment_id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function submissions($classroom_id, $assignment_id)
    {
        Log::debug('Viewing all submissions', [
            'classroom_id' => $classroom_id,
            'assignment_id' => $assignment_id,
            'user_id' => Auth::id()
        ]);
        
        try {
            $classroom = Classroom::findOrFail($classroom_id);
            
            // Check if the authenticated user is the teacher of this classroom
            if ($classroom->create_by !== Auth::id()) {
                Log::warning('Permission denied: User is not classroom creator', [
                    'user_id' => Auth::id(),
                    'classroom_id' => $classroom_id
                ]);
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'You do not have permission to view submissions for this classroom.');
            }
            
            // Get the assignment
            $assignment = $classroom->assignments()->whereNull('delete_at')->findOrFail($assignment_id);
            
            // Get all submissions for this assignment
            $submissions = $assignment->submissions()
                ->with('user')  // Eager load the user relationship
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get list of students who haven't submitted
            $studentsWithSubmissions = $submissions->pluck('user_id')->toArray();
            $studentsWithoutSubmissions = $classroom->members()
                ->where('role', 'student')
                ->whereNotIn('user_id', $studentsWithSubmissions)
                ->with('user')
                ->get();
            
            return view('teacher.submissions.index', compact(
                'classroom',
                'assignment',
                'submissions',
                'studentsWithoutSubmissions'
            ));
        } catch (\Exception $e) {
            Log::error('Exception in submissions method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'classroom_id' => $classroom_id,
                'assignment_id' => $assignment_id
            ]);
            
            return back()->withErrors(['msg' => 'Error processing request: ' . $e->getMessage()]);
        }
    }

    /**
     * Download an assignment file.
     *
     * @param  int  $classroom_id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadAssignment($classroom_id, $id)
    {
        try {
            $classroom = Classroom::findOrFail($classroom_id);
            
            // Check if the authenticated user is the teacher of this classroom
            if ($classroom->create_by !== Auth::id()) {
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'You do not have permission to download assignments from this classroom.');
            }
            
            // Get the assignment
            $assignment = $classroom->assignments()->findOrFail($id);
            
            // Check if file exists
            if (!$assignment->file || !file_exists(storage_path('app/' . $assignment->file))) {
                return back()->with('error', 'Assignment file not found.');
            }
            
            // Return the file for download
            return response()->download(storage_path('app/' . $assignment->file), basename($assignment->file));
        } catch (\Exception $e) {
            Log::error('Exception in downloadAssignment', [
                'message' => $e->getMessage(),
                'classroom_id' => $classroom_id,
                'assignment_id' => $id
            ]);
            
            return back()->withErrors(['msg' => 'Error downloading file: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified submission.
     *
     * @param int $classroom_id
     * @param int $assignment_id
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSubmission($classroom_id, $assignment_id, $id)
    {
        try {
            $classroom = Classroom::findOrFail($classroom_id);
            
            // Check if the authenticated user is the teacher of this classroom
            if ($classroom->create_by !== Auth::id()) {
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'You do not have permission to view submissions in this classroom.');
            }
            
            // Get the assignment
            $assignment = $classroom->assignments()->findOrFail($assignment_id);
            
            // Get the submission with user information
            $submission = $assignment->submissions()->with('user')->findOrFail($id);
            
            // Check if submission is late
            $submissionDate = \Carbon\Carbon::parse($submission->created_at);
            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
            $isLate = $submissionDate->isAfter($dueDate);
            
            return view('teacher.submissions.show', compact(
                'classroom',
                'assignment',
                'submission',
                'isLate',
                'submissionDate',
                'dueDate'
            ));
        } catch (\Exception $e) {
            \Log::error('Exception in showSubmission', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'classroom_id' => $classroom_id,
                'assignment_id' => $assignment_id,
                'submission_id' => $id
            ]);
            
            return back()->withErrors(['msg' => 'Error retrieving submission: ' . $e->getMessage()]);
        }
    }
}