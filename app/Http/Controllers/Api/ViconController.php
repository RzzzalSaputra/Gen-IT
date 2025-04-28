<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vicon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\View\View;

class ViconController extends Controller
{
    /**
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     in="header",
     *     name="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     * )
     */

    protected $default_folder = 'vicon';
    protected $file_indexes = ['img'];

    public function __construct()
    {
        request()->headers->set('Accept', 'application/json');
    }

    /**
     * @OA\Get(
     *     path="/api/vicons",
     *     summary="Get all Vicons with pagination",
     *     tags={"Vicons"},
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
     *         description="Search by title or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="created_by",
     *         in="query",
     *         description="Filter by creator",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="List of Vicons with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function apiIndex(Request $request)
    {
        $query = Vicon::query();

        // Filter berdasarkan created_by
        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Pencarian berdasarkan title atau desc
        if ($request->has('_search')) {
            $search = $request->_search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('desc', 'like', "%$search%");
            });
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $vicons = $query->paginate($perPage);

        return response()->json($vicons);
    }

    /**
     * Display a listing of vicons in the frontend
     */
    public function index(): View
    {
        $query = Vicon::query()
            ->with(['creator']);

        // Default ordering
        $query->orderBy('created_at', 'desc');

        // Get paginated results
        $vicons = $query->paginate(9);
        
        return view('vicon.index', compact('vicons'));
    }

    /**
     * @OA\Get(
     *     path="/api/vicons/active",
     *     tags={"Vicons"},
     *     summary="Get all active vicons",
     *     description="Returns list of active (not soft deleted) vicons",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active()
    {
        $vicons = Vicon::all();
        return response()->json($vicons, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/vicons",
     *     summary="Create new vicon",
     *     tags={"Vicons"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","desc","img","time","link"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="desc", type="string"),
     *                 @OA\Property(property="img", type="file"),
     *                 @OA\Property(property="time", type="string", format="date-time"),
     *                 @OA\Property(property="link", type="string"),
     *                 @OA\Property(property="download", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vicon created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vicon created successfully"),
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
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'time' => 'required|date',
            'link' => 'required|url',
            'download' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $vicon = new Vicon();
            $vicon->title = $request->title;
            $vicon->desc = $request->desc;
            $vicon->time = $request->time;
            $vicon->link = $request->link;
            $vicon->download = $request->download ?? null;
            $vicon->created_by = Auth::id();
            $vicon->img = $this->default_folder . '/default.jpg';
            $vicon->save();

            $timestamp = now()->format('Ymd_His');
            $random = mt_rand(100, 999);

            // Simpan gambar jika ada
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('vicons/images', $filename, 'public');
                $vicon->img = 'vicons/images/' . $filename;
                $vicon->save();
            }

            DB::commit();
            return response()->json(['message' => 'Vicon created successfully', 'data' => $vicon], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating vicon: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating vicon',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/vicons/{id}",
     *     summary="Get vicon by ID",
     *     tags={"Vicons"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vicon details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vicon not found"
     *     )
     * )
     */
    public function apiShow($id)
    {
        $vicon = Vicon::withTrashed()->find($id);
        
        if (!$vicon) {
            return response()->json(['message' => 'Vicon not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Increment view count logic could be added here if needed
        
        return response()->json($vicon, Response::HTTP_OK);
    }

    /**
     * Display the specified vicon in the frontend
     */
    public function show($id): View
    {
        $vicon = Vicon::with(['creator'])
            ->findOrFail($id);
            
        // Increment view count if such field exists
        if (property_exists($vicon, 'view_count')) {
            $vicon->increment('view_count', 1);
        }
            
        return view('vicon.show', compact('vicon'));
    }

    /**
     * @OA\Post(
     *     path="/api/vicons/{id}",
     *     summary="Update vicon",
     *     tags={"Vicons"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", nullable=true),
     *                 @OA\Property(property="desc", type="string", nullable=true),
     *                 @OA\Property(property="img", type="file", nullable=true),
     *                 @OA\Property(property="time", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="download", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vicon updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vicon not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'time' => 'nullable|date',
            'link' => 'nullable|url',
            'download' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            // Find vicon by ID
            $vicon = Vicon::find($id);
            if (!$vicon) {
                return response()->json(['message' => 'Vicon not found'], Response::HTTP_NOT_FOUND);
            }

            // Update vicon data
            $updateData = [];
            foreach(['title', 'desc', 'time', 'link', 'download'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            if (!empty($updateData)) {
                $vicon->update($updateData);
            }

            $timestamp = now()->format('Ymd_His');
            $random = mt_rand(100, 999);

            // Handle image upload
            if ($request->hasFile('img')) {
                if (!empty($vicon->img) && !str_contains($vicon->img, 'default.jpg')) {
                    $oldImagePath = storage_path('app/public/' . $vicon->img);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $file = $request->file('img');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = Str::slug($originalName);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$random}_{$slugName}_{$timestamp}.{$ext}";
                $path = $file->storeAs('vicons/images', $filename, 'public');
                $vicon->update(['img' => 'vicons/images/' . $filename]);
            }

            DB::commit();
            return response()->json(['message' => 'Vicon updated successfully', 'data' => $vicon], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating vicon: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating vicon', 
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/vicons/{id}",
     *     summary="Delete vicon",
     *     tags={"Vicons"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vicon deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vicon not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $vicon = Vicon::findOrFail($id);
            
            // Delete the image file if it exists and isn't the default
            if ($vicon->img && !str_contains($vicon->img, 'default.jpg')) {
                $path = storage_path('app/public/' . str_replace('/storage/', '', $vicon->img));
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Perform the deletion
            $vicon->delete();
            
            DB::commit();
            return response()->json(['message' => 'Vicon deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting vicon: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting vicon',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}