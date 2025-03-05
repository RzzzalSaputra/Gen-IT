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
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

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
     *     summary="Get all vicons",
     *     tags={"Vicons"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of all vicons including soft deleted ones"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Admin access required"
     *     )
     * )
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $vicons = Vicon::withTrashed()->get();
        return response()->json($vicons, Response::HTTP_OK);
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
            'download' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // Create Vicon with default image
            $vicon = new Vicon([
                'title' => $data['title'],
                'desc' => $data['desc'],
                'time' => $data['time'],
                'link' => $data['link'],
                'download' => $data['download'] ?? null,
                'created_by' => Auth::id(),
                'img' => '/storage/' . $this->default_folder . '/default.jpg' // Default image
            ]);

            // Handle image upload
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $imageName = 'img_' . time() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs($this->default_folder, $imageName, 'public');
                $vicon->img = '/storage/' . $imagePath;
            }

            $vicon->save();

            DB::commit();
            return response()->json(['message' => 'Vicon created successfully', 'data' => $vicon], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
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
    public function show($id)
    {
        $vicon = Vicon::withTrashed()->find($id);
        
        if (!$vicon) {
            return response()->json(['message' => 'Vicon not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Increment view count logic could be added here if needed
        
        return response()->json($vicon, Response::HTTP_OK);
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

            // Handle image upload if provided
            if ($request->hasFile('img')) {
                // Delete previous image if it exists and isn't the default
                if (!empty($vicon->img) && !str_contains($vicon->img, 'default.jpg')) {
                    $oldImagePath = str_replace('/storage/', '', $vicon->img);
                    if (Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                    }
                }

                $image = $request->file('img');
                $timestamp = Carbon::now()->format('Y-m-d_His');
                $imageName = $vicon->id . '_' . $timestamp . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs($this->default_folder, $imageName, 'public');
                $vicon->update(['img' => '/storage/' . $imagePath]);
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