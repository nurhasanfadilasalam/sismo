@extends('layouts.app') @section('title', '') @section('content')
@section('content')
@include('sweet::alert')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan > Status Perangkat</h4>
                </div>
            </div>
        </div>

    </div>

    <div class="content">
        <div class="clearfix"></div>

        <div class="card">

            <div class="card-header">
                <ul class="nav nav-tabs align-items-end card-header-tabs w-100" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('status_perangkat') ? 'active' : null }}"
                            href="{{ url('status_perangkat') }}" role="tab">
                            <i class="fa fa-list mr-2"></i>Status Perangkat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('traffic_jaringan') ? 'active' : null }}"
                            href="{{ url('traffic_jaringan') }}" role="tab">
                            <i class="far fa-file-alt"></i>Traffic Jaringan</a>
                    </li>
                </ul>
                <!-- tab panel -->
            </div>

            <!-- body -->
            {{-- <div class="card-body"> --}}
            <div class="tab-content">
                <div class="tab-pane {{ request()->is('status_perangkat') ? 'active' : null }}"
                    id="{{!! url('status_perangkat') !!}}" role="tabpanel"></div>
            </div>

            {{-- </div> --}}

            <!-- MULAI CONTAINER -->
            {{-- <div class="content"> --}}
                {{-- <div class="card"> --}}
                {{-- head --}}
                <div class="card-header">
                    {{-- <h4>
                            Jumlah Server : {{ $datajumlah}}
                    </h4> --}}

                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center pb-2">
                                            <div class="dot-indicator bg-danger mr-2"></div>
                                            <p class="mb-0">Total Server</p>
                                        </div>
                                        <h4 class="font-weight-semibold">{{ $datajumlah}}</h4>
                                        <div class="progress progress-md">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center pb-2">
                                            <div class="dot-indicator bg-danger mr-2"></div>
                                            <p class="mb-0">Perangkat Status Down</p>
                                        </div>
                                        <h4 class="font-weight-semibold">
                                            {{ $downServer }}
                                        </h4>
                                        <div class="progress progress-md">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{($datajumlah*10)-($upServer*10) }}%"
                                                aria-valuenow="{{($datajumlah*10)-($downServer*10) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center pb-2">
                                            <div class="dot-indicator bg-success mr-2"></div>
                                            <p class="mb-0">Perangkat Status Up</p>
                                        </div>
                                        <h4 class="font-weight-semibold">
                                            {{ $upServer }}
                                        </h4>
                                        <div class="progress progress-md">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{($datajumlah*10)-($downServer*10) }}%"
                                                aria-valuenow="{{($datajumlah*10)-($upServer*10) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- body --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>SSID</th>
                                    <th>Gedung</th>
                                    <th>Date Time </th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($datas as $key => $data)
                                <tr>
                                    <td>{{ $datas->firstItem() + $key }}</td>
                                    <td>{{ $data->nama_perangkat }}</td>
                                    <td>{{ $data->gedung }}</td>
                                    <td>{{ $data->updated_at }}</td>
                                    <td>{{ $data->ip_perangkat }}</td>
                                    <td>

                                        <center>
                                            @if (Ping::check($data->ip_perangkat) == 200)
                                            {{-- <audio autoplay>
                                                <source src='https://interactive-examples.mdn.mozilla.net/media/cc0-audio/t-rex-roar.mp3' type='audio/mp3'>
                                            </audio> --}}

                                            <i class="fa fa-caret-up"
                                                style="font-size:21px; color:rgb(111, 255, 0)"></i>

                                            <p class="text-center">
                                                {{-- <i class="fa fa-caret-up" style="font-size:26px; color:green"></i> --}}
                                                {{-- <br> --}}
                                                {{-- <label class="badge badge-success">LIVE</label> --}}
                                                <label style="color:rgb(97, 221, 2)">UP</label>
                                            </p>

                                            @else
                                            
                                            <audio autoplay>
                                                <source
                                                    src='https://interactive-examples.mdn.mozilla.net/media/cc0-audio/t-rex-roar.mp3'
                                                    type='audio/mp3'>
                                            </audio>
                                            

                                            <i class="fa fa-caret-down" style="font-size:21px; color:red"></i>

                                            <p class="text-center">
                                                {{-- <i class="fa fa-caret-down" style="font-size:26px; color:red"></i> --}}
                                                {{-- <br> --}}
                                                <label style="color:red">DOWN</label>
                                            </p>
                                            {{-- <p class="text-center"><label class="badge badge-danger">DIE</label></p> --}}

                                            @endif
                                        </center>

                                    </td>
                                    <td>

                                        <center>
                                            <a href="{{ route('laporan.show', [$data->id]) }}"
                                                class="btn btn-info text-white btn-sm" title="Detail">
                                                <i class="fas fa-poll-h"></i></a>
                                            <p class="text-center">Detail</p>
                                        </center>
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
                {{-- </div> --}}
            {{-- </div> --}}
            <!-- AKHIR CONTAINER -->







        </div> <!-- MULAI MODAL FORM TAMBAH/EDIT-->
        <div class="modal fade" id="tambah-edit-modal" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-judul"></h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="form-tambah-edit" name="form-tambah-edit" class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-12">

                                    <input type="hidden" name="id" id="id">

                                    <div class="form-group">
                                        <label for="name" class="col-sm-12 control-label">Nama Pegawai</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="nama_pegawai"
                                                name="nama_pegawai" value="" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-sm-12 control-label">Jenis Kelamin</label>
                                        <div class="col-sm-12">
                                            <select name="jenis_kelamin" id="jenis_kelamin"
                                                class="form-control required">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-sm-12 control-label">E-mail</label>
                                        <div class="col-sm-12">
                                            <input type="email" class="form-control" id="email" name="email" value=""
                                                required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-sm-12 control-label">Alamat</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" name="alamat" id="alamat"
                                                required></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-offset-2 col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-block" id="tombol-simpan"
                                        value="create">Simpan
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- AKHIR MODAL -->


        <!-- MULAI MODAL KONFIRMASI DELETE-->

        <div class="modal fade" tabindex="-1" role="dialog" id="konfirmasi-modal" data-backdrop="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">PERHATIAN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><b>Jika menghapus Pegawai maka</b></p>
                        <p>*data pegawai tersebut hilang selamanya, apakah anda yakin?</p>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" name="tombol-hapus" id="tombol-hapus">Hapus
                            Data</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- AKHIR MODAL -->


        <!-- LIBARARY JS -->
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>

        <script type="text/javascript" language="javascript"
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

        <script type="text/javascript" language="javascript"
            src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"
            integrity="sha256-sPB0F50YUDK0otDnsfNHawYmA5M0pjjUf4TvRJkGFrI=" crossorigin="anonymous"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.js"
            integrity="sha256-siqh9650JHbYFKyZeTEAhq+3jvkFCG8Iz+MHdr9eKrw=" crossorigin="anonymous"></script>


        <!-- AKHIR LIBARARY JS -->


        <!-- JAVASCRIPT -->


        <!-- JAVASCRIPT -->


    </div>





</div>

</div>

<script>
    function autoRefreshPage() {
        window.location = window.location.href;
        }
        setInterval('autoRefreshPage()', 20000);
</script>

<script>
    function playSound(url) {
        var audio = new Audio(url);
        // const audio = new Audio(url);
        audio.play();
    }
</script>
@endsection