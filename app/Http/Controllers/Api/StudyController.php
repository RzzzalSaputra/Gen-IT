<?php

namespace App\Http\Controllers\Api;

use App\Models\Study;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class StudyController extends Controller
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
     *     path="/api/studies",
     *     summary="Get all studies with pagination",
     *     tags={"Studies"},
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
     *         description="Search by study name or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="school_id",
     *         in="query",
     *         description="Filter by school ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="level",
     *         in="query",
     *         description="Filter by study level",
     *         required=false,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(response=200, description="List of studies with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = Study::query();

        // Filter berdasarkan sekolah
        if ($request->has('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter berdasarkan level
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        // Pencarian berdasarkan nama atau deskripsi studi
        if ($request->has('_search')) {
            $search = $request->_search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $studies = $query->paginate($perPage);

        return response()->json($studies);
    }

    /**
     * @OA\Get(
     *     path="/api/studies/active",
     *     tags={"Studies"},
     *     summary="Get all active study programs",
     *     description="Returns list of active (not soft deleted) study programs",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        return response()->json(Study::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/studies",
     *     summary="Create a new study program",
     *     tags={"Studies"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"school_id", "name", "description", "duration", "level"},
     *                 @OA\Property(property="school_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="duration", type="string"),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="level", type="integer", description="ID of the option representing the education level")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Study program created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Study program created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="school_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="duration", type="string"),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", nullable=true, example="/storage/studies/images/img_1.jpg"),
     *                 @OA\Property(property="level", type="integer"),
     *                 @OA\Property(property="read_counter", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
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
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string|max:255',
            'link' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'level' => 'required|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // First create the study with a default image path
            $study = new Study([
                'school_id' => $data['school_id'],
                'name' => $data['name'],
                'description' => $data['description'],
                'duration' => $data['duration'],
                'link' => $data['link'] ?? null,
                'level' => $data['level'],
                'read_counter' => 0,
                'img' => '/storage/studies/images/default.jpg', // Default image path
            ]);

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $imageName = 'img_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('studies/images', $imageName, 'public');
                $study->img = '/storage/' . $imagePath;
            }

            $study->save();

            DB::commit();
            return response()->json(['message' => 'Study program created successfully', 'data' => $study], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating study program: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating study program',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/studies/{id}",
     *     tags={"Studies"},
     *     summary="Get specific study program by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Study ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Study program not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        $study = Study::with(['school', 'levelOption'])->find($id);

        if (!$study) {
            if (request()->is('api/*')) {
                return response()->json(['message' => 'Study program not found'], Response::HTTP_NOT_FOUND);
            }
            abort(404, 'Study program not found');
        }

        // Increment read counter
        $study->read_counter = $study->read_counter + 1;
        $study->save();
        
        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json($study, Response::HTTP_OK);
        }
        
        // For web view
        return view('studies.show', compact('study'));
    }

    /**
     * @OA\Post(
     *     path="/api/studies/{id}",
     *     tags={"Studies"},
     *     summary="Update an existing study program",
     *     description="Update a study program with optional image upload.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Study ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="school_id", type="integer", nullable=true),
     *                 @OA\Property(property="name", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="duration", type="string", nullable=true),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="level", type="integer", nullable=true, description="ID of the option representing the education level")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Study program updated successfully"),
     *     @OA\Response(response=404, description="Study program not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'nullable|exists:schools,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:255',
            'link' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'level' => 'nullable|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Find study by ID
            $study = Study::find($id);
            if (!$study) {
                return response()->json(['message' => 'Study program not found'], 404);
            }

            // Update study data
            $updateData = [];
            foreach(['school_id', 'name', 'description', 'duration', 'link', 'level'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            if (!empty($updateData)) {
                $study->update($updateData);
            }

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                if (!empty($study->img) && $study->img != '/storage/studies/images/default.jpg') {
                    $path = storage_path('app/public/' . str_replace('/storage/', '', $study->img));
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $image = $request->file('img');
                $timestamp = Carbon::now()->format('Y-m-d_His');
                $imageName = $study->id . '_' . $timestamp . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('studies/images', $imageName, 'public');
                $study->update(['img' => '/storage/' . $imagePath]);
            }

            DB::commit();
            return response()->json(['message' => 'Study program updated successfully', 'data' => $study], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating study program', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/studies/{id}",
     *     tags={"Studies"},
     *     summary="Soft delete a study program",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Study ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Study program deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Study program not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $study = Study::find($id);

        if (!$study) {
            Log::warning("Study program with ID $id not found.");
            return response()->json(['message' => 'Study program not found'], Response::HTTP_NOT_FOUND);
        }

        Log::info('Soft deleting study program:', ['id' => $study->id]);
        $study->delete();

        return response()->json(['message' => 'Study program successfully soft deleted.', 'deleted_at' => $study->deleted_at], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/studies/{id}/restore",
     *     tags={"Studies"},
     *     summary="Restore a soft-deleted study program",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Study ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Study program restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Study program not found"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $study = Study::withTrashed()->findOrFail($id);
        $study->restore();
        return response()->json($study, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/schools/{schoolId}/studies",
     *     tags={"Studies"},
     *     summary="Get all study programs for a specific school",
     *     @OA\Parameter(
     *         name="schoolId",
     *         in="path",
     *         required=true,
     *         description="School ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="School not found"
     *     )
     * )
     */
    public function getStudiesBySchool(int $schoolId): JsonResponse
    {
        $studies = Study::where('school_id', $schoolId)->get();
        return response()->json($studies, Response::HTTP_OK);
    }
}