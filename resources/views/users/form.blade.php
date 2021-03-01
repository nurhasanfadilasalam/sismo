@extends("layouts.app")

@section("title") @if(!empty($user)) Edit @else Tambah @endif User @endsection

@section("content")
<section class="section">
    <div class="section-header"><h1>@if(!empty($user)) Edit @else Tambah @endif User</h1></div>
</section>

<section class="section">
    <div class="section-body">
        @if (session('status'))
            <div class="alert alert-warning">
                {{ session('status') }}
            </div>
        @endif 
        <form enctype="multipart/form-data" class="bg-white shadow-sm p-3" action="@if(!empty($user)) {{route('users.update', [$user->id])}} @else {{ route('users.store') }} @endif" method="POST">
            @csrf

            @if(!empty($user))
                <input type="hidden" value="PUT" name="_method">
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap" type="text" name="name" id="name" value="@if(!empty($user)){{ $user->name }}@else{{ old('name') }}@endif" autocomplete="off"/>
                        @error('name')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input class="form-control @error('username') is-invalid @enderror" placeholder="username" type="text" name="username" id="username" @if(!empty($user)) disabled @endif value="@if(!empty($user)){{ $user->username }}@else{{ old('username') }}@endif" autocomplete="off"/>
                        @error('username')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone number</label> 
                        <input type="text" name="phone" class="form-control" value="@if(!empty($user)){{ $user->phone }}@else{{ old('phone') }}@endif">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" placeholder="Ex: user@email.com" type="text" name="email" id="email" value="@if(!empty($user)){{ $user->email }}@else{{ old('email') }}@endif"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror" placeholder="password" type="password" name="password" id="password"/>
                        @error('password')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input class="form-control" placeholder="ketikkan ulang password" type="password" name="password_confirmation" id="password_confirmation"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" style="height: 100px;" class="form-control">@if(!empty($user)){{ $user->address }}@else{{ old('address') }}@endif</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="form-label">Hak Akses</label>
                        <div class="form-group">
                            <input type="checkbox" name="roles[]" id="ADMIN" value="ADMIN" {{ (!empty($user) && in_array("ADMIN", json_decode($user->roles)) ) ? "checked" : "" }} >
                            <label for="ADMIN">Admin</label>

                            <input type="checkbox" name="roles[]" id="OWNER" value="OWNER" {{ (!empty($user) && in_array("OWNER", json_decode($user->roles)) ) ? "checked" : "" }} >
                            <label for="OWNER">Owner</label>
                                        
                            <input type="checkbox" name="roles[]" id="DRIVER" value="DRIVER" {{ (!empty($user) && in_array("DRIVER", json_decode($user->roles)) ) ? "checked" : "" }} >
                            <label for="DRIVER">Driver</label>
                            <input type="hidden" class="form-control @error('roles') is-invalid @enderror">
                            @error('roles')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="avatar">Foto</label><br>
                        @if(!empty($user))
                            <span>Current avatar: </span>
                            @if($user->avatar)
                                <br><img src="{{ asset('storage/user/'.$user->avatar) }}" width="120px" /><br>
                            @else 
                                <span>[No Avatar]</span>
                            @endif
                        @endif
                        <input id="avatar" name="avatar" type="file" class="form-control">
                        @if(!empty($user))<small class="text-muted">Kosongkan jika tidak ingin mengubah avatar</small>@endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Status</label>
                        <div class="radio">
                            <label for="active"><input value="ACTIVE" type="radio" class="" id="active" name="status" @if(!empty($user)) {{$user->status == "ACTIVE" ? "checked" : ""}} @else {{ "checked" }} @endif> Active</label>
                            <label for="nonactive"><input value="NONACTIVE" type="radio" class="" id="nonactive" name="status" @if(!empty($user)) {{$user->status == "NONACTIVE" ? "checked" : ""}} @endif> Nonactive</label>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-12">
                    <button class="btn btn-lg btn-primary float-right" type="submit">Save</button>
                    <a href="{{ url('users') }}" class="btn btn-lg btn-danger float-left">Cancel</a>
                </div>
            </div>
        </form> 
    </div>
</section>
@endsection