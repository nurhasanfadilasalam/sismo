@extends('layouts.app')

@section('title', '| Laporan Inventaris')
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
        <h1>Laporan Inventaris</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('reports/inventories') }}" method="GET" name="postform">  
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="type" class="form-label">Tipe</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="in" @if(Request::get('type') == 'in'){{ 'selected' }}@endif>Masuk</option>
                                            <option value="out" @if(Request::get('type') == 'out'){{ 'selected' }}@endif>Keluar</option>
                                        </select>
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
                                <div class="col-md-3">
                                    <div class="pt-4">
                                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-search"></i> Pencarian</button>
                                        <a href="{{ url('reports/inventories') }}" class="btn btn-success btn-lg"><i class="fas fa-sync-alt"></i> Reload</a>
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
                                                <th>Nama</th>
                                                <th>Masuk</th>
                                                <th>Keluar</th>
                                                <th>Harga</th>
                                                <th width="120px">Expired</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                                <th>Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php( $totalIn = $totalOut = 0)
                                        @foreach($datas as $key => $tracks)
                                            <tr>
                                                <td>{{ $tracks->created_at }}</td>
                                                <td>{{ $tracks->inventory->name }}</td>
                                                <td>
                                                @if($tracks->type == 'in')
                                                    @php( $totalIn += $tracks->qty )
                                                    {{ $tracks->qty }} {{ $tracks->unit }}
                                                @endif
                                                </td>
                                                <td>
                                                @if($tracks->type == 'out')
                                                    @php( $totalOut += $tracks->qty )
                                                    {{ $tracks->qty }} {{ $tracks->unit }}
                                                @endif
                                                </td>
                                                <td>{{ number_format($tracks->price,0,'.','.') }}</td>
                                                <td>{{ ($tracks->expired) }}</td>
                                                <td class="text-center">
                                                    @if($tracks->type == 'in')
                                                    <span class="text-success"><i class="fas fa-sign-in-alt"></i><br>Masuk</span>
                                                    @else
                                                    <span class="text-danger"><i class="fas fa-sign-out-alt"></i><br>Keluar</span>
                                                    @endif
                                                </td>
                                                <td>{{ $tracks->note }}</td>
                                                <td>{{ $tracks->createdUser->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="font-weight: 800;">
                                                <td colspan="2" class="text-right">Total</td>
                                                <td>{{ number_format($totalIn,0,'.','.') }}</td>
                                                <td>{{ number_format($totalOut,0,'.','.') }}</td>
                                                <td colspan="5"></td>
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