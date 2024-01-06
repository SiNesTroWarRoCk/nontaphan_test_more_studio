<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Revenue AS RevenueModel;
class RevenueController extends Controller
{
    public function getRevenue(Request $request)
    {
        $paramValue = $request->query('filter_type');
        $revenueData = RevenueModel::getByFilter($paramValue);

        return response()->json(['data' => $revenueData], 200);
    }
}
