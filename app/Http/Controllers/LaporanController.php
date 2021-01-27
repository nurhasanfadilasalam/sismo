<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gedung;
use App\Logstatus;
use Ndum\Laravel\Snmp;
use Ping;


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
        $data = Logstatus::orderBy('id', 'desc');
        
        
         // logstatus
         $datas = $data->paginate(10);
        return view('laporan.status_perangkat', ['datas' => $datas, 'halaman' => $halaman]);
    }


    public function status_perangkat()
    {

        $snmp = new Snmp();
        // use Ping;
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

        $data = Logstatus::orderBy('id', 'desc');
        // $data = Logstatus::orderBy('id', 'desc')
        //         ->leftJoin('gedung', 'users.id', '=', 'posts.user_id');

        // $data = Logstatus::select(\DB::raw("COUNT(*) as count"))
        //             ->whereYear('created_at', date('Y'))
        //             ->groupBy(\DB::raw("Month(created_at)"))
                   
        
        //             Logstatus::select('id')
        //             ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
        //             ->get();
        
        $url = 'www.google.com';
        $health = Ping::check($url);

        if($health == 200) {
            return 'Alive!';
        } else {
            return 'Dead :(';
        }

        // logstatus
        $datas = $data->paginate(10);

        //  untuk active halaman
         $halaman = "laporan";
        
        // return view('home.orderin');
        return view('laporan.status_perangkat', ['halaman' => $halaman , 'result1' => $result1, 'datas' => $datas,]);

        

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