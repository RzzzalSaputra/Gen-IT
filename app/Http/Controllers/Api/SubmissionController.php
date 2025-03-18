<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Submission;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class SubmissionController extends Controller
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
     *     path="/api/submissions",
     *     summary="Get all submissions with pagination",
     *     tags={"Submissions"},
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
     *         name="type",
     *         in="query",
     *         description="Filter by submission type",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by submission status",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="List of submissions with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request): JsonResponse|View
    {
        // For API requests
        if (request()->is('api/*')) {
            $query = Submission::query();

            // Filter berdasarkan status dan type
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Pencarian berdasarkan title atau content
            if ($request->has('_search')) {
                $search = $request->_search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('content', 'like', "%$search%");
                });
            }

            // Pagination
            $perPage = $request->_limit ?? 10;
            $submissions = $query->paginate($perPage);

            return response()->json($submissions);
        }
        
        // For web requests
        $submissions = Submission::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('submission.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get submission types from options table - using 'type' instead of 'group'
        $types = Option::where('type', 'submission_type')->get();
        
        return view('submission.create', compact('types'));
    }

    /**
     * @OA\Post(
     *     path="/api/submissions",
     *     summary="Store a newly created submission",
     *     tags={"Submissions"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "type"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="link", type="string"),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="type", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Submission created successfully"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Error creating submission")
     * )
     */
    public function store(Request $request)
    {
        // Validate based on request type
        if (request()->is('api/*')) {
            $validator = Validator::make($request->all(), [
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'file'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'link'    => 'nullable|string',
                'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type'    => 'required|integer|exists:options,id',
                'status'  => 'required|integer|exists:options,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        } else {
            $validator = Validator::make($request->all(), [
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'file'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'link'    => 'nullable|string|url',
                'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type'    => 'required|integer|exists:options,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // For web requests, get pending status ID
            $statusId = $request->status ?? null;
            
            if (!request()->is('api/*')) {
                $pendingStatus = Option::where('value', 'pending')
                    ->where('type', 'submission_status') // Changed from 'group' to 'type'
                    ->first();
                
                if (!$pendingStatus) {
                    if (request()->is('api/*')) {
                        return response()->json(['message' => 'Status "pending" not found in options'], 500);
                    }
                    return redirect()->back()->with('error', 'Status "pending" not found in options.')->withInput();
                }
                
                $statusId = $pendingStatus->id;
            }

            $submission = Submission::create([
                'title'      => $request->title,
                'content'    => $request->content,
                'file'       => null,
                'link'       => $request->link,
                'img'        => null,
                'type'       => $request->type,
                'status'     => $statusId,
                'created_by' => Auth::id(),
            ]);

            $timestamp = now()->format('Ymd_His');

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                $filename = "{$submission->id}_{$timestamp}.{$ext}";
                $path = $file->storeAs('submissions/files', $filename, 'public');
                $submission->file = '/storage/' . $path;
            }

            if ($request->hasFile('img')) {
                $img = $request->file('img');
                $imgExt = $img->getClientOriginalExtension();
                $imgName = "{$submission->id}_{$timestamp}.{$imgExt}";
                $imgPath = $img->storeAs('submissions/images', $imgName, 'public');
                $submission->img = '/storage/' . $imgPath;
            }

            $submission->save();
            DB::commit();

            if (request()->is('api/*')) {
                return response()->json(['message' => 'Submission created successfully', 'data' => $submission], 201);
            }
            
            return redirect()->route('submissions.index')->with('success', 'Your submission has been sent for approval.');
        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->is('api/*')) {
                return response()->json(['message' => 'Error creating submission', 'error' => $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Error creating submission: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *     path="/api/submissions/{id}",
     *     summary="Get submission by ID",
     *     tags={"Submissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Submission details"),
     *     @OA\Response(response=404, description="Submission not found")
     * )
     */
    public function show($id)
    {
        $submission = Submission::findOrFail($id);
        
        if (request()->is('api/*')) {
            return response()->json(['data' => $submission]);
        }
        
        // For web view - only allow creators to view their own submissions
        if ($submission->created_by !== Auth::id()) {
            return redirect()->route('submissions.index')->with('error', 'You do not have permission to view this submission.');
        }
        
        return view('submission.show', compact('submission'));
    }

    /**
     * @OA\Post(
     *     path="/api/submissions/{id}",
     *     tags={"Submissions"},
     *     summary="Update an existing submission",
     *     description="Update a submission with optional file and image uploads.",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", nullable=true),
     *                 @OA\Property(property="content", type="string", nullable=true),
     *                 @OA\Property(property="file", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="link", type="string", nullable=true),
     *                 @OA\Property(property="img", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="type", type="integer", nullable=true),
     *                 @OA\Property(property="status", type="integer", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Submission updated successfully"),
     *     @OA\Response(response=404, description="Submission not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'file'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'link'    => 'nullable|string',
            'img'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type'    => 'nullable|integer|exists:options,id',
            'status'  => 'nullable|integer|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $submission = Submission::find($id);
            if (!$submission) {
                return response()->json(['message' => 'Submission not found'], 404);
            }

            $submission->update([
                'title'      => $request->title ?? $submission->title,
                'content'    => $request->content ?? $submission->content,
                'link'       => $request->link ?? $submission->link,
                'type'       => $request->type ?? $submission->type,
                'status'     => $request->status ?? $submission->status,
            ]);

            $timestamp = now()->format('Ymd_His');

            // Handle file upload
            if ($request->hasFile('file')) {
                if (!empty($submission->file)) {
                    $oldFilePath = storage_path('app/public/' . str_replace('/storage/', '', $submission->file));
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file = $request->file('file');
                $filename = "file_{$submission->id}_{$timestamp}." . $file->getClientOriginalExtension();
                $path = $file->storeAs('submissions/files', $filename, 'public');
                $submission->update(['file' => '/storage/' . $path]);
            }

            // Handle image upload
            if ($request->hasFile('img')) {
                if (!empty($submission->img)) {
                    $oldImagePath = storage_path('app/public/' . str_replace('/storage/', '', $submission->img));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('img');
                $imageName = "img_{$submission->id}_{$timestamp}." . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('submissions/images', $imageName, 'public');
                $submission->update(['img' => '/storage/' . $imagePath]);
            }

            DB::commit();
            return response()->json(['message' => 'Submission updated successfully', 'data' => $submission], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating submission', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/submissions/{id}/approve",
     *     summary="Approve a submission",
     *     tags={"Submissions"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Submission approved successfully"),
     *     @OA\Response(response=404, description="Submission not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function updateApproval($id)
    {
        DB::beginTransaction();
        try {
            $submission = Submission::find($id);
            if (!$submission) {
                return response()->json(['message' => 'Submission not found'], 404);
            }

            $submission->update([
                'approve_at' => now(),
                'approve_by' => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Submission approved successfully', 'data' => $submission], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error approving submission', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/submissions/{id}",
     *     summary="Delete a submission",
     *     tags={"Submissions"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Submission ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Submission deleted successfully"),
     *     @OA\Response(response=404, description="Submission not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $submission = Submission::find($id);
            if (!$submission) {
                return response()->json(['message' => 'Submission not found'], 404);
            }

            $submission->delete();
            DB::commit();
            return response()->json(['message' => 'Submission deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error deleting submission', 'error' => $e->getMessage()], 500);
        }
    }
}