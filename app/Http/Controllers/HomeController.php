<?php

namespace App\Http\Controllers;

use App\Partners;
use App\Inventories;
use App\InventoriesStocks;
use App\Purchases;
use App\PurchasesDetail;
use App\Sales;
use App\Brokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        //untuk active halaman
        $halaman = "dashboard";

        // $sumPartners = Partners::count();
        
        // $sumStocks = InventoriesStocks::where('expired', '>', date('Y-m-d'))->sum('stock');

        // $sumSales = DB::table('sales')
        //     ->join('sales_details', 'sales_details.sales_id', '=', 'sales.id')
        //     ->where('date_select', '=', date('Y-m-d'))
        //     ->sum('subtotal');

        // $sumBrokens = DB::table('brokens')
        //     ->join('brokens_details', 'brokens_details.brokens_id', '=', 'brokens.id')
        //     ->where('date_select', '=', date('Y-m-d'))
        //     ->sum('broken');

        // $sumPurchases = DB::table('purchases')
        //     ->join('purchases_detail', 'purchases_detail.purchases_id', '=', 'purchases.id')
        //     ->where('date_select', '=', date('Y-m-d'))
        //     ->sum('subtotal');

        // $lastSales = Sales::orderBy('id', 'desc')
        //     ->where('date_select', '=', date('Y-m-d'))
        //     ->limit(5)
        //     ->get();

        // return view('home.index');
        return view('laporan.index', ['halaman' => $halaman ]);
    }



}
