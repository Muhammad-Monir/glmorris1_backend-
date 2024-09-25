<?php

namespace App\Http\Controllers\Api\Backend;

use Exception;
use App\Models\Item;
use App\Models\Room;
use App\Helper\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function show($id)
    {
        try {

            $room = Room::with('items')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $room,
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Room not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.location_id' => 'required|exists:locations,id',
            '*.room_name' => 'required|string|max:255',
            '*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            '*.items' => 'required|array',
            '*.items.*.item_name' => 'required|string|max:255',
            '*.items.*.location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        try {
            DB::beginTransaction();

            foreach ($request->all() as $roomData) {
                $room = new Room();
                $room->location_id = $roomData['location_id'];
                $room->room_name = $roomData['room_name'];

                if (isset($roomData['photo']) && $roomData['photo']) {
                    $randomString = (string) Str::uuid();
                    $roomPhoto = Helper::fileUpload($roomData['photo'], 'rooms', $roomData['photo']->getClientOriginalName() . '_' . $randomString);
                    $room->photo = $roomPhoto;
                }

                $room->save();

                foreach ($roomData['items'] as $itemData) {
                    $item = new Item();
                    $item->room_id = $room->id;
                    $item->item_name = $itemData['item_name'];
                    $item->location = $itemData['location'] ?? null;

                    $item->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Created successfully',
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'room_name'       => 'required|string|max:255',
            'room_photo'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',

            // Validation for items (array)
            'items'                  => 'required|array',
            'items.*.id'             => 'required|exists:items,id',
            'items.*.item_name'      => 'required|string|max:255',
            'items.*.location'       => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        try {

            DB::beginTransaction();


            $room = Room::findOrFail($id);
            $room->room_name = $request->room_name;


            if ($request->hasFile('photo')) {
                $randomString = (string) Str::uuid();
                $roomPhoto = Helper::fileUpload($request->file('photo'), 'rooms', $request->photo->getClientOriginalName() . '_' . $randomString);
                $room->photo = $roomPhoto;
            }

            $room->save();


            foreach ($request->items as $itemData) {

                $item = Item::findOrFail($itemData['id']);
                $item->item_name = $itemData['item_name'] ?? null;
                $item->location = $itemData['location'] ?? null;

                $item->save();
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the room and items',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $room = Room::findOrFail($id);

            $room->delete();

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the room',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // public function updateMultipleRooms(Request $request)
    // {
    //     // Validate the request
    //     $validator = Validator::make($request->all(), [
    //         '*.id' => 'required|exists:rooms,id', // Ensure the room exists
    //         '*.location_id' => 'required|exists:locations,id',
    //         '*.room_name' => 'required|string|max:255',
    //         '*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
    //         '*.items' => 'required|array',
    //         '*.items.*.id' => 'required|exists:items,id', // Ensure the item exists
    //         '*.items.*.item_name' => 'required|string|max:255',
    //         '*.items.*.location' => 'nullable|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->errors()->first(),
    //             'status' => false,
    //         ], 400);
    //     }

    //     try {
    //         // Start DB transaction
    //         DB::beginTransaction();

    //         // Loop through the request (array of rooms)
    //         foreach ($request->all() as $roomData) {
    //             // Find the room by ID and update
    //             $room = Room::findOrFail($roomData['id']);
    //             $room->location_id = $roomData['location_id'];
    //             $room->room_name = $roomData['room_name'];

    //             // Handle room photo upload if provided
    //             if (isset($roomData['photo']) && $roomData['photo']) {
    //                 $randomString = (string) Str::uuid();
    //                 $roomPhoto = Helper::fileUpload($roomData['photo'], 'rooms', $roomData['photo']->getClientOriginalName() . '_' . $randomString);
    //                 $room->photo = $roomPhoto;
    //             }

    //             $room->save();

    //             // Update the associated items for the room
    //             foreach ($roomData['items'] as $itemData) {
    //                 // Find the item by ID and update
    //                 $item = Item::findOrFail($itemData['id']);
    //                 $item->item_name = $itemData['item_name'];
    //                 $item->location = $itemData['location'] ?? null;

    //                 // Handle item photo upload if needed (optional)
    //                 if (isset($itemData['item_photo']) && $request->hasFile('items.*.item_photo')) {
    //                     $randomString = (string) Str::uuid();
    //                     $itemPhoto = Helper::fileUpload($request->file('items.*.item_photo'), 'items', $itemData['item_photo']->getClientOriginalName() . '_' . $randomString);
    //                     $item->item_photo = $itemPhoto;
    //                 }

    //                 $item->save();
    //             }
    //         }

    //         // Commit transaction
    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Rooms and items updated successfully',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Rollback transaction if something goes wrong
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while updating the rooms and items',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
