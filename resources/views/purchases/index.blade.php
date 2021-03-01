@extends('layouts.app')

@section('title', '| Barang Masuk')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Barang Masuk</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('purchases') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('purchases.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Input Barang Masuk</a>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-info alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                {{ session('status') }}
                            </div>
                        @endif 
                        <hr class="my-3">
                        <div class="table-responsive">
                            <table class="table table-stripped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>No Faktur</th>
                                        <th>Komponen</th>
                                        <th>Keterangan</th>
                                        <th>Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $data->date_select }}</td>
                                        <td>{{ $data->transaction_number }}</td>
                                        <td>
                                            @if(!empty($data->details))
                                                <ul class="pl-3">
                                                @foreach($data->details as $detail)
                                                    <li>
                                                        <b>{{ $detail->purchasesInventory->name }},</b> exp: {{ $detail->inventoryStock->expired }}<br>
                                                        @php( $userRoles = json_decode(Auth::user()->roles) )
                                                        @if(in_array("OWNER", $userRoles) )
                                                        <span>Harga Beli : {{ number_format($detail->price_purchase,0,'.','.') }}</span><br>
                                                        @endif
                                                        <span>Jumlah: {{ $detail->qty }} {{ $detail->unit }} </span>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>{{ $data->notes }}</td>
                                        <td>{{ $data->createdUser->name }}</td>
                                        <td>
                                            <a class="btn btn-warning text-white btn-sm" href="{{ url('brokens/create?pc='.$data->id) }}" title="Proses Barang Tidak Layak"><i class="fas fa-pencil-ruler"></i></a>
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
</section>
@endsection