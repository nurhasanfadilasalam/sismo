@extends('layouts.app')

@section('title', '| Laporan Lap Telur')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Barang Masuk</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('reports/wipes') }}" method="GET" name="postform">  
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
                                    <div class="pt-4">
                                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-search"></i> Pencarian</button>
                                        <a href="{{ url('reports/wipes') }}" class="btn btn-success btn-lg"><i class="fas fa-sync-alt"></i> Reload</a>
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
                                            <th>No. Faktur</th>
                                            <th>Pengelap</th>
                                            <th>Komponen</th>
                                            <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php( $totalwipe = $totalbroken = 0)
                                        @foreach($datas as $key => $data)
                                            <tr>
                                                <td>{{ $data->date_select }}</td>
                                                <td>{{ $data->purchases->transaction_number }}</td>
                                                <td>{{ $data->wiper }}</td>
                                                <td>
                                                    @if(!empty($data->details))
                                                        <ul class="pl-3">
                                                        @foreach($data->details as $detail)                                                
                                                            <li><b> {{ $detail->wipesInventory->name }} </b> <br>
                                                                <span>Jumlah dilap: {{ $detail->wipe }}<br>
                                                                Jumlah Rusak: {{ $detail->broken }}<br></span>
                                                                <b>Harga Baru</b><br>                                                       
                                                                @foreach($detail->inventoryStock->pricesList as $price)
                                                                <span>- Harga {{ $price->type_name }}: {{ number_format($price->price,0,'.','.') }} {{ $detail->notes }}</span><br>
                                                                @endforeach
                                                            </li>
                                                            @php( $totalwipe += $detail->wipe )
                                                            @php( $totalbroken += $detail->broken )
                                                        @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>{{ $data->notes }}</td>                
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="font-weight: 800;">
                                                <td colspan="3" class="text-right">Jumlah Telur yang dilap</td>
                                                <td>{{ number_format($totalwipe,0,'.','.') }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                            <tr style="font-weight: 800;">
                                                <td colspan="3" class="text-right">Jumlah Telur yang rusak selama pengelapan</td>
                                                <td>{{ number_format($totalbroken,0,'.','.') }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
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