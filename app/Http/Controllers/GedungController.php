<?php

namespace App\Http\Controllers;

use App\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class GedungController extends Controller
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
        $filterName = $request->get('nama_gedung');
        $filterKode = $request->get('kode_gedung');
        // $filterTelp = $request->get('telp');
        // $filterAddress = $request->get('address');

        
        $data = Gedung::orderBy('id', 'asc');

        if ($request->get('do') == 'ajaxselect2') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('nama_gedung', 'like', '%'.$filterKey.'%');
                    $data->orWhere('kode_gedung', 'like', '%'.$filterKey.'%');
                }
            }

            $datas = $data->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->nama_gedung,
                ));
            }
            
            return json_encode($res);
        } 
        elseif ($request->get('do') == 'ajaxall') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $data->where('nama_gedung', 'like', '%'.$filterKey.'%');
                }
            }

            $datas = $data->get();

            return json_encode($datas);
        } 
        else {
            if ($request->has('kode_gedung')){
                if (!empty($filterKode)) {
                    $data->where('kode_gedung', 'like', '%'.$filterKode.'%');
                }
            }
    
            if ($request->has('nama_gedung')){
                if (!empty($filterName)) {
                    $data->where('nama_gedung', 'like', '%'.$filterName.'%');
                }
            }

            // if ($request->has('telp')){
            //     if (!empty($filterTelp)) {
            //         $data->where('phone', 'like', '%'.$filterTelp.'%');
            //     }
            // }

            // if ($request->has('address')){
            //     if (!empty($filterAddress)) {
            //         $data->where('address', 'like', '%'.$filterAddress.'%');
            //     }
            // }
    
    
            
            $datas = $data->paginate(10);
            $halaman = "gedung";
            
            return view('gedung.index', [ 'datas' => $datas, 'halaman' => $halaman ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "gedung";
        return view("gedung.form", compact('halaman'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newData = new Gedung;
        $newData->nama_gedung = $request->get('nama_gedung');
        $newData->kode_gedung = $request->get('kode_gedung');
        $newData->created_by = \Auth::user()->id;

        $newData->photo = '';
        if($request->file('photo')){
            $filename = time().'.jpeg';
            $file = $request->file('photo')->storeAs('public/gedung', $filename);
            $newData->photo = $filename;
        }
        $newData->save();
        
        return redirect()->route('gedung.index')->with('status', 'Data successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gedung  $gedung
     * @return \Illuminate\Http\Response
     */
    public function show(Gedung $gedung)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Partners  $partners
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $halaman = "gedung";
        
        $gedung = Gedung::findOrFail($id);
        
        return view('gedung.form', ['data' => $gedung, 'halaman' => $halaman]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gedung  $gedung
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $thisData = Gedung::findOrFail($id);
        $thisData->nama_gedung = $request->get('nama_gedung');
        $thisData->kode_gedung = $request->get('kode_gedung');
        $thisData->updated_by = \Auth::user()->id;

        if($request->file('photo')){
            if($thisData->photo && file_exists(storage_path('app/' . $thisData->photo))){
                Storage::delete($thisData->photo);
            }

            $file = $request->file('photo')->store('images/gedung');
            $thisData->photo = $file;
        }
        $thisData->save();
        
        return redirect()->route('gedung.index')->with('status', 'Data successfully edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gedung  $gedung
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Gedung::findOrFail($id);

        if($data->photo && file_exists(storage_path('app/' . $data->photo))){
            Storage::delete($data->photo);
        }

        $data->deleted_by = \Auth::user()->id;
        $data->save();

        $data->delete();

        return redirect()->route('gedung.index')->with('status', 'Data successfully delete');
    }
}
