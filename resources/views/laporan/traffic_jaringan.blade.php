@extends('layouts.app') @section('title', '') @section('content')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan > Traffic Jaringan</h4>
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
                            href="{{ url('status_perangkat') }}" role="tab"><i class="fa fa-list mr-2"></i>Status
                            Perangkat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('traffic_jaringan') ? 'active' : null }}"
                            href="{{ url('traffic_jaringan') }}" role="tab"><i class="far fa-file-alt"></i>Traffic
                            Jaringan</a>
                    </li>

                </ul>

                <!-- tab panel -->

            </div>

            <!-- body card -->
            <div class="card-body">
                <div class="tab-content">

                    <div class="tab-pane {{ request()->is('traffic_jaringan') ? 'active' : null }}"
                        id="{{!! url('traffic_jaringan') !!}}" role="tabpanel"></div>

                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">

                                <div class="row">
                                    <div class="col-md-8">
                                        
                                        {{-- @php $perangkat = Request::get('perangkat') @endphp
                                        <select name="perangkat" id="perangkat" class="form-control form-control-sm" onchange="this.value.submit">
                                            <option value="">Semua Status</option>
                                            <option value="1" {{ $perangkat == 1 }}>Server 1</option>
                                            <option value="2"  {{ $perangkat == 2 }}>Server 2</option>
                                        </select> --}}

                                        <div class="form-group">
                                            
                                            <label class="form-label" for="name">Pilih Perangkat : </label>
                                            @error('perangkat')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                            
                                            <select name="perangkat" id="perangkat" class="form-control" onchange="this.form.value" required>
                                                
                                                @foreach ($perangkatList as $perangkat)                                
                                                <option> 
                                                    {{ $perangkat }}
                                                </option> 
                                                @endforeach
                                            </select>
                                            
                                        </div>
                                        
                                    </div>

                                </div>

                                <br>
                                <br>

                                <div class="col-md-8">
                                    <div class="chart-container">
                                        <canvas id="lineChart"></canvas>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>

            </div>

        </div>







    </div>


</div>


<script>
    var lineChart = document.getElementById('lineChart').getContext('2d');
    var myLineChart = new Chart(lineChart, {
        type: 'line',
        data: {
            labels: [
                @foreach($traffic10 as $waktu)
                    "{{date('H:i:s',strtotime($waktu->created_at))}}",
                @endforeach
            
            ],
            
            
            
            datasets: [
                {
                label: "Data InOctet",
                borderColor: "#1d7af3",
                pointBorderColor: "#FFF",
                pointBackgroundColor: "#1d7af3",
                pointBorderWidth: 2,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 1,
                pointRadius: 3,
                backgroundColor: 'rgba(62, 153, 250,0.4)',
                fill: false,
                borderWidth: 1.5,
                data: [
                   
                    @foreach($traffics10 as $nilai)
                    @php
                    $nilai = $nilai['nilai'];
                    
                    @endphp '{{"$nilai"}}',
                    @endforeach

                ]
            },
            {
                label: "Data OutOctet",
                borderColor: "#FFd7af3",
                pointBorderColor: "#FFF",
                pointBackgroundColor: "#1d7af3",
                pointBorderWidth: 2,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 1,
                pointRadius: 3,
                backgroundColor: 'rgba(62, 153, 250,0.4)',
                fill: false,
                borderWidth: 1.5,
                data: [
                   
                    @foreach($traffics20 as $nilai)
                    @php
                    $nilai = $nilai['nilai'];
                    
                    @endphp '{{"$nilai"}}',
                    @endforeach

                ]
            }
            ],

        },
        options: {
            legend: {
                display: false                
            },
            scales: {
                xAxes: [{
                gridLines: {
                    display: true,
                    color: "gray",
                    borderDash: [1, 3],
                },
                scaleLabel: {
                    display: true,
                    labelString: "Time",
                    fontColor: "green"
                }
                }],
                yAxes: [{
                gridLines: {
                    display: false,
                    color: "gray",
                    borderDash: [1, 3],
                },
                ticks: {
                    display: true,
                    suggestedMin: 1000000,
                    suggestedMax: 5000000,
                },
                scaleLabel: {
                    display: true,
                    labelString: "Traffic (Kbps)",
                    fontColor: "green"
                }
                }]
            }
        }
    });
</script>

<script>
    function autoRefreshPage() {
        window.location = window.location.href;
        }
        setInterval('autoRefreshPage()', 35000);
</script>

<script>
    

    function filter() {
       

        let perangkat = $('#perangkatFind').val();
        perangkat = perangkat ? perangkat : '';

        // location.href = "{{ url('users') }}?name=" + name + "&email=" + email + "&status=" + status;
    }
</script>
@endsection