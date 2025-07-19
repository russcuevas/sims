<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewAvailableCarsController extends Controller
{
    public function ViewAvailableCarsPage(Request $request)
    {
        $filter = $request->input('filter');
        $sort = $request->input('sort');

        $query = DB::table('cars')
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
            ->groupBy('cars.id', 'cars.car', 'cars.plate_number');

        if ($filter) {
            $query->having('status', '=', $filter);
        }

        if ($sort === 'asc' || $sort === 'desc') {
            $query->orderBy('status', $sort);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('cars.car', 'like', "%$search%")
                    ->orWhere('cars.plate_number', 'like', "%$search%");
            });
        }


        $cars = $query->get();

        $lowFinishedProducts = DB::table('product_details')
            ->where('category', 'finish product')
            ->where('quantity', '<', 1000)
            ->where('is_archived', 0)
            ->get();

        return view('admin.view_available_cars', compact('cars', 'lowFinishedProducts'));
    }
}
