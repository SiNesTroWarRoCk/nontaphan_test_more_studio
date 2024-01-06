<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\ParkingLot as ParkingLotModel;

class ParkingLotController extends Controller
{
    public function createParkingLot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:250',
            'fee_per_hour' => 'numeric|between:0.00,9999999.99',
            'status' => 'required|max:250',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'grace_min_period' => 'integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422));
        }

        $parkingLots = ParkingLotModel::create([
            'title' => $request->title,
            'fee_per_hour' => $request->fee_per_hour,
            'status' => $request->status,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'grace_min_period' => $request->grace_min_period,
        ]);

        return response()->json(['data' => $parkingLots, 'message' => 'Add Parking Lot successfully'], 201);
    }

    public function listParkingLot(Request $request)
    {     
        $validator = Validator::make($request->all(), [
            'user_lat' => 'required|numeric',
            'user_lon' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422));
        }

        $latitude = $request->user_lat;
        $longitude = $request->user_lon;
        $nearestParkingLots = ParkingLotModel::nearestTo($latitude, $longitude)->get();

        return response()->json(['data' => $nearestParkingLots], 200);
    }
    public function getParkingLot($parkingLotId)
    {
        $parkingLot = ParkingLotModel::getById($parkingLotId);
        
        if ($parkingLot) {
            return response()->json(['data' => $parkingLot], 200);
        } else {
            return response()->json(['error' => 'Parking lot not found.'], 200);
        }
    }
}
