@extends('layouts.app')

@section('title', '| Laporan Penjualan')
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
        <h1>Laporan Penjualan</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('reports/sales') }}" method="GET" name="postform">  
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="type" class="form-label">Partner</label>
                                        <select name="id" id="partnersData" class="form-control">
                                            <option value="">Ketikkan nama partners</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pt-4 row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-search"></i> Pencarian</button>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ url('reports/sales') }}" class="btn btn-success btn-lg"><i class="fas fa-sync-alt"></i> Reload</a>
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
                                                <th>No Invoice</th>
                                                <th>Partner</th>
                                                <th>Komponen</th>
                                                <th>Keterangan</th>
                                                <th>Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php( $total = 0)
                                        @foreach($datas as $key => $data)
                                            <tr>
                                                <td>{{ $data->date_select }}</td>
                                                <td>{{ $data->transaction_number }}</td>
                                                <td>{{ $data->partner->name }}</td>
                                                <td>
                                                    @if(!empty($data->details))
                                                        <ul class="pl-3">
                                                        @foreach($data->details as $detail)
                                                            <li>
                                                                <b>{{ $detail->name }},</b><br>
                                                                <span>Jumlah: {{ $detail->qty }} {{ $detail->unit }} = {{ number_format($detail->subtotal,0,'.','.') }}</span>
                                                            </li>
                                                            @php( $total += $detail->subtotal )
                                                        @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>{{ $data->notes }}</td>
                                                <td>{{ $data->createdUser->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="font-weight: 800;">
                                                <td colspan="3" class="text-right">Total</td>
                                                <td>{{ number_format($total,0,'.','.') }}</td>
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

@section('footer-scripts')
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        const selId = `@if(Request::get('id')){{ Request::get('id') }}@endif`

        if (selId) {
            const invenId = `@if($partnersData){{ $partnersData->id }}@endif`;
            const invenText = `@if($partnersData){{ $partnersData->name }}@endif`;
            const newDataList = { id: eval(invenId), text: invenText, selected: true };
            selectedInit(newDataList);
        }
        else
            selectedInit();
    });

    function selectedInit(data) {
        if (data) {
            console.log('selectedInit(data)', data);
            $('#partnersData').empty();
            $('#partnersData').select2({
                data: [{ id: data.id, text: data.text, unit: data.unit, selected: true }],
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketik nama partner',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/partners') }}`,
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
                invenId = $("#partnersData option:selected").val();
                invenText = $("#partnersData option:selected").text();
                // data = $("#partnersData").select2('data')[0];
            });
        } else {
            $('#partnersData').empty();
            $('#partnersData').select2({
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketik nama partner',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/partners') }}`,
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
                invenId = $("#partnersData option:selected").val();
                invenText = $("#partnersData option:selected").text();
                data = $("#partnersData").select2('data')[0];
            });
        }
    }
</script>
@endsection