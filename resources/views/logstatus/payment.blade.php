@extends('layouts.app')

@section('title', ' | Pembayaran')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manjemen Pembayaran</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Pembayaran Invoice : {{ $sales->transaction_number }}</h6>
                                <h6>Partner : {{ $sales->partner->name }}</h6>
                                <h6>Tanggal Transaksi : {{ $sales->date_select }}</h6>
                                <h6>Status Pembayaran : 
                                @if ($sales->payment_status == '1')
                                    <b class="text-success">Lunas</b>
                                @else
                                    <b class="text-warning">Belum</b>
                                @endif
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <h6>Total Transaksi : {{ number_format($total,0,'.','.') }}</h6>
                                <h6>Total Pembayaran : {{ number_format($paymenttotal,0,'.','.') }}</h6>
                                <h6>Sisa : {{ number_format($minus,0,'.','.') }}</h6>
                            </div>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-warning alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                {{ session('status') }}
                            </div>
                        @endif 
                        <div class="buttons">
                            <a href="{{ url('sales') }}" class="btn btn-danger"><i class="fas fa-left"></i> Kembali</a>
                            <a href="{{ route('sales.payments', [$sales->id]) }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            @if ($sales->payment_status == '0')
                            <a href="{{ route('sales.payments', [$sales->id]) }}?do=add" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Pembayaran</a>
                            @endif
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-stripped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>Pembayaran</th>
                                        <th>Keterangan</th>
                                        <th>Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->date_select }}</td>
                                        <td>{{ number_format($data->pay,0,'.','.') }}</td>
                                        <td>{{ $data->notes }}</td>
                                        <td>{{ $data->createdUser->name }}</td>
                                        <td>
                                            <a class="btn btn-info text-white btn-sm" href="{{ route('sales.payments', [$sales->id]) }}?do=edit&id={{ $data->id }}" title="Edit"><i class="fas fa-edit"></i></a>
                                            @if ($sales->payment_status == '0')
                                            <a class="btn btn-danger text-white btn-sm" href="{{ route('sales.payments', [$sales->id]) }}?do=delete&id={{ $data->id }}" title="Delete"><i class="fas fa-trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection