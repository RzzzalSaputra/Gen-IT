<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="Get all articles including soft deleted ones",
     *     description="Returns list of all articles including soft deleted records",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Article::withTrashed()->get(), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/articles/active",
     *     tags={"Articles"},
     *     summary="Get all active articles",
     *     description="Returns list of active (not soft deleted) articles",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        return response()->json(Article::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Create a new article",
     *     tags={"Articles"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "slug", "content", "summary", "status", "type", "writer", "post_time"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="slug", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="summary", type="string"),
     *                 @OA\Property(property="status", type="integer"),
     *                 @OA\Property(property="type", type="integer"),
     *                 @OA\Property(property="writer", type="string"),
     *                 @OA\Property(property="post_time", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Article created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Article created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug',
            'content' => 'required|string',
            'summary' => 'required|string|max:255',
            'status' => 'required|exists:options,id',
            'type' => 'required|exists:options,id',
            'writer' => 'required|string|max:255',
            'post_time' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $userId = Auth::id();

            $article = Article::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'summary' => $data['summary'],
                'status' => $data['status'],
                'type' => $data['type'],
                'writer' => $data['writer'],
                'post_time' => $data['post_time'],
                'created_by' => $userId  // Use created_by instead of create_by
            ]);

            DB::commit();

            return response()->json(['message' => 'Article created successfully', 'data' => $article], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating article', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Get specific article by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Article ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $article = Article::withTrashed()->find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($article, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Update an existing article",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Article ID",
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
     *                 @OA\Property(property="summary", type="string", nullable=true),
     *                 @OA\Property(property="status", type="integer", nullable=true),
     *                 @OA\Property(property="type", type="integer", nullable=true),
     *                 @OA\Property(property="writer", type="string", nullable=true),
     *                 @OA\Property(property="post_time", type="string", format="date-time", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Article updated successfully"),
     *     @OA\Response(response=404, description="Article not found"),
     *     @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $id,
            'content' => 'nullable|string',
            'summary' => 'nullable|string|max:255',
            'status' => 'nullable|exists:options,id',
            'type' => 'nullable|exists:options,id',
            'writer' => 'nullable|string|max:255',
            'post_time' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $article = Article::find($id);
            if (!$article) {
                return response()->json(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
            }

            // Update article with only provided fields
            $article->update($request->only([
                'title', 'slug', 'content', 'summary', 'status', 'type', 'writer', 'post_time'
            ]));

            DB::commit();
            return response()->json(['message' => 'Article updated successfully', 'data' => $article], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating article', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Soft delete an article",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Article ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            Log::warning("Article with ID $id not found.");
            return response()->json(['message' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }

        Log::info('Soft deleting article:', ['id' => $article->id]);
        $article->delete();

        return response()->json([
            'message' => 'Article successfully soft deleted.',
            'deleted_at' => $article->deleted_at
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/articles/{id}/restore",
     *     tags={"Articles"},
     *     summary="Restore a soft-deleted article",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Article ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $article = Article::withTrashed()->findOrFail($id);
        $article->restore();
        // No need to manually set delete_at to null

        return response()->json(['message' => 'Article restored successfully', 'data' => $article], Response::HTTP_OK);
    }
}