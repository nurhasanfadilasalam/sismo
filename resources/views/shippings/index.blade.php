@extends('layouts.app')

@section('title', '| Pengiriman')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pengiriman</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('shippings') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            @if( in_array("OWNER", json_decode(Auth::user()->roles)) || in_array("ADMIN", json_decode(Auth::user()->roles)) )
                            <a href="{{ route('shippings.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Buat Pengiriman</a>
                            @endif
                        </div>
                        @if (session('status'))
                            <div class="alert alert-warning alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                {{ session('status') }}
                            </div>
                        @endif 
                        <hr class="my-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>No Tracking</th>
                                        <th>Driver</th>
                                        <th>Rute</th>
                                        <th class="th-lg">Komponen</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="trFilter">
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('notracking') }}" id="notrackingFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter No.Tracking"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindNoTracking" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('driver') }}" id="driverFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter Driver"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindDriver" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('rute') }}" id="ruteFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter Rute"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindRute" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $data->date_select }}</td>
                                        <td>{{ $data->tracking_number }}</td>
                                        <td>{{ $data->driver->name }}</td>
                                        <td>{{ $data->routes->name }}</td>
                                        <td>
                                            @if($data->details)
                                            <ul class="pl-1">
                                                @foreach($data->details as $detail)
                                                <li>
                                                    <span><b>(PO: {{ $detail->shippingPreorder->po_number }})</b> {{ $detail->name }}</span><br>
                                                    @if ($detail->status == '1')
                                                        <b class="text-success">Selesai</b>
                                                    @else
                                                        <b class="text-warning">Belum di proses</b>
                                                    @endif
                                                </li>
                                                @endforeach 
                                            </ul>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('shippings.edit', [$data->id]) }}" class="btn btn-info text-white btn-sm" title="Proses Pengiriman"><i class="fas fa-poll-h"></i> Proses</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $datas->appends(Request::all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function(e) {
    $('.btnFindNoTracking, .btnFindDriver, .btnFindRute').click(function(e) {
        filter();
    });

        $('#notrackingFind, #driverFind, #ruteFind').on('keypress',function(e) {
            if(e.which == 13) {
                filter();
            }
        });

        
    })

     function filter() {
        let notracking = $('#notrackingFind').val();
        notracking = notracking ? notracking : ''; 

        let driver = $('#driverFind').val();
        driver = driver ? driver : ''; 

        let rute = $('#ruteFind').val();
        rute = rute ? rute : '';       
        location.href = "{{ url('shippings') }}?notracking=" + notracking + "&driver=" + driver + "&rute=" + rute;
    }
</script>
@endsection