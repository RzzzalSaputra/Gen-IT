<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all posts with pagination",
     *     tags={"Posts"},
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
     *         description="Search by title or content",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="created_by",
     *         in="query",
     *         description="Filter by creator ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="layout",
     *         in="query",
     *         description="Filter by layout ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(response=200, description="List of posts with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function apiIndex(Request $request)
    {
        $query = Post::query()->withTrashed();

        // Filter berdasarkan pembuat
        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Filter berdasarkan layout
        if ($request->has('layout')) {
            $query->where('layout', $request->layout);
        }

        // Pencarian berdasarkan title atau content
        if ($request->has('_search')) {
            $search = $request->_search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Sorting (default: desc)
        $direction = $request->_dir ?? 'desc';
        $query->orderBy('created_at', $direction);

        // Pagination
        $perPage = $request->_limit ?? 10;
        $posts = $query->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * Display a listing of posts in the frontend
     */
    public function index(Request $request): View
    {
        $query = Post::query()
            ->with(['option', 'user']);

        // Search functionality with case insensitivity
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Default ordering
        $query->orderBy('created_at', 'desc');

        // Get paginated results
        $posts = $query->paginate(9);
        
        return view('post.index', compact('posts'));
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
     *                 @OA\Property(property="video_url", type="string", nullable=true),
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
     *                 @OA\Property(property="video_url", type="string", nullable=true),
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
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'video_url' => 'nullable|string|url',
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
                'video_url' => $data['video_url'] ?? null,
                'layout' => $data['layout'],
                'created_by' => $userId,
            ]);

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $timestamp = now()->format('Ymd_His');
                $random = mt_rand(100, 999);

                // Get original filename and create slug
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = Str::slug($originalName);

                // Generate a unique filename
                $filename = "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";

                // Store the file in the 'posts/files' directory inside the public disk
                $filePath = $file->storeAs('posts/files', $filename, 'public');
                $post->file = $filePath;
            }

            // Handle image upload
            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $timestamp = now()->format('Ymd_His');
                $random = mt_rand(100, 999);

                // Get original image filename and create slug
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = Str::slug($originalName);

                // Generate a unique image filename
                $imageName = "{$random}_{$slugName}_{$timestamp}.{$image->getClientOriginalExtension()}";

                // Store the image in the 'posts/images' directory inside the public disk
                $imagePath = $image->storeAs('posts/images', $imageName, 'public');
                $post->img = $imagePath;
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
    public function apiShow(int $id): JsonResponse
    {
        $post = Post::with(['option', 'user'])
            ->findOrFail($id);
            
        return response()->json($post);
    }

    /**
     * Display the specified post in the frontend
     */
    public function show($id): View
    {
        $post = Post::with(['option', 'user'])
            ->findOrFail($id);
            
        // Increment counter if it exists
        if (property_exists($post, 'counter')) {
            $post->increment('counter', 1);
        }
            
        return view('post.show', compact('post'));
    }

    /**
     * @OA\Post(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Update an existing post",
     *     description="Update a post with optional file and image uploads.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
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
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="video_url", type="string", nullable=true),
     *                 @OA\Property(property="layout", type="integer", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Post updated successfully"),
     *     @OA\Response(response=404, description="Post not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'slug'    => 'nullable|string|max:255|unique:posts,slug,' . $id,
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|string|url',
            'layout'  => 'nullable|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Cari data post berdasarkan ID
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }

            // Update data post
            $post->update([
                'title'   => $request->title ?? $post->title,
                'slug'    => $request->slug ?? $post->slug,
                'content' => $request->content ?? $post->content,
                'video_url' => $request->video_url ?? $post->video_url,
                'layout'  => $request->layout ?? $post->layout,
            ]);

            // Handle file upload jika ada
            if ($request->hasFile('file')) {
                if (!empty($post->file)) {
                    $path = storage_path('app/public/' . str_replace('/storage/', '', $post->file));
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $file = $request->file('file');
                $timestamp = now()->format('Ymd_His');
                $random = mt_rand(100, 999);

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = Str::slug($originalName);

                // Generate a unique filename
                $filename = "{$random}_{$slugName}_{$timestamp}.{$file->getClientOriginalExtension()}";

                // Store the file in the 'posts/files' directory inside the public disk
                $filePath = $file->storeAs('posts/files', $filename, 'public');
                // Save the relative path (without the storage prefix)
                $post->update(['file' => 'posts/files/' . $filename]);
            }

            // Handle image upload jika ada
            if ($request->hasFile('img')) {
                if (!empty($post->img)) {
                    $path = storage_path('app/public/' . str_replace('/storage/', '', $post->img));
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                $image = $request->file('img');
                $timestamp = now()->format('Ymd_His');
                $random = mt_rand(100, 999);

                // Get original image name and create slug
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $slugName = Str::slug($originalName);

                // Generate a unique image filename
                $imageName = "{$random}_{$slugName}_{$timestamp}.{$image->getClientOriginalExtension()}";

                // Store the image in the 'posts/images' directory inside the public disk
                $imagePath = $image->storeAs('posts/images', $imageName, 'public');
                // Save the relative path (without the storage prefix)
                $post->update(['img' => 'posts/images/' . $imageName]);
            }

            DB::commit();
            return response()->json(['message' => 'Post updated successfully', 'data' => $post], 200);
        } catch (\Exception $e) {
            DB::rollback();
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

        Log::info('Soft deleting post:', ['id' => $post->id]);
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
        return response()->json(['message' => 'Post restored successfully', 'data' => $post], Response::HTTP_OK);
    }
}
