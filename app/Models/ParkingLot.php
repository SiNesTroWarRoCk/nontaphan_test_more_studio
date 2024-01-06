<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ParkingLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'fee_per_hour',
        'status',
        'lat',
        'lon',
        'grace_min_period',
    ];

    protected $table = 'parking_lots';

    public function parkingSpaces()
    {
        return $this->hasMany(ParkingSpace::class);
    }

    public function scopeNearestTo($query, $latitude, $longitude)
    {
        return $query->select(
            'id AS parking_lot_id',
            'title AS parking_lot_title',
            DB::raw('(6371 * ACOS(COS(RADIANS(' . $latitude . ')) * COS(RADIANS(lat)) * COS(RADIANS(lon - ' . $longitude . ')) + SIN(RADIANS(' . $latitude . ')) * SIN(RADIANS(lat)))) AS distance')
        )->orderBy('distance', 'asc');       
    }

    public static function getById($id)
    {
        return static::with('parkingSpaces')->find($id);
    }

}

class ParkingSpace extends Model
{
    protected $table = 'parking_spaces';
}