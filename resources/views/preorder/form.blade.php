@extends("layouts.app")

@section('title', '| Preorder')
@section("content")
<link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
<style>
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
        <h1>Preorder (PO)</h1>
    </div>
    <div class="section-body">
        <form id="preorderForm" action="@if(!empty($data)) {{ route('preorder.update', [$data->id]) }} @else {{ route('preorder.store') }} @endif" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
            @if(!empty($data))
            <input type="hidden" value="PUT" name="_method">
            @endif

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
                        @if($data)
                        <input type="text" class="form-control " readonly name="date" id="date" value="{{ date('m/d/Y', strtotime($data->date_select)) }}">
                        @else
                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"  id="date" value="{{ date('Y-m-d') }}">
                        @endif
                        @error('date')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="partnerData">Partner</label>
                        <div class="input-group">
                            @if($data)
                            <input type="text" class="form-control " readonly name="partnerEdit" value="{{ $data->partner->name }}">
                            @else
                            <select name="partner" id="partnerData" class="form-control @error('partner') is-invalid @enderror partnerData"></select>
                            <div class="input-group-append">
                                <button class="btn btn-md btn-primary btnFindPartner" title="Daftar Partner" type="button"><i class="fas fa-search"></i></button>
                            </div>
                            @error('partner')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="po">No. PO</label>
                        <input type="text" class="form-control @error('po') is-invalid @enderror" @if($data) readonly @endif name="po" id="po" value="@if($data){{ $data->po_number }}@else{{ old('po') }}@endif ">
                        @error('po')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="notes">Catatan</label>
                        <textarea name="notes" placeholder="Catatan" @if($data) readonly @endif class="form-control myform-textarea">@if($data) {{ $data->notes }} @else {{ old('notes') }} @endif </textarea>
                    </div>
                </div>
            </div>
            <br>
            <hr class="my-3">
            <div class="coloum">
                <div class="col-md-12">
                    <h5>Pencarian Barang</h5>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group">
                                <select id="invenData" class="form-control">
                                    <option value="">Ketikkan nama barang</option>
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
                        <div class="col-md-2">
                            <input type="number" min="1" id="qtySelect" class="form-control" placeholder="Jumlah" value="">
                        </div>
                        <div class="col-md-3 text-right">
                            <button type="button" title="Tambahkan ke daftar" class="btn btn-md btn-primary addListSelect"><i class="fas fa-plus"></i> Tambahkan ke daftar</button>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <h5 class="text-center">Daftar Barang</h5>
                    <div>
                        <input type="hidden" name="iventories" class="form-control  @error('iventories') is-invalid @enderror">
                        @error('iventories')
                            <h5 class="text-center invalid-feedback">* {{ $message }}</h5>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                           <ul class="list-group" id="listPicking">
                                                    
                                <li class="list-group-item d-flex justify-content-between align-items-center">Belum ada barang yang dipilih.</li>
                            
                            </ul>
                            <br>
                            <!-- <h5 class="float-left">Total</h5><h5 class="float-right">0</h5> -->
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
                <hr>

                <br>
                <a class="btn btn-lg btn-danger" href="{{ url('preorder') }}">Batal</a>
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
<script>
    let partnersCollect = listSelected = invenCollect = invenSelected = [];
    let partnerId = partnerText = invenId = invenText = invenUnit = '';
    let now = "{{ date('Y-m-d') }}";
    invenSelected = JSON.parse(`{!! $oldinven !!}`)
    $('.loadingText').hide();
    $(document).ready(function () {
        selectedInitPartner();
        selectedInit();
        renderList()

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
            $.get("{{ url('services/inventories') }}", {do: 'ajaxall'}, function(res) {
                if (res) {
                    let newHtml = `<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`

                    $.each(res, (key, vl) => {
                        invenCollect.push(vl);

                        newHtml += `<tr>
                            <td>${vl.name}</td>
                            <td>${vl.stock}</td>
                            <td>${vl.unit}</td>
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
            if (invenId) {
                if ($('#qtySelect').val()) {
                    // validasi check qty need and total stock
                    $.get("{{ url('services/inventories') }}", { do: 'getreadystock', id: invenId, qty: $('#qtySelect').val(), type: 'Partner' }, function(result) {
                        if (result.status) {
                            const newData = { id: invenId, name: invenText, unit: invenUnit, qty: $('#qtySelect').val(), subTotal: result.total, dataStock: result.stocks}
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
                            selectedInit();
                        } 
                        else alert(result.msg);
                    });

                } else
                    alert('Silahkan isi kolom jumlah.')
            } else 
                alert('Silahkan pilih barang dahulu.')
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
        const newDataList = { id: eval(invenId), text: invenText, unit: invenUnit, selected: true };
        // console.log('added newDataList', newDataList);
        selectedInit(newDataList);
        $('#myModalIven').modal('hide');
        $('#unitSelect').val(invenUnit);
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

    $('#preorderForm').submit(function(ev) {
        ev.preventDefault();

        if (!invenSelected || invenSelected.length <= 0) alert('Mohon input daftar barang')
        else {
            spinner.show();
            $("#preorderForm :input").prop("readonly", true);
            $("#preorderForm :button").prop("disabled", true);

            $.post($(this).attr('action'), $(this).serialize(), function(res) {
                console.log('res.status',res.status);
                spinner.hide();
                if (res.status) {
                    console.log(res);
                    alert(res.msg)
                    
                    $("#preorderForm :input").prop("readonly", false);
                    $("#preorderForm :button").prop("disabled", false);

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
                $("#preorderForm :input").prop("readonly", false);
                $("#preorderForm :button").prop("disabled", false);
            });
        }
    })

    function renderList() {
        $('#listPicking').html('');
        console.log('invenSelected',invenSelected);
        if (!invenSelected || invenSelected.length <= 0) {
            $('#listPicking').append(
            `<li class="list-group-item d-flex justify-content-between align-items-center">Belum ada barang yang dipilih.</li>`
            )        
        }
        else {
           $.each(invenSelected, (k, v) => {
            // const subtotal = eval(v.qty) * eval(v.price);
            $('#listPicking').append(
                `<li class="list-group-item d-flex justify-content-between align-items-center" id="liConList-${v.id}">
                    ${v.name}
                    <input type="hidden" name="iventories[${v.id}]" value="${v.id}&${v.qty}&${v.unit}">
                    <span class="float-right">(${v.qty} ${v.unit}) <button type="button" data-id="${v.id}" class="btn btn-icon btn-sm btn-danger btnDeleteList"><i class="fa fa-trash"></i></button></span>
                </li>`
            )
            })
        }
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
                partnerId = $("#partnerData option:selected").val();
                partnerText = $("#partnerData option:selected").text();
            });
        }
    }

    function selectedInit(data) {
        if (data) {
            console.log('selectedInit(data)', data);
            $('#invenData').empty();
            $('#invenData').select2({
                data: [{ id: data.id, text: data.text, unit: data.unit, selected: true }],
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
                data = $("#invenData").select2('data')[0];
                // console.log('dataIvenData', data);
                invenId = $("#invenData option:selected").val();
                invenText = $("#invenData option:selected").text();
                invenUnit = data.unit;
                $('#unitSelect').val(invenUnit);
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
                data = $("#invenData").select2('data')[0];
                // console.log('dataIvenData (nodata)', data);
                invenId = $("#invenData option:selected").val();
                invenText = $("#invenData option:selected").text();
                invenUnit = data.unit;
                $('#unitSelect').val(invenUnit);
                $('#qtySelect').focus();
            });
        }
    }
</script>
@endsection