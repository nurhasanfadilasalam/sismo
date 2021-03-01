@extends('layouts.app') @section('title', '')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-right">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>DASHBOARD</h4>
                </div>
            </div>
        </div>

    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="row">
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
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{($downServer/$datajumlah)*100 }}%"
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
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($upServer/$datajumlah)*100 }}%"
                                                aria-valuenow="{{($datajumlah*10)-($upServer*10) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-6 grid-margin stretch-card">
                    <div class="card text-black">
                        <div class="card-body">
                            <div class="d-flex justify-content-between pb-2 align-items-center">
                                <h2 class="font-weight-semibold mb-0">100.000</h2>
                                <div class="icon-holder">
                                    <i class="mdi mdi-briefcase-outline"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h5 class="font-weight-semibold mb-0">Total Stok Farmasi</h5>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-md-12 grid-margin stretch-card">
                    {{-- <div class="card"> --}}
                        <div class="card-body pb-0">

                            @if (Ping::check('wwww.google.com') == 200)
                            <div class="alert alert-success" role="alert">
                                <p class="mb-0">Connected </p> Terakhir Update: 2021-01-25 18:09:09
                             {{-- {{($updatewaktu)}} --}}
                            </div>
                            
                            @else

                            <div class="alert alert-danger" role="alert">
                                <p class="mb-0">Gagal Terhubung Dengan Server</p> Terakhir Update: 2021-01-25 18:09:09
                             {{-- {{($updatewaktu)}} --}}
                            </div>


                            @endif

                            {{-- <div class="d-flex justify-content-between">
                                <h4 class="card-title mb-0">Grafik Server 1</h4>
                            </div> --}}

                        </div>
                        
                        <div class="card border">

                            <div class="card-header">
                                <div class="card-title">Grafik Server 1</div>
                            </div>

                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="lineChart"></canvas>
                                </div>
                                <h5>Keterangan :</h5>
                                <ul>
                                    <li>x : satuan waktu per menit</li>
                                    <li>y : satuan grafik kbps</li>
                                    {{-- <li>Suhu optimum 25 - 30&#176;C</li> --}}
                                </ul>
                            </div>
                        </div>

                    {{-- </div> --}}
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
                @foreach($traffic1 as $waktu)
                    "{{date('H:i:s',strtotime($waktu->created_at))}}",
                @endforeach
            
            ],
            
            
            
            datasets: [{
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
                   
                    @foreach($traffics1 as $nilai)
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
                   
                    @foreach($traffics2 as $nilai)
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
                    suggestedMin: 0,
                    suggestedMax: 50,
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
        setInterval('autoRefreshPage()', 15000);
</script>
@endsection

{{-- @section('js')

@endsection --}}