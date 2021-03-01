@extends('layouts.app')


@section('title', ' | Log StatusPerangkat')
@section('content')
{{-- <section class="section"> --}}
{{-- <div class="section-header"> --}}
{{-- <h1>Perangkat</h1> --}}
{{-- </div> --}}


<div class="card">
    <div class="card-header">
        <h4>Log Status Perangkat</h4>
    </div>
</div>



{{-- </section> --}}

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('logstatus') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i>
                                Reload</a>
                            <!-- <a href="{{ route('logstatus.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Penjualan (Invoice) Baru</a> -->
                        </div>
                        @if (session('status'))
                        <div class="alert alert-warning alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            {{ session('status') }}
                        </div>
                        @endif
                        <hr class="my-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Date Time</th>
                                        <th>Nama Perangkat</th>
                                        <th>IP Perangkat</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $data->updated_at }}</td>
                                        <td>{{ $data->nama_perangkat }}</td>
                                        <td>{{ $data->ip_perangkat }}</td>
                                        {{-- <td>
                                            @if($data->keterangan == 'UP')
                                            <p class="text-center"><label
                                                    class="badge badge-success">{{strtoupper($data->keterangan)}}</label>
                                            </p>
                                            @elseif($data->keterangan == 'DOWN')
                                            <p class="text-center"><label
                                                    class="badge badge-danger">{{strtoupper($data->keterangan)}}</label></p>
                                            @endif

                                        </td> --}}

                                        {{-- $health_status = Ping::check($dt);
                                        if ($health_status == 200) {
                                        return 'UP';
                                        // dd($health);
                                        } else {
                                        return 'DOWN';
                                        } --}}

                                        {{-- <td>
                                            {{ Ping::check($data->ip_perangkat) }}
                                            @if('{{ Ping::check($data->ip_perangkat) }}' == 200 )
                                            <p class="text-center"><label
                                                    class="badge badge-success">UP</label>
                                            </p>
                                            @elseif('{{ Ping::check($data->ip_perangkat)  }}' != 200 )
                                            <p class="text-center"><label
                                                    class="badge badge-danger">DOWN</label></p>
                                            @endif
                                        </td> --}}

                                        <td>
                                            @if (Ping::check($data->ip_perangkat) == 200)
                                            <p class="text-center"><label
                                                class="badge badge-success">UP</label></p>
                                            </p>
                                            @else
                                            <p class="text-center"><label
                                                class="badge badge-danger">DOWN</label></p>
                                            </p>
                                            @endif
                                        
                                        </td>

                                        <td>{{ $data->gedung }} </td>
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
    $(document).ready(function (e) {
        $('.btnFindNoInvoice').click(function (e) {
            filter();
        });

        $('#noinvoiceFind').on('keypress', function (e) {
            if (e.which == 13) {
                filter();
            }
        });
    })

    function filter() {
        let noinvoice = $('#noinvoiceFind').val();
        noinvoice = noinvoice ? noinvoice : '';

        location.href = "{{ url('perangkat') }}?noinvoice=" + noinvoice;
    }
</script>
@endsection