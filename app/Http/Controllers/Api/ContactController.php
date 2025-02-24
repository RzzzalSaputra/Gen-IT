<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        request()->headers->set('Accept', 'application/json');
    }

    /**
     * @OA\Get(
     *     path="/api/contacts",
     *     summary="Get all contacts",
     *     tags={"Contacts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of contacts"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized - Admin access required"
     *     )
     * )
     */
    public function index()
    {

        $contacts = Contact::all();
        return response()->json(['data' => $contacts]);
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Cari ID dari option dengan value = "pending"
            $pendingStatus = Option::where('value', 'pending')->first();

            if (!$pendingStatus) {
                return response()->json(['message' => 'Status "pending" tidak ditemukan di options'], 500);
            }

            $contact = Contact::create([
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'message' => $request->message,
                'created_by' => Auth::id(),
                'status' => $pendingStatus->id // Assign status "pending" ke contact
            ]);

            DB::commit();
            return response()->json(['message' => 'Contact created successfully', 'data' => $contact], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error creating contact', 'error' => $e->getMessage()], 500);
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
        return response()->json(['data' => $contact]);
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