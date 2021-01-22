@extends('layouts.app')

@section('title', '| Laporan Ringkasan')
@section('bodyclass', 'sidebar-mini')
@section('content')
<link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
<style>
    .select2-selection {
        padding: 5px 10px !important;
    }
</style>
<section class="section">
    <div class="section-header">
        <h1>Laporan Ringkasan</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('reports') }}" method="GET" name="postform">  
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="start" class="form-label">Dari Tanggal</label>
                                        <input type="date" name="start" class="form-control" value="@if(Request::get('start')){{ Request::get('start') }}@else{{ date('Y-m-d') }}@endif"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="until" class="form-label">Sampai Tanggal</label>
                                        <input type="date" name="until" class="form-control" value="@if(Request::get('until')){{ Request::get('until') }}@else{{ date('Y-m-d') }}@endif"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="type" class="form-label">Barang</label>
                                        <select name="id" id="invenData" class="form-control">
                                            <option value="">Ketikkan nama barang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pt-4">
                                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-search"></i> Pencarian</button>
                                        <a href="{{ url('reports') }}" class="btn btn-success btn-lg"><i class="fas fa-sync-alt"></i> Reload</a>
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
                                                <th>Nama Barang</th>
                                                <th>No Invoice</th>
                                                <th width="120px">Expired</th>
                                                <th>Jumlah</th>
                                                <th>Harga Beli</th>
                                                <th>Harga Jual</th>
                                                <th>Margin</th>
                                                <th>Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php( $totalQty = 0)
                                        @php( $totalPrice = 0)
                                        @php( $totalPurchase = 0)
                                        @php( $totalMargin = 0)
                                        @foreach($datas as $key => $data)
                                            <tr>
                                                <td>{{ $data->created_at }}</td>
                                                <td>{{ $data->salesDetail->name }}</td>
                                                <td>{{ $data->salesDetail->sales->transaction_number }}</td>
                                                <td>{{ $data->expired }}</td>
                                                <td>{{ $data->qty }}</td>
                                                @php( $margin = $data->price - $data->price_purchase )
                                                <td>{{ number_format($data->price_purchase,0,'.','.') }}</td>
                                                <td>{{ number_format($data->price,0,'.','.') }}</td>
                                                <td>{{ number_format($margin,0,'.','.') }}</td>
                                                <td>{{ $data->salesDetail->sales->createdUser->name }}</td>
                                            </tr>
                                            @php( $totalQty += $data->qty )
                                            @php( $totalPrice += $data->price )
                                            @php( $totalPurchase += $data->price_purchase )
                                            @php( $totalMargin += $margin )
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="font-weight: 800;">
                                                <td colspan="4" class="text-right">Total</td>
                                                <td>{{ number_format($totalQty ,0,'.','.') }}</td>
                                                <td>{{ number_format($totalPrice ,0,'.','.') }}</td>
                                                <td>{{ number_format($totalPurchase ,0,'.','.') }}</td>
                                                <td>{{ number_format($totalMargin ,0,'.','.') }}</td>
                                                <td></td>
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

@section('footer-scripts')
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        const selId = `@if(Request::get('id')){{ Request::get('id') }}@endif`

        if (selId) {
            const invenId = `@if($invenData){{ $invenData->id }}@endif`;
            const invenText = `@if($invenData){{ $invenData->name }}@endif`;
            const newDataList = { id: eval(invenId), text: invenText, selected: true };
            selectedInit(newDataList);
        }
        else
            selectedInit();
    });

    function selectedInit(data) {
        if (data) {
            console.log('selectedInit(data)', data);
            $('#invenData').empty();
            $('#invenData').select2({
                data: [{ id: data.id, text: data.text, unit: data.unit, selected: true }],
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketik nama barang',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/inventories') }}`,
                    data: { do: 'ajaxselect2' },
                    delay: 50,
                    data: function(params) {
                        return {
                            keyword: params.term,
                            do: 'ajaxselect2'
                        }
                    },
                    processResults: function (data, page) {
                        return {
                            results: data
                        };
                    },
                    cache: false,
                }
            }).on('select2:select', function (evt) {
                invenId = $("#invenData option:selected").val();
                invenText = $("#invenData option:selected").text();
                // data = $("#invenData").select2('data')[0];
            });
        } else {
            $('#invenData').empty();
            $('#invenData').select2({
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketik nama barang',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/inventories') }}`,
                    data: { do: 'ajaxselect2' },
                    delay: 50,
                    data: function(params) {
                        return {
                            keyword: params.term,
                            do: 'ajaxselect2'
                        }
                    },
                    processResults: function (data, page) {
                        console.log('data processResults (nodata)', data);
                        return {
                            results: data
                        };
                    },
                    cache: false,
                }
            }).on('select2:select', function (evt) {
                invenId = $("#invenData option:selected").val();
                invenText = $("#invenData option:selected").text();
                data = $("#invenData").select2('data')[0];
            });
        }
    }
</script>
@endsection