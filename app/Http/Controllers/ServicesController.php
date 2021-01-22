<?php

namespace App\Http\Controllers;

use App\User;
use App\Masters;
use App\Partners;
use App\Routes;
use App\RoutesDetails;
use App\Inventories;
use App\InventoriesStocks;
use App\InventoriesPrices;
use App\Preorder;
use App\PreorderDetails;
use App\Shippings;
use App\ShippingsDetails;
use App\ShippingsDetailsInventories;
use App\ShippingsDetailsStocks;
use App\Sales;
use App\Purchases;
use App\PurchasesDetail;
use App\Brokens;
use App\BrokensDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;


class ServicesController extends Controller
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

    public function inventories(Request $request) 
    {
        $result = array();
        $do = $request->get('do');
        $id = $request->get('id');

        if ($do == 'byid') {
            $result = Inventories::findOrFail($id);
        } 
        elseif ($do == 'ajaxselect2') {
            $data = Inventories::orderBy('id', 'desc');

            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                }
            }

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                $type = $request->get('type');
                $invenDataPrice = InventoriesPrices::where('inventories_id', $val->id)->where('type_name',$type)->value('price');
 
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->name,
                    'unit' => $val->unit,
                    'price' => $invenDataPrice,
                ));
            }
            
            $result = $res;
        } 
        elseif ($do == 'ajaxall') {
            $data = Inventories::orderBy('id', 'desc');

            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                }
            }
            
            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                $type = $request->get('type');
                $invenDataPrice = InventoriesPrices::where('inventories_id', $val->id)->where('type_name',$type)->value('price');
 
                array_push($res, array(
                    'id' => $val->id,
                    'name' => $val->name,
                    'unit' => $val->unit,
                    'stock' => $val->stock,
                    'price' => $invenDataPrice,
                ));
            }
            
            $result = $res;
        }
        else if ($do == 'getreadystock') {
            $type = $request->get('type');
            $invenDataStock = InventoriesStocks::where('inventories_id', $id)->orderBy('expired', 'asc')->get();
            $invenDataPrice = InventoriesPrices::where('inventories_id', $id)->where('type_name',$type)->value('price');

            if ($invenDataStock) {
                $qtyNeed = $need = $request->get('qty');
                $now = date('Y-m-d');
                $collectStocks = array();
                $sumAvailable = $use = $total = 0;

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
                            array_push($collectStocks, array(
                                    'id_stock' => $stock->id,
                                    'stock' => $stock->stock,
                                    'expired' => $stock->expired,
                                    'price' => $invenDataPrice,
                                    'used' => $use,
                                    'subtotal' => $subTotal,
                                )
                            );
                        }
                    }

                    $total += $subTotal;
                }

                if ($sumAvailable < $qtyNeed) {
                    $result = array('status' => false, 'msg' => 'Stock tidak mencukupi, tersisa ' . $sumAvailable );
                } else {
                    $result = array('status' => true, 'stocks' => $collectStocks, 'total' => $total);
                }

            } else 
                $result = array('status' => false, 'msg' => 'Stock tidak tersedia' );
        }

        return response()->json($result);
    }

    public function partners(Request $request) 
    {
        $result = array();
        $do = $request->get('do');
        $id = $request->get('id');

        $data = Partners::orderBy('id', 'asc');

        if ($request->get('do') == 'ajaxselect2') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                    $data->orWhere('phone', 'like', '%'.$filterKey.'%');
                    $data->orWhere('email', 'like', '%'.$filterKey.'%');
                    $data->orWhere('address', 'like', '%'.$filterKey.'%');
                }
            }

            if ($request->has('noroutes')){
                $data->doesntHave('routesDetails');
            }

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->name,
                ));
            }
            
            $result = $res;
        } 
        elseif ($request->get('do') == 'ajaxall') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                }
            }

            if ($request->has('noroutes')){
                $data->doesntHave('routesDetails');
            }
            
            $result = $data->get();
        }
        
        elseif ($request->get('do') == 'getPartners'){
            $result = Partners::findOrFail($id);
        }

        return response()->json($result);
    }

    public function drivers(Request $request) 
    {
        $result = array();
        $do = $request->get('do');

        $data = User::where('roles', 'like', '%DRIVER%')->orderBy('id', 'asc');

        if ($request->get('do') == 'ajaxselect2') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                    $data->orWhere('phone', 'like', '%'.$filterKey.'%');
                    $data->orWhere('email', 'like', '%'.$filterKey.'%');
                    $data->orWhere('address', 'like', '%'.$filterKey.'%');
                }
            }

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->name,
                ));
            }
            
            $result = $res;
        } 
        elseif ($request->get('do') == 'ajaxall') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                }
            }

            $result = $data->get();
        }

        return response()->json($result);
    }

    public function routes(Request $request) 
    {
        $result = array();
        $do = $request->get('do');

        $data = Routes::orderBy('id', 'asc');

        if ($request->get('do') == 'ajaxselect2') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                }
            }

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->name,
                ));
            }
            
            $result = $res;
        } 
        elseif ($request->get('do') == 'ajaxall') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                }
            }

            $result = $data->get();
        }

        return response()->json($result);
    }


    public function purchases(Request $request) 
    {
        $result = array();
        $do = $request->get('do');

        $data = Purchases::orderBy('id', 'desc');

        if ($request->get('do') == 'ajaxselect2') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('transaction_number', 'like', '%'.$filterKey.'%');
                }
            }

            $data->whereHas('details', function (Builder $query){
                $query->whereHas('purchasesInventory', function (Builder $inven){
                    $inven->where('type_id', 11); //# manual set id master param as "Telur"
                });
            });

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->transaction_number . ' (' . $val->date_select . ')',
                ));
            }
            
            $result = $res;
        } 
        elseif ($request->get('do') == 'ajaxall') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('transaction_number', 'like', '%'.$filterKey.'%');
                }
            }            
            
            if ($request->has('id')) {
                $filterId = $request->get('id');
                if (!empty($filterId)) {
                    $data->where('id', $filterId);
                }
            }

            // $data->whereHas('details', function (Builder $query){
            //     $query->whereHas('purchasesInventory', function (Builder $inven){
            //         $inven->where('type_id', 11); //# manual set id master param as "Telur"
            //     });
            // });

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                $detailPurchases = array();
                if (!empty($val->details)) {
                    foreach ($val->details as $detail) {                        
                            $qty = $detail->qty;
                            $unit = $detail->unit;
                            $idStock = $detail->stocks_id;
                            $detailPrices = array();

                            $stockPrices = inventoriesPrices::where('stocks_id', $idStock)->get();
                            $firstPrices = Arr::first($stockPrices);
                            
                            foreach ($stockPrices as $price) {
                                $subtotal = $qty * $price->price;
                                array_push($detailPrices, array(
                                    'type_name' => $price->type_name,
                                    'type_id' => $price->type_id,
                                    'price' => $price->price, 
                                    'firstPrice' => $firstPrices->price,
                                    'qty' => $qty,
                                    'unit' => $unit,  
                                    'subtotal' => $subtotal                              
                                ));                                
                            }
                            $stocks = inventoriesStocks::findOrFail($idStock);
  
                            array_push($detailPurchases, array(
                                'id' => $detail->id,
                                'type' => $detail->purchasesInventory->type_id,
                                'invenName' => $detail->purchasesInventory->name,
                                'invenId' => $detail->inventory_id,
                                'stockId' => $detail->stocks_id,
                                'stock' => $stocks->stock,
                                'unit' => $detail->unit,
                                'qty' => $detail->qty,
                                'exp' => $detail->inventoryStock->expired,
                                'pricePurchase' => $detail->price_purchase,
                                'subtotal' => $detail->subtotal,
                                'prices'    => $firstPrices->price, 
                            ));                                             
                    }
                }             

                array_push($res, array(
                    'id'        => $val->id,
                    'date'      => $val->date_select,
                    'number'    => $val->transaction_number,
                    'notes'     => $val->notes,
                    'details'   => $detailPurchases,
                ));
            }
            
            $result = $res;
        }
        elseif ($request->get('do') == 'ajaxalltelur') {       
            if ($request->has('id')) {
                $filterId = $request->get('id');
                if (!empty($filterId)) {
                    $data->where('id', $filterId);
                }
            }

            $data->whereHas('details', function (Builder $query){
                $query->whereHas('purchasesInventory', function (Builder $inven){
                    $inven->where('type_id', 1); //# manual set id master param as "Telur"
                });
            });
            
            $datas = $data->get();
            
            $res = array();
            foreach ($datas as $key => $val) {
                $detailPurchases = array();
                if (!empty($val->details)) {
                    foreach ($val->details as $detail) {
                        if ($detail->purchasesInventory->type_id == '11') 
                        {
                            $qty = $detail->qty;
                            $unit = $detail->unit;
                            $idStock = $detail->stocks_id;
                            $detailPrices = array();

                            $stockPrices = inventoriesPrices::where('stocks_id', $idStock)->get();
                            $firstPrices = Arr::first($stockPrices);
                            
                            foreach ($stockPrices as $price) {
                                $subtotal = $qty * $price->price;
                                array_push($detailPrices, array(
                                    'type_name' => $price->type_name,
                                    'type_id' => $price->type_id,
                                    'price' => $price->price, 
                                    'firstPrice' => $firstPrices->price,
                                    'qty' => $qty,
                                    'unit' => $unit,  
                                    'subtotal' => $subtotal                              
                                ));                                
                            }
                            $stocks = inventoriesStocks::findOrFail($idStock);
  
                            array_push($detailPurchases, array(
                                'id' => $detail->id,
                                'type' => $detail->purchasesInventory->type_id,
                                'invenName' => $detail->purchasesInventory->name,
                                'invenId' => $detail->inventory_id,
                                'stockId' => $detail->stocks_id,
                                'stock' => $stocks->stock,
                                'unit' => $detail->unit,
                                'qty' => $detail->qty,
                                'exp' => $detail->inventoryStock->expired,
                                'pricePurchase' => $detail->price_purchase,
                                'subtotal' => $detail->subtotal,
                                'prices'    => $detailPrices, 
                            ));
                        }
                    }
                }              
               
                array_push($res, array(
                    'id'        => $val->id,
                    'date'      => $val->date_select,
                    'number'    => $val->transaction_number,
                    'notes'     => $val->notes,
                    'details'   => $detailPurchases,
                ));
            }
            
            $result = $res;
        }
        elseif ($request->get('do') == 'stock'){
            if ($request->has('id')) {
                $filterId = $request->get('id');
                if (!empty($filterId)) {
                    $data->where('id', $filterId);
                }                
            }
            $invenDataStock = InventoriesStocks::findOrFail($request->get('id'));

            if ($invenDataStock) {
                $qtyNeed = $need = $request->get('qty');
                $now = date('Y-m-d');
                $sumAvailable = $use = $total = 0;                  
                if ($invenDataStock->stock > 0 && $invenDataStock->expired > $now) {
                        $sumAvailable += $invenDataStock->stock;                        
                }


                if ($sumAvailable < $qtyNeed) {
                    $result = array('status' => false, 'msg' => 'Stock tidak mencukupi, tersisa ' . $sumAvailable );
                } else {
                    $result = array('status' => true);
                }

            } else 
                $result = array('status' => false, 'msg' => 'Stock tidak tersedia' );
        }

        return response()->json($result);
    }

    public function preorders(Request $request) 
    {
        $result = array();
        $do = $request->get('do');

        if ($request->get('do') == 'byidpo') {
            $poId = $request->get('po');
            
            $data = PreorderDetails::where('preorder_id', $poId)->get()->toArray();

            $result = $data;
        } 
        else {
            $routesId = $request->get('route');
            $routesData = RoutesDetails::where('routes_id', $routesId)->pluck('partners_id')->toArray();
    
            $data = Preorder::where('shippings_id', NULL)
                ->whereIn('partners_id', $routesData)
                ->orderBy('id', 'desc');
    
            if ($request->get('do') == 'ajaxselect2') {
                if ($request->has('keyword')){
                    $filterKey = $request->get('keyword');
                    if (!empty($filterKey)) {
                        $data->whereHas('partner', function ($query) use ($filterKey) {
                            $query->where('name', 'like', '%'.$filterKey.'%');
                            $query->orWhere('phone', 'like', '%'.$filterKey.'%');
                            $query->orWhere('email', 'like', '%'.$filterKey.'%');
                            $query->orWhere('address', 'like', '%'.$filterKey.'%');
                        });
                        
                        $data->orWhere('po_number', 'like', '%'.$filterKey.'%');
                    }
                }
    
                $datas = $data->get();
                $res = array();
                foreach ($datas as $key => $val) {
                    array_push($res, array(
                        'id' => $val->id,
                        'text' => '(PO: '. $val->po_number . ') ' . $val->partner->name,
                        'number' => $val->po_number,
                        'partnerId' => $val->partner->id,
                        'partnerName' => $val->partner->name,
                        'partnerPhone' => $val->partner->phone,
                        'partnerAddress' => $val->partner->address,
                    ));
                }
                
                $result = $res;
            } 
            elseif ($request->get('do') == 'ajaxall') {
                if ($request->has('keyword')) {
                    $filterKey = $request->get('keyword');
                    if (!empty($filterKey)) {
                        $data->where('po_number', 'like', '%'.$filterKey.'%');
                    }
                }
    
                $datas = $data->get();
                $res = array();
                foreach ($datas as $key => $val) {
                    array_push($res, array(
                        'id' => $val->id,
                        'date' => $val->date_select,
                        'number' => $val->po_number,
                        'partnerId' => $val->partner->id,
                        'partnerName' => $val->partner->name,
                        'partnerPhone' => $val->partner->phone,
                        'partnerAddress' => $val->partner->address,
                        'notes' => $val->notes,
                        'details' => $val->details,
                    ));
                }
    
                $result = $res;
            }
        }

        return response()->json($result);
    }

    public function shippings(Request $request) 
    {
        $result = array();
        $do = $request->get('do');

        if ($request->get('do') == 'shippingdetailsstocks') {
            $shippingId = $request->get('idshipping');
            $detailId = $request->get('iddetail');
            
            $data = ShippingsDetails::findOrFail($detailId);

            $detailsIvens = $data->detailsInventories;

            foreach ($detailsIvens as $key => $iven) 
            {
                $iven->detailsShippingStocks->toArray();
            }
            
            $detailsIvens = $detailsIvens->toArray();
            $result = $data->toArray();
        } 

        return response()->json($result);
    }

    public function sales(Request $request) 
    {
        $result = array();
        $do = $request->get('do');

        $data = Sales::where('shippings_id', NULL)->orderBy('id', 'desc');

        if ($request->get('do') == 'ajaxselect2') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->whereHas('partner', function ($query) use ($filterKey) {
                        $query->where('name', 'like', '%'.$filterKey.'%');
                        $query->orWhere('phone', 'like', '%'.$filterKey.'%');
                        $query->orWhere('email', 'like', '%'.$filterKey.'%');
                        $query->orWhere('address', 'like', '%'.$filterKey.'%');
                    });
                    
                    $data->orWhere('transaction_number', 'like', '%'.$filterKey.'%');
                }
            }

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->transaction_number . '(' .$val->partner->name. ')',
                    'number' => $val->transaction_number,
                    'partnerId' => $val->partner->id,
                    'partnerName' => $val->partner->name,
                    'partnerPhone' => $val->partner->phone,
                    'partnerAddress' => $val->partner->address,
                ));
            }
            
            $result = $res;
        } 
        elseif ($request->get('do') == 'ajaxall') {
            if ($request->has('keyword')) {
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('name', 'like', '%'.$filterKey.'%');
                }
            }

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id'        => $val->id,
                    'date'      => $val->date_select,
                    'number'    => $val->transaction_number,
                    'partnerId' => $val->partner->id,
                    'partnerName' => $val->partner->name,
                    'partnerPhone' => $val->partner->phone,
                    'partnerAddress' => $val->partner->address,
                    'notes'     => $val->notes,
                    // 'details'   => $detailPurchases,
                ));
            }

            $result = $res;
        }

        return response()->json($result);
    }
}
