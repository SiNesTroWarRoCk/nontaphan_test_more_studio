<?php

namespace App\Http\Controllers;

use App\Models\Reservation AS ReservationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    public function checkin($parkingSpaceId)
    {
        $userId = Auth::id();

        $parkingSpace = ReservationModel::create([
            'user_id' => $userId,
            'parking_space_id' => $parkingSpaceId,
            'checkin' => Carbon::now(),
        ]);

        return response()->json(['data' => $parkingSpace, 'message' => 'Check In successfully'], 201);
    }
    public function checkout($parkingSpaceId)
    {
        $userId = Auth::id();

        $checkoutResponse = ReservationModel::checkoutProcess($userId,$parkingSpaceId);
        if($checkoutResponse){
            return response()->json(['data' => $checkoutResponse, 'message' => 'Check out successfully'], 201);
        }else{
            return response()->json(['data' => $checkoutResponse, 'message' => 'Cannot Check out Because You not Check In or You Checkout Already'], 200);
        }
    }
}
