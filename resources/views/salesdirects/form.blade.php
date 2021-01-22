@extends("layouts.app")

@section('title', '| Penjualan Langsung')
@section("content")
<link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
<style>
    .hiden{
        display: none;
    }
    .select2-container {
        width: 89% !important;
    }

    .select2-selection {
        padding: 5px 10px !important;
    }

    @media only screen and (max-width: 1024px) {
        .select2-container {
            width: 85% !important;
        }
    }
</style>
<section class="section">
    <div class="section-header">
        <h1>Penjualan Langsung</h1>
    </div>
    <div class="section-body">
        <form id="salesForm" action="{{route('salesdirects.store')}}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
            <div class="row">
                @if (session('status'))
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ session('status') }}
                        </div>
                    </div>
                @endif
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="user">Tanggal</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="date" value="{{ date('Y-m-d') }}">
                        @error('date')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                     <div class="form-group">
                        <label class="form-label" for="invoice">No. Invoice</label>
                        <input type="text" class="form-control @error('invoice') is-invalid @enderror" name="invoice" id="invoice" value="{{ old('invoice') }}">
                        @error('invoice')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>                   
                </div>
                <div class="col-md-6">
                    <div class="form-group" >
                        <label for="customer_type">Pembeli</label>
                        <div class="input-group">
                            <select class="form-control @error('customer_type') is-invalid @enderror" name="customer_type" id="customer_type">
                                <option value="">Pilih TIpe Pembeli</option>
                                <option value="Umum">Umum</option>
                                <option value="Langganan">Langganan</option>
                            </select>
                            @error('customer_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group" id="customer">
                        <label class="form-label" for="name_customer">Nama Pelanggan</label>
                        <input type="text" class="form-control @error('name_customer') is-invalid @enderror" name="name_customer" id="name_customer" value="{{ old('name_customer') }}">
                    </div>   
                                                 
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="notes">Catatan</label>
                        <textarea name="notes" placeholder="Catatan" class="form-control myform-textarea">{{ old('notes') }}</textarea>
                    </div>     
                </div>
            </div>
            <br>
            <hr class="my-3">
            <div class="coloum">                
                <hr>
                <div class="col-md-12">
                    <h5>Form Transaksi</h5>
                    <div style="padding: 10px; background-color: #f8f8f8;">
                        <div class="row">
                            <!-- {% if not record.bills.isPaid or record.bills.isCanceled %} -->
                            <div class="col-md-12 no-print-area">
                            <div style="padding: 10px; background-color: #f8f8f8;">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <select id="invenData" class="form-control">
                                                <option value="">Pilih Tipe Pembeli dahulu</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-md btn-primary btnFindIven" title="Daftar Barang" type="button"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <small><a target="_blank" href="{{ url('inventories/create') }}"><i>Tambah barang inventori baru</i></a></small>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" id="unitSelect" class="form-control" placeholder="Satuan" value="" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" id="price" class="form-control" placeholder="Harga" value="" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" min="1" id="qtySelect" class="form-control" placeholder="Jumlah" value="">
                                    </div>
                                    <div class="col-md-3" style="margin-top:10px;">
                                        <select name="disctype" id="disctype" class="form-control inputPayMargin" >
                                        <option value="">Tipe Diskon</option>
                                        <option value="fix">Rp (Fix)</option>
                                        <option value="percent">% (Persen)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5" style="margin-top:10px;">
                                        <input type="number" class="form-control inputPayMargin" name="discount" id="disc" placeholder="* Diskon Item" >
                                    </div>
                                    <div class="col-md-4" style="margin-top:10px;">
                                        <button type="button" title="Tambahkan ke daftar" class="btn btn-md btn-primary addListSelect"><i class="fas fa-plus"></i> Tambahkan ke daftar</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <!-- {% endif %} -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped" style="border: 1px solid #e9e6e6;margin-top:10px;">
                            <thead>
                                <tr>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                                <th>Jumlah</th>

                                <!-- {% if bill.isPaid == false %}<th>Aksi</th>{% endif %} -->
                                </tr>
                            </thead>
                            <tbody id="listitempembayaran"></tbody>
                            </table>

                            <div id="noitem">
                            <span style="display: block;text-align: -webkit-center;">Belum Ada Barang yang dipilih</span>
                            <hr>
                            </div>
                            <div id="paymenstoverview" style="margin-top: -10px;float: right;">
                            <div style="display: inline-block;border: 1px solid #e9e6e6;width: 250px;padding: 0px 0px 0px 15px;">
                                <h5>Total Biaya</h5>
                                <h3 style="margin-top: -10px;"><b class="medicin" id="totalpayment">Rp. 0</b></h3>
                            </div>
                            <div style="display: inline-block;border: 1px solid #e9e6e6;width: 250px;padding: 0px 0px 0px 15px;">
                                <h5>Total Diskon</h5>
                                <h3 style="margin-top: -10px;"><b id="totaldisc">Rp. 0</b></h3>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <br>
                <a class="btn btn-lg btn-danger" href="{{ url('sales') }}">Batal</a>
                <button type="submit" class="btn btn-lg btn-success float-right btnGrandSave">Simpan Transaksi</button>
                <h6 class="text-muted float-right loadingText">Loading...</h6>
            </div>

            
        </form>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="myModalPartner">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Data Partner</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="bodyAllPartner">
        </div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="myModalIven">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Data Barang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body"id="bodyAllIven"></div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>

<script>

    let partnersCollect = listSelected = invenCollect = invenSelected = [];
    let partnerId = partnerText = invenId = invenText = invenUnit = '';
    let now = "{{ date('Y-m-d') }}";
    let totalpayment = 0;
    let normalPrice = 0;
    let totaldisc = 0;
    let idIvenBtnAdd;


    $('.loadingText').hide();
    $(document).ready(function () {
        $('#invenData, .btnFindIven, #qtySelect, #disctype, #disc, .addListSelect').attr('disabled', true);

 		$("#customer").hide()
        $('#customer_type').change(function(){
 			if($(this).val() == "Langganan"){
 			    $("#customer").show();
 			}else{
 			    $("#customer").hide();
 			}
            selectedInit();
 		});

        $('.btnFindPartner').click(function(e) {
            partnersCollect = [];
            $.get("{{ url('services/partners') }}", { do: 'ajaxall' }, function(res) {
                if (res) {
                    let newHtml = `<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Telp</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`

                    $.each(res, (key, vl) => {
                        partnersCollect.push(vl);

                        newHtml += `<tr>
                            <td>${vl.name}</td>
                            <td>${vl.phone ? vl.phone : ''}</td>
                            <td>${vl.email ? vl.email : ''}</td>
                            <td>${vl.address ? vl.address : ''}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info btnListPartnerSelect" data-id="${vl.id}" title="Pilih : ${vl.name}">pilih</button>
                            </td>
                        </tr>`
                    }) 

                    newHtml += `</tbody></table>`
                    $('#bodyAllPartner').html(newHtml)
                    console.log('partnersCollect', partnersCollect);
                } else
                    $('#bodyAllPartner').html('Data empty.')
                
                $('#myModalPartner').modal('show');
            })
        })

        $('.btnFindIven').click(function(e) {
            invenCollect = [];
            $.get("{{ url('services/inventories') }}", {do: 'ajaxall', type: $('#customer_type').val()}, function(res) {
                if (res) {
                    let newHtml = `<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Harga</th>

                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`

                    $.each(res, (key, vl) => {
                        invenCollect.push(vl);

                        newHtml += `<tr>
                            <td>${vl.name}</td>
                            <td>${vl.stock}</td>
                            <td>${vl.unit}</td>
                            <td>${vl.price}</td>

                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info btnListSelect" data-id="${vl.id}" title="Pilih : ${vl.name}">pilih</button>
                            </td>
                        </tr>`
                    }) 

                    newHtml += `</tbody></table>`
                    $('#bodyAllIven').html(newHtml)
                    console.log('invenCollect', invenCollect);
                } else
                    $('#bodyAllIven').html('Data empty.')
                
                $('#myModalIven').modal('show');
            })
        })

        $('.addListSelect').click(function(e) {
            if ($('#customer_type').val()) {
                if (invenId){
                    if ($('#qtySelect').val()) {
                    // validasi check qty need and total stock
                        $.get("{{ url('services/inventories') }}", { do: 'getreadystock', id: invenId, qty: $('#qtySelect').val(), type: $('#customer_type').val() }, function(result) {
                            if (result.status) {                                
                                
                                if (idIvenBtnAdd) { // ADD IVEN LIST BILL
                                    thisPayments = invenSelected.find(x => x.id == idIvenBtnAdd);

                                    const allPurchaseIven = thisPayments.stock.map(item => parseInt(item.price));
                                    const sumPurchaseIven = allPurchaseIven.length ? _.sum(allPurchaseIven) : 0;
                                    
                                    thisPayments.purchasePrice = sumPurchaseIven || 0;
                                    thisPayments.disc = disc || 0;
                                    thisPayments.disctype = disctype || '';

                                    if (disc >= 0) {
                                    thisPayments.discPrice = parseInt(thisPayments.normalPrice) - parseInt(disc);

                                    if (disctype === 'percent')
                                        thisPayments.discPrice = parseInt(thisPayments.normalPrice) - (parseInt(thisPayments.normalPrice) * (parseFloat(disc) / 100))
                                    }
                                }
                                else {                       
                                    const disctype = $('#disctype').val();
                                    const disc = $('#disc').val() ? eval($('#disc').val()) : 0;
                                    const priceThisPayments = $('#price').val() || 0;
                                    const getData = {
                                        price: priceThisPayments * $('#qtySelect').val(),
                                        disctype: $('#disctype').val(),
                                        disc: disc,
                                    };
                            
                                    let discPrice = getData.price;
                                    if (getData.disc) {
                                        discPrice = getData.disctype === 'percent' ?
                                        parseInt(getData.price) - (parseInt(getData.price) * (parseFloat(getData.disc) / 100)) : parseInt(getData.price) - parseInt(getData.disc)
                                    }
                                    const newData = { 
                                        id: invenId, 
                                        name: invenText, 
                                        unit: invenUnit, 
                                        qty: $('#qtySelect').val(),
                                        normalPrice: getData.price ,
                                        discPrice: discPrice || 0,
                                        disc: getData.disc || 0,
                                        disctype: getData.disctype, 
                                        subTotal: result.total, 
                                        dataStock: result.stocks}
                                    console.log('newData',newData);
                                    const findBefore = invenSelected.find((x) => x.id == invenId);
                                    if (findBefore) {
                                        invenSelected = invenSelected.map((x) => { if (x.id == invenId) return newData; else return x; } )
                                    } else {
                                        invenSelected.push(newData);
                                    }
                                    
                                    invenCollect = [];
                                    invenId = invenText = invenUnit = '';
                                    $('#qtySelect').val('');
                                    $('#invenData').val(null);
                                    renderList()
                                    $('#unitSelect').val('');
                                    $('#price').val('');
                                    $('#disctype').val('');
                                    $('#disc').val('');
                                    selectedInit();
                                }
                            }    
                            else alert(result.msg);
                        });
                    } else
                    alert('Silahkan isi kolom jumlah.')
                } else{
                alert('Silahkan pilih barang dahulu.')
                }
            } else 
            alert('Silahkan pilih tipe pembeli dahulu.')
            
        })
    });

    $(document).on('click', '.btnListPartnerSelect', function(ev) {
        const selectedId = $(this).data('id');
        let findFirst = partnersCollect.find(x => x.id === selectedId)

        partnerId = findFirst.id;
        partnerText = findFirst.name;
        const newDataList = { id: eval(partnerId), text: partnerText, selected: true };
        selectedInitPartner(newDataList);
        $('#myModalPartner').modal('hide');
    })

    $(document).on('click', '.btnListSelect', function(ev) {
        const selectedId = $(this).data('id');
        let findFirst = invenCollect.find(x => x.id === selectedId)

        invenId = findFirst.id;
        invenText = findFirst.name;
        invenUnit = findFirst.unit;
        invenPrice = findFirst.price;

        const newDataList = { id: eval(invenId), text: invenText, unit: invenUnit, price: invenPrice, selected: true };
        // console.log('added newDataList', newDataList);
        selectedInit(newDataList);
        $('#myModalIven').modal('hide');
        $('#unitSelect').val(invenUnit);
        $('#price').val(invenPrice);
        $('#qtySelect').focus();
    })

    $(document).on('click', '.btnDeleteList', function (ev) {
        const id = $(this).data('id');
        invenSelected = invenSelected.filter((x) => { if (x.id != id) return x } )
        console.log('invenSelected after delete', invenSelected);
        
        renderList()
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#salesForm').submit(function(ev) {
        ev.preventDefault();
        console.log('cust type',$('#customer_type').val());
        if (!$('#date').val()) alert('Mohon input tanggal')
        else if (!$('#invoice').val()) alert('Mohon input nomor invoice')
        else if (!$('#customer_type').val()) alert('Mohon pilih tipe pembeli')
        else if ($('#customer_type').val() == 'Langganan' && !$('#name_customer').val()) alert('Mohon input nama pelanggan')
        else if (!invenSelected || invenSelected.length <= 0) alert('Mohon input daftar barang')
        else {
            spinner.show();
            $("#salesForm :input").prop("readonly", true);
            $("#salesForm :button").prop("disabled", true);

            $.post($(this).attr('action'), $(this).serialize(), function(res) {
                console.log(res);
                spinner.hide();

                if (res.status) {
                    alert(res.msg)
                    $("#salesForm :input").prop("readonly", false);
                    $("#salesForm :button").prop("disabled", false);

                    if (res.link) location.href = res.link;
                    else location.reload();
                } 
                else {
                    alert(res.msg);
                    $("#salesForm :input").prop("readonly", false);
                    $("#salesForm :button").prop("disabled", false);
                }
            }).fail(function(xhr, status, error) {
                console.log(error);
                spinner.hide();
                alert('Gagal, silahkan reload dan coba lagi.');
                $("#salesForm :input").prop("readonly", false);
                $("#salesForm :button").prop("disabled", false);
            });
        }
    })

    function renderList() {

        let html = '';
        if (invenSelected.length) {        
            invenSelected.forEach(function (item, i) {
                html += `<tr id="listedonBills${item.id}">`;
                html += `<td>${item.name} (${item.qty} ${item.unit})</td>`;
                html += `
                    <td>Rp. ${convertToRp(item.normalPrice)}</td>
                    <td>
                    ${item.disctype === 'percent' ? '' : 'Rp. '}
                    ${convertToRp(parseInt(item.disc))}
                    ${item.disctype === 'percent' ? ' %' : ''}
                    </td>
                    <td>Rp. ${item.discPrice ? convertToRp(item.discPrice) : 0}</td>`;
                   
                html += `</tr>`;
            });
        
            const getTotalPay = invenSelected.map(item => parseInt(item.discPrice));
            console.log('getTotalPay', getTotalPay);
            totalpayment = getTotalPay.length ? getTotalPay.reduce((a, b) => a + b ): 0;
            console.log('totalpayment', totalpayment);
        
            const getNormalPrices = invenSelected.map(itm => parseInt(itm.normalPrice));
            normalPrice = getNormalPrices.length ? getNormalPrices.reduce((a, b) => a + b ) : 0;
            console.log('normalPrice',normalPrice);
            
            totaldisc = getNormalPrices.length ? normalPrice - totalpayment: 0;
            console.log('totaldisc',totaldisc);
        } 
        else {
        totalpayment = totalpurchase = totaldisc = 0;
        }
        
        $('#totalpayment').text(convertToRp(totalpayment || 0));
        $('#totaldisc').text(convertToRp(totaldisc || 0));
        $('#listitempembayaran').html(html);

        if (invenSelected.length) $('#noitem').hide();
        else $('#noitem').show();
    }

    function selectedInitPartner(data) {
        if (data) {
            console.log('selectedInitPartner(data)', data);
            $('#partnerData').empty();
            $('#partnerData').select2({
                data: [{ id: data.id, text: data.text, unit: data.unit, selected: true }],
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketikkan kata kunci partner [nama, telp, email, alamat] atau klik tombol cari',
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
                partnerId = $("#partnerData option:selected").val();
                partnerText = $("#partnerData option:selected").text();
            });
        } else {
            $('#partnerData').empty();
            $('#partnerData').select2({
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketikkan kata kunci partner [nama, telp, email, alamat] atau klik tombol cari',
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
                            do: 'ajaxselect2',
                            type: $('#customer_type').val(),
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
                partnerId = $("#partnerData option:selected").val();
                partnerText = $("#partnerData option:selected").text();
            });
        }
    }

    function selectedInit(data) {
        $('#invenData, .btnFindIven, #qtySelect, #disctype, #disc, .addListSelect').removeAttr('disabled');
        typeCustomer = $("#customer_type").val();

        if (typeCustomer) {
            if (data) {
                console.log('selectedInit(data)', data);
                $('#invenData').empty();
                $('#invenData').select2({
                    data: [{ id: data.id, text: data.text, unit: data.unit, price: data.price, selected: true }],
                    minimumInputLength: 2,
                    allowClear: true,
                    placeholder: 'Ketik nama barang atau klik tombol cari',
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
                                do: 'ajaxselect2',
                                type: typeCustomer
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
                    data = $("#invenData").select2('data')[0];
                    // console.log('dataIvenData', data);
                    invenId = $("#invenData option:selected").val();
                    invenText = $("#invenData option:selected").text();
                    invenUnit = data.unit;
                    invenPrice = data.price;
                    console.log('invenPrice',invenPrice);

                    $('#unitSelect').val(invenUnit);
                    $('#price').val(invenPrice);
                    $('#qtySelect').focus();
                });
            } else {
                $('#invenData').empty();
                $('#invenData').select2({
                    minimumInputLength: 2,
                    allowClear: true,
                    placeholder: 'Ketik nama barang atau klik tombol cari',
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
                                do: 'ajaxselect2',
                                type: typeCustomer

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
                    data = $("#invenData").select2('data')[0];
                    // console.log('dataIvenData (nodata)', data);
                    invenId = $("#invenData option:selected").val();
                    invenText = $("#invenData option:selected").text();
                    invenUnit = data.unit;
                    invenPrice = data.price;    
                    console.log('invenPrice',invenPrice);

                    $('#unitSelect').val(invenUnit);
                    $('#price').val(invenPrice);
                    $('#qtySelect').focus();
                });
            }
        }
    }
</script>
@endsection