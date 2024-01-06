<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Revenue extends Model
{
    use HasFactory;
    public static function getByFilter($filterType)
    {
        if($filterType=='day'){
            $results = DB::table('revenues')
            ->select(DB::raw('YEAR(date) as revenue_year'), DB::raw('MONTH(date) as revenue_month'), DB::raw('DAY(date) as revenue_day'), DB::raw('SUM(amount) as total_revenue'))
            ->groupBy('revenue_year', 'revenue_month', 'revenue_day')
            ->get();
        }else if($filterType == 'week'){
            $results = DB::table('revenues')
            ->select(DB::raw('YEAR(date) as revenue_year'), DB::raw('WEEK(date) as revenue_week'), DB::raw('SUM(amount) as total_revenue'))
            ->groupBy('revenue_year', 'revenue_week')
            ->get();
        }else if($filterType == 'month'){
            $results = DB::table('revenues')
            ->select(DB::raw('YEAR(date) as revenue_year'), DB::raw('MONTH(date) as revenue_month'), DB::raw('SUM(amount) as total_revenue'))
            ->groupBy('revenue_year', 'revenue_month')
            ->get();
        }
        return $results;
    }
}
