<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    protected $default_folder = 'gallery';

    public function __construct()
    {
        request()->headers->set('Accept', 'application/json');
    }

    /**
     * @OA\Get(
     *     path="/api/gallery",
     *     summary="Get all gallery items",
     *     tags={"Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of gallery items"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index()
    {
        $galleries = Gallery::all();
        return response()->json(['data' => $galleries]);
    }

    /**
     * @OA\Post(
     *     path="/api/gallery",
     *     summary="Create new gallery item",
     *     tags={"Gallery"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","type"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="type", type="integer"),
     *                 @OA\Property(property="file", type="file"),
     *                 @OA\Property(property="link", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Gallery item created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'type'    => 'required|integer',
            'file'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // File tidak wajib
            'link'    => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Buat gallery dengan informasi yang diperlukan
            $gallery = Gallery::create([
                'title'      => $request->title,
                'type'       => $request->type,
                'link'       => $request->link ?? null,
                'created_by'  => Auth::id(),
                'file'       => $request->file ?? null,
            ]);

            // Handle file upload jika ada
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $timestamp = Carbon::now()->format('Y-m-d_His');
                $filename = $gallery->id . '_' . $timestamp . '.' . $file->getClientOriginalExtension();


                // Simpan file ke storage/app/public/gallery
                $path = $file->storeAs('gallery', $filename, 'public');

                // Update path file di database
                $gallery->file = '/storage/gallery/' . $filename;
                $gallery->save();
            }

            DB::commit();
            return response()->json(['message' => 'Gallery item created successfully', 'data' => $gallery], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating gallery item ', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/gallery/{id}",
     *     summary="Get gallery item by ID",
     *     tags={"Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Gallery item details"),
     *     @OA\Response(response=404, description="Gallery item not found")
     * )
     */
    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json(['message' => 'Gallery item not found'], 404);
        }
        return response()->json(['data' => $gallery]);
    }

    /**
     * @OA\Post(
     *     path="/api/gallery/{id}",
     *     summary="Update gallery item",
     *     tags={"Gallery"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID dari gallery yang akan diperbarui",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", nullable=true, example="Gambar Baru"),
     *                 @OA\Property(property="type", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="link", type="string", nullable=true, format="url", example="https://example.com"),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true, description="File gambar yang akan diupload")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Gallery item updated successfully"),
     *     @OA\Response(response=404, description="Gallery item not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'type'    => 'nullable|integer',
            'file'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link'    => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Cari data gallery berdasarkan ID
            $gallery = Gallery::find($id);
            if (!$gallery) {
                return response()->json(['message' => 'Gallery item not found'], 404);
            }

            // Update data gallery
            $gallery->update([
                'title'  => $request->title ?? $gallery->title,
                'type'   => $request->type ?? $gallery->type,
                'link'   => $request->link ?? $gallery->link,
            ]);

            // Handle file upload jika ada
            if ($request->hasFile('file')) {
                
                // Hapus file jika ada
                if (!empty($gallery->file)) {
                    $path = storage_path('app/public/' . str_replace('/storage/', '', $gallery->file));

                    if (file_exists($path)) {
                        unlink($path); // Hapus file dengan PHP langsung
                    }
                }

                $file = $request->file('file');
                $timestamp = Carbon::now()->format('Y-m-d_His');
                $filename = $gallery->id . '_' . $timestamp . '.' . $file->getClientOriginalExtension();

                // Simpan file ke storage/app/public/gallery
                $path = $file->storeAs('gallery', $filename, 'public');

                // Update path file di database
                $gallery->file = '/storage/gallery/' . $filename;
                $gallery->save();
            }

            DB::commit();
            return response()->json(['message' => 'Gallery item updated successfully', 'data' => $gallery], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating gallery item', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/gallery/{id}",
     *     summary="Delete gallery item",
     *     tags={"Gallery"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Gallery item deleted successfully"),
     *     @OA\Response(response=404, description="Gallery item not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $gallery = Gallery::findOrFail($id);

            // Hapus file jika ada
            if (!empty($gallery->file)) {
                $path = storage_path('app/public/' . str_replace('/storage/', '', $gallery->file));

                if (file_exists($path)) {
                    unlink($path); // Hapus file dengan PHP langsung
                }
            }

            // Hapus data dari database (kalau pakai SoftDeletes, bisa pakai forceDelete)
            $gallery->delete();

            DB::commit();
            return response()->json(['message' => 'Gallery item deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error deleting gallery item',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
