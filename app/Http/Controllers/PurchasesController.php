<?php

namespace App\Http\Controllers;

use App\Purchases;
use App\PurchasesDetail;
use App\Inventories;
use App\InventoriesStocks;
use App\InventoriesTracks;
use App\InventoriesPrices;

use App\Masters;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Controller
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
    public function index(Request $request)
    {
        $do = $request->get('do');
        $filterName = $request->get('name');
        $halaman = "barangmasuk";
        
        $data = Purchases::orderBy('id', 'desc');

        if ($request->has('name')){
            if (!empty($filterName)) {
                $data->where('name', 'like', '%'.$filterName.'%');
            }
        }

        $datas = $data->paginate(10);

        return view('purchases.index', [ 'datas' => $datas, 'halaman' => $halaman ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "barangmasuk";
        $priceTypes = Masters::where('type', 'priceType')->get();
        return view('purchases.form', compact('halaman','priceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'transactionnumber' => 'required',
            'iventories' => 'required',
        ],
        [
            'iventories.required' => 'Mohon pilih barang dahulu.',
        ]);

        DB::beginTransaction();

        try {
            $newData = new Purchases;
            $newData->date_select = $request->get('date');
            $newData->transaction_number = $request->get('transactionnumber');
            $newData->notes = $request->get('notes');
            $newData->created_by = \Auth::user()->id;
            $newData->save();

            $ivens = $request->get('iventories');           
            
            foreach ($ivens as $key => $iven) {
                
                $invenEncode = json_decode($iven);

                echo gettype($invenEncode);
                
                $getPick = explode('&', $iven);
                $idIven = $invenEncode->id;
                $qty    = $invenEncode->qty;                
                $exp    = $invenEncode->exp;
                $prices = $invenEncode->prices;
                $purchasePrice = $invenEncode->pricePurchases;
                $subtotal = $qty * $purchasePrice;
                $firstPrices = Arr::first($prices);              

                $dataIven = Inventories::findOrFail($idIven);                
                $unit = $dataIven->unit;
                $firstPrice = Arr::first($prices);

                // save new stocks
                $newStock = new InventoriesStocks;
                $newStock->inventories_id = $idIven;
                $newStock->stock = $qty;
                $newStock->expired = $exp;                   
                $newStock->price = $firstPrice->price;                  
                $newStock->price_purchase = $purchasePrice;
                $newStock->notes = "Barang Masuk " . $request->get('transactionnumber');
                $newStock->created_by = \Auth::user()->id;
                $newStock->save();

                foreach ($prices as $key => $price) 
                {
                    $priceTypeId = $price->type_id;
                    $priceName = $price->name;
                    $pricePrice = $price->price;                   
                
                    //save InvetoriesPrice               
                    $newPrices = new InventoriesPrices;
                    $newPrices->inventories_id = $newData->id;
                    $newPrices->stocks_id = $newStock->id;
                    $newPrices->type_id = $priceTypeId;
                    $newPrices->type_name = $priceName;
                    $newPrices->price = $pricePrice;
                    $newPrices->created_by = \Auth::user()->id;
                    $newPrices->save();
                }

                // save purchases details
                $newDetail = new PurchasesDetail;
                $newDetail->purchases_id = $newData->id;
                $newDetail->inventory_id = $idIven;
                $newDetail->stocks_id = $newStock->id;
                $newDetail->qty = $qty;
                $newDetail->unit = $unit;               
                $newDetail->price = $firstPrice->price;
                $newDetail->price_purchase = $purchasePrice;
                $newDetail->subtotal = $subtotal;
                $newDetail->save();

                // update stock from total stocks
                $updateStock = $dataIven->stock + $qty;
                $dataIven->stock = $updateStock;
                $dataIven->save();
    
                // save track new stok
                $newTracks = new InventoriesTracks;
                $newTracks->inventories_id = $idIven;
                $newTracks->stocks_id = $newStock->id;
                $newTracks->qty = $qty;
                $newTracks->unit = $unit;
                $newTracks->expired = $exp;
                $newTracks->price = $firstPrice->price;
                $newTracks->type = 'in';
                $newTracks->note = 'Barang Masuk ' . $request->get('transactionnumber');
                $newTracks->created_by = \Auth::user()->id;
                $newTracks->save();
            }
            
            DB::commit();

            return redirect()->route('purchases.index')->with('status', 'Barang Masuk berhasil disimpan');
        } catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('purchases.create')->with('status', 'Purchases FAILED');
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
        //
        
    }
    
    public function report(Request $request)
    {
        $data = Purchases::orderBy('id', 'desc');
        
        $start = $request->get('start') ?? date('Y-m-d');
        $data->whereDate('created_at', '>=', $start);
        
        $until = $request->get('until') ?? date('Y-m-d');
        $data->whereDate('created_at', '<=', $until);

        $datas = $data->get();
        
        $halaman = "laporanbarangmasuk";
        return view('reports.purchases', compact('datas', 'halaman'));
    }
}
