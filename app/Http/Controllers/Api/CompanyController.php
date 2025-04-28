<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
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

class CompanyController extends Controller
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
     *     path="/api/companies",
     *     summary="Get all companies with pagination",
     *     tags={"Companies"},
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
     *         description="Search by company name or description",
     *         required=false,
     *         @OA\Schema(type="string")
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
     *     @OA\Response(response=200, description="List of companies with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = Company::query();

        // Filter berdasarkan provinsi
        if ($request->has('province')) {
            $query->where('province', 'like', "%{$request->province}%");
        }

        // Filter berdasarkan kota
        if ($request->has('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // Pencarian berdasarkan nama atau deskripsi perusahaan - CASE-INSENSITIVE SEARCH
        if ($request->has('_search')) {
            $search = $request->_search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $companies = $query->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $companies->items(),
                'meta' => [
                    'current_page' => $companies->currentPage(),
                    'last_page' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total()
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        // Get unique provinces and cities for filters
        $provinces = Company::distinct('province')->pluck('province')->filter()->values();
        $cities = Company::distinct('city')->pluck('city')->filter()->values();
        
        // Get cities with their associated provinces
        $cityProvinceMap = Company::select('city', 'province')
            ->whereNotNull('city')
            ->whereNotNull('province')
            ->distinct()
            ->get()
            ->groupBy('province')
            ->map(function($cities) {
                return $cities->pluck('city')->filter()->values();
            })
            ->toArray();

        return view('companies.index', compact('companies', 'provinces', 'cities', 'cityProvinceMap'));
    }

    /**
     * @OA\Get(
     *     path="/api/companies/active",
     *     tags={"Companies"},
     *     summary="Get all active companies",
     *     description="Returns list of active (not soft deleted) companies",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        return response()->json(Company::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/companies",
     *     summary="Create a new company",
     *     tags={"Companies"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "description", "province", "city", "address"},
     *                 @OA\Property(property="name", type="string", description="Company name - must be unique"),
     *                 @OA\Property(property="description", type="string", description="Company description"),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true, description="Company image"),
     *                 @OA\Property(property="gmap", type="string", nullable=true, description="Google Maps embed code or URL"),
     *                 @OA\Property(property="province", type="string", description="Province/state location"),
     *                 @OA\Property(property="city", type="string", description="City location"),
     *                 @OA\Property(property="address", type="string", description="Full address"),
     *                 @OA\Property(property="website", type="string", nullable=true, description="Company website URL"),
     *                 @OA\Property(property="instagram", type="string", nullable=true, description="Instagram profile URL/handle"),
     *                 @OA\Property(property="facebook", type="string", nullable=true, description="Facebook page URL"),
     *                 @OA\Property(property="x", type="string", nullable=true, description="X/Twitter profile URL/handle")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Company created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Company created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="img", type="string", nullable=true, example="/storage/companies/images/img_1.jpg"),
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
            'name' => 'required|string|max:255|unique:companies',
            'description' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            
            $company = new Company([
                'name' => $data['name'],
                'description' => $data['description'],
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

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $timestamp = now()->format('Ymd_His');
                $random = mt_rand(100, 999);
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('companies/images', $filename, 'public');
                $company->img = 'companies/images/' . $filename;
            }

            $company->save();

            DB::commit();
            return response()->json(['message' => 'Company created successfully', 'data' => $company], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating company: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating company',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
     *     summary="Get specific company by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function show($id)
    {
        $company = Company::with('jobs')->findOrFail($id);

        // Increment read counter
        $company->increment('read_counter');

        // Check if it's an API request by URL pattern
        if (request()->is('api/*')) {
            return response()->json($company, Response::HTTP_OK);
        }
        
        // For web view requests, explicitly render the view
        return view('companies.show', compact('company'));
    }

    /**
     * @OA\Post(
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
     *     summary="Update an existing company",
     *     description="Update a company with optional image upload.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
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
     *     @OA\Response(response=200, description="Company updated successfully"),
     *     @OA\Response(response=404, description="Company not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:companies,name,'.$id,
            'description' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            $company = Company::find($id);
            if (!$company) {
                return response()->json(['message' => 'Company not found'], 404);
            }

            // Update company data
            $updateData = [];
            foreach(['name', 'description', 'gmap', 'province', 'city', 
                    'address', 'website', 'instagram', 'facebook', 'x'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            if (!empty($updateData)) {
                $company->update($updateData);
            }

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                // Delete old image
                if (!empty($company->img)) {
                    $oldImagePath = storage_path('app/public/' . $company->img);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $file = $request->file('img');
                $timestamp = now()->format('Ymd_His');
                $random = mt_rand(100, 999);
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = \Illuminate\Support\Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('companies/images', $filename, 'public');
                $company->update(['img' => 'companies/images/' . $filename]);
            }

            DB::commit();
            return response()->json(['message' => 'Company updated successfully', 'data' => $company], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating company', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/companies/{id}",
     *     tags={"Companies"},
     *     summary="Soft delete a company",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $company = Company::find($id);
            if (!$company) {
                return response()->json(['message' => 'Company not found'], 404);
            }

            // Delete the image file if exists
            if (!empty($company->img)) {
                $imagePath = storage_path('app/public/' . $company->img);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $company->delete();
            DB::commit();
            return response()->json(['message' => 'Company deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting company: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting company',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/companies/{id}/restore",
     *     tags={"Companies"},
     *     summary="Restore a soft-deleted company",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();
        return response()->json($company, Response::HTTP_OK);
    }
}