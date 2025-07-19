<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewAvailableCarsController extends Controller
{
    public function ViewAvailableCarsPage()
    {
        $cars = DB::table('cars')
            ->leftJoin('delivery_orders', function ($join) {
                $join->on('cars.id', '=', 'delivery_orders.car')
                    ->where('delivery_orders.status', '=', 'pending');
            })
            ->select(
                'cars.id',
                'cars.car',
                'cars.plate_number',
                DB::raw("CASE WHEN COUNT(delivery_orders.id) > 0 THEN 'Not Available' ELSE 'Available' END as status")
            )
            ->groupBy('cars.id', 'cars.car', 'cars.plate_number')
            ->get();

        // fetch notification finish products
        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        return view('admin.view_available_cars', compact('cars', 'lowFinishedProducts'));
    }
}
