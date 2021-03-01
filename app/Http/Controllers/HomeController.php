<?php

namespace App\Http\Controllers;

use App\Gedung;
use App\Perangkat;
use App\Traffic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ping;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        //untuk active halaman
        $halaman = "dashboard";

        //count live server up or die
        $dataServers = Perangkat::pluck('ip_perangkat');
        foreach($dataServers as $dataServer) {
            $diagnosa[] = Ping::check($dataServer); 

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

        //function grapik
        $data_traffic1 = Traffic::select('nilai','created_at')
                        ->where('keterangan', 'inoctet')
                        ->orderBy('created_at','desc')->take(60)->get();
        $reversed1 = $data_traffic1->reverse();
        $new1    = $reversed1->all();

        $data_traffic2 = Traffic::select('nilai','created_at')
                        ->where('keterangan', 'outoctet')
                        ->orderBy('created_at','desc')->take(60)->get();
        $reversed2 = $data_traffic2->reverse();
        $new2    = $reversed2->all();
        
        $max_date = Traffic::max('created_at');
        $max = date('Y-m-d H:i:s',strtotime($max_date));
        $now = date('Y-m-d H:i:s');
        $dif = strtotime($now)-strtotime($max);
        $hours = floor($dif / (60 * 60));
        $seconds = $dif - $hours * (60 * 60);


        return view('laporan.index', [
            'halaman' => $halaman, 
            'datajumlah'=> $datajumlah, 
            'upServer' =>$upServer, 
            'downServer' =>$downServer,
            'dataServers' =>$dataServers,
            
            'traffic1' => $new1,
            'traffic2' => $new2,
            'traffics1' => $new1,
            'traffics2' => $new2,
            'selisih' => $seconds,
            'updatewaktu' =>$max
            ]);
    }



}
