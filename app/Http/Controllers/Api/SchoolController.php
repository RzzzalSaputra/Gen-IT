<?php

namespace App\Http\Controllers\Api;

use App\Models\School;
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

class SchoolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/schools",
     *     tags={"Schools"},
     *     summary="Get all schools including soft deleted ones",
     *     description="Returns list of all schools including soft deleted records",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(School::withTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/schools/active",
     *     tags={"Schools"},
     *     summary="Get all active schools",
     *     description="Returns list of active (not soft deleted) schools",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        return response()->json(School::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/schools",
     *     summary="Create a new school",
     *     tags={"Schools"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "description", "type", "province", "city", "address"},
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="type", type="integer"),
     *                 @OA\Property(property="gmap", type="string", nullable=true),
     *                 @OA\Property(property="province", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="website", type="string", nullable=true),
     *                 @OA\Property(property="instagram", type="string", nullable=true),
     *                 @OA\Property(property="facebook", type="string", nullable=true),
     *                 @OA\Property(property="x", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="School created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="School created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", nullable=true, example="/storage/schools/images/img_1.jpg"),
     *                 @OA\Property(property="type", type="integer"),
     *                 @OA\Property(property="gmap", type="string", nullable=true),
     *                 @OA\Property(property="province", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="website", type="string", nullable=true),
     *                 @OA\Property(property="instagram", type="string", nullable=true),
     *                 @OA\Property(property="facebook", type="string", nullable=true),
     *                 @OA\Property(property="x", type="string", nullable=true),
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Changed back to nullable
            'type' => 'required|exists:options,id',
            'gmap' => 'nullable|string',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'website' => 'nullable|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'x' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // First create the school with a default image path
            $school = new School([
                'name' => $data['name'],
                'description' => $data['description'],
                'link' => $data['link'] ?? null,
                'type' => $data['type'],
                'gmap' => $data['gmap'] ?? null,
                'province' => $data['province'],
                'city' => $data['city'], 
                'address' => $data['address'],
                'website' => $data['website'] ?? null,
                'instagram' => $data['instagram'] ?? null,
                'facebook' => $data['facebook'] ?? null,
                'x' => $data['x'] ?? null,
                'read_counter' => 0,
                'img' => '/storage/schools/images/default.jpg', // Default image path
            ]);

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $imageName = 'img_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('schools/images', $imageName, 'public');
                $school->img = '/storage/' . $imagePath;
            }

            $school->save();

            DB::commit();
            return response()->json(['message' => 'School created successfully', 'data' => $school], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating school: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating school',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/schools/{id}",
     *     tags={"Schools"},
     *     summary="Get specific school by ID",
     *     @OA\Parameter(
     *         name="id",
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
    public function show(int $id): JsonResponse
    {
        $school = School::withTrashed()->find($id);

        if (!$school) {
            return response()->json(['message' => 'School not found'], Response::HTTP_NOT_FOUND);
        }

        // Increment read counter
        $school->read_counter = $school->read_counter + 1;
        $school->save();

        return response()->json($school, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/schools/{id}",
     *     tags={"Schools"},
     *     summary="Update an existing school",
     *     description="Update a school with optional image upload.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="School ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="type", type="integer", nullable=true),
     *                 @OA\Property(property="gmap", type="string", nullable=true),
     *                 @OA\Property(property="province", type="string", nullable=true),
     *                 @OA\Property(property="city", type="string", nullable=true),
     *                 @OA\Property(property="address", type="string", nullable=true),
     *                 @OA\Property(property="website", type="string", nullable=true),
     *                 @OA\Property(property="instagram", type="string", nullable=true),
     *                 @OA\Property(property="facebook", type="string", nullable=true),
     *                 @OA\Property(property="x", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="School updated successfully"),
     *     @OA\Response(response=404, description="School not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'nullable|exists:options,id',
            'gmap' => 'nullable|string',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'x' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Find school by ID
            $school = School::find($id);
            if (!$school) {
                return response()->json(['message' => 'School not found'], 404);
            }

            // Update school data
            $updateData = [];
            foreach(['name', 'description', 'link', 'type', 'gmap', 'province', 'city', 
                    'address', 'website', 'instagram', 'facebook', 'x'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            if (!empty($updateData)) {
                $school->update($updateData);
            }

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                if (!empty($school->img)) {
                    $path = storage_path('app/public/' . str_replace('/storage/', '', $school->img));
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $image = $request->file('img');
                $timestamp = Carbon::now()->format('Y-m-d_His');
                $imageName = $school->id . '_' . $timestamp . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('schools/images', $imageName, 'public');
                $school->update(['img' => '/storage/' . $imagePath]);
            }

            DB::commit();
            return response()->json(['message' => 'School updated successfully', 'data' => $school], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating school', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/schools/{id}",
     *     tags={"Schools"},
     *     summary="Soft delete a school",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="School ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="School deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="School not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $school = School::find($id);

        if (!$school) {
            Log::warning("School with ID $id not found.");
            return response()->json(['message' => 'School not found'], Response::HTTP_NOT_FOUND);
        }

        Log::info('Soft deleting school:', ['id' => $school->id]);
        $school->delete();

        return response()->json(['message' => 'School successfully soft deleted.', 'deleted_at' => $school->deleted_at], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/schools/{id}/restore",
     *     tags={"Schools"},
     *     summary="Restore a soft-deleted school",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="School ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="School restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="School not found"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $school = School::withTrashed()->findOrFail($id);
        $school->restore();
        return response()->json($school, Response::HTTP_OK);
    }
}