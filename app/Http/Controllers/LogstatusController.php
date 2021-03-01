<?php

namespace App\Http\Controllers;

use App\Logstatus;
use App\Perangkat;
use App\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Ping;

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
        // $do = $request->get('do');
        

        // $pinger = new Ping;
        // $data = Perangkat::pluck('ip_perangkat');

        $data = Perangkat::orderBy('id', 'desc');

        $datas = $data;
        
        // $health = Ping::check($url);

        // if($health == 200) {
        //     return 'Alive!';
        // } else {
        //     return 'Dead :(';
        // }


        
        foreach($datas as $key => $dt) {
            try{
                $health_status = Ping::check($dt);
                if ($health_status == 200) {
                    return 'UP';
                    // dd($health);
                } else {
                    return 'DOWN';
                }
            } catch (Exception $e) {
                Flash::error($e->getMessage());
            }

            // $dataDiagnosa = collect(array_values($health_status));

            // dd($dataDiagnosa);

        }

        // dd($dataDiagnosa);

        // logstatus
        $datas = $data->paginate(10);
        
        $halaman = "logstatus";
        return view('logstatus.index', [ 'datas' => $datas, 'halaman' => $halaman ]);
    }


    public function healthCheck($url)
    {
        $health = Ping::check($url);

        if($health == 200) {
            return 'UP';
        } else {
            return 'DOWN';
        }
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