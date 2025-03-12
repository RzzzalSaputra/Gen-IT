<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Material;


class MaterialController extends Controller
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
     *     path="/api/materials",
     *     summary="Get all materials with pagination",
     *     tags={"Materials"},
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
     *         description="Search by material title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="layout",
     *         in="query",
     *         description="Filter by layout ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by material type ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="created_by",
     *         in="query",
     *         description="Filter by creator ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(response=200, description="List of materials with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        // Get content_type from either route parameter or query parameter
        $contentType = $request->route('content_type') ?? $request->query('content_type') ?? 'text';
        
        // Query builder for materials
        $query = Material::query();
        
        // Apply content type filter
        if ($contentType == 'text') {
            // Filter for text materials (no file, no video link)
            $query->where(function($q) {
                $q->whereNull('file')
                  ->where(function($q2) {
                      $q2->whereNull('link')
                         ->orWhere('link', '');
                  });
            });
        } elseif ($contentType == 'downloadable') {
            // Filter for downloadable materials (has file)
            $query->whereNotNull('file');
        } elseif ($contentType == 'video') {
            // Filter for video materials (has YouTube link)
            $query->where('link', 'like', '%youtube.com%')
                  ->orWhere('link', 'like', '%youtu.be%');
        }
        
        // Apply search if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
        
        // Get paginated results
        $materials = $query->latest()->paginate(12);
        
        return view('material.index', compact('materials'));
    }


    /**
     * @OA\Post(
     *     path="/api/materials",
     *     summary="Create a new material",
     *     tags={"Materials"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "slug", "content", "layout", "type"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="layout", type="integer"),
     *                 @OA\Property(property="type", type="integer"),
     *                 @OA\Property(property="file", type="file", format="binary", nullable=true),
     *                 @OA\Property(property="img", type="file", format="binary", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Material created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'slug'    => 'required|string|max:255|unique:materials,slug',
            'content' => 'required|string',
            'layout'  => 'required|integer',
            'type'    => 'required|integer',
            'file'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $material = Material::create([
                'title'      => $request->title,
                'slug'       => $request->slug,
                'content'    => $request->content,
                'layout'     => $request->layout,
                'type'       => $request->type,
                'created_by' => Auth::id(),
            ]);

            // Format timestamp untuk nama file biar unik
            $timestamp = now()->format('Ymd_His');

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $filename = "{$material->id}_{$timestamp}.{$ext}";
                $path = $file->storeAs('materials/files', $filename, 'public');

                // Simpan path file di database
                $material->file = '/storage/' . $path;
            }

            // Handle image upload
            if ($request->hasFile('img')) {
                $img = $request->file('img');
                $imgExt = $img->getClientOriginalExtension();
                $imgName = "{$material->id}_{$timestamp}.{$imgExt}";
                $imgPath = $img->storeAs('materials/images', $imgName, 'public');

                // Simpan path image di database
                $material->img = '/storage/' . $imgPath;
            }

            // Simpan perubahan di database
            $material->save();

            DB::commit();
            return response()->json(['message' => 'Material created successfully', 'data' => $material], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating material', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/materials/{id}",
     *     tags={"Materials"},
     *     summary="Update an existing material",
     *     description="Update a material with optional file and image uploads.",
     *     security={{ "bearerAuth": {} }},
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
     *                 @OA\Property(property="slug", type="string", nullable=true),
     *                 @OA\Property(property="content", type="string", nullable=true),
     *                 @OA\Property(property="layout", type="integer", nullable=true),
     *                 @OA\Property(property="type", type="integer", nullable=true),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Material updated successfully"),
     *     @OA\Response(response=404, description="Material not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'slug'    => 'nullable|string|max:255|unique:materials,slug,' . $id,
            'content' => 'nullable|string',
            'layout'  => 'nullable|integer|exists:options,id',
            'type'    => 'nullable|integer|exists:options,id',
            'file'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $material = Material::find($id);
            if (!$material) {
                return response()->json(['message' => 'Material not found'], 404);
            }

            $material->update([
                'title'      => $request->title ?? $material->title,
                'slug'       => $request->slug ?? $material->slug,
                'content'    => $request->content ?? $material->content,
                'layout'     => $request->layout ?? $material->layout,
                'type'       => $request->type ?? $material->type,
                'created_by' => Auth::id(), // Update ID pembuat untuk tracking
            ]);

            // Handle file upload
            if ($request->hasFile('file')) {
                if (!empty($material->file)) {
                    $oldFilePath = storage_path('app/public/' . str_replace('/storage/', '', $material->file));
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file = $request->file('file');
                $timestamp = now()->format('Ymd_His');
                $filename = "file_{$material->id}_{$timestamp}." . $file->getClientOriginalExtension();
                $path = $file->storeAs('materials/files', $filename, 'public');
                $material->update(['file' => '/storage/' . $path]);
            }

            // Handle image upload
            if ($request->hasFile('img')) {
                if (!empty($material->img)) {
                    $oldImagePath = storage_path('app/public/' . str_replace('/storage/', '', $material->img));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('img');
                $timestamp = now()->format('Ymd_His');
                $imageName = "img_{$material->id}_{$timestamp}." . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('materials/images', $imageName, 'public');
                $material->update(['img' => '/storage/' . $imagePath]);
            }

            DB::commit();
            return response()->json(['message' => 'Material updated successfully', 'data' => $material], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating material', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/materials/{id}",
     *     summary="Delete material",
     *     tags={"Materials"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Material deleted successfully"),
     *     @OA\Response(response=404, description="Material not found")
     * )
     */
    public function destroy($id)
    {
        $material = Material::find($id);
        if (!$material) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        return DB::transaction(function () use ($material) {
            $material->delete();
            return response()->json(['message' => 'Material deleted successfully'], 200);
        });
    }

    /**
     * @OA\Post(
     *     path="/api/materials/{id}/restore",
     *     summary="Restore a deleted material",
     *     tags={"Materials"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Material ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Material restored successfully"),
     *     @OA\Response(response=404, description="Material not found"),
     *     @OA\Response(response=400, description="Material is not deleted"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function restore($id)
    {
        $material = Material::withTrashed()->find($id);
        if (!$material) {
            return response()->json(['message' => 'Material not found'], 404);
        }

        if (!$material->trashed()) {
            return response()->json(['message' => 'Material is not deleted'], 400);
        }

        $material->restore();

        return response()->json(['message' => 'Material restored successfully', 'data' => $material], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $material = Material::findOrFail($id);
        
        // Increment read counter
        $material->increment('read_counter');
        
        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json($material);
        }
        
        // For web view
        return view('material.show', compact('material'));
    }
    
    /**
     * Download a material file and increment download counter.
     */
    public function download($id)
    {
        $material = Material::findOrFail($id);
        
        // Increment the download counter
        $material->increment('download_counter');
        
        // Fix the file path construction
        $filePath = storage_path('app/public/materials/files/' . basename($material->file));
        
        // Check if file exists
        if (!file_exists($filePath)) {
            // Try alternate path formats
            $alternateFilePath = public_path('storage/materials/files/' . basename($material->file));
            
            if (file_exists($alternateFilePath)) {
                return response()->download($alternateFilePath);
            }
            
            // If still not found, return error
            abort(404, 'File not found: ' . basename($material->file));
        }
        
        return response()->download($filePath);
    }
}
