@extends("layouts.app")

@section("title") Data User @endsection

@section("content")
<section class="section">
    <div class="section-header"><h1>Data User</h1></div>

</section>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('users') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('users.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Data</a>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-info alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                {{ session('status') }}
                            </div>
                        @endif 
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="th-md">Nama</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Hak Akses</th>
                                        <th>Status</th>
                                        <th>Avatar</th>
                                        <th class="action1">Aksi</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="trFilter">
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('name') }}" id="nameFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter nama"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindNama" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('email') }}" id="emailFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter email"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindEmail" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th></th>
                                        <th> 
                                            @php $status = Request::get('status') @endphp
                                            <select name="status" id="statusFind" class="form-control form-control-sm" onchange="filter()">
                                                <option value="">Semua Status</option>
                                                <option value="ACTIVE" {{ $status == "ACTIVE" ? "selected" : "" }}>Active</option>
                                                <option value="NONACTIVE"  {{ $status == "NONACTIVE" ? "selected" : "" }}>Nonactive</option>
                                            </select>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php( $userRoles = json_decode(Auth::user()->roles) )
                                @if (!empty($users))
                                    @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $key }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach(json_decode($user->roles) as $role)
                                            <span>{{ $role }}</span><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($user->status == "ACTIVE")
                                                <span class="badge badge-success">{{$user->status}}</span>
                                            @else 
                                                <span class="badge badge-danger">{{$user->status}}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/user/' . $user->avatar) }}" width="80px"/> 
                                            @else 
                                                <span>[No Image]</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('users.show', [$user->id]) }}" class="btn btn-info text-white btn-sm" title="Detail"><i class="fas fa-poll-h"></i></a>
                                            @if($user->id != 1 )
                                            @if(in_array("OWNER", $userRoles) || in_array("ADMIN", $userRoles ))
                                            <a class="btn btn-info text-white btn-sm" href="{{ route('users.edit', [$user->id]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form onsubmit="return confirm('Delete data?')" class="d-inline" action="{{route('users.destroy', [$user->id])}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger text-white btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endif
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">Data Kosong</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $users->appends(Request::all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(e) {
        $('.f, .btnFindEmail').click(function(e) {
            filter();
        });

        $('#nameFind, #emailFind').on('keypress',function(e) {
            if(e.which == 13) {
                filter();
            }
        });
    })

    function filter() {
        let name = $('#nameFind').val();
        name = name ? name : '';

        let email = $('#emailFind').val();
        email = email ? email : '';

        let status = $('#statusFind').val();
        status = status ? status : '';

        location.href = "{{ url('users') }}?name=" + name + "&email=" + email + "&status=" + status;
    }
</script>
@endsection