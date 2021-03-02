<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gedung;
use App\Logstatus;
use App\Perangkat;
use App\Traffic;
use Ndum\Laravel\Snmp;
use Ping;
use UxWeb\SweetAlert\SweetAlert;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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

        $data = Perangkat::orderBy('id', 'desc');

        
        
        // logstatus
        $datas = $data->paginate(10);
        return view('laporan.status_perangkat', ['datas' => $datas, 'halaman' => $halaman]);
    }


    public function status_perangkat()
    {

        

        // use Ping;
        // $snmp1->newClient('localhost', 2, 'mycommunity');
        // $snmp->newClient('localhost', 1, 'public');

        // $snmp = new Snmp();
        // $ipaddress = 'localhost';
        // $snmp->newClient($ipaddress, 1, 'public');
        // // inoctet
        // // $result1 = $snmp->getValue('1.3.6.1.2.1.2.2.1.10.1');
        // // outoctet
        // $result1 = $snmp->getValue('1.3.6.1.2.1.2.2.1.16.1');
        // // dd($result1);




        // $result2 = $snmp->getValue('1.3.6.1.2.1.2.2.1.16');
        
        
        // $result2 = $snmp->getValue('1.3.6.1.2.1.1.3.0'); //uptime
        // $result2 = $snmp->fromString('ifInOctets.4');
        
        // $result2 = $snmp->walk('1.3.6.1.2.1.1.1.0'); 

        // $result2 = $snmp->getValue('1.3.6.1.2.1.1.3.0');
        // $result3 = $snmp->getValue('1.3.6.1.2.1.1.1'); 
        ## hostname

        // $result2 = $snmp->getValue('1.3.6.1.2.1.10');
        // $result2 = $snmp2->getValue('1.3.6.1.2.1');

        // $oids = $snmp->get('1.3.6.1.4.1.9.0');
        // $oids = $snmp->get('1.3.6.1.2.1.1.1', '1.3.6.1.2.1.1.3', '1.3.6.1.2.1.1.5');

        // echo $snmp->getValue('1.3.6.1.2.1').PHP_EOL;
        
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

        // $newPatient = new Patients; 
        // $newPatient->uuid = Uuid::uuid4()->getHex();
        //         $newPatient->mr = $newMR;
        //         $newPatient->patient_type = $types->id;
        //         $newPatient->name = $bpjsData->nama;
        //         $newPatient->ktp = $bpjsData->noKTP;
        //         $newPatient->bpjs = $bpjsData->noKartu;
        //         $newPatient->phone = $bpjsData->noHP;
        //         $newPatient->date_birth = $bpjsBirth;
        //         $newPatient->gender = strtolower($bpjsData->sex);
        //         $newPatient->blood = $bpjsData->golDarah;
        //         $newPatient->bpjs_data = $request->get('bpjs_data');
        //         $newPatient->created_by = Auth::user()->id;
        //         $newPatient->save();    
        
        //for table data    
        $data = Perangkat::orderBy('id', 'desc'); 
        $datas = $data->paginate(10); 

        $snmp = new Snmp();
        $ipaddress = 'localhost';
        $snmp->newClient($ipaddress, 1, 'public');
        // inoctet
        $result1 = $snmp->getValue('1.3.6.1.2.1.2.2.1.10.1');
        // outoctet 
        $result2 = $snmp->getValue('1.3.6.1.2.1.2.2.1.16.1');

        $newTraffic = new Traffic;
        $newTraffic->perangkat_id = '2';
        // $newTraffic->nilai = (($result1 * 8)/1000000);
        $random = rand();
        $newTraffic->nilai = ($result1/1024);
        $newTraffic->keterangan = 'inoctet';
        $newTraffic->created_by = Auth::user()->id;
        $newTraffic->save();
        
        $newTraffic = new Traffic;
        $newTraffic->perangkat_id = '2';
        // $newTraffic->nilai = (($result2 * 8)/1000000);
        $newTraffic->nilai = (($result2 /1024) *2);
        $newTraffic->keterangan = 'outoctet';
        $newTraffic->created_by = Auth::user()->id;
        $newTraffic->save(); 
        

        // dd($result1);
         
        
        //count live server up or die
        // $dataServers = Perangkat::pluck('ip_perangkat');
        foreach($datas as $dataServer) {
            $diagnosa[] = Ping::check($dataServer->ip_perangkat); 

            $qtyUp = 0;
            $qtyDown = 0;
            foreach($diagnosa as $check){
                if ($check == 200){
                    $qtyUp += 1;
                }elseif($check != 200){
                    $qtyDown += 1;
                    
                }
            }
            
        }
        
        $upServer = $qtyUp;
        $downServer= $qtyDown;
      
        $datajumlah = Perangkat::count('id');

        SweetAlert::message('Thanks for comment!')->persistent('Close');
        //  untuk active halaman
         $halaman = "laporan";

        //  return view('laporan.status_perangkat', ['halaman' => $halaman , 'result1' => $result1, 'datas' => $datas, 'datajumlah' => $datajumlah, 'downServer' => $downServer, 'upServer'=> $upServer ]);
         return view('laporan.status_perangkat', compact(
             'halaman', 
             'result1',
             'datas' ,
             'datajumlah', 
             'downServer', 
             'upServer'
            )
        ); 

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
        // $id = $request->id;
        
        // $post   =   Orderlist::updateOrCreate(['id' => $id],
        //             [
        //                 'nama_pegawai' => $request->nama_pegawai,
        //                 'jenis_kelamin' => $request->jenis_kelamin,
        //                 'email' => $request->email,
        //                 'alamat' => $request->alamat,
        //             ]); 

        // return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data_traffic = Traffic::select('nilai','created_at')->orderBy('created_at','desc')->take(15)->get();
        $reversed = $data_traffic->reverse();
        $new    = $reversed->all();
        
        $max_date = Traffic::max('created_at');
        $max = date('Y-m-d H:i:s',strtotime($max_date));
        $now = date('Y-m-d H:i:s');
        $dif = strtotime($now)-strtotime($max);
        $hours = floor($dif / (60 * 60));
        $seconds = $dif - $hours * (60 * 60);

        // $data_traffic_last = Traffic::pluck('nilai')
        // ->where('keterangan', 'inoctet')
        // ->orderBy('created_at','desc')->take(1)->get();

        $data_traffic_last_inoctet = Traffic::select('nilai')
            ->where([   ['keterangan', '=', 'inoctet'],])
            ->latest('created_at')->first();

        $data_traffic_last_outoctet = Traffic::select('nilai')
            ->where([   ['keterangan', '=', 'outoctet'],])
            ->latest('created_at')->first();    


        // dd($data_traffic_last);
        
        //  untuk active halaman
        $halaman = "laporan";
        $laporan = Perangkat::findOrFail($id);
        
        return view('laporan.show', [
            'laporan' => $laporan, 
            'halaman' => $halaman,
            'suhu' => $new,
            'suhuu' => $new,
            'selisih' => $seconds,
            'updatewaktu' =>$max,
            'data_traffic_last_inoctet' => $data_traffic_last_inoctet,
            'data_traffic_last_outoctet' => $data_traffic_last_outoctet
        ]);
    }



    public function traffic_jaringan(Request $request){


        // nilai default
        $filterPerangkat = 2;
        // $filterPerangkat = $request->get('perangkat');

        $datajumlah = Perangkat::count('id');

     
        // dd($perangkats);
        // $perangkat = Perangkat::orderBy('id', 'asc');
        // if ($request->has('perangkat')){
        //     if (!empty($filterPerangkat)) {
        //         $perangkat->where('id', $filterPerangkat);    
                
        //     }
        // }
    
    // dd($filterPerangkat);            
    //function grapik
    $data_traffic1 = Traffic::select('nilai','created_at')
        ->where([
            ['keterangan', '=', 'inoctet'],
            ['perangkat_id','=', $filterPerangkat],
        ])
        
        ->orderBy('created_at','desc')->take(60)->get();
    
        $reversed1 = $data_traffic1->reverse();
        $new10    = $reversed1->all();

    $data_traffic2 = Traffic::select('nilai','created_at')
         ->where([
            ['keterangan', '=', 'outoctet'],
            ['perangkat_id','=', $filterPerangkat],
         ])
        
        ->orderBy('created_at','desc')->take(60)->get();
    $reversed2 = $data_traffic2->reverse();
    $new20    = $reversed2->all();

    $max_date = Traffic::max('created_at');
    $max = date('Y-m-d H:i:s',strtotime($max_date));
    $now = date('Y-m-d H:i:s');
    $dif = strtotime($now)-strtotime($max);
    $hours = floor($dif / (60 * 60));
    $seconds = $dif - $hours * (60 * 60);



    $perangkatList = array();
    $perangkatList = Perangkat::pluck('nama_perangkat','id');

    // $perangkats = Perangkat::orderBy('id', 'asc');

    //  untuk active halaman
    $halaman = "laporan";

    return view(
        'laporan.traffic_jaringan',
        [
            'halaman' => $halaman, 
            'datajumlah'=> $datajumlah, 
            'traffic10' => $new10,
            'traffic20' => $new20,
            'traffics10' => $new10,
            'traffics20' => $new20,
            'selisih' => $seconds,
            'updatewaktu' =>$max,
            'filterPerangkat' =>$filterPerangkat,
            
            'perangkatList' =>$perangkatList,
        ]

        );
    
            
        




         

    }



    

    public function grafik_traffic()
    {
        $data_traffic = Traffic::select('nilai','created_at')->orderBy('created_at','desc')->take(15)->get();
        $reversed = $data_traffic->reverse();
        $new    = $reversed->all();
        
        $max_date = Traffic::max('created_at');
        $max = date('Y-m-d H:i:s',strtotime($max_date));
        $now = date('Y-m-d H:i:s');
        $dif = strtotime($now)-strtotime($max);
        $hours = floor($dif / (60 * 60));
        $seconds = $dif - $hours * (60 * 60);

        return view(
            'laporan.show',
            [
                'suhu' => $new,
                'suhuu' => $new,
                'selisih' => $seconds,
                'updatewaktu' =>$max
            ]

        );
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