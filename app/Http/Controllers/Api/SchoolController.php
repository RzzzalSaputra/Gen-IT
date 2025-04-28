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
    public function __construct()
    {
        // Only set the Accept header for API requests
        if (request()->is('api/*')) {
            request()->headers->set('Accept', 'application/json');
        }
    }

    /**
     * @OA\Get(
     *     path="/api/schools",
     *     summary="Get all schools with pagination",
     *     tags={"Schools"},
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
     *         description="Search by school name or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by school type",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="province",
     *         in="query",
     *         description="Filter by province",
     *         required=false,
     *         @OA\Schema(type="string", example="Jawa Barat")
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="Filter by city",
     *         required=false,
     *         @OA\Schema(type="string", example="Bandung")
     *     ),
     *     @OA\Response(response=200, description="List of schools with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = School::query();

        // Filter berdasarkan tipe sekolah
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan provinsi
        if ($request->has('province')) {
            $query->where('province', 'like', "%{$request->province}%");
        }

        // Filter berdasarkan kota
        if ($request->has('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // Pencarian berdasarkan nama atau deskripsi sekolah - MODIFIED FOR CASE-INSENSITIVE SEARCH
        if ($request->has('_search')) {
            $search = $request->_search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $schools = $query->with('typeOption')->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $schools->items(),
                'meta' => [
                    'current_page' => $schools->currentPage(),
                    'last_page' => $schools->lastPage(),
                    'per_page' => $schools->perPage(),
                    'total' => $schools->total()
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        // Get unique provinces and cities for filters
        $provinces = School::distinct('province')->pluck('province')->filter()->values();
        $cities = School::distinct('city')->pluck('city')->filter()->values();
        
        // Get school types for filter tabs - fixed query
        try {
            // Try to get school types from distinct values in the schools table instead
            $schoolTypeIds = School::distinct('type')->pluck('type')->filter()->values();
            $schoolTypes = \App\Models\Option::whereIn('id', $schoolTypeIds)->get();
        } catch (\Exception $e) {
            // Fallback to empty collection if query fails
            $schoolTypes = collect();
        }

        // Get cities with their associated provinces
        $cityProvinceMap = School::select('city', 'province')
            ->whereNotNull('city')
            ->whereNotNull('province')
            ->distinct()
            ->get()
            ->groupBy('province')
            ->map(function($cities) {
                return $cities->pluck('city')->filter()->values();
            })
            ->toArray();

        return view('schools.index', compact('schools', 'provinces', 'cities', 'schoolTypes', 'cityProvinceMap'));
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
            
            $school = new School([
                'name' => $data['name'],
                'description' => $data['description'],
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
            ]);

            $timestamp = now()->format('Ymd_His');
            $random = mt_rand(100, 999);

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('schools/images', $filename, 'public');
                $school->img = 'schools/images/' . $filename;
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
    public function show($id)
    {
        $school = School::with(['typeOption', 'studies'])->findOrFail($id);

        // Increment read counter
        $school->increment('read_counter');

        // Check if it's an API request by URL pattern
        if (request()->is('api/*')) {
            return response()->json($school, Response::HTTP_OK);
        }
        
        // For web view requests, explicitly render the view
        return view('schools.show', compact('school'));
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
            $school = School::find($id);
            if (!$school) {
                return response()->json(['message' => 'School not found'], 404);
            }

            // Update school data
            $updateData = [];
            foreach(['name', 'description', 'type', 'gmap', 'province', 'city', 
                    'address', 'website', 'instagram', 'facebook', 'x'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            if (!empty($updateData)) {
                $school->update($updateData);
            }

            $timestamp = now()->format('Ymd_His');
            $random = mt_rand(100, 999);

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                if (!empty($school->img)) {
                    $oldImagePath = storage_path('app/public/' . $school->img);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $file = $request->file('img');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('schools/images', $filename, 'public');
                $school->update(['img' => 'schools/images/' . $filename]);
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