@extends("layouts.app")

@section('title', '| Barang Masuk')
@section("content")
<link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
<style>
    @media only screen and (max-width: 1024px) {
        .select2-container {
            width: 80% !important;
        }
    }

    .select2-container {
        width: 85% !important;
    }

    .select2-selection {
        padding: 5px 10px !important;
    }
</style>
<section class="section">
    <div class="section-header">
        <h1>Barang Masuk</h1>
    </div>
    <div class="section-body">
        <form id="#purchaseForm" action="{{route('purchases.store')}}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
            @if (session('status'))
                <div class="alert alert-warning alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="date">Tanggal</label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" id="date" value="{{ date('Y-m-d') }}">
                        @error('date')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="transactionNumber">No. Faktur</label>
                        <input type="text" class="form-control" name="transactionnumber" id="transactionNumber" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="notes">Catatan</label>
                        <textarea name="notes" placeholder="Catatan" class="form-control myform-textarea" style="min-height: 120px;"></textarea>
                    </div>
                </div>
            </div>
            <br>
            <hr class="my-3">
            <div class="coloum">
                <div class="col-md-12">
                    <h5>Pencarian Barang</h5>
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-3">
                            <input type="text" id="unitSelect" class="form-control" placeholder="Satuan" value="" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="number" min="1" id="qtySelect" class="form-control" placeholder="Qty / Jumlah Masuk" value="">
                        </div>
                        @php( $userRoles = json_decode(Auth::user()->roles) )
                        @if(in_array("OWNER", $userRoles) )
                        <div class="col-md-3">
                            <input type="number" min="1" id="pricePurchasesSelect" class="form-control" placeholder="Harga Beli @" value="">
                        </div>
                        @endif
                        @foreach($priceTypes as $key => $price)
                        <div class="col-md-3">
                            <input class="form-control pricesClass @if($errors->has('price')) is-invalid @endif" placeholder="Harga Jual {{ $price->value }} @satuan" name="price[{{ $price->id }}]" id="priceId_{{ $price->id }}" data-id="{{ $price->id }}" data-name="{{ $price->value }}" value="@if(!empty($data)){{ $data->price }}@endif" type="number" min="0"/>
                        </div>
                        @endforeach
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-1">
                            <label class="form-label float-right">Expired : </label>
                        </div>
                        <div class="col-md-6">
                            <input type="date" id="expSelect" class="form-control" placeholder="EXP" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-5 text-right">
                            <button type="button" title="Tambahkan ke daftar" class="btn btn-md btn-block btn-primary addListSelect"><i class="fas fa-plus"></i> Tambahkan ke daftar</button>
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
                                <li class="list-group-item d-flex justify-content-between align-items-center">Belum ada barang dipilih.</li>
                            </ul>
                            <br>
                            <!-- <h5 class="float-left">Total</h5><h5 class="float-right">0</h5> -->
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
                <hr>

                <br>
                <a class="btn btn-lg btn-danger" href="{{ url('purchases') }}">Batal</a>
                <button type="submit"class="btn btn-lg btn-success float-right">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</section>

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
    let invenCollect = [];
    let invenSelected = [];
    let invenId = '';
    let invenText = '';
    let now = "{{ date('Y-m-d') }}";

    $(document).ready(function () {
        selectedInit();

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
            const pricesColect = []; 
            $('.pricesClass').each(function(i, o) {
                const thisId = $(this).val()
                pricesColect.push({ 
                    type_id: $(this).data('id'), 
                    price: thisId,
                    name : $(this).data('name')
                })
            })

            if (invenId) {
                if ( $('#qtySelect').val() && pricesColect.length || $('#pricePurchasesSelect').val() && $('#expSelect').val() ) {
                    const newData = { 
                        id: invenId, 
                        name: invenText, 
                        unit: $('#unitSelect').val(), 
                        qty: $('#qtySelect').val(), 
                        prices: pricesColect, 
                        pricePurchases: $('#pricePurchasesSelect').val() ? $('#pricePurchasesSelect').val() : 0 ,
                        exp: $('#expSelect').val() 
                    }
                    const findBefore = invenSelected.find((x) => x.id == invenId);
                    
                    if (findBefore) {
                        invenSelected = invenSelected.map((x) => { if (x.id == invenId) return newData; else return x; } )
                        console.log('invenSelected after map', invenSelected);
                    } else {
                        invenSelected.push(newData);
                    }
                    console.log('invenSelected before', invenSelected);
                    
                    invenCollect = [];
                    invenId = invenText = '';
                    $('.pricesClass').val('');
                    $('#unitSelect').val('');
                    $('#qtySelect').val('');
                    $('#priceSelect').val('');
                    $('#pricePurchasesSelect').val('');
                    $('#expSelect').val(now);
                    $('#invenData').val(null);
                    renderList()
                    selectedInit();
                } else
                    alert('Silahkan isi kolom jumlah, harga dan expired.')
            } else 
                alert('Silahkan pilih barang dahulu.')
        })
    });

    function renderList() {
        $('#listPicking').html('');
        $.each(invenSelected, (k, v) => {
           
            const subtotal = eval(v.qty) * eval(v.pricePurchases);
            let detailHtml = '';
            $.each(v.prices, (l, w) => {      
                detailHtml += `<b>Hrg. Jual ${w.name}:</b> ${w.price}<br>`
            })

            $('#listPicking').append(
                `<li class='list-group-item d-flex justify-content-between align-items-center' id='liConList-${v.id}'>
                    <span>${v.name} (exp: ${v.exp}) <br>${detailHtml}</span>
                    <input type='hidden' class='tes'name='iventories[${v.id}]' value='${JSON.stringify(v).replace(/\'/g, "")}'                   
                    <span class='float-right'>${v.qty} ${v.unit} 
                    @php( $userRoles = json_decode(Auth::user()->roles) )
                    @if(in_array("OWNER", $userRoles) )
                    x ${convertToRp(v.pricePurchases)} <b>${convertToRp(subtotal)}</b> 
                    @endif
                    <button type='button' data-id='${v.id}' class='btn btn-icon btn-sm btn-danger btnDeleteList'><i class='fa fa-trash'></i></button></span>
                 </li>`
            )
            console.log('tes',$('.tes').val());   
 
        })
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
                invenId = $("#invenData option:selected").val();
                invenText = $("#invenData option:selected").text();

                data = $("#invenData").select2('data')[0];
                console.log('dataIvenData', data);

                $('#unitSelect').val(data.unit)
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
                invenId = $("#invenData option:selected").val();
                invenText = $("#invenData option:selected").text();

                data = $("#invenData").select2('data')[0];
                console.log('dataIvenData (nodata)', data);
                
                $('#unitSelect').val(data.unit)
                $('#qtySelect').focus();
            });
        }
    }

    $(document).on('click', '.btnListSelect', function(ev) {
        const selectedId = $(this).data('id');
        let findFirst = invenCollect.find(x => x.id === selectedId)

        invenId = findFirst.id;
        invenText = findFirst.name;
        const newDataList = { id: eval(invenId), text: invenText, unit: findFirst.unit, selected: true };
        console.log('newDataList', newDataList);
        
        selectedInit(newDataList);

        $('#unitSelect').val(findFirst.unit);
        $('#myModalIven').modal('hide');
        $('#qtySelect').focus();
    })

    $(document).on('click', '.btnDeleteList', function (ev) {
        const id = $(this).data('id');
        invenSelected = invenSelected.filter((x) => { if (x.id != id) return x } )
        console.log('invenSelected after delete', invenSelected);
        
        renderList()
    })
</script>
@endsection