<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
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
        $filterEmail = $request->get('email');
        $filterName = $request->get('name');
        $filterStatus = $request->get('status');
        
        $user = User::orderBy('id', 'asc');

        if ($request->get('do') == 'ajaxselect2') {
            if ($request->has('keyword')){
                $filterKey = $request->get('keyword');
                if (!empty($filterKey)) {
                    $user->where('roles', 'like', '%DRIVER%');
                    $user->orwhere('name', 'like', '%'.$filterKey.'%');
                    $user->orWhere('phone', 'like', '%'.$filterKey.'%');
                    $user->orWhere('email', 'like', '%'.$filterKey.'%');
                    $user->orWhere('address', 'like', '%'.$filterKey.'%');
                } else
                    $user->where('roles', 'like', '%DRIVER%');
            }

            $datas = $user->get();
            $res = array();
            foreach ($datas as $key => $val) {
                array_push($res, array(
                    'id' => $val->id,
                    'text' => $val->name,
                ));
            }
            
            return json_encode($res);
        } else {
            if ($request->has('email')){
                if (!empty($filterEmail)) {
                    $user->where('email', 'like', '%'.$filterEmail.'%');
                }
            }
    
            if ($request->has('name')){
                if (!empty($filterName)) {
                    $user->where('name', 'like', '%'.$filterName.'%');
                }
            }
    
            if ($request->has('status')){
                if (!empty($filterStatus)) {
                    $user->where('status', $filterStatus);
                }
            }
    
            $users = $user->paginate(10);
            //untuk active halaman
            $halaman = "userakses";
            
            return view("users.index", [ 'users' => $users, 'halaman' => $halaman ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $halaman = "userakses";        
        return view("users.form", compact('halaman'));
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
            'roles' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed|min:4',
        ]);

        $newUser = new User; 

        $newUser->name = $request->get('name');
        $newUser->username = $request->get('username');
        $newUser->roles = json_encode($request->get('roles'));
        $newUser->name = $request->get('name');
        $newUser->address = $request->get('address');
        $newUser->phone = $request->get('phone');
        $newUser->email = $request->get('email');
        $newUser->password = \Hash::make($request->get('password'));
        
    
        $newUser->avatar = '';
        if($request->file('avatar')){
            $filename = time().'.jpeg';
            $file = $request->file('avatar')->storeAs('public/user', $filename);
            $newUser->avatar = $filename;
        }

        $newUser->save();
        
        return redirect()->route('users.index')->with('status', 'User successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $halaman = "userakses";
        $user = User::findOrFail($id);
        
        return view('users.show', ['user' => $user, 'halaman' => $halaman]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $halaman = "userakses";
        $user = User::findOrFail($id);
        
        return view('users.form', ['user' => $user, 'halaman' => $halaman]);
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
        if (!empty($request->get('password'))) {
            $request->validate([
                'name' => 'required',
                'roles' => 'required',
                'password' => 'confirmed|min:4',
            ],[
                'password.confirmed' => 'Password dan konfirmasi password tidak sesuai',
            ]);
        } else {
            $request->validate([
                'name' => 'required',
                'roles' => 'required',
            ]);
        }

        $user = User::findOrFail($id);

        $user->name = $request->get('name');
        $user->roles = json_encode($request->get('roles'));
        $user->address = $request->get('address');
        $user->phone = $request->get('phone');
        $user->status = $request->get('status');

        if (!empty($request->get('password'))) {
            $user->password = \Hash::make($request->get('password'));
        }
    
        if($request->file('avatar')){
            if($user->avatar && file_exists(storage_path('public/user/' . $user->avatar))){
                Storage::delete($user->avatar);
            }

            $filename = time().'.jpeg';
            $file = $request->file('avatar')->storeAs('public/user', $filename);
            $user->avatar = $filename;
        }

        $user->save();

        return redirect()->route('users.index')->with('status', 'User succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user->avatar && file_exists(storage_path('public/user/' . $user->avatar))){
            Storage::delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'User successfully delete');
    }
}
