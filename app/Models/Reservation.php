<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'parking_space_id',
        'checkin',
        'checkout'
    ];

    public static function checkoutProcess($userId,$parkingSpaceId){
        $response = array();
        $checkinData = DB::table('reservations')
        ->where('user_id', $userId)
        ->where('parking_space_id', $parkingSpaceId)
        ->where('checkout', null)
        ->select('checkin')
        ->first();
        if($checkinData){         
            $parkingSpaceData = DB::table('parking_spaces')
            ->where('id', $parkingSpaceId)
            ->select('parking_lot_id')
            ->first();
            if($parkingSpaceData){
                $parkingLotData = DB::table('parking_lots')
                    ->where('id', $parkingSpaceData->parking_lot_id)
                    ->select('grace_min_period', 'fee_per_hour')
                    ->first();

                $now = Carbon::now();
                $startDateTime = Carbon::parse($checkinData->checkin);

                $diffInMin = $startDateTime->diffInMinutes($now);
                $diffInHours = $startDateTime->diffInHours($now);
                $response['checkin'] = $checkinData->checkin;
                $response['checkout'] = $now->format('Y-m-d H:i:s');
                if($diffInMin > $parkingLotData->grace_min_period){                   
                    if($diffInHours == 0){
                        $response['parking_fee'] = number_format($parkingLotData->fee_per_hour,2);
                    }else{
                        $response['parking_fee'] = number_format($diffInHours * $parkingLotData->fee_per_hour, 2);    
                    }

                    DB::table('reservations')
                        ->where('user_id', $userId)
                        ->where('parking_space_id', $parkingSpaceId)
                        ->where('checkout', null)
                        ->update([
                            'checkout' => $now,
                        ]);

                    $dataToRevenueInsert = [
                        'amount' => $response['parking_fee'],
                        'parking_lot_id' => $parkingSpaceData->parking_lot_id,
                        'date' => $now,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    DB::table('revenues')->insert($dataToRevenueInsert);

                }else{
                    $response['parking_fee'] = 0;    
                }             
            }
        }
        

        return $response;
    }
}
