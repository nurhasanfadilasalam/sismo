<?php

namespace App\Http\Controllers;

use App\Perangkat;
use App\Gedung;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('rolecheck:OWNER');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
       
        
        $data = Perangkat::orderBy('id', 'asc');
        
    
    
            $datas = $data->paginate(10);
            $gedung = Gedung::all();
            $halaman = "parameter";
            
            return view("perangkat.index", [ 'datas' => $datas, 'halaman' => $halaman ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {

        $newData = new Perangkat;
        $halaman = "parameter";   
        return view("perangkat.form",compact('halaman'));

    
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newData = new Perangkat; 
        // $gedung = $request->get('gedung');

        $newData->nama_perangkat = $request->get('nama_perangkat');
        $newData->ip_perangkat = $request->get('ip_perangkat');
        $newData->gedung = $request->get('gedung');
        $newData->keterangan = $request->get('keterangan');
        $newData->created_by = \Auth::user()->id;

        $newData->save();

        return redirect()->route('perangkat.index')->with('status', 'Data successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function show($id = NULL)
    {
        // no thing
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        
        $data = Perangkat::findOrFail($id);

        $halaman = "parameter";

        return view('perangkat.form', ['data' => $data, 'halaman' => $halaman]);
        
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Perangkat::findOrFail($id);

        $data->nama_perangkat = $request->get('nama_perangkat');
        $data->ip_perangkat = $request->get('ip_perangkat');
        $data->updated_by = \Auth::user()->id;

        $data->save();

        return redirect()->route('perangkat.index')->with('status', 'Data successfully updated');
       
    }

    // public function update(Request $request, $id)
    // {
    //     $thisData = Gedung::findOrFail($id);
    //     $thisData->nama_gedung = $request->get('nama_gedung');
    //     $thisData->kode_gedung = $request->get('kode_gedung');
    //     $thisData->updated_by = \Auth::user()->id;

    //     if($request->file('photo')){
    //         if($thisData->photo && file_exists(storage_path('app/' . $thisData->photo))){
    //             Storage::delete($thisData->photo);
    //         }

    //         $file = $request->file('photo')->store('images/gedung');
    //         $thisData->photo = $file;
    //     }
    //     $thisData->save();
        
    //     return redirect()->route('gedung.index')->with('status', 'Data successfully edited');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Perangkat  $perangkat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $type = $request->get('type');

        $data = Perangkat::findOrFail($id);

        $data->deleted_by = \Auth::user()->id;
        $data->save();

        $data->delete();

        return redirect()->route('perangkat.index')->with('status', 'Data successfully delete');

    }
}