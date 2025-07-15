<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Sale;  
use App\Models\Order; 
use App\Models\User;  
use Carbon\Carbon;   

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $selectedMonthYear = $request->input('month');

        $querySales = Sale::query();

        $targetMonth = null;
        $targetYear = null;

        if ($selectedMonthYear) {
            $parts = explode('-', $selectedMonthYear);
            if (count($parts) === 2) {
                $targetMonth = (int) $parts[0];
                $targetYear = (int) $parts[1];
            }
        }

        if ($targetMonth && $targetYear) {
            $querySales->whereMonth('sale_date', $targetMonth)
                       ->whereYear('sale_date', $targetYear);
            $selectedDateForDisplay = Carbon::create($targetYear, $targetMonth, 1);
        } else {
            $targetMonth = Carbon::now()->month;
            $targetYear = Carbon::now()->year;
            $querySales->whereMonth('sale_date', $targetMonth)
                       ->whereYear('sale_date', $targetYear);
            $selectedDateForDisplay = Carbon::now();
        }

        $totalSales = $querySales->sum('total_amount');

        
        $ordersPendingCount = Order::where('status', 'pending')->count();

        
        $newUsersThisMonthCount = User::whereMonth('created_at', $selectedDateForDisplay->month)
                                      ->whereYear('created_at', $selectedDateForDisplay->year)
                                      ->count();

        return view('admin.admindashboard', compact('totalSales', 'selectedDateForDisplay', 'ordersPendingCount', 'newUsersThisMonthCount'));
    }
}
