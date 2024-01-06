<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingLotController;
use App\Http\Controllers\ParkingSpaceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RevenueController;


Route::post("register", [AuthController::class, "register"]);
Route::post("login", [AuthController::class, "login"]);
Route::post("parking-lots", [ParkingLotController::class, "createParkingLot"]);
Route::post("parking-lots/{parking_lot_id}/parking-space", [ParkingSpaceController::class, "createParkingSpace"]);

Route::group([
    "middleware" => ["checkBearerToken"]
], function () {

    Route::get("list-parking-lot", [ParkingLotController::class, "listParkingLot"]);
    Route::get("parking-lot/{parking_lot_id}", [ParkingLotController::class, "getParkingLot"]);    
    Route::post("check-in/{parking_space_id}", [ReservationController::class, "checkin"]);    
    Route::put("check-out/{parking_space_id}", [ReservationController::class, "checkout"]);    
    Route::get("revenue", [RevenueController::class, "getRevenue"]);    
});


