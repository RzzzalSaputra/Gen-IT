<?php
namespace App\Http\Controllers\Api;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class OptionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/options",
     *     summary="Get all options with pagination",
     *     tags={"Options"},
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
     *         description="Search by option value",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by option type",
     *         required=false,
     *         @OA\Schema(type="string", example="job_type")
     *     ),
     *     @OA\Response(response=200, description="List of options with pagination"),
     *     @OA\Response(response=403, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = Option::query();

        // Filter berdasarkan type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Pencarian berdasarkan value
        if ($request->has('_search')) {
            $query->where('value', 'like', "%{$request->_search}%");
        }

        // Pagination
        $perPage = $request->_limit ?? 10;
        $options = $query->paginate($perPage);

        return response()->json($options);
    }

    /**
     * @OA\Get(
     *     path="/api/options/active",
     *     tags={"Options"},
     *     summary="Get all active options",
     *     description="Returns list of active (not soft deleted) options",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="type", type="string"),
     *                 @OA\Property(property="value", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="updated_at", type="string", format="datetime")
     *             )
     *         )
     *     )
     * )
     */
    public function active(): JsonResponse
    {
        return response()->json(Option::all(), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/options",
     *     tags={"Options"},
     *     summary="Create a new option",
     *     description="Store a newly created option",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type","value"},
     *             @OA\Property(property="type", type="string", maxLength=255),
     *             @OA\Property(property="value", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Option created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="value", type="string"),
     *             @OA\Property(property="created_at", type="string", format="datetime"),
     *             @OA\Property(property="updated_at", type="string", format="datetime")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $option = Option::create($validatedData);
        return response()->json($option, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/options/{id}",
     *     tags={"Options"},
     *     summary="Get specific option by ID",
     *     description="Returns a specific option by ID including soft deleted ones",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Option ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="value", type="string"),
     *             @OA\Property(property="created_at", type="string", format="datetime"),
     *             @OA\Property(property="updated_at", type="string", format="datetime"),
     *             @OA\Property(property="deleted_at", type="string", format="datetime", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $option = Option::withTrashed()->find($id);

        if (!$option) {
            return response()->json(['message' => 'Option not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($option, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/options/{id}",
     *     tags={"Options"},
     *     summary="Update an option",
     *     description="Update the specified option",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Option ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string", maxLength=255),
     *             @OA\Property(property="value", type="string", maxLength=255)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Option updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="value", type="string"),
     *             @OA\Property(property="created_at", type="string", format="datetime"),
     *             @OA\Property(property="updated_at", type="string", format="datetime")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(Request $request, Option $option): JsonResponse
    {
        $validatedData = $request->validate([
            'type' => 'sometimes|required|string|max:255',
            'value' => 'sometimes|required|string|max:255',
        ]);

        $option->update($validatedData);
        return response()->json($option, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/options/{id}",
     *     tags={"Options"},
     *     summary="Soft delete an option",
     *     description="Soft delete the specified option",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Option ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Option deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option not found"
     *     )
     * )
     */
    public function destroy(Option $option): JsonResponse
    {
        Log::info('Deleting option:', ['id' => $option->id]);
        $option->delete();
        Log::info('Option after delete:', ['option' => $option->toArray()]);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     *     path="/api/options/{id}/restore",
     *     tags={"Options"},
     *     summary="Restore a soft-deleted option",
     *     description="Restore a previously soft-deleted option",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Option ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Option restored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="value", type="string"),
     *             @OA\Property(property="created_at", type="string", format="datetime"),
     *             @OA\Property(property="updated_at", type="string", format="datetime")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Option not found"
     *     )
     * )
     */
    public function restore(int $id): JsonResponse
    {
        $option = Option::withTrashed()->findOrFail($id);
        $option->restore();
        return response()->json($option, Response::HTTP_OK);
    }
}