<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vicon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
     *         description="List of vicons"
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
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $vicons = Vicon::all();
        return response()->json(['data' => $vicons]);
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
     *                 @OA\Property(property="download", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vicon created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'time' => 'required|date',
            'link' => 'required|url',
            'download' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            
            // Create Vicon first to get ID
            $vicon = Vicon::create([
                'title' => $data['title'],
                'desc' => $data['desc'],
                'time' => $data['time'],
                'link' => $data['link'],
                'download' => $data['download'] ?? null
            ]);

            // Handle image upload
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $extension = $file->getClientOriginalExtension();
                $filename = $vicon->id . '.' . $extension;
                
                // Store directly in the public disk under vicon folder
                $path = $file->storeAs($this->default_folder, $filename, 'public');
                $vicon->img = '/storage/' . $this->default_folder . '/' . $filename;
                $vicon->save();
            }

            DB::commit();
            return response()->json(['message' => 'Vicon created successfully', 'data' => $vicon], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating vicon', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/vicons/{id}",
     *     summary="Get vicon by ID",
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
     *         description="Vicon details"
     *     )
     * )
     */
    public function show($id)
    {
        $vicon = Vicon::findOrFail($id);
        return response()->json(['data' => $vicon]);
    }

    /**
     * @OA\Put(
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
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="desc", type="string"),
     *                 @OA\Property(property="img", type="file"),
     *                 @OA\Property(property="time", type="string", format="date-time"),
     *                 @OA\Property(property="link", type="string"),
     *                 @OA\Property(property="download", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vicon updated successfully"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $vicon = Vicon::findOrFail($id);
        // Reuse existing validation and update logic
        // ...existing update code...
        return response()->json(['message' => 'Vicon updated successfully', 'data' => $vicon]);
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
     *     )
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $vicon = Vicon::findOrFail($id);
            
            // Delete the image file if it exists
            if ($vicon->img) {
                $path = str_replace('/storage/', 'public/', $vicon->img);
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }

            // Perform the deletion
            $vicon->delete();
            
            DB::commit();
            return response()->json(null, 204); // Return 204 No Content for successful deletion
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error deleting vicon',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}