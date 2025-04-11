<?php

namespace App\Http\Controllers\Api;

use App\Models\ClassroomMember;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClassroomMemberController extends Controller
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
     *     path="/api/classrooms/{classroom_id}/members",
     *     summary="Get all members of a classroom with pagination",
     *     tags={"Classroom Members"},
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
     *         description="Search by user name or email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filter by role (teacher/student)",
     *         required=false,
     *         @OA\Schema(type="string", example="student")
     *     ),
     *     @OA\Response(response=200, description="List of classroom members with pagination"),
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

        // Check if user is authorized to view members
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            return response()->json(['message' => 'You are not a member of this classroom'], Response::HTTP_FORBIDDEN);
        }

        // Build the query
        $query = ClassroomMember::where('classroom_id', $classroomId)
                                ->with('user');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by user name or email
        if ($request->filled('_search')) {
            $searchTerm = $request->_search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
            });
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $members = $query->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $members->items(),
                'meta' => [
                    'current_page' => $members->currentPage(),
                    'last_page' => $members->lastPage(),
                    'per_page' => $members->perPage(),
                    'total' => $members->total(),
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        return view('classroom_members.index', compact('members', 'classroom'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/members",
     *     summary="Add a new member to classroom",
     *     tags={"Classroom Members"},
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
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"user_id", "role"},
     *                 @OA\Property(property="user_id", type="integer", description="User ID"),
     *                 @OA\Property(property="role", type="string", description="Member role (teacher, student)"),
     *                 @OA\Property(property="email", type="string", description="Email of user to invite (alternative to user_id)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Member added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Member added successfully"),
     *             @OA\Property(property="data", type="object")
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
     *         response=404,
     *         description="Classroom or user not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to add members"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="User is already a member"
     *     )
     * )
     */
    public function store(Request $request, $classroomId)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify the user is a teacher/admin of this classroom
        $userId = Auth::id();
        $isTeacher = $classroom->create_by == $userId || 
                      $classroom->members()
                               ->where('user_id', $userId)
                               ->where('role', 'teacher')
                               ->exists();
        
        if (!$isTeacher) {
            return response()->json(['message' => 'You are not authorized to add members'], Response::HTTP_FORBIDDEN);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required_without:email|integer|exists:users,id',
            'email' => 'required_without:user_id|email|exists:users,email',
            'role' => 'required|string|in:teacher,student',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            // Get user ID from email if provided
            if ($request->filled('email') && !$request->filled('user_id')) {
                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    return response()->json(['message' => 'User not found with this email'], Response::HTTP_NOT_FOUND);
                }
                $request->merge(['user_id' => $user->id]);
            }

            // Check if user is already a member
            $existingMember = ClassroomMember::where('classroom_id', $classroomId)
                                           ->where('user_id', $request->user_id)
                                           ->first();
            
            if ($existingMember) {
                return response()->json(['message' => 'User is already a member of this classroom'], Response::HTTP_CONFLICT);
            }

            // Create new member
            $member = ClassroomMember::create([
                'classroom_id' => $classroomId,
                'user_id' => $request->user_id,
                'role' => $request->role,
                'joined_at' => now(),
            ]);

            $member->load('user');
            
            DB::commit();
            return response()->json(['message' => 'Member added successfully', 'data' => $member], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding classroom member: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error adding classroom member',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/{classroom_id}/members/{id}",
     *     tags={"Classroom Members"},
     *     summary="Get specific classroom member by ID",
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
     *         description="Classroom Member ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Member not found"
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

        // Verify the user is a member of this classroom
        $userId = Auth::id();
        $isMember = $classroom->create_by == $userId || 
                    $classroom->members()->where('user_id', $userId)->exists();
        
        if (!$isMember) {
            return response()->json(['message' => 'You are not a member of this classroom'], Response::HTTP_FORBIDDEN);
        }

        // Find the member
        $member = ClassroomMember::where('classroom_id', $classroomId)
                                ->where('id', $id)
                                ->with('user')
                                ->first();
        
        if (!$member) {
            return response()->json(['message' => 'Member not found'], Response::HTTP_NOT_FOUND);
        }

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json($member, Response::HTTP_OK);
        }

        // For web view
        return view('classroom_members.show', compact('member', 'classroom'));
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/members/{id}",
     *     tags={"Classroom Members"},
     *     summary="Update an existing classroom member (e.g., change role)",
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
     *         description="Classroom Member ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"role"},
     *                 @OA\Property(property="role", type="string", description="Member role (teacher, student)")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Member updated successfully"),
     *     @OA\Response(response=404, description="Member not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=403, description="Not authorized to update")
     * )
     */
    public function update(Request $request, $classroomId, $id)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        // Verify the user is a teacher/admin of this classroom
        $userId = Auth::id();
        $isTeacher = $classroom->create_by == $userId || 
                      $classroom->members()
                               ->where('user_id', $userId)
                               ->where('role', 'teacher')
                               ->exists();
        
        if (!$isTeacher) {
            return response()->json(['message' => 'You are not authorized to update members'], Response::HTTP_FORBIDDEN);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|in:teacher,student',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Find the member
        $member = ClassroomMember::where('classroom_id', $classroomId)
                                ->where('id', $id)
                                ->first();
        
        if (!$member) {
            return response()->json(['message' => 'Member not found'], Response::HTTP_NOT_FOUND);
        }

        // Prevent changing role of classroom creator
        if ($member->user_id == $classroom->create_by) {
            return response()->json(['message' => 'Cannot change role of classroom creator'], Response::HTTP_FORBIDDEN);
        }

        DB::beginTransaction();
        try {
            $member->update([
                'role' => $request->role,
            ]);
            
            $member->load('user');
            
            DB::commit();
            return response()->json(['message' => 'Member updated successfully', 'data' => $member], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating member', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/classrooms/{classroom_id}/members/{id}",
     *     tags={"Classroom Members"},
     *     summary="Remove a member from classroom",
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
     *         description="Classroom Member ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Member removed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Member not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Not authorized to remove"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot remove classroom creator"
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

        // Find the member
        $member = ClassroomMember::where('classroom_id', $classroomId)
                                ->where('id', $id)
                                ->first();
        
        if (!$member) {
            return response()->json(['message' => 'Member not found'], Response::HTTP_NOT_FOUND);
        }

        // Prevent removing classroom creator
        if ($member->user_id == $classroom->create_by) {
            return response()->json(['message' => 'Cannot remove classroom creator'], Response::HTTP_BAD_REQUEST);
        }

        // Check authorization: User can remove themselves OR a teacher can remove others
        $userId = Auth::id();
        $canRemove = ($userId == $member->user_id) || // Self-removal
                     ($classroom->create_by == $userId) || // Classroom creator
                     ($classroom->members() // Teacher
                               ->where('user_id', $userId)
                               ->where('role', 'teacher')
                               ->exists());
        
        if (!$canRemove) {
            return response()->json(['message' => 'You are not authorized to remove this member'], Response::HTTP_FORBIDDEN);
        }

        DB::beginTransaction();
        try {
            $member->delete();
            
            DB::commit();
            return response()->json(['message' => 'Member removed successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error removing member', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms/{classroom_id}/leave",
     *     tags={"Classroom Members"},
     *     summary="Leave a classroom (remove self as member)",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="classroom_id",
     *         in="path",
     *         required=true,
     *         description="Classroom ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully left the classroom"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Classroom not found or you are not a member"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot leave as classroom creator"
     *     )
     * )
     */
    public function leaveClassroom($classroomId)
    {
        // Find the classroom
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            return response()->json(['message' => 'Classroom not found'], Response::HTTP_NOT_FOUND);
        }

        $userId = Auth::id();

        // Check if user is the creator
        if ($classroom->create_by == $userId) {
            return response()->json([
                'message' => 'Classroom creator cannot leave. You must delete the classroom or transfer ownership first.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Find the membership
        $member = ClassroomMember::where('classroom_id', $classroomId)
                                ->where('user_id', $userId)
                                ->first();
        
        if (!$member) {
            return response()->json(['message' => 'You are not a member of this classroom'], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            $member->delete();
            
            DB::commit();
            return response()->json(['message' => 'Successfully left the classroom'], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error leaving classroom', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}