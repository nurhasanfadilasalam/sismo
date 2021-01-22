<?php

namespace App\Http\Controllers;

use App\Spend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class SpendController extends Controller
{
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
        $filterNotes = $request->get('notes');
        $data = Spend::orderBy('id', 'asc');    
        if ($request->has('notes')){
            if (!empty($filterNotes)) {
                $data->where('notes', 'like', '%'.$filterNotes.'%');
            }
        }
    
        $datas = $data->paginate(10);
        //untuk active halaman
        $halaman = "pengeluaran";
        return view('spend.index', [ 'datas' => $datas, 'halaman' => $halaman ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "pengeluaran";  
        return view('spend.form', compact('halaman'));
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
            'date_select' => 'required',
            'notes' => 'required',
            'total' => 'required|numeric',
        ],
        [
            'date_select.required' => 'Silahkan pilih tanggal.',
            'notes.required' => 'Input Pengeluaran.',
            'total.required' => 'Input Jumlah Pengeluaran.',

        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;

            $newData = new Spend;
            $newData->date_select = $request->get('date_select');
            $newData->notes = $request->get('notes');
            $newData->total = $request->get('total');
            $newData->created_by = \Auth::user()->id;
            $newData->save();           
        
            if (!$resultStatus) {
                DB::rollback();
                $resultMsg = $resultMsg ? $resultMsg : 'Pengeluaran gagal disimpan, silahkan coba lagi.';
            } else {
                DB::commit();

                $resultMsg = 'Pengeluaran berhasil disimpan.';
                $resultLink = '/spend';
            }
            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('spend.create')->with('status', 'Pengeluaran gagal');
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
        $halaman = "pengeluaran";
        $datas = Spend::findOrFail($id);
        
        return view('spend.form', ['data' => $datas, 'halaman' => $halaman]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $request->validate([
            'date_select' => 'required',
            'notes' => 'required',
            'total' => 'required|numeric',
        ],
        [
            'date_select.required' => 'Silahkan pilih tanggal.',
            'notes.required' => 'Input Pengeluaran.',
            'total.required' => 'Input Jumlah Pengeluaran.',

        ]);

        DB::beginTransaction();

        try {
            $resultStatus = true;

            $newData = Spend::findOrFail($id);
            $newData->date_select = $request->get('date_select');
            $newData->notes = $request->get('notes');
            $newData->total = $request->get('total');
            $newData->created_by = \Auth::user()->id;
            $newData->save();           
        
            if (!$resultStatus) {
                DB::rollback();

                $resultMsg = $resultMsg ? $resultMsg : 'Pengeluaran gagal disimpan, silahkan coba lagi.';
            } else {
                DB::commit();

                $resultMsg = 'Pengeluaran berhasil diubah.';
                $resultLink = '/spend';
            }
            return response()->json(array('status' => $resultStatus, 'msg' => $resultMsg, 'link' => $resultLink));
        } catch (\Throwable $th) {
            throw $th;

            DB::rollback();
            return redirect()->route('spend/{$id}/edit')->with('status', 'Pengeluaran gagal');
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
        $spend = Spend::findOrFail($id);
      
        $spend->delete();

        return redirect()->route('spend.index')->with('status', 'Pengeluaran berhasil dihapus');
    }
    public function laporan(Request $request)
    {        
        $filterNotes = $request->get('notes');
        $data = Spend::orderBy('id', 'asc');    
        if ($request->has('notes')){
            if (!empty($filterNotes)) {
                $data->where('notes', 'like', '%'.$filterNotes.'%');
            }
        }
    
        $datas = $data->paginate(10);
        //untuk active halaman
        $halaman = "pengeluaran";
        return view('spend.laporan', [ 'datas' => $datas, 'halaman' => $halaman ]);
    }
}
