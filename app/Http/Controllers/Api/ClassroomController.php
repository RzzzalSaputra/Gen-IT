<?php

namespace App\Http\Controllers\Api;

use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\ClassroomMaterial;
use App\Models\ClassroomAssignment;
use App\Models\ClassroomSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClassroomController extends Controller
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
     *     path="/api/classrooms",
     *     summary="Get all classrooms with pagination",
     *     tags={"Classrooms"},
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
     *         description="Search by classroom name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="create_by",
     *         in="query",
     *         description="Filter by creator ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="List of classrooms with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = Classroom::query();

        // Filter by creator
        if ($request->filled('create_by')) {
            $query->where('create_by', $request->create_by);
        }

        // Search by name
        if ($request->filled('_search')) {
            $searchTerm = $request->_search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
            });
        }

        // If user is logged in, show only classrooms they created or are a member of
        if (Auth::check()) {
            $userId = Auth::id();
            $query->where(function($q) use ($userId) {
                $q->where('create_by', $userId)
                  ->orWhereHas('members', function($query) use ($userId) {
                      $query->where('user_id', $userId);
                  });
            });
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $classrooms = $query->with('creator')->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $classrooms->items(),
                'meta' => [
                    'current_page' => $classrooms->currentPage(),
                    'last_page' => $classrooms->lastPage(),
                    'per_page' => $classrooms->perPage(),
                    'total' => $classrooms->total(),
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        return view('classrooms.index', compact('classrooms'));
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/active",
     *     tags={"Classrooms"},
     *     summary="Get all active classrooms",
     *     description="Returns list of active (not soft deleted) classrooms",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        $userId = Auth::id();
        $classrooms = Classroom::where(function($q) use ($userId) {
            $q->where('create_by', $userId)
              ->orWhereHas('members', function($query) use ($userId) {
                  $query->where('user_id', $userId);
              });
        })->get();
        
        return response()->json($classrooms, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms",
     *     summary="Create a new classroom",
     *     tags={"Classrooms"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "description"},
     *                 @OA\Property(property="name", type="string", description="Classroom name"),
     *                 @OA\Property(property="description", type="string", description="Classroom description")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Classroom created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Classroom created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="code", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="create_by", type="integer"),
     *                 @OA\Property(property="create_at", type="string", format="date-time"),
     *                 @OA\Property(property="update_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            // Generate a unique code for the classroom
            $code = $this->generateUniqueCode();
            
            $classroom = Classroom::create([
                'name' => $request->name,
                'code' => $code,
                'description' => $request->description,
                'create_by' => Auth::id(),
                'create_at' => now(),
                'update_at' => now(),
            ]);
            
            // Also add the creator as a teacher member
            $classroom->members()->create([
                'user_id' => Auth::id(),
                'role' => 'teacher',
                'joined_at' => now(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Classroom created successfully', 'data' => $classroom], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating classroom: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating classroom',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/{id}",
     *     tags={"Classrooms"},
     *     summary="Get specific classroom by ID",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Classroom not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not a member of this classroom"
     *     )
     * )
     */
    public function show($id)
    {
        $classroom = Classroom::with(['creator', 'members.user', 'materials', 'assignments'])->findOrFail($id);

        // Check if user is a member or creator of this classroom
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            if (request()->is('api/*')) {
                return response()->json(['message' => 'You are not a member of this classroom'], 403);
            }
            return redirect()->route('classrooms.index')->with('error', 'You are not a member of this classroom');
        }

        // Check if it's an API request by URL pattern
        if (request()->is('api/*')) {
            return response()->json($classroom, Response::HTTP_OK);
        }
        
        // For web view requests, explicitly render the view
        return view('classrooms.show', compact('classroom'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{id}",
     *     tags={"Classrooms"},
     *     summary="Update an existing classroom",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Classroom updated successfully"),
     *     @OA\Response(response=404, description="Classroom not found"),
     *     @OA\Response(response=403, description="Forbidden - Not authorized to update"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Find classroom by ID
            $classroom = Classroom::find($id);
            if (!$classroom) {
                return response()->json(['message' => 'Classroom not found'], 404);
            }

            // Check if user is authorized (creator or teacher)
            $userId = Auth::id();
            $isAuthorized = $classroom->create_by == $userId || 
                            $classroom->members()
                                     ->where('user_id', $userId)
                                     ->where('role', 'teacher')
                                     ->exists();
            
            if (!$isAuthorized) {
                return response()->json(['message' => 'Not authorized to update this classroom'], 403);
            }

            // Update classroom data
            $updateData = [];
            foreach(['name', 'description'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            if (!empty($updateData)) {
                $updateData['update_at'] = now();
                $classroom->update($updateData);
            }

            DB::commit();
            return response()->json(['message' => 'Classroom updated successfully', 'data' => $classroom], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating classroom', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/classrooms/{id}",
     *     tags={"Classrooms"},
     *     summary="Soft delete a classroom",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Classroom deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Classroom not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to delete"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            Log::warning("Classroom with ID $id not found.");
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if user is authorized (only creator can delete)
        if (Auth::id() != $classroom->create_by) {
            return response()->json(['message' => 'Not authorized to delete this classroom'], 403);
        }

        $classroom->update(['delete_at' => now()]);

        return response()->json([
            'message' => 'Classroom successfully soft deleted.',
            'deleted_at' => $classroom->delete_at
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{id}/restore",
     *     tags={"Classrooms"},
     *     summary="Restore a soft-deleted classroom",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Classroom restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Classroom not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to restore"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $classroom = Classroom::where('id', $id)->whereNotNull('delete_at')->first();
        
        if (!$classroom) {
            return response()->json(['message' => 'Deleted classroom not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Check if user is authorized (only creator can restore)
        if (Auth::id() != $classroom->create_by) {
            return response()->json(['message' => 'Not authorized to restore this classroom'], 403);
        }
        
        $classroom->update(['delete_at' => null]);
        
        return response()->json([
            'message' => 'Classroom successfully restored',
            'data' => $classroom
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/join",
     *     tags={"Classrooms"},
     *     summary="Join a classroom using invite code",
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"code"},
     *                 @OA\Property(property="code", type="string", description="Classroom invite code")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successfully joined the classroom"),
     *     @OA\Response(response=404, description="Classroom not found"),
     *     @OA\Response(response=400, description="Already a member of this classroom"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find classroom by code
        $classroom = Classroom::where('code', $request->code)
                            ->whereNull('delete_at')
                            ->first();

        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found or invalid code'], 404);
        }

        $userId = Auth::id();
        
        // Check if already a member
        if ($classroom->create_by == $userId || 
            $classroom->members()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'You are already a member of this classroom'], 400);
        }

        // Add user as a member with student role
        DB::beginTransaction();
        try {
            $classroom->members()->create([
                'user_id' => $userId,
                'role' => 'student',
                'joined_at' => now(),
            ]);
            
            DB::commit();
            return response()->json([
                'message' => 'Successfully joined the classroom',
                'data' => $classroom
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error joining classroom', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate a unique invite code for a classroom
     * 
     * @return string
     */
    private function generateUniqueCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 5;
        
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (Classroom::where('code', $code)->exists());
        
        return $code;
    }
    
    /**
     * Display the student classroom dashboard
     */
    public function studentDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $userId = Auth::id();
        
        // Get classrooms where user is a member with assignments and materials
        $joinedClassrooms = Classroom::whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['creator', 'members', 
                'assignments.submissions' => function($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'materials'
            ])
            ->whereNull('delete_at')
            ->get();
            
        return view('student.classrooms.index', compact('joinedClassrooms'));
    }
    
    /**
     * Display the form to join a classroom
     */
    public function joinForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        return view('student.classrooms.join');
    }
    
    /**
     * Process the classroom join request
     */
    public function processJoin(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $code = $request->code;
        $userId = Auth::id();
        
        // Find classroom by code
        $classroom = Classroom::where('code', $code)
            ->whereNull('delete_at')
            ->first();
            
        if (!$classroom) {
            return redirect()->back()
                ->with('error', 'Invalid classroom code. Please check and try again.');
        }
        
        // Check if user is already a member
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
                    
        if ($isMember) {
            return redirect()->route('student.classrooms.show', $classroom->id)
                ->with('info', 'You are already a member of this classroom.');
        }
        
        // Add user as a member
        try {
            DB::beginTransaction();
            
            ClassroomMember::create([
                'classroom_id' => $classroom->id,
                'user_id' => $userId,
                'role' => 'student',
                'joined_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('student.classrooms.show', $classroom->id)
                ->with('success', 'Successfully joined the classroom!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error joining classroom: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred while joining the classroom. Please try again.');
        }
    }
    
    /**
     * Show classroom details for a student
     */
    public function showForStudent($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $classroom = Classroom::with(['creator', 'members.user'])->findOrFail($id);
        
        // Check if user is a member
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
                    
        if (!$isMember) {
            return redirect()->route('student.classrooms.index')
                ->with('error', 'You are not a member of this classroom.');
        }
        
        // Get materials
        $materials = ClassroomMaterial::where('classroom_id', $id)
            ->whereNull('delete_at')
            ->with('creator')
            ->orderBy('create_at', 'desc')
            ->get();
            
        // Get assignments
        $assignments = ClassroomAssignment::where('classroom_id', $id)
            ->whereNull('delete_at')
            ->with('creator')
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Get user's status for each assignment
        foreach ($assignments as $assignment) {
            $submission = ClassroomSubmission::where('assignment_id', $assignment->id)
                ->where('user_id', $userId)
                ->first();
            
            $assignment->user_submission = $submission;
        }
        
        return view('student.classrooms.show', compact('classroom', 'materials', 'assignments'));
    }

    /**
     * Student leaves a classroom
     */
    public function leaveClassroom($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $classroom = Classroom::findOrFail($id);
        $userId = Auth::id();
        
        // Check if user is a member
        $member = ClassroomMember::where('classroom_id', $id)
            ->where('user_id', $userId)
            ->first();
            
        if (!$member) {
            return redirect()->route('student.classrooms.index')
                ->with('error', 'You are not a member of this classroom.');
        }
        
        // Don't allow classroom creator to leave
        if ($classroom->create_by == $userId) {
            return redirect()->route('student.classrooms.show', $id)
                ->with('error', 'As the creator of this classroom, you cannot leave it.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete the classroom membership
            $member->delete();
            
            DB::commit();
            
            return redirect()->route('student.classrooms.index')
                ->with('success', 'You have successfully left the classroom.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error leaving classroom: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred while trying to leave the classroom. Please try again.');
        }
    }
}