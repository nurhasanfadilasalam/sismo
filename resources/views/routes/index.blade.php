@extends('layouts.app')

@section('title', '| Rute')
@section('content')
<section class="section">
    <div class="section-header"><h1>Rute Pengiriman</h1></div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('routes') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('routes.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Data</a>
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
                                        <th>#</th>
                                        <th>Nama Rute</th>
                                        <th>Komponen</th>                                        
                                        <th class="action2">Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="trFilter">
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('name') }}" id="nameFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter Nama"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindNama" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('komponen') }}" id="komponenFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter Komponen"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindKomponen" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(!empty($routes))
                                    @foreach($routes as $key => $route)
                                        <tr>     
                                            <td>{{ $routes->firstItem() + $key }}</td>
                                            <td>{{ $route->name }}</td>
                                            <td>  
                                            @if(!empty($route->details))
                                                <ul class="pl-3">
                                                @foreach($route->details as $detail)
                                                    <li>
                                                        <b>({{ $detail->order }}) {{ $detail->partner->name }},</b><br>
                                                        <span><b>Telp: </b>{{ $detail->partner->phone ?? '-' }}, <b>Alamat: </b>{{ $detail->partner->address }} </span>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            @endif
                                            </td>
                                           
                                            <td>
                                                <a class="btn btn-info text-white btn-sm" href="{{ url('routes/'.$route->id.'/edit' ) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form onsubmit="return confirm('Delete data?')" class="d-inline" action="{{ route('routes.destroy', [$route->id]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger text-white btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">Data Kosong</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $routes->appends(Request::all())->links() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function(e) {
        $('.btnFindNama, .btnFindKomponen').click(function(e) {
            filter();
        });

        $('#nameFind, #komponenFind').on('keypress',function(e) {
            if(e.which == 13) {
                filter();
            }
        });

        
    })

     function filter() {
        let name = $('#nameFind').val();
        name = name ? name : '';       

        let komponen = $('#komponenFind').val();
        komponen = komponen ? komponen : '';

        location.href = "{{ url('routes') }}?name=" + name + "&komponen=" + komponen;
    }
</script>
@endsection
