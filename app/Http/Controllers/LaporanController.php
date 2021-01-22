<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orderlist;
use Ndum\Laravel\Snmp;


class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //untuk active halaman
        $halaman = "laporan";

        // return view('home.orderin');
        // return view('orderin', ['halaman' => $halaman ]);

        $list_orderlist = Orderlist::all();
        if($request->ajax()){
            return datatables()->of($list_orderlist)
                        ->addColumn('action', function($data){
                            $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-info btn-sm edit-post"><i class="far fa-edit"></i> Edit</a>';
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="far fa-trash-alt"></i> Delete</button>';     
                            return $button;
                        })
                        ->rawColumns(['action'])
                        ->addIndexColumn()
                        ->make(true);
        }

        return view('laporan.status_perangkat', ['halaman' => $halaman]);
    }

    public function status_perangkat()
    {

        $snmp = new Snmp();
        // $snmp1->newClient('localhost', 2, 'mycommunity');
        // $snmp->newClient('localhost', 1, 'mycommunity');
        $ipaddress = 'localhost';
        $snmp->newClient($ipaddress, 1, 'mycommunity');
        $result1 = $snmp->getValue('1.3.6.1.2.1.1.5.0');
        $result2 = $snmp->getValue('1.3.6.1.2.1.1.1.0'); ## hostname
        // $result2 = $snmp2->getValue('1.3.6.1.2.1');

        // $oids = $snmp->get('1.3.6.1.4.1.9.0');
        // $oids = $snmp->get('1.3.6.1.2.1.1.1', '1.3.6.1.2.1.1.3', '1.3.6.1.2.1.1.5');

        // echo $snmp->getValue('1.3.6.1.2.1').PHP_EOL;
        // dd($result1);
        // dd($result1 , $result2 );
        // dd($oids);

        // $walk = $snmp->walk();

        // while($walk->hasOids()) {
        //     try {
        //         # Get the next OID in the walk
        //         $oid = $walk->next();
        //         echo sprintf("%s = %s", $oid->getOid(), $oid->getValue()).PHP_EOL;
        //     } catch (\Exception $e) {
        //         # If we had an issue, display it here (network timeout, etc)
        //         echo "Unable to retrieve OID. ".$e->getMessage().PHP_EOL;
        //     }
        // }

        // dd($oids);
        //  untuk active halaman
         $halaman = "laporan";
        
        // return view('home.orderin');
        return view('laporan.status_perangkat', ['halaman' => $halaman , 'result1' => $result1]);

        

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
        $id = $request->id;
        
        $post   =   Orderlist::updateOrCreate(['id' => $id],
                    [
                        'nama_pegawai' => $request->nama_pegawai,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'email' => $request->email,
                        'alamat' => $request->alamat,
                    ]); 

        return response()->json($post);
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