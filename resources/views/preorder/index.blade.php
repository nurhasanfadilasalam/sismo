@extends('layouts.app')

@section('title', ' | Preorder')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Preorder (PO)</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('preorder') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('preorder.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Preorder Baru</a>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-warning alert-dismissible">
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
                                        <th>Info PO</th>
                                        <th>Komponen</th>
                                        <th width="200px">Pengiriman</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="trFilter">
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('name') }}" id="nameFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter PO dan partner"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindNama" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $data->date_select }}</td>
                                        <td>
                                            <span>No. PO : <b>{{ $data->po_number }}</b></span><br>
                                            <span>Partner : <b>{{ $data->partner->name }}</b></span>
                                        </td>
                                        <td>
                                            @if(!empty($data->details))
                                            <ul class="pl-3">
                                                @foreach($data->details as $detail)
                                                <li><b>{{ $detail->name }},</b><span> {{ $detail->qty }} {{ $detail->unit }}</span></li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </td>
                                        <td> 
                                            @if($data->shippingDetail)
                                            <div>
                                                <span>No. Track : <b>{{ $data->shippingDetail->shippingData->tracking_number ?? '-' }}</b></span><br>
                                                <span>Rute : <b>{{ $data->shippingDetail->shippingData->routes->name ?? '-' }}</b></span><br>
                                                <span>Driver : <b>{{ $data->shippingDetail->shippingData->driver->name ?? '-' }}</b></span><br>
                                                @if ($data->shippingDetail->status == '1')
                                                    <b class="text-success">Selesai</b>
                                                @else
                                                    <b class="text-warning">Belum di proses</b>
                                                @endif
                                            </div>
                                            @else
                                                <small>Belum ada pengiriman</small>
                                            @endif
                                            <span>{{ $data->notes }}</span>
                                        </td>
                                        <td>
                                            @if($data->shippingDetail)
                                                <a href="{{ url('shippings') }}" type="button" class="btn btn-secondary text-white btn-sm" disable title="PO sudah memiliki data pengiriman tidak dapat diubah"><i class="fas fa-shipping-fast"></i> Pengiriman</button>
                                            @else
                                                <a href="{{ route('preorder.edit', [$data->id]) }}" class="btn btn-info text-white btn-sm" title="Ubah PO"><i class="fas fa-poll-h"></i> Ubah</a>
                                            @endif
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
        $('.btnFindNama').click(function(e) {
            filter();
        });

            $('#nameFind').on('keypress',function(e) {
                if(e.which == 13) {
                    filter();
                }
            });        
    })

    function filter() {
        let name = $('#nameFind').val();
        name = name ? name : '';       
        location.href = "{{ url('preorder') }}?name=" + name;
    }
</script>
@endsection