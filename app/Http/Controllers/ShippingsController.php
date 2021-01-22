<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

use App\Preorder;
use App\Sales;
use App\SalesDetails;
use App\SalesDetailsStocks;
use App\Inventories;
use App\InventoriesStocks;
use App\InventoriesTracks;
use App\InventoriesPrices;
use App\Shippings;
use App\ShippingsDetails;
use App\ShippingsDetailsInventories;
use App\ShippingsDetailsStocks;

class ShippingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('rolecheck:OWNER|ADMIN|DRIVER');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $do = $request->get('do');
        $filterNoTracking = $request->get('notracking');
        $filterDriver = $request->get('driver');
        $filterRute = $request->get('rute');

        $halaman = "pengiriman";
        
        $data = Shippings::orderBy('id', 'desc');

        if ($request->has('notracking')){
            if (!empty($filterNoTracking)) {
                $data->where('tracking_number', 'like', '%'.$filterNoTracking.'%');
            }
        }

        if ($request->has('driver')){
            if (!empty($filterDriver)) {
                $data->WhereHas('driver', function (Builder $query) use ($filterDriver){                   
                    $query->where('name', 'like', '%'.$filterDriver.'%');                    
                });
            }
        }

        if ($request->has('rute')){
            if (!empty($filterRute)) {
                $data->WhereHas('routes', function (Builder $query) use ($filterRute){                  
                    $query->where('name', 'like', '%'.$filterRute.'%');                           
                });
            }
        }

        $datas = $data->paginate(10);

        return view('shippings.index', compact('datas', 'halaman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "pengiriman";

        $last = Shippings::whereDate('created_at', Carbon::today())->count();

        return view('shippings.form', compact('halaman', 'last'));
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
            'route' => 'required',
            'driver' => 'required',
            'tracking_number' => 'required',
            'preorders' => 'required',
        ],
        [
            'preorders.required' => 'Mohon pilih po dahulu.',
        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;
            $resultMsg = '';
            $resultLink = '';

            $newData = new Shippings;
            $newData->date_select = $request->get('date');
            $newData->tracking_number = $request->get('tracking_number');
            $newData->driver_id = $request->get('driver');
            $newData->routes_id = $request->get('route');
            $newData->notes = $request->get('notes');
            $newData->created_by = \Auth::user()->id;
            $newData->save();

            $preorders = $request->get('preorders');
            foreach ($preorders as $preorder) 
            {
                $po = Preorder::findOrFail($preorder);
                $po->shippings_id = $newData->id;
                $po->shippings_status = '0';
                $po->save();
                
                //# save shipping details for data PO and partners
                $newDetailShipping = new ShippingsDetails;
                $newDetailShipping->shippings_id = $newData->id;
                $newDetailShipping->preorder_id = $preorder;
                $newDetailShipping->sales_id = 0;
                $newDetailShipping->partners_id = $po->partner->id;
                $newDetailShipping->name = $po->partner->name;
                $newDetailShipping->phone = $po->partner->phone;
                $newDetailShipping->address = $po->partner->address;
                $newDetailShipping->save();

                //# get from PreorderDetails
                $poDetails = $po->details;
                
                foreach ($poDetails as $ky => $PreorderDetails)  //#>> loop as inventories from detail PO
                {
                    $now     = date('Y-m-d');
                    $idIven  = $PreorderDetails->inventory_id;
                    $qtyPO   = $PreorderDetails->qty;
                    $unit    = $PreorderDetails->unit;
                    $totalShippingInventoryDetail = 0;

                    //# for validation stock
                    $invenData = Inventories::findOrFail($idIven);

                    $invenDataStock = InventoriesStocks::where('inventories_id', $idIven)
                        ->where('stock', '>', 0)
                        ->where('expired', '>', $now)
                        ->orderBy('expired', 'asc')
                        ->get();

                    $invenDataPrice = InventoriesPrices::where('inventories_id', $idIven)
                        ->where('type_name','Partner')
                        ->value('price');


                    if (count($invenDataStock) <= 0) {
                        $resultStatus = false;
                        $resultMsg = 'Stok ' . $invenData->name . ' tidak tersedia';
                        break;
                    } else {
                        $qtyNeed = $need = $qtyPO;
                        $collectStocks = array();
                        $sumAvailable = $use = $total = 0;
    
                        if ($invenData->stock < $qtyNeed) {            
                            $resultStatus = false;
                            $resultMsg = 'Stock tidak mencukupi ' . $invenData->name . ', tersisa ' . $invenData->stock;
                            break;
                        } else {
                            //# save shipping detail inventories
                            $newDetailShippingInven = new ShippingsDetailsInventories;
                            $newDetailShippingInven->shippings_id = $newData->id;
                            $newDetailShippingInven->shippings_details_id = $newDetailShipping->id;
                            $newDetailShippingInven->inventory_id = $idIven;
                            $newDetailShippingInven->name = $invenData->name;
                            $newDetailShippingInven->unit = $unit;
                            $newDetailShippingInven->iven_qty_po = $qtyPO;
                            $newDetailShippingInven->iven_subtotal = '0';
                            $newDetailShippingInven->save();
    
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
                                                            
                                        //# save shipping details stocks
                                        $newDetailShippingStocks = new ShippingsDetailsStocks;
                                        $newDetailShippingStocks->shippings_id = $newData->id;
                                        $newDetailShippingStocks->shippings_details_id = $newDetailShipping->id;
                                        $newDetailShippingStocks->shippings_details_inventory_id = $newDetailShippingInven->id;
                                        $newDetailShippingStocks->inventory_id = $idIven;
                                        $newDetailShippingStocks->stocks_id = $stock->id;
                                        $newDetailShippingStocks->expired = $stock->expired;
                                        $newDetailShippingStocks->price = $invenDataPrice;;
                                        $newDetailShippingStocks->price_purchase = $stock->price_purchase ? $stock->price_purchase : 0;
                                        $newDetailShippingStocks->stock_qty = $use;
                                        $newDetailShippingStocks->stock_subtotal = $subTotal;
                                        $newDetailShippingStocks->save();
        
                                        //# update stocks
                                        $thisInventoriesStocks = InventoriesStocks::findOrFail($stock->id);
                                        $newInvenStock = $thisInventoriesStocks->stock - $use;
    
                                        $thisInventoriesStocks->stock = $newInvenStock;
                                        $thisInventoriesStocks->updated_by = \Auth::user()->id;
                                        $thisInventoriesStocks->save();
    
                                        //# save track new stok
                                        $newTracks = new InventoriesTracks;
                                        $newTracks->inventories_id = $idIven;
                                        $newTracks->stocks_id = $stock->id;
                                        $newTracks->qty  = $use;
                                        $newTracks->unit = $unit;
                                        $newTracks->expired = $stock->expired;
                                        $newTracks->price = $invenDataPrice;
                                        $newTracks->type = 'out';
                                        $newTracks->note = 'Pengiriman No Tracking ' . $request->get('tracking_number');
                                        $newTracks->created_by = \Auth::user()->id;
                                        $newTracks->save();
                                    }
                                } //# end if stock can to used
        
                                $totalShippingInventoryDetail += $subTotal;
                            } //# endforeach > all stocks haved this inventories
    
                            //# update inven data for total stock 
                            $newTotalStock = $invenData->stock - $qtyPO;
                            $invenData->stock = $newTotalStock;
                            $invenData->save();
    
                            //# update stocks in details inven
                            $newDetailShippingInven->iven_subtotal = $totalShippingInventoryDetail;
                            $newDetailShippingInven->save();
                        } //# endif > this inventories stocks enough
    
                    } //# endif > this inventories have stocks or not
                }
            }

            if (!$resultStatus) {
                DB::rollback();

                $resultMsg = $resultMsg ? $resultMsg : 'Shipping Failed created, reload and try again';
            } else {
                DB::commit();

                $resultMsg = 'Shipping successfully created';
                $resultLink = '/shippings';
            }

            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('shippings.create')->with('status', 'Shippings FAILED');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function show(Shippings $shipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $show = 'wait';
        $halaman = "pengiriman";
        $shipping = Shippings::findOrFail($id);

        if ($request->has('show') && $request->get('show') == 'done'){
            $show = 'done';

            $details = ShippingsDetails::select('shippings_details.*', 'shippings_details.id as details_id', 
                    'preorders.po_number', 'routes_details.order', 'users.name as proccess_by_name')
                ->join('preorders', 'preorders.id', 'shippings_details.preorder_id')
                ->join('partners', 'partners.id', 'shippings_details.partners_id')
                ->join('routes_details', 'routes_details.partners_id', 'partners.id')
                ->leftJoin('users', 'users.id', 'shippings_details.proccess_by')
                ->where('shippings_details.shippings_id', $id)
                ->where('shippings_details.status', '1')
                ->orderBy('routes_details.order')
                ->get();
        } 
        else {
            $details = ShippingsDetails::select('shippings_details.*', 'shippings_details.id as details_id', 'preorders.po_number', 'routes_details.order', 'users.name as proccess_by_name')
                ->join('preorders', 'preorders.id', 'shippings_details.preorder_id')
                ->join('partners', 'partners.id', 'shippings_details.partners_id')
                ->join('routes_details', 'routes_details.partners_id', 'partners.id')
                ->leftJoin('users', 'users.id', 'shippings_details.proccess_by')
                ->where('shippings_details.shippings_id', $id)
                ->where('shippings_details.status', '0')
                ->orderBy('routes_details.order')
                ->get();
        }

        return view('shippings.proccess', compact('show', 'shipping', 'details', 'halaman'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $shipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {
        //
    }

    public function proccess(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'time' => 'required',
            'id' => 'required',
            'invoice' => 'required',
        ],
        [
            'id.required' => 'Mohon pilih invoice dahulu.',
        ]);

        //# detailShipping
        $id = $request->get('id'); 
        $detailData  = ShippingsDetails::findOrFail($id);
        $shippingsId = $detailData->shippings_id;
        $resultLink  = "/shippings"."/".$shippingsId.'/edit';

        DB::beginTransaction();

        try {
            $resultStatus = true;
            $resultMsg = '';
            $resultLink = '';
    
            //## update shippings details
            $detailData->proccess_date = $request->get('date');
            $detailData->proccess_time = $request->get('time');
            $detailData->proccess_notes = $request->get('notes');
            $detailData->proccess_by = \Auth::user()->id;
            $detailData->status = '1';
            $detailData->save();
    
            $shippingsData = $detailData->shippingData;

            //# create sales partner / invoces
            $newSales = new Sales;
            $newSales->date_select = $request->get('date');
            $newSales->shippings_id = $shippingsId;
            $newSales->shippings_details_id = $detailData->id;
            $newSales->partners_id = $detailData->partners_id;
            $newSales->transaction_number = $request->get('invoice');
            $newSales->notes = $request->get('notes');
            $newSales->created_by = \Auth::user()->id;
            $newSales->save();

            //# update shipping detail with sales id
            $detailData->sales_id = $newSales->id;
            $detailData->save();
            
            $idIvenAccepts = $request->get('accept_id_iven');
            $idStockAccepts = $request->get('accept_id_stocks');
            $qtyStockAccepts = $request->get('accept_qty');
            $notesStockAccepts = $request->get('accept_notes');
            
            //# asume loop inventories data from ShippingsDetailsInventories
            foreach ($idIvenAccepts as $key => $idDetailsInventory) 
            {
                $thisStocksAccept = $idStockAccepts[$key];
                $thisQtyAccept = $qtyStockAccepts[$key];
                $thisNotesAccept = $notesStockAccepts[$key];
                
                $detailShippingInven = ShippingsDetailsInventories::findOrFail($idDetailsInventory);
                
                $idIventory = $detailShippingInven->inventory_id;
                $name = $detailShippingInven->name;
                $unit = $detailShippingInven->unit;
                $invenDataPrice = InventoriesPrices::where('inventories_id', $idIventory)
                        ->where('type_name','Partner')
                        ->value('price');
                $totalSalesDetail = $thisQtyInvenAccept = 0;

                $newSalesDetail = new SalesDetails;
                $newSalesDetail->sales_id = $newSales->id;
                $newSalesDetail->inventory_id = $idIventory;
                $newSalesDetail->name = $name;
                $newSalesDetail->unit = $unit;
                $newSalesDetail->subtotal = 0; //# temp data, updated bellow;
                $newSalesDetail->qty = 0; //# temp data, updated bellow;
                $newSalesDetail->save();

                //# loop data asume from shippingDetailsStocks
                foreach ($thisStocksAccept as $k => $idDetailsShippingStock) 
                {
                    $subTotalDetailsStocks = 0;
                    $detailShippingStockData = ShippingsDetailsStocks::findOrFail($idDetailsShippingStock);
                    $thisShippingStockQtyPO = $detailShippingStockData->stock_qty;

                    $thisStockIdAccept = $detailShippingStockData->stocks_id;
                    $thisStockQtyAccept = $thisQtyAccept[$k];
                    $thisStockNotesAccept = $thisNotesAccept[$k];

                    //# valdation accept is minus than po
                    if ($thisStockQtyAccept > $thisShippingStockQtyPO) {
                        $resultStatus = false;
                        $resultMsg = 'Input melebihi data po ' . $detailShippingInven->name . ', PO : ' . $thisShippingStockQtyPO;
                        break;
                    } 
                    else {
                        if ($thisStockQtyAccept < $thisShippingStockQtyPO) {
                            //# update stocks
                            $thisInventoriesStocks = InventoriesStocks::findOrFail($thisStockIdAccept); 

                            //# return stock
                            $backStock = $thisShippingStockQtyPO - $thisStockQtyAccept;
                            $newInvenStock = $thisInventoriesStocks->stock + $backStock;

                            $thisInventoriesStocks->stock = $newInvenStock;
                            $thisInventoriesStocks->updated_by = \Auth::user()->id;
                            $thisInventoriesStocks->save();

                            // save track new stok
                            $newTracks = new InventoriesTracks;
                            $newTracks->inventories_id = $idIventory;
                            $newTracks->stocks_id = $thisInventoriesStocks->id;
                            $newTracks->qty  = $backStock;
                            $newTracks->unit = $unit;
                            $newTracks->expired = $thisInventoriesStocks->expired;
                            $newTracks->price = $invenDataPrice;
                            $newTracks->type = 'in';
                            $newTracks->note = 'Retur Pengiriman Partner ' . $request->get('invoice');
                            $newTracks->created_by = \Auth::user()->id;
                            $newTracks->save();
                        }
    
                        $idStocks = $thisStockIdAccept;
                        $subTotalDetailsStocks = $thisStockQtyAccept * $detailShippingStockData->price;
                        
                        $thisQtyInvenAccept += $thisStockQtyAccept; 
                        $totalSalesDetail += $subTotalDetailsStocks;
    
                        // save sales details stocks
                        $DetailsStocks = new SalesDetailsStocks;
                        $DetailsStocks->sales_id = $newSales->id;
                        $DetailsStocks->sales_details_id = $newSalesDetail->id;
                        $DetailsStocks->inventory_id = $idIventory;
                        $DetailsStocks->stocks_id = $idStocks;
                        $DetailsStocks->expired = $detailShippingStockData->expired;
                        $DetailsStocks->price_purchase = $detailShippingStockData->price_purchase;
                        $DetailsStocks->price = $detailShippingStockData->price;
                        $DetailsStocks->qty  = $thisStockQtyAccept;
                        $DetailsStocks->subtotal = $subTotalDetailsStocks;
                        $DetailsStocks->created_by = \Auth::user()->id;
                        $DetailsStocks->save();

                        //# update in shipping details stocks info
                        $detailShippingStockData->stock_qty_accept = $thisStockQtyAccept;
                        $detailShippingStockData->stock_subtotal_accept = $subTotalDetailsStocks;
                        $detailShippingStockData->proccess_by = \Auth::user()->id;
                        $detailShippingStockData->proccess_notes = $thisStockNotesAccept;
                        $detailShippingStockData->save();
                    } //# end if not greather than PO
                } //# end loop detailsStocksShipping
                

                //# update in shipping details inventories info
                $detailShippingInven->iven_qty_accept = $thisQtyInvenAccept;
                $detailShippingInven->proccess_by = \Auth::user()->id;
                $detailShippingInven->save();

                //# update in sales details
                $newSalesDetail->subtotal = $totalSalesDetail;
                $newSalesDetail->qty = $thisQtyInvenAccept;
                $newSalesDetail->save();

            } //# end loop detailsInvensShipping

            if (!$resultStatus) {
                DB::rollback();

                $resultMsg = $resultMsg ? $resultMsg : 'Sales Failed created, reload and try again';
            } else {
                DB::commit();

                $resultMsg = $resultMsg ? $resultMsg : 'Data successfully created';
            }
            
            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        }
        catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return response()->json(array('status' => false, 'msg' => 'Server FAILED', 'link' => $resultLink));
        }
    }
    
    public function report(Request $request)
    {
        $data = ShippingsDetails::orderBy('id', 'desc');
        
        $start = $request->get('start') ?? date('Y-m-d');
        $data->whereDate('created_at', '>=', $start);
        
        $until = $request->get('until') ?? date('Y-m-d');
        $data->whereDate('created_at', '<=', $until);

        $datas = $data->get();
        //untuk active halaman
        $halaman = "laporanpengiriman";

        return view('reports.shippings', compact('datas', 'halaman'));
    }
}
