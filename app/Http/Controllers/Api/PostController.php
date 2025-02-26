<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


/**
 * @OA\Tag(
 *     name="Posts",
 *     description="API Endpoints for Posts management"
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Get all posts including soft deleted ones",
     *     description="Returns list of all posts including soft deleted records",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Post::withTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/active",
     *     tags={"Posts"},
     *     summary="Get all active posts",
     *     description="Returns list of active (not soft deleted) posts",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        return response()->json(Post::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "slug", "content", "layout"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="layout", type="integer"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="file", type="string", nullable=true, example="/storage/posts/files/file_1.pdf"),
     *                 @OA\Property(property="img", type="string", nullable=true, example="/storage/posts/images/img_1.jpg"),
     *                 @OA\Property(property="layout", type="integer"),
     *                 @OA\Property(property="created_by", type="integer"),
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
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'layout' => 'required|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $userId = Auth::id();

            $post = Post::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'layout' => $data['layout'],
                'created_by' => $userId,
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = 'file_' . $post->id . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('posts/files', $fileName, 'public');
                $post->file = '/storage/' . $filePath;
            }

            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $imageName = 'img_' . $post->id . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('posts/images', $imageName, 'public');
                $post->img = '/storage/' . $imagePath;
            }

            $post->save();
            DB::commit();

            return response()->json(['message' => 'Post created successfully', 'data' => $post], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating post', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Get specific post by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::withTrashed()->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($post, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Fully update a post",
     *     description="Update all fields of an existing post. Supports optional file and image uploads.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "slug", "content", "layout"},
     *                 @OA\Property(property="title", type="string", example="Updated Title"),
     *                 @OA\Property(property="slug", type="string", example="updated-title"),
     *                 @OA\Property(property="content", type="string", example="Updated full content"),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="layout", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="title", type="string", example="Updated Title"),
     *                 @OA\Property(property="slug", type="string", example="updated-title"),
     *                 @OA\Property(property="content", type="string", example="Updated full content"),
     *                 @OA\Property(property="file", type="string", nullable=true, example="/storage/posts/files/file_5.pdf"),
     *                 @OA\Property(property="img", type="string", nullable=true, example="/storage/posts/images/img_5.jpg"),
     *                 @OA\Property(property="layout", type="integer", example=2),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-24T08:00:00Z")
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
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error updating post"),
     *             @OA\Property(property="error", type="string", example="Exception message")
     *         )
     *     )
     * )
     */

    public function update(Request $request, Post $post): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'slug' => 'string|max:255|unique:posts,slug,' . $post->id,
            'content' => 'string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'layout' => 'exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $data = $request->except(['file', 'img']);
            $post->update($data);

            if ($request->hasFile('file')) {
                if ($post->file) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $post->file));
                }
                $file = $request->file('file');
                $fileName = 'file_' . $post->id . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('posts/files', $fileName, 'public');
                $data['file'] = '/storage/' . $filePath;
            }

            if ($request->hasFile('img')) {
                if ($post->img) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $post->img));
                }
                $image = $request->file('img');
                $imageName = 'img_' . $post->id . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('posts/images', $imageName, 'public');
                $data['img'] = '/storage/' . $imagePath;
            }

            if (isset($data['file']) || isset($data['img'])) {
                $post->update($data);
            }

            DB::commit();
            $post->refresh();

            return response()->json(['message' => 'Post updated successfully', 'data' => $post], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating post', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Soft delete a post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post deleted successfully"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            Log::warning("Post with ID $id not found.");
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        Log::info('Soft deleting contact:', ['id' => $post->id]);
        $post->delete();

        return response()->json(['message' => 'Post successfully soft deleted.', 'deleted_at' => $post->deleted_at], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/posts/{id}/restore",
     *     tags={"Posts"},
     *     summary="Restore a soft-deleted post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post restored successfully"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        return response()->json($post, Response::HTTP_OK);
    }
}
