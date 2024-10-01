<?php

namespace App\Http\Controllers\Api\Backend;

use Exception;
use App\Models\Item;
use App\Models\Room;
use App\Models\Section;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function index(Request $request)
    {
        try {
            $locationId = $request->query('location_id');

            if (!$locationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'location_id is required',
                ], 400);
            }

            $rooms = Room::where('location_id', $locationId)->get();

            if ($rooms->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No rooms found for the specified location_id',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $rooms,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the rooms',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getItemsByRoomId(Request $request)
    {
        try {
            $roomId = $request->query('room_id');

            if (!$roomId) {
                return response()->json([
                    'success' => false,
                    'message' => 'room_id is required',
                ], 400);
            }

            $items = Item::where('room_id', $roomId)->get();

            if ($items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items found for the specified room_id',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $items,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the items',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getSectionsByItemId(Request $request)
    {
        try {
            $itemId = $request->query('item_id');

            if (!$itemId) {
                return response()->json([
                    'success' => false,
                    'message' => 'item_id is required',
                ], 400);
            }

            $sections = Section::where('item_id', $itemId)->get();

            if ($sections->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sections found for the specified item_id',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $sections,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the sections',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function show($id)
    {
        try {

            $room = Room::with(['items.sections'])->findOrFail($id);

            // $response = [
            //     'id' => $room->id,
            //     'location_id' => $room->location_id,
            //     'room_name' => $room->room_name,
            //     'photo' => $room->photo,
            //     'items' => $room->items->map(function ($item) {
            //         return [
            //             'id' => $item->id,
            //             'pointer_name' => $item->pointer_name,
            //             'offset' => [
            //                 'x' => $item->offset_x,
            //                 'y' => $item->offset_y,
            //             ],
            //             'sections' => $item->sections->map(function ($section) {
            //                 return [
            //                     'id' => $section->id,
            //                     'location' => $section->location,
            //                     'items_dsc' => json_decode($section->items_dsc),
            //                 ];
            //             })
            //         ];
            //     })
            // ];

            return response()->json([
                'success' => true,
                'data' => $room,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request)
    {
        if (count($request->all()) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Request body is empty. Please provide data.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            '*.location_id' => 'required|exists:locations,id',
            '*.room_name' => 'required|string|max:255',
            '*.photo' => 'required|string',
            '*.items' => 'required|array',
            '*.items.*.pointer_name' => 'required|string|max:255',
            '*.items.*.offset.x' => 'required|numeric',
            '*.items.*.offset.y' => 'required|numeric',
            '*.items.*.section' => 'required|array',
            '*.items.*.section.*.location' => 'required|string|max:255',
            '*.items.*.section.*.items_dsc' => 'required|array',
            '*.items.*.section.*.items_dsc.*' => 'required|string|max:255'
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
                    $room->photo = $this->saveBase64Image($roomData['photo'], 'rooms');
                }

                $room->save();

                foreach ($roomData['items'] as $itemData) {
                    $item = new Item();
                    $item->room_id = $room->id;
                    $item->pointer_name = $itemData['pointer_name'];
                    $item->offset_x = $itemData['offset']['x'];
                    $item->offset_y = $itemData['offset']['y'];
                    $item->save();

                    foreach ($itemData['section'] as $sectionData) {
                        $section = new Section();
                        $section->item_id = $item->id;
                        $section->location = $sectionData['location'];
                        $section->items_dsc = json_encode($sectionData['items_dsc']);
                        $section->save();
                    }
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
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    private function saveBase64Image($base64Image, $folder)
    {
        $directoryPath = public_path('uploads/' . $folder);

        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        if (strpos($base64Image, ';base64,') !== false) {
            $imageParts = explode(";base64,", $base64Image);
            $base64Image = $imageParts[1];
        }

        $imageType = isset($imageParts[0]) && strpos($imageParts[0], 'image/') !== false
            ? explode('/', $imageParts[0])[1]
            : 'png';

        $imageBase64 = base64_decode($base64Image);

        if ($imageBase64 === false) {
            throw new Exception('Invalid base64 image data');
        }

        $fileName = Str::uuid() . '.' . $imageType;

        $filePath = 'uploads/' . $folder . '/' . $fileName;

        file_put_contents(public_path($filePath), $imageBase64);

        return $filePath;
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required|exists:locations,id',
            'room_name' => 'required|string|max:255',
            'photo' => 'nullable|string',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:items,id',
            'items.*.pointer_name' => 'required|string|max:255',
            'items.*.offset.x' => 'required|numeric',
            'items.*.offset.y' => 'required|numeric',
            'items.*.section' => 'required|array',
            'items.*.section.*.id' => 'nullable|exists:sections,id',
            'items.*.section.*.location' => 'required|string|max:255',
            'items.*.section.*.items_dsc' => 'required|array',
            'items.*.section.*.items_dsc.*' => 'required|string|max:255'
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
            $room->location_id = $request->location_id;
            $room->room_name = $request->room_name;

            if (isset($request->photo) && $request->photo) {
                $room->photo = $this->saveBase64Image($request->photo, 'rooms');
            }

            $room->save();


            foreach ($request->items as $itemData) {
                if (isset($itemData['id'])) {
                    $item = Item::findOrFail($itemData['id']);
                } else {
                    $item = new Item();
                    $item->room_id = $room->id;
                }

                $item->pointer_name = $itemData['pointer_name'];
                $item->offset_x = $itemData['offset']['x'];
                $item->offset_y = $itemData['offset']['y'];
                $item->save();

                foreach ($itemData['section'] as $sectionData) {

                    if (isset($sectionData['id'])) {
                        $section = Section::findOrFail($sectionData['id']);
                    } else {
                        $section = new Section();
                        $section->item_id = $item->id;
                    }

                    $section->location = $sectionData['location'];
                    $section->items_dsc = json_encode($sectionData['items_dsc']);
                    $section->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Room and items updated successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the room and items',
                'error' => $e->getMessage(),
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
                'message' => 'Room and its items deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required',
                    'error' => $validator->errors(),
                ], 400);
            }

            $searchTerm = $request->input('query');

            $locations = Location::whereHas('rooms.items.sections', function ($query) use ($searchTerm) {
                $query->where('location', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('items_dsc', 'LIKE', "%{$searchTerm}%");
            })
                ->with(['rooms' => function ($roomQuery) use ($searchTerm) {
                    $roomQuery->whereHas('items.sections', function ($query) use ($searchTerm) {
                        $query->where('location', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('items_dsc', 'LIKE', "%{$searchTerm}%");
                    })->with(['items' => function ($itemQuery) use ($searchTerm) {
                        $itemQuery->where('pointer_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhereHas('sections', function ($sectionQuery) use ($searchTerm) {
                                $sectionQuery->where('location', 'LIKE', "%{$searchTerm}%")
                                    ->orWhere('items_dsc', 'LIKE', "%{$searchTerm}%");
                            })->with('sections');
                    }]);
                }])->get();

            if ($locations->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $locations,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function updateSection(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'section_id' => 'required|exists:sections,id',
    //         'item_id' => 'required|exists:items,id',
    //         'location' => 'nullable|string|max:255',
    //         'items_dsc' => 'nullable|array',
    //         'items_dsc.*' => 'nullable|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->errors()->first(),
    //             'status' => false,
    //         ], 400);
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $section = Section::findOrFail($request->section_id);

    //         $sectionData = [
    //             'item_id' => $request->item_id,
    //             'location' => $request->location,
    //             'items_dsc' => json_encode($request->items_dsc),
    //         ];

    //         $section->update($sectionData);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'data' => $section,
    //             'message' => 'Section updated successfully',
    //         ], 200);
    //     } catch (Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while updating the section',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function updateSection(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|exists:items,id',
                // 'sections' => 'required|array',
                'sections.*.location' => 'nullable|string|max:255',
                'sections.*.items_dsc' => 'nullable|array',
                'sections.*.section_id' => 'nullable|exists:sections,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first(),
                    'status' => false,
                ], 400);
            }

            DB::beginTransaction();

            $itemId = $request->input('item_id');
            $sections = $request->input('sections');

            foreach ($sections as $sectionData) {
                if (isset($sectionData['section_id'])) {
                    $section = Section::find($sectionData['section_id']);
                    if ($section) {
                        $section->location = $sectionData['location'];
                        $section->items_dsc = json_encode($sectionData['items_dsc']);
                        $section->save();
                    }
                } else {
                    $section = new Section();
                    $section->item_id = $itemId;
                    $section->location = $sectionData['location'];
                    $section->items_dsc = json_encode($sectionData['items_dsc']);
                    $section->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sections updated or added successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating or adding sections',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
