<?php

namespace App\Http\Controllers;

use App\SalesDirects;
use App\SalesDirectsDetails;
use App\Inventories;
use App\InventoriesStocks;
use App\InventoriesPrices;
use App\InventoriesTracks;
use App\Masters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesDirectsController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('rolecheck:OWNER|ADMIN');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $data = SalesDirects::orderBy('id', 'desc');    
        $datas = $data->paginate(10);
        $halaman = "penjualanlangsung";

        return view('salesdirects.index', [ 'datas' => $datas, 'halaman' => $halaman ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "penjualanlangsung";
        $types = Masters::where('type', 'priceType')->get();
        return view('salesdirects.form', compact('halaman','types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer_type = $request->get('customer_type');
        $request->validate([
            'date' => 'required',
            'invoice' => 'required',
            'iventories' => 'required',
            'customer_type' => 'required',
        ],
        [
            'iventories.required' => 'Mohon pilih barang dahulu.',
        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;
            $resultMsg = '';
            $resultLink = '';

            $newData = new SalesDirects;
            $newData->date_select = $request->get('date');
            $newData->customer_type = $request->get('customer_type');
            $newData->transaction_number = $request->get('invoice');
            $newData->name_customer = $request->get('name_customer');
            $newData->notes = $request->get('notes');
            $newData->created_by = \Auth::user()->id;
            $newData->save();

            $ivens = $request->get('iventories');

            foreach ($ivens as $iven) {
                $totalSalesDetail = 0;
                $now     = date('Y-m-d');
                $getPick = explode('&', $iven);
                $idIven  = $getPick[0];
                $qty     = $getPick[1];
                $unit    = $getPick[2];
                // for validation stock
                $invenData = Inventories::findOrFail($idIven);

                $invenDataStock = InventoriesStocks::where('inventories_id', $idIven)
                    ->where('stock', '>', 0)
                    ->where('expired', '>', $now)
                    ->orderBy('expired', 'asc')
                    ->get();

                $invenDataPrice = InventoriesPrices::where('inventories_id', $idIven)
                    ->where('type_name',$customer_type)
                    ->value('price');

                if (count($invenDataStock) <= 0) {
                    $resultStatus = false;
                    $resultMsg = 'Stok ' . $invenData->name . ' tidak tersedia';
                    break;
                } else {
                    $qtyNeed = $need = $qty;
                    $collectStocks = array();
                    $sumAvailable = $use = $total = 0;

                    if ($invenData->stock < $qtyNeed) {            
                        $resultStatus = false;
                        $resultMsg = 'Stock tidak mencukupi ' . $invenData->name . ', tersisa ' . $invenData->stock;
                        break;
                    } else {
                       
                        foreach ($invenDataStock as $stock) {                                  
                            $subTotal = 0;
                            if ($stock->stock > 0 && $stock->expired > $now) {
                                $sumAvailable += $stock->stock;
    
                                if ($need) {                                    
                                    if ($stock->stock >= $need) {
                                        $use = $need;
                                    } else {
                                        $use = $stock->stock;
                                    }
    
                                    $need -= $use;


                                    $subTotal = $use * $invenDataPrice;    

                                    // save sales details stocks
                                    $DetailsStocks = new SalesDirectsDetails;
                                    $DetailsStocks->sales_directs_id = $newData->id;
                                    $DetailsStocks->inventory_id = $idIven;
                                    $DetailsStocks->stocks_id = $stock->id;
                                    $DetailsStocks->expired = $stock->expired;
                                    $DetailsStocks->name = $invenData->name;
                                    $DetailsStocks->unit = $unit;
                                    $DetailsStocks->price_purchase = $stock->price_purchase ? $stock->price_purchase : 0;
                                    $DetailsStocks->price = $invenDataPrice;
                                    $DetailsStocks->qty  = $use;
                                    $DetailsStocks->subtotal = $subTotal;
                                    $DetailsStocks->created_by = \Auth::user()->id;
                                    $DetailsStocks->save();
    
                                    // update stocks
                                    $thisInventoriesStocks = InventoriesStocks::findOrFail($stock->id);
                                    $newInvenStock = $thisInventoriesStocks->stock - $use;

                                    $thisInventoriesStocks->stock = $newInvenStock;
                                    $thisInventoriesStocks->updated_by = \Auth::user()->id;
                                    $thisInventoriesStocks->save();

                                    // save track new stok
                                    $newTracks = new InventoriesTracks;
                                    $newTracks->inventories_id = $idIven;
                                    $newTracks->stocks_id = $stock->id;
                                    $newTracks->qty  = $use;
                                    $newTracks->unit = $unit;
                                    $newTracks->expired = $stock->expired;
                                    $newTracks->price = $invenDataPrice;
                                    $newTracks->type = 'out';
                                    $newTracks->note = 'Penjualan Langsung ' . $request->get('invoice');
                                    $newTracks->created_by = \Auth::user()->id;
                                    $newTracks->save();
                                }
                            }
    
                            $totalSalesDetail += $subTotal;
                        } // endforeach > all stocks haved this inventories

                        // update inven data for total stock 
                        $newTotalStock = $invenData->stock - $qty;
                        $invenData->stock = $newTotalStock;
                        $invenData->save();

                    } // endif > this inventories stocks enough

                } // endif > this inventories have stocks or not
            } // endforeach > all inventories from form
            
            if (!$resultStatus) {
                DB::rollback();
                $resultMsg = $resultMsg ? $resultMsg : 'Penjualan tidak berhasil disimpan, silahkan ulangi kembali';
            } else {
                DB::commit();
                $resultMsg = 'Penjualan berhasil disimpan';
                $resultLink = '/salesdirects';
            }

            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        }
        catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('salesdirects.create')->with('status', 'Penjualan gagal');
        }
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
        $data = SalesDirects::findOrFail($id);       

        $data->deleted_by = \Auth::user()->id;
        $data->save();

        $data->delete();

        return redirect()->route('salesdirects.index')->with('status', 'Data berhasil dihapus');

    }
}
