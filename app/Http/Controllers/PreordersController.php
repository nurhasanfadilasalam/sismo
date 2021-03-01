<?php

namespace App\Http\Controllers;

use App\Preorder;
use App\PreorderDetails;
use App\Inventories;
use App\InventoriesStocks;
use App\InventoriesTracks;
use App\Partners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class PreordersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('rolecheck:OWNER|ADMIN');
    }

    public function index(Request $request)
    {        
        $data = Preorder::orderBy('id', 'desc'); 
        $filterName = $request->get('name');

        if ($request->has('name')){
            if (!empty($filterName)) {
                $data->where('po_number', $filterName);
                $data->orWhereHas('partner', function (Builder $query) use ($filterName){                    
                    $query->where('name', 'like', '%'.$filterName.'%');               
                });
            }
        }
        $datas = $data->paginate(10);
        //untuk active halaman
        $halaman = "preorder";
        return view('preorder.index', [ 'datas' => $datas, 'halaman' => $halaman ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "preorder";
        $datas = '';
        $oldinven = array();

        return view('preorder.form', [      
            'halaman' => $halaman,
            'data' => $datas,
            'oldinven' => json_encode($oldinven)
        ]);
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
            'partner' => 'required',
            'po' => 'required',
            'iventories' => 'required',
        ],
        [
            'iventories.required' => 'Mohon pilih barang dahulu.',
        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;
            $resultMsg = '';
            $resultLink = '';

            $newData = new Preorder;
            $newData->date_select = $request->get('date');
            $newData->partners_id = $request->get('partner');
            $newData->po_number = $request->get('po');
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

                // save po details 
                $DetailsStocks = new PreorderDetails;
                $DetailsStocks->preorder_id = $newData->id;
                $DetailsStocks->inventory_id = $idIven;
                $DetailsStocks->name = $invenData->name;
                $DetailsStocks->unit = $unit;
                $DetailsStocks->qty = $qty;
                $DetailsStocks->created_by = \Auth::user()->id;
                $DetailsStocks->save();   
            } // endforeach > all inventories from form
            
            if (!$resultStatus) {
                DB::rollback();

                $resultMsg = $resultMsg ? $resultMsg : 'Preorder tidak berhasil tersimpan, refresh dan coba lagi';
            } else {
                DB::commit();

                $resultMsg = 'Preorder berhasil disimpan';
                $resultLink = '/preorder';
            }

            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        }
        catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('preorder.create')->with('status', 'Preorder FAILED');
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
        $halaman = "preorder";
        $datas = Preorder::findOrFail($id);

        $oldinven = array();
        foreach($datas->details as $detail){
            array_push($oldinven, array( 
                'id' => $detail->inventory_id, 
                'name' => $detail->name, 
                'qty' => $detail->qty, 
                'unit' => $detail->unit,
                'subTotal' => $detail->subtotal
                )
            );
        }

        return view('preorder.form', [        
            'halaman' => $halaman,
            'data' => $datas,
            'oldinven' => json_encode($oldinven)
        ]);
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
         
         $request->validate([            
            'iventories' => 'required'
        ],
        [
            'iventories.required' => 'Mohon pilih barang dahulu.',
        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;
            $resultMsg = '';
            $resultLink = '';

            $oldDetail = PreorderDetails::where('preorder_id', $id)
                        ->delete();
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

                if (count($invenDataStock) <= 0) {
                    $resultStatus = false;
                    $resultMsg = 'Stok ' . $invenData->name . ' tidak tersedia';
                    break;
                } else {
                    $qtyNeed = $need = $qty;
                    $use = $total = 0;

                    if ($invenData->stock < $qtyNeed) {            
                        $resultStatus = false;
                        $resultMsg = 'Stock tidak mencukupi ' . $invenData->name . ', tersisa ' . $invenData->stock;
                        break;
                    } else {
                        foreach ($invenDataStock as $stock) {
                            $subTotal = 0;
                            if ($stock->stock > 0 && $stock->expired > $now) {
    
                                if ($need) {
                                    if ($stock->stock >= $need) {
                                        $use = $need;
                                    } else {
                                        $use = $stock->stock;
                                    }
    
                                    $need -= $use;
                                    $subTotal = $use * $stock->price;                                  

                                    // save sales details stocks
                                    $DetailsStocks = new PreorderDetails;
                                    $DetailsStocks->preorder_id = $id;
                                    $DetailsStocks->inventory_id = $idIven;
                                    $DetailsStocks->name = $invenData->name;
                                    $DetailsStocks->unit = $unit;
                                    $DetailsStocks->qty  = $use;
                                    $DetailsStocks->created_by = \Auth::user()->id;
                                    $DetailsStocks->save();   
                         
                                }
                            }    
                        } // endforeach > all stocks haved this inventories
                    } // endif > this inventories stocks enough

                } // endif > this inventories have stocks or not
            } // endforeach > all inventories from form
            
            if (!$resultStatus) {
                DB::rollback();
                $resultMsg = $resultMsg ? $resultMsg : 'Preorder tidak berhasil tersimpan, refresh dan coba lagi';
            } else {
                DB::commit();
                $resultMsg = 'Preorder berhasil disimpan';
                $resultLink = '/preorder';
            }

            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        }
        catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('preorder.edit', [$id])->with('status', 'Preorder FAILED');
        }

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
