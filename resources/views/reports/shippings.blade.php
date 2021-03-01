@extends('layouts.app')

@section('title', '| Laporan Pengiriman')
@section('bodyclass', 'sidebar-mini')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Pengiriman</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('reports/shippings') }}" method="GET" name="postform">  
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start" class="form-label">Dari Tanggal</label>
                                        <input type="date" name="start" class="form-control" value="@if(Request::get('start')){{ Request::get('start') }}@else{{ date('Y-m-d') }}@endif"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="until" class="form-label">Sampai Tanggal</label>
                                        <input type="date" name="until" class="form-control" value="@if(Request::get('until')){{ Request::get('until') }}@else{{ date('Y-m-d') }}@endif"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pt-4 row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-search"></i> Pencarian</button>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ url('reports/shippings') }}" class="btn btn-success btn-lg"><i class="fas fa-sync-alt"></i> Reload</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                @if (count($datas) > 0)
                                <div class="table-responsive">
                                    <table class="table table-stripped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>No Tracking</th>
                                                <th>Driver</th>
                                                <th class="th-xlg">Penjualan</th>
                                                <th class="th-xlg">Detail Pengiriman</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                                <th>Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($datas as $key => $data)
                                            <tr>
                                                <td>{{ $data->shippingData->created_at }}</td>
                                                <td>{{ $data->shippingData->tracking_number }}</td>
                                                <td>{{ $data->shippingData->driver->name }}</td>
                                                <td>
                                                    @if ($data->status == '1')
                                                    <b>Invoice : {{ $data->shippingSales->transaction_number }}</b><br>
                                                    @endif
                                                    <span>{{ $data->name }} (Telp: {{ $data->phone }})</span><br>
                                                    <span>Alamat : {{ $data->address }}</span>
                                                </td>
                                                <td>
                                                    <span>Tanggal : {{ $data->proccess_date }}</span><br>
                                                    <span>Jam : {{ $data->proccess_time }}</span><br>
                                                    <span>Oleh : {{ $data->createdShippingUser ? $data->createdShippingUser->name : '' }}</span><br>
                                                    <span>Catatan : {{ $data->proccess_notes }}</span><br>
                                                </td>
                                                <td class="text-center">
                                                    @if ($data->status == '1')
                                                        <b class="text-success">Selesai</b>
                                                    @else
                                                        <b class="text-warning">Belum di proses</b>
                                                    @endif
                                                </td>
                                                <td>{{ $data->notes }}</td>
                                                <td>{{ $data->shippingData->createdUser->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <h5>Data Kosong.</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection