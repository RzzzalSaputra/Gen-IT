<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
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
     *     path="/api/jobs",
     *     summary="Get all jobs with pagination",
     *     tags={"Jobs"},
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
     *         description="Search by job title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="company_id",
     *         in="query",
     *         description="Filter by company ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by job type ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="experience",
     *         in="query",
     *         description="Filter by experience level",
     *         required=false,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Parameter(
     *         name="work_type",
     *         in="query",
     *         description="Filter by work type (office/remote/hybrid)",
     *         required=false,
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Parameter(
     *         name="province",
     *         in="query",
     *         description="Filter by province",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="Filter by city",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="List of jobs with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = Job::query();

        // Filter by company_id, type, experience, and work_type
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('experience')) {
            $query->where('experience', $request->experience);
        }

        if ($request->filled('work_type')) {
            $query->where('work_type', $request->work_type);
        }

        // Filter by province (through company relation)
        if ($request->filled('province')) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('province', $request->province);
            });
        }

        // Filter by city (through company relation)
        if ($request->filled('city')) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        // Search by title - MODIFIED FOR CASE-INSENSITIVE SEARCH
        if ($request->filled('_search')) {
            $searchTerm = $request->_search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
            });
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $jobs = $query->with(['company', 'jobType', 'experienceLevel', 'workType'])->paginate($perPage);

        // If this is an API request
        if (request()->is('api/*')) {
            return response()->json([
                'data' => $jobs->items(),
                'meta' => [
                    'current_page' => $jobs->currentPage(),
                    'last_page' => $jobs->lastPage(),
                    'per_page' => $jobs->perPage(),
                    'total' => $jobs->total(),
                ]
            ], Response::HTTP_OK);
        }

        // For web view
        // Get job types for filter tabs
        try {
            $jobTypes = \App\Models\Option::where('type', 'job_type')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching job types: ' . $e->getMessage());
            $jobTypes = collect();
        }

        // Get experience levels for dropdown
        try {
            $experienceLevels = \App\Models\Option::where('type', 'experience_level')->orderBy('id')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching experience levels: ' . $e->getMessage());
            $experienceLevels = collect();
        }

        // Get work types for dropdown
        try {
            $workTypes = \App\Models\Option::where('type', 'work_type')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching work types: ' . $e->getMessage());
            $workTypes = collect();
        }

        // Get companies for dropdown
        $companies = \App\Models\Company::orderBy('name')->get();
        
        // Get provinces for filtering
        $provinces = \App\Models\Company::distinct('province')->pluck('province')->filter()->values();
        
        // Get cities for filtering
        $cities = \App\Models\Company::distinct('city')->pluck('city')->filter()->values();
        
        // Get cities with their associated provinces
        $cityProvinceMap = \App\Models\Company::select('city', 'province')
            ->whereNotNull('city')
            ->whereNotNull('province')
            ->distinct()
            ->get()
            ->groupBy('province')
            ->map(function ($cities) {
                return $cities->pluck('city')->toArray();
            })
            ->toArray();

        return view('jobs.index', compact(
            'jobs', 
            'jobTypes', 
            'experienceLevels',
            'workTypes', 
            'companies',
            'provinces',
            'cities',
            'cityProvinceMap'
        ));
    }

    /**
     * @OA\Get(
     *     path="/api/jobs/active",
     *     tags={"Jobs"},
     *     summary="Get all active job listings",
     *     description="Returns list of active (not soft deleted) jobs",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        return response()->json(Job::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/jobs",
     *     summary="Create a new job listing",
     *     tags={"Jobs"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"company_id", "title", "description", "requirment", "salary_range", "register_link", "type", "experience", "work_type"},
     *                 @OA\Property(property="company_id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="requirment", type="string"),
     *                 @OA\Property(property="salary_range", type="integer"),
     *                 @OA\Property(property="register_link", type="string", description="URL or other text information for registration"),
     *                 @OA\Property(property="type", type="integer", description="ID of the option representing job type"),
     *                 @OA\Property(property="experience", type="integer", description="ID of the option representing required experience"),
     *                 @OA\Property(property="work_type", type="integer", description="ID of the option representing work type (office/remote/hybrid)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Job created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Job created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="company_id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="requirment", type="string"),
     *                 @OA\Property(property="salary_range", type="integer"),
     *                 @OA\Property(property="register_link", type="string"),
     *                 @OA\Property(property="type", type="integer"),
     *                 @OA\Property(property="experience", type="integer"),
     *                 @OA\Property(property="work_type", type="integer"),
     *                 @OA\Property(property="read_counter", type="integer"),
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
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirment' => 'required|string',
            'salary_range' => 'required|integer',
            'register_link' => 'required|string',
            'type' => 'required|exists:options,id',
            'experience' => 'required|exists:options,id',
            'work_type' => 'required|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $job = Job::create([
                'company_id' => $request->company_id,
                'title' => $request->title,
                'description' => $request->description,
                'requirment' => $request->requirment,
                'salary_range' => $request->salary_range,
                'register_link' => $request->register_link,
                'type' => $request->type,
                'experience' => $request->experience,
                'work_type' => $request->work_type,
                'read_counter' => 0,
            ]);

            DB::commit();
            return response()->json(['message' => 'Job created successfully', 'data' => $job], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating job: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating job',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jobs/{id}",
     *     tags={"Jobs"},
     *     summary="Get specific job by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found"
     *     )
     * )
     */
    public function show($id)
    {
        $job = Job::with(['company', 'jobType', 'experienceLevel', 'workType'])->findOrFail($id);

        // Increment read counter
        $job->increment('read_counter');

        // Check if it's an API request by URL pattern
        if (request()->is('api/*')) {
            return response()->json($job, Response::HTTP_OK);
        }
        
        // For web view requests, explicitly render the view
        return view('jobs.show', compact('job'));
    }

    /**
     * @OA\Post(
     *     path="/api/jobs/{id}",
     *     tags={"Jobs"},
     *     summary="Update an existing job listing",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="company_id", type="integer", nullable=true),
     *                 @OA\Property(property="title", type="string", nullable=true),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="requirment", type="string", nullable=true),
     *                 @OA\Property(property="salary_range", type="integer", nullable=true),
     *                 @OA\Property(property="register_link", type="string", nullable=true),
     *                 @OA\Property(property="type", type="integer", nullable=true),
     *                 @OA\Property(property="experience", type="integer", nullable=true),
     *                 @OA\Property(property="work_type", type="integer", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Job updated successfully"),
     *     @OA\Response(response=404, description="Job not found"),
     *     @OA\Response(response=422, description="Validation errors"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirment' => 'nullable|string',
            'salary_range' => 'nullable|integer',
            'register_link' => 'nullable|string',
            'type' => 'nullable|exists:options,id',
            'experience' => 'nullable|exists:options,id',
            'work_type' => 'nullable|exists:options,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Find job by ID
            $job = Job::find($id);
            if (!$job) {
                return response()->json(['message' => 'Job not found'], 404);
            }

            // Update job data
            $updateData = [];
            foreach(['company_id', 'title', 'description', 'requirment', 'salary_range', 
                    'register_link', 'type', 'experience', 'work_type'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }
            
            if (!empty($updateData)) {
                $job->update($updateData);
            }

            DB::commit();
            return response()->json(['message' => 'Job updated successfully', 'data' => $job], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error updating job', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/jobs/{id}",
     *     tags={"Jobs"},
     *     summary="Soft delete a job listing",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $job = Job::find($id);

        if (!$job) {
            Log::warning("Job with ID $id not found.");
            return response()->json(['message' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        Log::info('Soft deleting job:', ['id' => $job->id]);
        $job->delete();

        return response()->json(['message' => 'Job successfully soft deleted.', 'deleted_at' => $job->deleted_at], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/jobs/{id}/restore",
     *     tags={"Jobs"},
     *     summary="Restore a soft-deleted job",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job restored successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $job = Job::withTrashed()->findOrFail($id);
        $job->restore();
        return response()->json($job, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/companies/{companyId}/jobs",
     *     tags={"Jobs"},
     *     summary="Get all jobs for a specific company",
     *     @OA\Parameter(
     *         name="companyId",
     *         in="path",
     *         required=true,
     *         description="Company ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function getJobsByCompany(int $companyId): JsonResponse
    {
        $jobs = Job::where('company_id', $companyId)->get();
        return response()->json($jobs, Response::HTTP_OK);
    }
}