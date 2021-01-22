<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TaskController extends Controller
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
        $halaman = "laporan";

        // return view('home.orderin');
        // return view('task', ['halaman' => $halaman ]);

    }



    public function traffic_jaringan()
    {
        //untuk active halaman
        $halaman = "laporan";
        // $data= "Test Data";
        // return view('home.processed');
        return view('laporan.traffic_jaringan', ['halaman' => $halaman ]);

    }

  

}
