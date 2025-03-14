<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Contact;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
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
     *     path="/api/contacts",
     *     tags={"Contacts"},
     *     summary="Display a listing of contacts",
     *     operationId="contactIndex",
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
     *         name="respond_by",
     *         in="query",
     *         description="Filter by user who responded",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=5
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
     *             example=2
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
     *         name="_dir",
     *         in="query",
     *         description="Order by direction (asc/desc)",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="asc"
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse|View
    {
        // For API requests
        if (request()->is('api/*')) {
            $query = Contact::query()->withTrashed();

            if ($request->has('respond_by')) {
                $query->where('respond_by', $request->respond_by);
            }
            if ($request->has('created_by')) {
                $query->where('created_by', $request->created_by);
            }
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $direction = $request->_dir ?? 'desc';
            $query->orderBy('created_at', $direction);

            $perPage = $request->_limit ?? 10;
            $contacts = $query->paginate($perPage);

            return response()->json($contacts, 200);
        }
        
        // For web requests
        $contacts = Contact::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('contact.index', compact('contacts'));
    }

    public function create()
    {
        return view('contact.create');
    }

    /**
     * @OA\Post(
     *     path="/api/contacts",
     *     summary="Create new contact",
     *     tags={"Contacts"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"message"},
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contact created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            if (request()->is('api/*')) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Cari ID dari option dengan value = "pending"
            $pendingStatus = Option::where('value', 'pending')->first();

            if (!$pendingStatus) {
                if (request()->is('api/*')) {
                    return response()->json(['message' => 'Status "pending" tidak ditemukan'], 500);
                }
                return redirect()->back()->with('error', 'Status "pending" tidak ditemukan.');
            }

            $contact = Contact::create([
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'message' => $request->message,
                'created_by' => Auth::id(),
                'status' => $pendingStatus->id
            ]);

            DB::commit();

            if (request()->is('api/*')) {
                return response()->json(['message' => 'Contact created successfully', 'data' => $contact], 201);
            }
            return redirect()->route('contacts.index')->with('success', 'Pesan anda telah terkirim! Admin akan segera meresponnya.');
        } catch (\Exception $e) {
            DB::rollback();

            if (request()->is('api/*')) {
                return response()->json(['message' => 'Error creating contact', 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/contacts/{id}",
     *     summary="Get contact by ID",
     *     tags={"Contacts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact details"
     *     )
     * )
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        
        if (request()->is('api/*')) {
            return response()->json(['data' => $contact]);
        }
        
        // For web view if needed
        return view('contact.show', compact('contact'));
    }

    /**
     * @OA\Put(
     *     path="/api/contacts/{id}",
     *     summary="Update contact message only",
     *     tags={"Contacts"},
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
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"message"},
     *                 @OA\Property(property="message", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact message updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Contact message updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="message", type="string", example="New message content")
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $contact = Contact::findOrFail($id);
            $contact->update(['message' => $request->message]);
            DB::commit();

            return response()->json([
                'message' => 'Contact message updated successfully',
                'data' => $contact
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error updating contact message',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/contacts/{id}/respond",
     *     summary="Update response for contact",
     *     tags={"Contacts"},
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
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"respond_message"},
     *                 @OA\Property(property="respond_message", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Response updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Response updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="respond_message", type="string", example="Your response here"),
     *                 @OA\Property(property="status", type="string", example="responded"),
     *                 @OA\Property(property="respond_by", type="integer", example=10)
     *             )
     *         )
     *     )
     * )
     */
    public function updateResponse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'respond_message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Ambil status ID dari tabel options berdasarkan value "responded"
            $status = Option::where('value', 'responded')->first();
            if (!$status) {
                return response()->json(['message' => 'Status "responded" not found'], 404);
            }

            $contact = Contact::findOrFail($id);
            $contact->update([
                'respond_message' => $request->respond_message,
                'respond_by' => Auth::id(),
                'status' => $status->id // Simpan status ID dari tabel options
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Response updated successfully',
                'data' => $contact
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error updating response',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/contacts/{id}",
     *     summary="Delete contact",
     *     tags={"Contacts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Success deleting contact with ID: 5")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();
            DB::commit();

            return response()->json([
                'message' => "Success deleting contact with ID: $id"
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error deleting contact',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/contacts/{id}/restore",
     *     summary="Restore deleted contact",
     *     tags={"Contacts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact restored successfully"
     *     )
     * )
     */
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $contact = Contact::onlyTrashed()->findOrFail($id);

            $contact->restore();
            DB::commit();
            return response()->json(['message' => 'Contact restored successfully', 'data' => $contact], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error restoring contact', 'error' => $e->getMessage()], 500);
        }
    }
}