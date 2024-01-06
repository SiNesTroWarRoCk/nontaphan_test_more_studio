<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingSpace as ParkingSpaceModel;
class ParkingSpaceController extends Controller
{
    public function createParkingSpace($parkingLotId)
    {
        $parkingSpace = ParkingSpaceModel::create([
            'parking_lot_id' => $parkingLotId,
        ]);

        return response()->json(['data' => $parkingSpace, 'message' => 'Add Parking Space successfully'], 201);
    }
}
