@extends("layouts.app")

@section('title', '| Rute')
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
        <h1>Form Rute</h1>
    </div>
    <div class="section-body">
        <form id="routesForm" action="@if(!empty($data)) {{ route('routes.update', [$data->id]) }} @else {{ route('routes.store') }} @endif" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
            @if(!empty($data))
            <input type="hidden" value="PUT" name="_method">
            @endif
            
            <div class="coloum">
                <div class="row">
                    @if (session('status'))
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif               
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="name">Nama Rute</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="@if($data){{ $data->name }}@else{{ old('name') }}@endif" autofocus>
                            @error('name')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                        </div>                    
                    </div>
                    <div class="col-md-8">
                        <h5>Pencarian Partner</h5>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select id="partnerData" class="form-control">
                                        <option value="">Ketikkan nama Partner</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-md btn-primary btnFindPartner" title="Daftar Partner" type="button"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                <small><a target="_blank" href="{{ url('partners/create') }}"><i>Tambah Partner baru</i></a></small>
                            </div>
                            <div class="col-md-2">
                                <input type="number" id="order" name="order" class="form-control" min="0" placeholder="Urutan">
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="button" title="Tambahkan ke daftar" class="btn btn-md btn-primary btn-block addListSelect"><i class="fas fa-plus"></i> Tambahkan</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <hr>
                        <h5 class="text-center">Daftar Partner</h5>
                        <div>                       
                            <input type="hidden" name="partners" class="form-control  @error('partners') is-invalid @enderror">
                            @error('partners')
                                <h5 class="text-center invalid-feedback">* {{ $message }}</h5>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <ul class="list-group" id="listPicking">
                                    @if(!empty($data->details))             
                                        @foreach($data->details as $detail)
                                        <li class="list-group-item d-flex justify-content-between align-items-center" id="list{{$detail->partners_id}}">
                                            <span><b>({{ $detail->order }})</b> {{ $detail->partner->name }}</span>
                                            <input type="hidden" name="partners[{{$detail->partners_id}}]" value="{{$detail->partners_id}}">
                                            <span class="float-right">({{$detail->partner->address}}) <button type="button" data-id="{{$detail->partners_id}}" class="btn btn-icon btn-sm btn-danger btnDeleteList"><i class="fa fa-trash"></i></button></span>
                                        </li>
                                        @endforeach
                                    @else                            
                                    <li class="list-group-item d-flex justify-content-between align-items-center">Belum ada partner dipilih.</li>
                                    @endif
                                </ul>
                                <br>
                                <!-- <h5 class="float-left">Total</h5><h5 class="float-right">0</h5> -->
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <hr>
                        <br>
                        <a class="btn btn-lg btn-danger" href="{{ url('routes') }}">Batal</a>
                        <button type="submit" class="btn btn-lg btn-success float-right btnGrandSave">Simpan Data</button>
                        <h6 class="text-muted float-right loadingText">Loading...</h6>
                    </div>
                </div>
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

@endsection

@section('footer-scripts')
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script>
    let partnersCollect = listSelected = invenCollect = partnerSelected = [];
    let partnerId = partnerText = invenId = invenText = invenUnit = '';
    partnerSelected = JSON.parse(`{!! $oldPartners !!}`)

    console.log('partnerSelected', partnerSelected);
    
    $('.loadingText').hide();
    $(document).ready(function () {
        selectedInitPartner();
        $('.btnFindPartner').click(function(e) {
            partnersCollect = [];
            $.get("{{ url('services/partners') }}", { do: 'ajaxall', noroutes: true }, function(res) {
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
  
        $('.addListSelect').click(function(e) {
            if (partnerId) {   
                const order = $('#order').val();
                if (order && order > 0) {
                    const findBeforeOrder = partnerSelected.find((x) => x.order == order);
                    console.log('findBeforeOrder',findBeforeOrder);

                    if (findBeforeOrder) {
                        alert(`Data urutan ${order} sudah digunakan`)
                    } else {
                        $.get("{{ url('services/partners') }}", { do: 'getPartners', id: partnerId }, function(result) {
                            if (result) {
                                const newData = { id: partnerId, name: result.name, address: result.address, order: order}
                                console.log('partnerSelected', typeof partnerSelected, partnerSelected );
                              
                                const findBefore = partnerSelected.find((x) => x.id == partnerId);
                                console.log('newData',newData);
                                
                                if (findBefore) {
                                    partnerSelected = partnerSelected.map((x) => { if (x.id == partnerId) return newData; else return x; } )
                                } else {
                                    partnerSelected.push(newData);
                                }
        
                                partnerId = '';
                                $('#partnerData').val(null);
                                $('#order').val(eval(order) + 1);
                                renderList()
                                selectedInitPartner();
                            }
                        });
                    }
                } 
                else 
                    alert('Input urutan minimal 1')
            } else
                alert('Silahkan pilih partner dahulu.')
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

    $(document).on('click', '.btnDeleteList', function (ev) {
        const id = $(this).data('id');
        partnerSelected = partnerSelected.filter((x) => { if (x.id != id) return x } )
        console.log('partnerSelected after delete', partnerSelected);

        renderList()
    })  

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#routesForm').submit(function(ev) {
        ev.preventDefault();
  
        if (!$('#name').val()) alert('Mohon input Nama Rute')
        // else if (!$('#partnerData').val()) alert('Mohon pilih partner')   
        else if (!partnerSelected || partnerSelected.length <= 0) alert('Mohon isi daftar partner')
        else {
            spinner.show();
            $("#routesForm :input").prop("readonly", true);
            $("#routesForm :button").prop("disabled", true);

            $.post($(this).attr('action'), $(this).serialize(), function(res) {
                console.log(res);
                spinner.hide();
                if (res.status) {
                    alert(res.msg)
                    $("#routesForm :input").prop("readonly", false);
                    $("#routesForm :button").prop("disabled", false);

                    if (res.link) location.href = res.link;
                    else location.reload();
                }
                else {
                    alert(res.msg);
                    $("#routesForm :input").prop("readonly", false);
                    $("#routesForm :button").prop("disabled", false);
                }
            }).fail(function(xhr, status, error) {
                console.log(error);
                spinner.hide();
                alert('Gagal, silahkan reload dan coba lagi.');
                $("#routesForm :input").prop("readonly", false);
                $("#routesForm :button").prop("disabled", false);
            });
        }
    })

    function renderList() {
        $('#listPicking').html('');
        $.each(partnerSelected, (k, v) => {
            console.log(v.id);
            $('#listPicking').append(
                `<li class="list-group-item d-flex justify-content-between align-items-center" id="liConList-${v.id}">
                    <span><b>(${v.order})</b> ${v.name}</span>
                    <input type="hidden" name="partners[${v.id}]" value="${v.id}|${v.order}">
                    <span class="float-right">(${v.address}) <button type="button" data-id="${v.id}" class="btn btn-icon btn-sm btn-danger btnDeleteList"><i class="fa fa-trash"></i></button></span>
                </li>`
            )
        })
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
                    data: { do: 'ajaxselect2', noroutes: true },
                    delay: 50,
                    data: function(params) {
                        return {
                            keyword: params.term,
                            do: 'ajaxselect2',
                            noroutes: true
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
                    data: { do: 'ajaxselect2', noroutes: true },
                    delay: 50,
                    data: function(params) {
                        return {
                            keyword: params.term,
                            do: 'ajaxselect2',
                            noroutes: true
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
   
</script>
@endsection
