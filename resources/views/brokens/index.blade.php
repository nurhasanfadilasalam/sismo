@extends('layouts.app')

@section('title', '| Barang Tidak Layak')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Barang Tidak Layak</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('brokens') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('brokens.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Input Barang Tidak Layak</a>
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
                                        <th>No. Faktur</th>
                                        <th>Komponen</th>
                                        <th>Keterangan</th>
                                        <th>Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $data->date_select }}</td>
                                        <td>{{ $data->purchases->transaction_number }}</td>
                                        <td>
                                            @if(!empty($data->details))
                                                <ul class="pl-3">
                                                @foreach($data->details as $detail)
                                                    <li>
                                                        <b>{{ $detail->brokensInventory->name }},</b> exp: {{ $detail->inventoryStock->expired }}<br>
                                                        <span>- Jumlah Masuk: {{ $detail->qty }} {{ $detail->unit }}</span><br>
                                                        <span>- Jumlah Tidak Layak: {{ $detail->broken }} {{ $detail->unit }}</span>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td> 
                                            @if(!empty($data->details))
                                                <ul class="pl-3">
                                                    @foreach($data->details as $detail)
                                                        <li><b>{{ $detail->brokensInventory->name }},</b><br> {{ $detail->notes }} </li>
                                                    @endforeach
                                                </ul>
                                            @endif                                                    
                                        </td>
                                        <td>{{ $data->createdUser->name }}</td>
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
@endsection