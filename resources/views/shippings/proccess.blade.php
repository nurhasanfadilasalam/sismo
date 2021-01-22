@extends('layouts.app')

@section('title', '| Proses Pengiriman')
@section('content')
<script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
<link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>

<section class="section">
    <div class="section-header">
        <h1>Proses Pengiriman</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="float-left">
                    <h5>No. Tracking : {{ $shipping->tracking_number }}</h5>
                    <h6>Driver : {{ $shipping->driver->name }}</h6>
                    <h6>Rute : {{ $shipping->routes->name }}</h6>
                    <h6>Catatan Pengiriman : {{ $shipping->notes }}</h6>
                </div>
                @if($show == 'done')
                    <a class="btn btn-warning float-right" href="{{ route('shippings.edit', [$shipping->id]) }}"><i class="fas fa-clipboard"></i> Lihat Pengiriman Belum Selesai</a>
                @else
                    <a class="btn btn-success float-right" href="{{ url('shippings/'.$shipping->id.'/edit?show=done') }}"><i class="fas fa-clipboard-check"></i> Lihat Pengiriman Selesai</a>
                @endif
                <a href="{{ url('shippings') }}" class="btn btn-danger mr-2 float-right"><i class="fas fa-left"></i> Kembali</a>
            </div>
            <div class="col-md-12">
                <hr>
                @if (session('status'))
                <div class="alert alert-warning alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    {{ session('status') }}
                </div>
                @endif
            </div>
            <div class="col-md-@if ($show == 'done'){{'12'}}@else{{'5'}}@endif">
                @if ($show == 'done')
                    <h6>Daftar PO yg selesai :</h6>
                @else
                    <h6>Pilih No. PO yg akan diproses :</h6>
                @endif
                @if(!empty($details))
                    <ul class="pl-3">
                    @foreach($details as $detail)
                        <li>
                            <div class="row mb-3">
                                <div class="col-md-@if ($show == 'done'){{'5'}}@else{{'8'}}@endif">
                                    <b>No. PO : {{ $detail->po_number }}</b><br>
                                    <b>(Urutan : {{ $detail->order }}), {{ $detail->name }}</b><br>
                                    <span>Telp: {{ $detail->phone }}</span><br>
                                    <span>Alamat : {{ $detail->address }}</span><br>
                                    <span>Status : 
                                        @if ($detail->status == '1')
                                            <b class="text-success">Selesai</b>
                                        @else
                                            <b class="text-warning">Belum di proses</b>
                                        @endif
                                    </span>
                                </div>
                                @if ($show != 'done')
                                    <div class="col-md-4">
                                        <button type="button" 
                                            data-id="{{ $detail->id }}" 
                                            data-po="{{ $detail->preorder_id }}" 
                                            data-sp="{{ $detail->shippings_id }}" 
                                            data-dt="{{ $detail->details_id }}" 
                                            data-num="{{ $detail->shippingPreorder->po_number }}"
                                            data-name="{{ $detail->name }}<br>Alamat : {{ $detail->address }}" 
                                            class="btn btn-primary mt-4 btnSelect"><i class="fas fa-chevron-circle-right"></i> pilih</button>
                                    </div>
                                @else
                                    <div class="col-md-7">
                                        <h6>Informasi Penerimaan :</h6>
                                        @if ($detail->status == '1')
                                        <div class="mb-2">
                                            <span>No. Invoice : {{ $detail->shippingSales->transaction_number }}</span><br>
                                            <span>Tanggal : {{ $detail->proccess_date }}</span><br>
                                            <span>Jam : {{ $detail->proccess_time }}</span><br>
                                            <span>Oleh : {{ $detail->proccess_by_name }}</span><br>
                                            <span>Catatan Partner : {{ $detail->proccess_notes }}</span><br>
                                        </div>
                                        @endif
                                        <ul>
                                        @php $totalDone = 0; @endphp
                                        @foreach($detail->detailsInventories as $inven)
                                            <li>
                                                <b>{{ $inven->name }}, ({{ $inven->unit }})</b><br>
                                                @foreach($inven->detailsShippingStocks as $stock)
                                                <span>PO/Pesan : {{ $stock->stock_qty }} x {{ number_format($stock->price,0,'.','.') }} = <b>{{ number_format($stock->stock_subtotal,0,'.','.') }}</b></span><br>
                                                <span>Terima : {{ $stock->stock_qty_accept }} x {{ number_format($stock->price,0,'.','.') }} = <b>{{ number_format($stock->stock_subtotal_accept,0,'.','.') }}</b></span><br>
                                                <span>Catatan : {{ $stock->proccess_notes }}</span><br>
                                                @php $totalDone += $stock->stock_subtotal_accept; @endphp
                                                @endforeach
                                            </li>
                                        @endforeach
                                        </ul>
                                        <h6>Total = {{ number_format($totalDone,0,'.','.') }}</h6>
                                    </div>
                                @endif
                                <hr>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <p><i>Belum ada data.</i></p>
                @endif
            </div>
            @if ($show != 'done')
            <div class="col-md-7">
                <h6>Proses Penerimaan :</h6>
                <form id="senderForm" action="{{ route('shippings.proccess') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h5>No. PO : <span id="poSelected"><b class="text-danger">Belum ada PO dipilih.</b></span></h5>
                            <h5><span id="poNameSelected"></span></h5>
                            <div class="form-group">
                                <input type="hidden" name="id" id="po_id" class="form-control @error('id') is-invalid @enderror">
                                @error('id')
                                    <div class="invalid-feedback">* {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="date">Tanggal Sampai</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="date" value="{{ date('Y-m-d') }}">
                                @error('date')
                                    <div class="invalid-feedback">* {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="time">Jam Sampai</label>
                                <input type="text" class="form-control @error('time') is-invalid @enderror" name="time" id="time" value="{{ date('H:i') }}">
                                @error('time')
                                    <div class="invalid-feedback">* {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="invoice">No. Invoice</label>
                                <input type="text" class="form-control @error('invoice') is-invalid @enderror" name="invoice" id="invoice" value="" placeholder="No Invoice dari pengiriman">
                                @error('invoice')
                                    <div class="invalid-feedback">* {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="notes">Catatan Partner</label>
                                <textarea name="notes" id="notes" placeholder="Catatan" class="form-control myform-textarea"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12" id="listProductsShipping"></div>
                    </div>
                    <br>
                    <a class="btn btn-lg btn-danger" href="{{ url('shippings') }}">Batal</a>
                    <button type="submit" class="btn btn-lg btn-success float-right">Simpan</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</section>
<script>
    let total = 0;
    const timepicker = new TimePicker('time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt) {
        let value = (evt.hour || '00') + ':' + (evt.minute || '00');
        evt.element.value = value;
    });

    $(document).on('click', '.btnSelect', function(evt) {
        const id = $(this).data('id');
        const shippingsId = $(this).data('sp');
        const detailsId = $(this).data('dt');
        const po = $(this).data('po');
        const num = $(this).data('num');
        const name = $(this).data('name');
        let newHtml = '';
        total = 0;

        $.get("{{ url('services/shippings') }}", { do: 'shippingdetailsstocks', idshipping: shippingsId, iddetail: detailsId }, (res) => {
            console.log('res', res);
            newHtml += `<h5>Daftar Barang :</h5>`
            if (res) {
                newHtml += `<ul class="pl-3">`
                if (res.details_inventories) {
                    $.each(res.details_inventories, (ky, vl) => {
                        total += eval(vl.iven_subtotal);
                        newHtml += `<li class="mb-2">`
                            newHtml += `<h6>${vl.name}</h6>`
                            newHtml += `<input type="hidden" name="accept_id_iven[${vl.id}]" value="${vl.id}">` // id_iven_detail_shipping

                            if (vl.details_shipping_stocks) {
                                $.each(vl.details_shipping_stocks, (k, v) => {
                                    newHtml += `<b>PO/Pesan : ${v.stock_qty} ${vl.unit} x  ${convertToRp(v.price)} = ${convertToRp(v.stock_subtotal)}</b> <small>(exp : ${v.expired})</small><br>`
                                    newHtml += `<input type="hidden" name="accept_id_stocks[${vl.id}][${v.id}]" value="${v.id}">` // id_stock_detail_shipping
                                    newHtml += `<b>Diterima : </b><small><i>* ubah jika barang yg diterima tidak sesuai pesanan</i></small><br>`
                                    newHtml +=  `<input type="number" name="accept_qty[${vl.id}][${v.id}]" id="qty_accept-${vl.inventory_id}" value="${v.stock_qty}" max="${v.stock_qty}" min="0" placeholder="Jumlah Diterima" class="form-control">`
                                    newHtml += `<b>Catatan : </b><br><input type="text" name="accept_notes[${vl.id}][${v.id}]" id="proccess_notes-${v.id}" value="" placeholder="Catatan Barang" class="form-control">`
                                    newHtml += `<hr>`
                                });
                            }

                        newHtml += `</li>`
                    })
                }
                newHtml += `</ul>`
                newHtml += `<h5 class="mt-3 text-right">Total : ${convertToRp(total)}</h5>`
            }
            
            $('#listProductsShipping').html(newHtml);
            $('#po_id').val(id)
            $('#poSelected').html(num)
            $('#poNameSelected').html(name)
            $('#invoice').focus();
        });
    })

    // TODO :: need function when change input accept qty calculate total

    $('#senderForm').submit(function(ev) {
        ev.preventDefault();

        if (!$('#po_id').val()) alert('Mohon pilih PO')
        else if (!$('#date').val()) alert('Mohon input tanggal')
        else if (!$('#time').val()) alert('Mohon input jam')
        else if (!$('#invoice').val()) alert('Mohon input No. Invoice')
        else {
            spinner.show();
            $("#shippingForm :input").prop("readonly", true);
            $("#shippingForm :button").prop("disabled", true);

            $.post($(this).attr('action'), $(this).serialize(), function(res) {
                console.log(res);
                spinner.hide();

                if (res.status) {
                    alert(res.msg)    
                    $("#shippingForm :input").prop("readonly", false);
                    $("#shippingForm :button").prop("disabled", false);

                    if (res.link) location.href = res.link;
                    else location.reload();
                } 
                else {
                    alert(res.msg)
                    $("#shippingForm :input").prop("readonly", false);
                    $("#shippingForm :button").prop("disabled", false);
                }
            }).fail(function(xhr, status, error) {
                console.log(error);
                spinner.hide();
                alert('Gagal, silahkan reload dan coba lagi.');
                $("#shippingForm :input").prop("readonly", false);
                $("#shippingForm :button").prop("disabled", false);
            });
        }
    });
</script>
@endsection