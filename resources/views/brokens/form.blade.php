@extends("layouts.app")

@section('title', '| Barang Tidak Layak')
@section("content")
<link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
<style>
    .select2-container {
        width: 90% !important;
    }

    .select2-selection {
        padding: 5px 10px !important;
    }

    @media only screen and (max-width: 1024px) {
        .select2-container {
            width: 70% !important;
        }
    }
</style>
<section class="section">
    <div class="section-header">
        <h1>Barang Tidak Layak</h1>
    </div>
    <div class="section-body">
        @if (session('status'))
            <div class="alert alert-info alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                {{ session('status') }}
            </div>
        @endif 
        <form action="{{route('brokens.store')}}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="user">Tanggal</label>
                        <input type="date" class="form-control" name="date" id="date" value="2020-04-20">
                    </div>
                    <div class="form-group">
                        <label for="purchaseData">No. Faktur</label>
                        <div class="input-group">
                            <select name="purchase" id="purchaseData" class="form-control @error('purchase') is-invalid @enderror purchaseData"></select>
                            <div class="input-group-append">
                                <button class="btn btn-md btn-primary btnFindPurchase" title="Daftar Barang Masuk" type="button"><i class="fas fa-search"></i></button>
                            </div>
                            @error('purchase')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="notes">Catatan</label>
                        <textarea name="notes" placeholder="Catatan" class="form-control myform-textarea"></textarea>
                    </div>
                </div>
            </div>
            <br>
            <hr class="my-3">
            <div class="coloum">
                <div class="col-md-12">
                    <h5 class="text-center">Daftar Barang</h5>
                    <div>
                        <input type="hidden" name="purchases_details" id="purchases_details" class="form-control  @error('purchases_details') is-invalid @enderror">
                        @error('purchases_details')
                            <h5 class="text-center invalid-feedback">* {{ $message }}</h5>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" id="listPicking">
                                <div class="col-md-12 text-center"><h6>Belum ada transaksi barang masuk dipilih.</h6></div>
                            </div>
                            <br>
                            <!-- <h5 class="float-left">Total</h5><h5 class="float-right">0</h5> -->
                        </div>
                    </div>
                </div>

                <br>
                <a class="btn btn-lg btn-danger" href="{{ url('brokens') }}">Batal</a>
                <button type="submit"class="btn btn-lg btn-success float-right">Simpan Data</button>
            </div>
        </form>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="myModalPurchase">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Data Purchase</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="bodyAllPurchase"></div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script>
    let dataPurchases = `@if(!empty($purchases)){{ $purchases->id }}@endif`
    let dataNumPurchases = `@if(!empty($purchases)){{ $purchases->transaction_number }}@endif`
    let dataBrokens = `@if(!empty($broken)){{ $broken->id }}@endif`
    let purchaseCollect = [];
    let purchaseSelected = [];
    let purchaseId = '';
    let purchaseText = '';
    let now = "{{ date('Y-m-d') }}";

    $(document).ready(function () {
        if (dataPurchases) {
            selectedInit({ id: eval(dataPurchases), text: dataNumPurchases, selected: true });
            purchaseCollectbyId(dataPurchases) // show old brokens details data
        } else {
            selectedInit();
        }

        $('.btnFindPurchase').click(function(e) {
            purchaseCollect = [];
            $.get("{{ url('services/purchases') }}", {do: 'ajaxall'}, function(res) {
                if (res) {
                    let newHtml = `<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No Faktur</th>
                                <th>Komponen</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`

                    $.each(res, (key, vl) => {
                        purchaseCollect.push(vl);
                        
                        let kompDiv = '';
                        if (vl.details) {
                            kompDiv += `<ul class="pl-3">`;
                            $.each(vl.details, (k, v) => {
                                kompDiv += `<li>
                                    <b>${v.invenName}</b><br>
                                    <span>${v.unit} ${v.qty} 
                                    @php( $userRoles = json_decode(Auth::user()->roles) )
                                    @if(in_array("OWNER", $userRoles) )
                                    x ${convertToRp(v.price)}</span> = <b>${convertToRp(v.subtotal)}</b>
                                    @endif
                                </li>`;
                            })
                            kompDiv += `</ul>`;
                        }

                        newHtml += `<tr>
                            <td>${vl.date}</td>
                            <td>${vl.number}</td>
                            <td>${kompDiv}</td>
                            <td>${vl.notes ? vl.notes : ''}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info btnListSelect" data-id="${vl.id}" title="Pilih : ${vl.name}">pilih</button>
                            </td>
                        </tr>`
                    }) 

                    newHtml += `</tbody></table>`
                    $('#bodyAllPurchase').html(newHtml)
                    console.log('purchaseCollect', purchaseCollect);
                } else
                    $('#bodyAllPurchase').html('Data empty.')
                
                $('#myModalPurchase').modal('show');
            })
        })
    });

    $(document).on('click', '.btnListSelect', function(ev) {
        const selectedId = $(this).data('id');
        purchaseSelected = purchaseCollect.find(x => x.id === selectedId)

        const newDataList = { id: eval(selectedId), text: purchaseSelected.number, selected: true };
        renderList()
        selectedInit(newDataList);
        $('#myModalPurchase').modal('hide');
    })

    $(document).on('click', '.btnDeleteList', function (ev) {
        const id = $(this).data('id');
        purchaseSelected = purchaseSelected.filter((x) => { if (x.id != id) return x } )
        console.log('purchaseSelected after delete', purchaseSelected);
        
        renderList()
    })

    function purchaseCollectbyId(id) {
        $('#listPicking').html('<div class="col-md-12 text-center"><h6>Loading! Proses load daftar barang....</h6></div>');
        $.get("{{ url('services/purchases') }}", { do: 'ajaxall', id: id }, function(res) {
            if (res) {
                purchaseSelected = res[0];
                renderList()
            } else alert("Data tidak ditemukan")
        });
    }

    function renderList() {
        $('#listPicking').html('');
        $('#purchases_details').val('');
        if (purchaseSelected) {
            let listInfo = listInput = '';
            $('#purchases_details').val(JSON.stringify(purchaseSelected.details));
            // console.log('purchaseSelected render', purchaseSelected);

            const brokenExist = purchaseSelected.brokens;
            const brokenList = [];
            if (brokenExist) {
                $.each(brokenExist, (k, v) => { 
                    if (v.details[0]) brokenList.push(v.details[0]) 
                });
            }

            $.each(purchaseSelected.details, (k, v) => {
                listInfo += `<div class="mb-4">
                        <b>${k + 1}. ${v.invenName}</b><br>
                        <span>${v.qty} ${v.unit} 
                        @php( $userRoles = json_decode(Auth::user()->roles) )
                        @if(in_array("OWNER", $userRoles) )
                        x ${convertToRp(v.pricePurchases)} = <b>${convertToRp(v.subtotal)}</b></span>
                        @endif
                    </div>`;

                const findIvenBrokens = brokenList.find(x => { if (x.inventories_id == v.invenId) return x });

                listInput += `<div class="row mb-4">
                        <input type="hidden" name="purchases_detail_id[${v.id}]" id="detail_id_${v.id}" value="${v.id}">
                        <input type="hidden" name="purchases_detail_inven[${v.id}]" id="detail_inven_${v.id}" value="${v.invenId}">
                        <input type="hidden" name="purchases_detail_stock[${v.id}]" id="detail_stock_${v.id}" value="${v.stockId}">
                        <input type="hidden" name="purchases_detail_qty[${v.id}]" id="detail_qty_${v.id}" value="${v.qty}">
                        <input type="hidden" name="purchases_detail_exp[${v.id}]" id="detail_exp_${v.id}" value="${v.exp}">
                        <input type="hidden" name="purchases_detail_unit[${v.id}]" id="detail_unit_${v.id}" value="${v.unit}">
                        <input type="hidden" name="purchases_detail_price[${v.id}]" id="detail_price_${v.id}" value="${v.prices}">
                        <div class="col-md-4">
                            <input type="number" name="brokens_qty[${v.id}]" value="${(findIvenBrokens ? findIvenBrokens.broken : '')}" ${(findIvenBrokens ? 'disabled' : '')} min="1" class="form-control brokenForm" placeholder="Jumlah tidak layak">
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="brokens_notes[${v.id}]" value="${(findIvenBrokens ? findIvenBrokens.notes : '')}" ${(findIvenBrokens ? 'disabled' : '')}  class="form-control brokenForm" placeholder="Catatan tidak layak">
                        </div>
                    </div>`
            })
    
            $('#listPicking').append(`
                <div class="col-md-4">
                    <h6><u>Barang Masuk</u></h6>
                    ${listInfo}
                </div>
                <div class="col-md-8">
                    <h6><u>Input Barang tidak layak</u></h6>
                    ${listInput}
                </div>`
            )

            // if (brokenExist) {
            //     $(".brokenForm").prop('disabled', true);
            // }
        } else {
            $('#listPicking').append('<div class="col-md-12 text-center"><h6>Tidak ada daftar barang.</h6></div>');
        }
    }

    function selectedInit(data) {
        if (data) {
            console.log('selectedInit(data)', data);
            $('#purchaseData').empty();
            $('#purchaseData').select2({
                data: [{ id: data.id, text: data.text, unit: data.unit, selected: true }],
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketik no faktur atau klik tombol cari',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/purchases') }}`,
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
                let selectedId = $("#purchaseData option:selected").val();
                purchaseCollectbyId(selectedId)
            });
        } else {
            $('#purchaseData').empty();
            $('#purchaseData').select2({
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketik no faktur atau klik tombol cari',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/purchases') }}`,
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
                let selectedId = $("#purchaseData option:selected").val();
                purchaseCollectbyId(selectedId)
            });
        }
    }
</script>
@endsection