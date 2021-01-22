@extends('layouts.app')

@section('title', '| Pengiriman')
@section('content')
<link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
<style>
    .select2-container {
        width: 86% !important;
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
        <h1>Pengiriman</h1>
    </div>
    <div class="section-body">
        <form id="shippingForm" action="{{ route('shippings.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
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
                        <label for="driverData">Driver</label>
                        <div class="input-group">
                            <select name="driver" id="driverData" class="form-control @error('driver') is-invalid @enderror driverData"></select>
                            <div class="input-group-append">
                                <button class="btn btn-md btn-primary btnFindDriver" title="Daftar Driver" type="button"><i class="fas fa-search"></i></button>
                            </div>
                            @error('driver')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="tracking_number">No. Tracking</label>
                        <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" name="tracking_number" id="tracking_number" value="{{ date('dmy') }}{{ $last + 1 }}">
                        @error('tracking_number')
                            <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                     <div class="form-group">
                        <label for="routesData">Rute Pengiriman</label>
                        <div class="input-group">
                            <select name="route" id="routesData" class="form-control @error('route') is-invalid @enderror routesData"></select>
                            <div class="input-group-append">
                                <button class="btn btn-md btn-primary btnFindRoute" title="Daftar Rute" type="button"><i class="fas fa-search"></i></button>
                            </div>
                            @error('route')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <hr class="my-3">
            <div class="coloum">
                <div class="col-md-12">
                    <h5>Pencarian PO yang akan dikirim</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <select id="preorderData" class="form-control">
                                    <option value="">Pilih rute pengiriman dahulu </option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-md btn-primary btnFindPreorder" title="Daftar PO" type="button"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <small><a target="_blank" href="{{ url('preorder/create') }}"><i>Buat PO baru</i></a></small>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" id="partnerIdSelect" value="">
                            <input type="text" id="partnerNameSelect" class="form-control" placeholder="Partner" value="" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="partnerAddressSelect" class="form-control" placeholder="Alamat" value="" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-block btn-primary addtoListSelect"><i class="fas fa-plus"></i> Tambahkan</button>
                        </div>
                    </div>
                    <h5 class="text-center">Daftar PO terpilih</h5>
                    <div>
                        <input type="hidden" name="preorders" class="form-control  @error('preorders') is-invalid @enderror">
                        @error('preorders')
                            <h5 class="text-center invalid-feedback">* {{ $message }}</h5>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <ul class="list-group" id="listPicking">
                                <li class="list-group-item d-flex justify-content-between align-items-center">Belum ada po dipilih.</li>
                            </ul>
                            <br>
                            <!-- <h5 class="float-left">Total</h5><h5 class="float-right">0</h5> -->
                        </div>
                        <div class="col-md-2"></div>

                    </div>
                </div>
                <hr>

                <br>
                <a class="btn btn-lg btn-danger" href="{{ url('shippings') }}">Batal</a>
                <button type="submit" class="btn btn-lg btn-success float-right">Simpan</button>
            </div>
        </form>
    </div>
</section>
<div class="modal fade" tabindex="-1" role="dialog" id="myModalDriver">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Data Driver</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="bodyAllDriver">
        </div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="myModalRoute">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Data Rute</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="bodyAllRoute">
        </div>
        <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="myModalPreorder">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Data PO</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body"id="bodyAllPreorder"></div>
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
    let driversCollect = routesCollect = preorderCollect = preorderSelected = [];
    let now = "{{ date('Y-m-d') }}";
    let driverId = driverText = '';
    let routeId = routeText = '';
    let preorderId = preorderNum = preorderPartnerId = preorderPartnerName = preorderPartnerAddress = '';

    $(document).ready(function () {
        selectedInitDriver();
        selectedInitRoutes();
        $('#preorderData, .btnFindPreorder').attr('disabled', true);

        $('.btnFindDriver').click(function(e) {
            driversCollect = [];
            $.get("{{ url('services/drivers') }}", { do: 'ajaxall' }, function(res) {
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
                        driversCollect.push(vl);

                        newHtml += `<tr>
                            <td>${vl.name}</td>
                            <td>${vl.phone ? vl.phone : ''}</td>
                            <td>${vl.email ? vl.email : ''}</td>
                            <td>${vl.address ? vl.address : ''}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info btnListDriverSelect" data-id="${vl.id}" title="Pilih : ${vl.name}">pilih</button>
                            </td>
                        </tr>`
                    }) 

                    newHtml += `</tbody></table>`
                    $('#bodyAllDriver').html(newHtml)
                    console.log('driversCollect', driversCollect);
                } else
                    $('#bodyAllDriver').html('Data empty.')
                
                $('#myModalDriver').modal('show');
            })
        })

        $('.btnFindRoute').click(function(e) {
            routesCollect = [];
            $.get("{{ url('services/routes') }}", { do: 'ajaxall' }, function(res) {
                if (res) {
                    let newHtml = `<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`

                    $.each(res, (key, vl) => {
                        routesCollect.push(vl);

                        newHtml += `<tr>
                            <td>${vl.name}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info btnListRouteSelect" data-id="${vl.id}" title="Pilih : ${vl.name}">pilih</button>
                            </td>
                        </tr>`
                    }) 

                    newHtml += `</tbody></table>`
                    $('#bodyAllRoute').html(newHtml)
                    console.log('routesCollect', routesCollect);
                } else
                    $('#bodyAllRoute').html('Data empty.')
                
                $('#myModalRoute').modal('show');
            })
        })
        
        $('.btnFindPreorder').click(function(e) {
            if (routeId) {
                preorderCollect = [];
                $.get("{{ url('services/preorders') }}", { do: 'ajaxall', route: routeId }, function(res) {
                    if (res) {
                        let newHtml = `<table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No. PO</th>
                                    <th>Partner</th>
                                    <th>Keterangan</th>
                                    <th>Komponen Barang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead><tbody>`
    
                        $.each(res, (key, vl) => {
                            preorderCollect.push(vl);
                            console.log('details', vl.details);
                            let componentShow = ''
                            if (vl.details) {
                                componentShow += '<ul style="margin-left: -30px;">'
                                $.each(vl.details, (k, v) => {
                                    componentShow += `<li>${v.name}, ${v.qty} ${v.unit}</li>`
                                });
                                componentShow += '</ul>'
                            }
    
                            newHtml += `<tr>
                                <td>${vl.date}</td>
                                <td>${vl.number}</td>
                                <td>
                                    <b>${vl.partnerName}</b><br>
                                    <span>Telp : ${vl.partnerPhone}</span><br>
                                    <span>Alamat : ${vl.partnerAddress}</span><br>
                                </td>
                                <td>${vl.notes}</td>
                                <td>${componentShow}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info btnListSelect" data-id="${vl.id}" title="Pilih : ${vl.name}">pilih</button>
                                </td>
                            </tr>`
                        }) 
    
                        newHtml += `</tbody></table>`
                        $('#bodyAllPreorder').html(newHtml)
                        console.log('preorderCollect', preorderCollect);
                    } else
                        $('#bodyAllPreorder').html('Data empty.')
                    
                    $('#myModalPreorder').modal('show');
                })
            } else 
                alert('Silahkan pilih rute pengiriman dahulu');
        })

        $('.addtoListSelect').click(function(e) {
            if (preorderId) {
                const newData = { 
                    id: preorderId, 
                    number: preorderNum, 
                    partnerId: preorderPartnerId,
                    partnerName: preorderPartnerName,
                    partnerAddress: preorderPartnerAddress,
                }
                const findBefore = preorderSelected.find((x) => x.id == preorderId);
                
                if (findBefore) {
                    preorderSelected = preorderSelected.map((x) => { if (x.id == preorderId) return newData; else return x; } )
                } else {
                    preorderSelected.push(newData);
                }
                
                preorderCollect = [];
                preorderId = preorderNum = preorderPartnerId = preorderPartnerName = preorderPartnerAddress = '';
                $('#partnerIdSelect, #partnerNameSelect, #partnerAddressSelect').val('');

                renderList()
                selectedInitDetails();
            } else 
                alert('Silahkan pilih po dahulu.')
        })
    });

    $(document).on('click', '.btnListDriverSelect', function(ev) {
        const selectedId = $(this).data('id');
        let findFirst = driversCollect.find(x => x.id === selectedId)

        driverId = findFirst.id;
        driverText = findFirst.name;
        const newDataList = { id: eval(driverId), text: driverText, selected: true };
        selectedInitDriver(newDataList);
        $('#myModalDriver').modal('hide');
    })

    $(document).on('click', '.btnListRouteSelect', function(ev) {
        const selectedId = $(this).data('id');
        let findFirst = routesCollect.find(x => x.id === selectedId)

        routeId = findFirst.id;
        routeText = findFirst.name;
        const newDataList = { id: eval(routeId), text: routeText, selected: true };
        selectedInitRoutes(newDataList);
        selectedInitDetails()
        $('#myModalRoute').modal('hide');
    })

    $(document).on('click', '.btnListSelect', function(ev) {
        const selectedId = $(this).data('id');
        let findFirst = preorderCollect.find(x => x.id === selectedId)

        preorderId = findFirst.id;
        preorderNum = findFirst.number;
        preorderPartnerId = findFirst.partnerId;
        preorderPartnerName = findFirst.partnerName;
        preorderPartnerAddress = findFirst.partnerAddress;
        const newDataList = { 
            id: eval(preorderId), 
            text: preorderNum, 
            partnerId: preorderPartnerId,
            partnerName: preorderPartnerName,
            partnerAddress: preorderPartnerAddress,
            selected: true 
        };
        
        selectedInitDetails(newDataList);
        $('#myModalPreorder').modal('hide');
        $('#partnerIdSelect').val(preorderPartnerId);
        $('#partnerNameSelect').val(preorderPartnerName);
        $('#partnerAddressSelect').val(preorderPartnerAddress);
    })

    $(document).on('click', '.btnDeleteList', function (ev) {
        const id = $(this).data('id');
        preorderSelected = preorderSelected.filter((x) => { if (x.id != id) return x } )
        console.log('preorderSelected after delete', preorderSelected);
        
        renderList()
    })

    function renderList() {
        $('#listPicking').html('');

        if (preorderSelected.length) {
            $.each(preorderSelected, (k, v) => {
                $('#listPicking').append(
                    `<li class="list-group-item d-flex justify-content-between align-items-center" id="liConList-${v.id}">
                        <input type="hidden" name="preorders[${v.id}]" value="${v.id}">
                        <span><b>(PO: ${v.number})</b> ${v.partnerName}</span>
                        <span class="float-right">${v.partnerAddress} <button type="button" data-id="${v.id}" class="btn btn-icon btn-sm btn-danger btnDeleteList"><i class="fa fa-trash"></i></button></span>
                    </li>`
                )
            })
        } else {
            $('#listPicking').append('<li class="list-group-item d-flex justify-content-between align-items-center">Tidak ada data.</li>');
        }
    }

    function selectedInitDriver(data) {
        if (data) {
            console.log('selectedInitDriver(data)', data);
            $('#driverData').empty();
            $('#driverData').select2({
                data: [{ id: data.id, text: data.text, unit: data.unit, selected: true }],
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketikkan kata kunci driver [nama, telp, email, alamat] atau klik tombol cari',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/drivers') }}`,
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
                driverId = $("#driverData option:selected").val();
                driverText = $("#driverData option:selected").text();
            });
        } else {
            $('#driverData').empty();
            $('#driverData').select2({
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketikkan kata kunci driver [nama, telp, email, alamat] atau klik tombol cari',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/drivers') }}`,
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
                driverId = $("#driverData option:selected").val();
                driverText = $("#driverData option:selected").text();
            });
        }
    }

    function selectedInitRoutes(data) {
        if (data) {
            console.log('selectedInitRoutes(data)', data);
            $('#routesData').empty();
            $('#routesData').select2({
                data: [{ id: data.id, text: data.text, unit: data.unit, selected: true }],
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketikkan nama rute atau klik tombol cari',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/routes') }}`,
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
                routeId = $("#routesData option:selected").val();
                routeText = $("#routesData option:selected").text();
                selectedInitDetails();
            });
        } else {
            $('#routesData').empty();
            $('#routesData').select2({
                minimumInputLength: 2,
                allowClear: true,
                placeholder: 'Ketikkan nama rute atau klik tombol cari',
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) { return 1; }
                        if (a.text < b.text) { return -1; }
                        return 0;
                    });
                },
                ajax: {
                    dataType: 'json',
                    url: `{{ url('services/routes') }}`,
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
                routeId = $("#routesData option:selected").val();
                routeText = $("#routesData option:selected").text();
                selectedInitDetails();
            });
        }
    }

    function selectedInitDetails(data) {
        $('#preorderData, .btnFindPreorder').removeAttr('disabled');
        routeIdSelect = $("#routesData option:selected").val();

        if (routeIdSelect) {
            if (data) {
                console.log('selectedInit (data)', data);
                $('#preorderData').empty();
                $('#preorderData').select2({
                    data: [{ 
                        id: data.id, 
                        text: data.text, 
                        partnerId: data.partnerId,
                        partnerName: data.partnerName,
                        partnerAddress: data.partnerAddress,
                        selected: true 
                    }],
                    minimumInputLength: 2,
                    allowClear: true,
                    placeholder: 'Ketik nomor po / partner atau klik tombol cari',
                    sorter: function(data) {
                        return data.sort(function (a, b) {
                            if (a.text > b.text) { return 1; }
                            if (a.text < b.text) { return -1; }
                            return 0;
                        });
                    },
                    ajax: {
                        dataType: 'json',
                        url: `{{ url('services/preorders') }}`,
                        data: { do: 'ajaxselect2', route: routeIdSelect },
                        delay: 50,
                        data: function(params) {
                            return {
                                keyword: params.term,
                                do: 'ajaxselect2',
                                route: routeIdSelect
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
                    data = $("#preorderData").select2('data')[0];
                    console.log('dataIvenData (init with data)', data);

                    preorderId = $("#preorderData option:selected").val();
                    preorderNum = data.number;
                    preorderPartnerId = data.partnerId;
                    preorderPartnerName = data.partnerName;
                    preorderPartnerAddress = data.partnerAddress;
                    
                    $('#partnerIdSelect').val(data.partnerId);
                    $('#partnerNameSelect').val(data.partnerName);
                    $('#partnerAddressSelect').val(data.partnerAddress);
                });
            } else {
                $('#preorderData').empty();
                $('#preorderData').select2({
                    minimumInputLength: 2,
                    allowClear: true,
                    placeholder: 'Ketik nomor po / partner atau klik tombol cari',
                    sorter: function(data) {
                        return data.sort(function (a, b) {
                            if (a.text > b.text) { return 1; }
                            if (a.text < b.text) { return -1; }
                            return 0;
                        });
                    },
                    ajax: {
                        dataType: 'json',
                        url: `{{ url('services/preorders') }}`,
                        data: { do: 'ajaxselect2', route: routeIdSelect },
                        delay: 50,
                        data: function(params) {
                            return {
                                keyword: params.term,
                                do: 'ajaxselect2',
                                route: routeIdSelect
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
                    data = $("#preorderData").select2('data')[0];
                    console.log('selectedInit (nodata)', data);

                    preorderId = $("#preorderData option:selected").val();
                    preorderNum = data.number;
                    preorderPartnerId = data.partnerId;
                    preorderPartnerName = data.partnerName;
                    preorderPartnerAddress = data.partnerAddress;

                    $('#partnerIdSelect').val(data.partnerId);
                    $('#partnerNameSelect').val(data.partnerName);
                    $('#partnerAddressSelect').val(data.partnerAddress);
                });
            }
        } else 
            alert('Silahkan pilih rute pengiriman dahulu');
    }

    $('#shippingForm').submit(function(ev) {
        ev.preventDefault();

        if (!$('#date').val()) alert('Mohon input tanggal')
        else if (!$('#tracking_number').val()) alert('Mohon input nomor tracking')
        else if (!$('#driverData').val()) alert('Mohon pilih driver')
        else if (!$('#routesData').val()) alert('Mohon pilih rute pengiriman')
        else if (!preorderSelected || preorderSelected.length <= 0) alert('Mohon input daftar PO')
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
    })

</script>
@endsection