<?php

namespace App\Http\Controllers;

use App\SalesDetailsStocks;
use App\Inventories;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //untuk active halaman

        $halaman = "laporanringkasan";

        $data = SalesDetailsStocks::orderBy('id', 'desc');
        
        $start = $request->get('start') ?? date('Y-m-d');
        $data->whereDate('created_at', '>=', $start);
        
        $until = $request->get('until') ?? date('Y-m-d');
        $data->whereDate('created_at', '<=', $until);

        $invenData = NULL;
        $filterId = $request->get('id');
        if ($request->has('id')){
            if (!empty($filterId)) {
                $data->where('inventory_id', '=', $filterId);

                $invenData = Inventories::findOrFail($filterId);
            }
        }

        $datas = $data->get();

        return view('reports.index', compact('datas', 'invenData', 'halaman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
