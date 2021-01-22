<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\Routes;
use App\RoutesDetails;
use App\Partners;


class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filterName = $request->get('name');
        $filterKomponen = $request->get('komponen');

        $data = Routes::orderBy('id', 'desc');     
    
        if ($request->has('name')){
            if (!empty($filterName)) {
                $data->where('name', 'like', '%'.$filterName.'%');
            }
        }
    
        if ($request->has('komponen')){
            if (!empty($filterKomponen)) {
                $data->whereHas('details', function (Builder $query) use ($filterKomponen){
                    $query->whereHas('partner', function (Builder $partner) use ($filterKomponen){
                        $partner->where('name', 'like', '%'.$filterKomponen.'%')
                        ->orWhere('phone', 'like', '%'.$filterKomponen.'%')
                        ->orWhere('address', 'like', '%'.$filterKomponen.'%');
                    });
                });
            }
        }

        $routes = $data->paginate(10);
        $halaman = "routes";

        return view('routes.index', [ 
            'routes' => $routes, 
            'halaman' => $halaman 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "routes";
        $datas = '';
        $oldPartners = array();

        return view('routes.form', [        
            'halaman' => $halaman,
            'data' => $datas,
            'oldPartners' => json_encode($oldPartners)
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
          
            'name' => 'required',
            'partners' => 'required',
        ],
        [
            'partners.required' => 'Mohon pilih partner dahulu.',
        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;
            $resultMsg = '';
            $resultLink = '';

            $newData = new Routes;
            $newData->name = $request->get('name');
            $newData->created_by = \Auth::user()->id;
            $newData->save();

            $partners = $request->get('partners');

            foreach ($partners as $partner) {
                $exPartner = explode('|', $partner);
                $idPartner = $exPartner[0];
                $orderPartner = $exPartner[1];

                // limitation partners have route cannot add to another route
                $findFirst = RoutesDetails::where("partners_id", $idPartner)->first();
                if (!empty($findFirst)) {
                    $resultStatus = false;
                    break;
                }

                $newDetail = new RoutesDetails;
                $newDetail->routes_id = $newData->id;
                $newDetail->partners_id = $idPartner;
                $newDetail->order = $orderPartner;
                $newDetail->save();
            }

            if (!$resultStatus) {
                DB::rollback();

                $resultMsg = $resultMsg ? $resultMsg : 'Rute tidak tersimpan, Silahkan reload halaman kembali';
            } else {
                DB::commit();

                $resultMsg = 'Rute berhasil disimpan';
                $resultLink = '/routes';
            }

            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));

        }
        catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('routes.create')->with('status', 'Rute Gagal dibuat');
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
        $halaman = "routes";
        $datas = Routes::findOrFail($id);

        $oldPartners = array();
        foreach($datas->details as $detail){
            array_push($oldPartners, array( 
                'id' => $detail->partners_id, 
                'order' => $detail->order, 
                'name' => $detail->partner->name, 
                'address' => $detail->partner->address 
                )
            );
        }

        return view('routes.form', [        
            'halaman' => $halaman,
            'data' => $datas,
            'oldPartners' => json_encode($oldPartners)
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
          
            'name' => 'required',
            'partners' => 'required',
        ],
        [
            'partners.required' => 'Mohon pilih partner dahulu.',
        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;
            $resultMsg = '';
            $resultLink = '';

            $thisData = Routes::findOrFail($id);     
            $thisData->name = $request->get('name');
            $thisData->updated_by = \Auth::user()->id;
            $thisData->save();

            $partners = $request->get('partners');

            $oldDetail = RoutesDetails::where('routes_id', $id)->delete();

            foreach ($partners as $partner) {
                $exPartner = explode('|', $partner);
                $idPartner = $exPartner[0];
                $orderPartner = $exPartner[1];

                $newDetail = new RoutesDetails;
                $newDetail->routes_id = $thisData->id;
                $newDetail->partners_id = $idPartner;
                $newDetail->order = $orderPartner;
                $newDetail->save();
            }

            if (!$resultStatus) {
                DB::rollback();

                $resultMsg = $resultMsg ? $resultMsg : 'Rute tidak tersimpan, Silahkan reload halaman kembali';
            } else {
                DB::commit();

                $resultMsg = 'Rute berhasil disimpan';
                $resultLink = '/routes';
            }

            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));

        }
        catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('routes.edit'. [$id])->with('status', 'Rute Gagal dibuat');
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
        $data = Routes::findOrFail($id);

        $data->delete();

        return redirect()->route('routes.index')->with('status', 'Rute successfully delete');
    }
}
