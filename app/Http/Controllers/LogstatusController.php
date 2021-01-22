<?php

namespace App\Http\Controllers;

use App\Logstatus;
// use App\SalesDetails;
// use App\SalesPayments;
// use App\SalesDetailsStocks;
// use App\Inventories;
// use App\InventoriesStocks;
// use App\InventoriesTracks;
// use App\InventoriesPrices;
use App\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class LogstatusController extends Controller
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
        // $filterNoInvoice = $request->get('noinvoice');
        // $filterPartner = $request->get('partner');
        
        $data = Logstatus::orderBy('id', 'desc');

        // if ($request->has('noinvoice')){
        //     if (!empty($filterNoInvoice)) {
        //         $data->WhereHas('partner', function (Builder $query) use ($filterPartner){                   
        //             $query->where('name', 'like', '%'.$filterPartner.'%');                    
        //         });
        //         $data->orWhere('transaction_number', 'like', '%'.$filterNoInvoice.'%');
        //     }
        // }

        // payments
        $datas = $data->paginate(10);
        
        $halaman = "logstatus";
        return view('logstatus.index', [ 'datas' => $datas, 'halaman' => $halaman ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $gedungs = Gedung::all();

        $halaman = "logstatus";
        return view('logstatus.form', compact('halaman', 'gedungs'));
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
            'invoice' => 'required',
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

            $newData = new Sales;
            $newData->date_select = $request->get('date');
            $newData->partners_id = $request->get('partner');
            $newData->transaction_number = $request->get('invoice');
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
                    ->where('type_name','Partner')
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
                        // save sales detail
                        $newDetail = new SalesDetails;
                        $newDetail->sales_id = $newData->id;
                        $newDetail->inventory_id = $idIven;
                        $newDetail->qty = $qty;
                        $newDetail->unit = $unit;
                        $newDetail->subtotal = '0';
                        $newDetail->stocks = '';
                        $newDetail->save();

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
    
                                    // do stock transactional
                                    array_push($collectStocks, array(
                                            'id_stock'  => $stock->id,
                                            'stock'     => $stock->stock,
                                            'expired'   => $stock->expired,
                                            'price'     => $invenDataPrice,
                                            'purchasesPrice' => $stock->price_purchase,
                                            'used'      => $use,
                                            'subtotal'  => $subTotal,
                                        )
                                    );

                                    // save sales details stocks
                                    $DetailsStocks = new SalesDetailsStocks;
                                    $DetailsStocks->sales_id = $newData->id;
                                    $DetailsStocks->sales_details_id = $newDetail->id;
                                    $DetailsStocks->inventory_id = $idIven;
                                    $DetailsStocks->stocks_id = $stock->id;
                                    $DetailsStocks->expired = $stock->expired;
                                    $DetailsStocks->name = $invenData->name;
                                    $DetailsStocks->unit = $unit;
                                    $DetailsStocks->price_purchase = $stock->price_purchase;
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
                                    $newTracks->note = 'Penjualan Partner ' . $request->get('invoice');
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

                        // update json stocks in details
                        $newDetail->subtotal = $totalSalesDetail;
                        $newDetail->stocks = json_encode($collectStocks);
                        $newDetail->save();
                    } // endif > this inventories stocks enough

                } // endif > this inventories have stocks or not
            } // endforeach > all inventories from form
            
            if (!$resultStatus) {
                DB::rollback();

                $resultMsg = $resultMsg ? $resultMsg : 'Sales Failed created, reload and try again';
            } else {
                DB::commit();

                $resultMsg = 'Sales successfully created';
                $resultLink = '/sales';
            }

            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        }
        catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('sales.create')->with('status', 'Sales FAILED');
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
    
    public function payments(Request $request, $id)
    {
        $sales = Sales::findOrFail($id);
        $total = SalesDetails::where('sales_id', $id)->sum('subtotal');
        $paymentsData = SalesPayments::orderBy('id', 'desc')->where('sales_id', $id)->get();
        $totalPayment = SalesPayments::where('sales_id', $id)->sum('pay');
        $minus = $total - ($totalPayment ? $totalPayment : 0);

        $do = $request->get('do');

        if ($do == 'add') {
            $halaman = "penjualanpartner";
            return view('sales.paymentform', [ 'halaman' => $halaman, 'sales' => $sales, 'total' => $total, 'paymenttotal' => $totalPayment, 'minus' => $minus ]);
        }
        elseif ($do == 'edit') {
            $halaman = "penjualanpartner";
            $paymentId = $request->get('id');
            $payment = SalesPayments::findOrFail($paymentId);

            return view('sales.paymentform', [ 'halaman' => $halaman, 'sales' => $sales, 'total' => $total, 'paymenttotal' => $totalPayment, 'minus' => $minus, 'payment' => $payment ]);
        } 
        elseif($do == 'delete') {
            $paymentId = $request->get('id');

            $thisData = SalesPayments::findOrFail($paymentId);
            $thisData->deleted_by = \Auth::user()->id;
            $thisData->save();

            $thisData->delete();
            
            $totalPayment = SalesPayments::where('sales_id', $id)->sum('pay');
    
            //# if done
            if ($totalPayment == $total) {
                $thisSales = Sales::findOrFail($id);
                $thisSales->payment_status = '1';
                $thisSales->payment_total = $totalPayment;
                $thisSales->save();
            } else {
                $thisSales = Sales::findOrFail($id);
                $thisSales->payment_status = '0';
                $thisSales->payment_total = $totalPayment;
                $thisSales->save();
            }

            return redirect()->route('sales.payments', [$id])->with('status', 'Data successfully deleted');
        }
        else {
            $halaman = "penjualanpartner";
            return view('sales.payment', [ 'halaman' => $halaman, 'sales' => $sales, 'total' => $total, 'payments' => $paymentsData, 'paymenttotal' => $totalPayment, 'minus' => $minus   ]);
        }
    }
    
    public function storepayments(Request $request)
    {
        $request->validate([
            'sales_id' => 'required',
            'pay' => 'required|lte:minus',
        ]);

        $salesId = $request->get('sales_id');

        $do = $request->get('do');

        if ($do == 'edit') {
            $paymentId = $request->get('payment_id');

            $thisData = SalesPayments::findOrFail($paymentId);
            $thisData->sales_id = $salesId;
            $thisData->date_select = $request->get('date');
            $thisData->pay = $request->get('pay');
            $thisData->notes = $request->get('notes');
            $thisData->updated_by = \Auth::user()->id;
            $thisData->save();
        } 
        else {
            $thisData = new SalesPayments;
            $thisData->sales_id = $salesId;
            $thisData->date_select = $request->get('date');
            $thisData->pay = $request->get('pay');
            $thisData->notes = $request->get('notes');
            $thisData->created_by = \Auth::user()->id;
            $thisData->save();
        }

        $totalPayment = SalesPayments::where('sales_id', $salesId)->sum('pay');

        //# if done
        if ($request->get('pay') == $request->get('minus')) {
            $thisSales = Sales::findOrFail($salesId);
            $thisSales->payment_status = '1';
            $thisSales->payment_total = $totalPayment;
            $thisSales->save();
        } else {
            $thisSales = Sales::findOrFail($salesId);
            $thisSales->payment_status = '0';
            $thisSales->payment_total = $totalPayment;
            $thisSales->save();
        }
        
        $msg = ($do == 'edit') ? 'updated' : 'created';

        return redirect()->route('sales.payments', [$salesId])->with('status', 'Data successfully '.$msg);
    }
    
    public function report(Request $request)
    {
        $data = Sales::orderBy('id', 'desc');
        
        $start = $request->get('start') ?? date('Y-m-d');
        $data->whereDate('created_at', '>=', $start);
        
        $until = $request->get('until') ?? date('Y-m-d');
        $data->whereDate('created_at', '<=', $until);

        $partnersData = NULL;
        $filterId = $request->get('id');
        if ($request->has('id')){
            if (!empty($filterId)) {
                $data->where('partners_id', '=', $filterId);

                $partnersData = Partners::findOrFail($filterId);
            }
        }

        $datas = $data->get();

        $halaman = "logstatus";

        return view('reports.sales', compact('datas', 'partnersData', 'halaman'));
    }
}
