<?php

namespace App\Http\Controllers\Api\Backend;

use Exception;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function show(Request $request)
    {
        try {
            $user = $request->user();

            $babyProfile = Location::where('user_id', $user->id)->get();

            return response()->json([
                'success' => true,
                'data' => $babyProfile
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_name'          => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        try {
        $location                 = new Location();
        $location->user_id         = auth()->user()->id;
        $location->location_name   = $request->location_name;

        $location->save();

        return response()->json([
            'success' => true,
            'message' => 'Created successfully',
        ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
