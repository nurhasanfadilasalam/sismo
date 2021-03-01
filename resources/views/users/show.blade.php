@extends("layouts.app")

@section("title") Detail User @endsection

@section("content")
<section class="section">
    <div class="section-header"><h1>Detail User</h1></div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <b>Name:</b> <br/>
                    {{$user->name}}
                    <br><br>

                    @if($user->avatar)
                    <img src="{{ asset('storage/user/' . $user->avatar) }}" width="124px"/>

                    @else 
                    No avatar
                    @endif 

                    <br>
                    <br>
                    <b>Username:</b><br>
                    {{$user->email}}

                    <br>
                    <br>
                    <b>Phone number</b> <br>
                    {{$user->phone}}

                    <br><br>
                    <b>Address</b> <br>
                    {{$user->address}}

                    <br>
                    <br>
                    <b>Roles:</b> <br>
                    @foreach (json_decode($user->roles) as $role)
                        &middot; {{$role}} <br>
                    @endforeach
                    
                    <p class="pull-right">
                        <a href="{{ url('users') }}" class="btn btn-danger">Back</a>
                    </p>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>
@endsection