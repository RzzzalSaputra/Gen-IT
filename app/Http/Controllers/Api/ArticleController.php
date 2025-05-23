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
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="Display a listing of articles",
     *     operationId="articleIndex",
     *     @OA\Response(
     *         response=200,
     *         description="successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Parameter(
     *         name="_page",
     *         in="query",
     *         description="Current page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_limit",
     *         in="query",
     *         description="Max items in a page",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=10
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_search",
     *         in="query",
     *         description="Word to search in title, content, summary, or writer",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_dir",
     *         in="query",
     *         description="Order by direction (asc/desc)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="asc"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by type",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=2
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="created_by",
     *         in="query",
     *         description="Filter by creator ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=5
     *         )
     *     ),
     * )
     */

    /**
     * Handle the API request for article listing
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Article::query()->withTrashed();

        // Remove status and type filters
        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Filter based on search in title, content, summary, or writer - with case insensitivity
        if ($request->has('_search')) {
            $search = $request->_search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(writer) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Sorting (default: desc)
        $direction = $request->_dir ?? 'desc';
        $query->orderBy('created_at', $direction);

        // Pagination
        $perPage = $request->_limit ?? 5;
        $articles = $query->paginate($perPage);

        return response()->json($articles);
    }

    /**
     * Display a listing of articles in the frontend
     */
    public function index(Request $request): View
    {
        $query = Article::query()
            ->with(['statusOption', 'typeOption', 'creator']);

        // Search functionality with case insensitivity
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(writer) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Default ordering
        $query->orderBy('created_at', 'desc');

        // Get paginated results
        $articles = $query->paginate(9);
        
        return view('article.index', compact('articles'));
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
    public function apiShow(int $id): JsonResponse
    {
        $article = Article::with(['statusOption', 'typeOption', 'creator'])
            ->findOrFail($id);
            
        return response()->json($article);
    }

    /**
     * Display the specified article in the frontend
     */
    public function show($id): View
    {
        $article = Article::with(['statusOption', 'typeOption', 'creator'])
            ->findOrFail($id);
            
        // Increment read counter if it exists
        if (property_exists($article, 'read_counter')) {
            $article->increment('read_counter', 1);
        }
            
        return view('article.show', compact('article'));
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