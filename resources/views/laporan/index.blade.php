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
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center pb-2">
                                        <div class="dot-indicator bg-danger mr-2"></div>
                                        <p class="mb-0">Perangkat Status Down</p>
                                    </div>
                                    <h4 class="font-weight-semibold">1</h4>
                                    <div class="progress progress-md">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-4 mt-md-0">
                                    <div class="d-flex align-items-center pb-2">
                                        <div class="dot-indicator bg-success mr-2"></div>
                                        <p class="mb-0">Perangkat Status Up</p>
                                    </div>
                                    <h4 class="font-weight-semibold">5</h4>
                                    <div class="progress progress-md">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 80%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="20"></div>
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

                            {{-- @if($status == 'down') --}}
                            <div class="alert alert-danger" role="alert">
                                <p class="mb-0">Gagal Terhubung Dengan Server</p> Terakhir Update: 2021-01-25 18:09:09
                                 {{-- {{($updatewaktu)}} --}}
                            </div>
                            {{-- @endif --}}

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

                {{-- <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mb-0">Pemeriksaan Hari ini</h4>
                            </div>
                            <h3 class="font-weight-medium">20</h3>
                        </div>
                        <canvas class="mt-n3" height="90" id="total-transaction"></canvas>
                    </div>
                </div> --}}


                {{-- next grafik --}}

                {{-- <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">10 Diagnosa Terbanyak</h4>
                            <canvas class="mt-4" height="100" id="market-overview-chart"></canvas>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>

        {{-- next 4 --}}
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title font-weight-semibold mb-0">Info Server : </h4>
                            <div class="row">

                                {{-- // server 1 --}}
                                <div class="col-12">
                                    <div class="wrapper border-bottom mb-2 pb-2">
                                        <h5 class="font-weight-semibold mt-4 mb-0">Server 1</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">
                                                <span>Router</span><br>
                                                <small>Status : Down Server</small>
                                            </p>
                                            <div class="dot-indicator bg-primary ml-auto"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <small><i><a href="#">Data Selengkapnya</a></i></small>
                                </div>

                                {{-- // server 2 --}}
                                <div class="col-12">
                                    <div class="wrapper border-bottom mb-2 pb-2">
                                        <h5 class="font-weight-semibold mt-4 mb-0">Server 2</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">
                                                <span>Router</span><br>
                                                <small>Status : Down Server</small>
                                            </p>
                                            <div class="dot-indicator bg-primary ml-auto"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <small><i><a href="#">Data Selengkapnya</a></i></small>
                                </div>


                                {{-- // server 3 --}}
                                <div class="col-12">
                                    <div class="wrapper border-bottom mb-2 pb-2">
                                        <h5 class="font-weight-semibold mt-4 mb-0">Server 3</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">
                                                <span>Router</span><br>
                                                <small>Status : Down Server</small>
                                            </p>
                                            <div class="dot-indicator bg-primary ml-auto"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <small><i><a href="#">Data Selengkapnya</a></i></small>
                                </div>

                                {{-- // server 4 --}}
                                <div class="col-12">
                                    <div class="wrapper border-bottom mb-2 pb-2">
                                        <h5 class="font-weight-semibold mt-4 mb-0">Server 4</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">
                                                <span>Router</span><br>
                                                <small>Status : Down Server</small>
                                            </p>
                                            <div class="dot-indicator bg-primary ml-auto"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <small><i><a href="#">Data Selengkapnya</a></i></small>
                                </div>


                                {{-- // server 5 --}}
                                <div class="col-12">
                                    <div class="wrapper border-bottom mb-2 pb-2">
                                        <h5 class="font-weight-semibold mt-4 mb-0">Server 5</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">
                                                <span>Router</span><br>
                                                <small>Status : Down Server</small>
                                            </p>
                                            <div class="dot-indicator bg-primary ml-auto"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <small><i><a href="#">Data Selengkapnya</a></i></small>
                                </div>


                                {{-- // server 6 --}}
                                <div class="col-12">
                                    <div class="wrapper border-bottom mb-2 pb-2">
                                        <h5 class="font-weight-semibold mt-4 mb-0">Server 6</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">
                                                <span>Router</span><br>
                                                <small>Status : Down Server</small>
                                            </p>
                                            <div class="dot-indicator bg-primary ml-auto"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <small><i><a href="#">Data Selengkapnya</a></i></small>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Warning Server</h4>

                            <div class="d-flex mt-3 py-2 border-bottom">
                                <span class="img-sm rounded-circle bg-danger text-white text-avatar"><i
                                        class="mdi mdi-pill"></i></span>
                                <div class="wrapper ml-2">
                                    <p class="mb-n1 font-weight-semibold">CPU</p>
                                    <small class="text-muted ml-auto">Stock : 10</small>
                                </div>
                            </div>

                            <small><i><a href="{{ # }}">Data Selengkapnya</a></i></small>
                        </div>
                    </div>
                </div> --}}

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

                {waktu: "08:00:20"},
                {waktu: "08:15:20"},
                {waktu: "08:30:20"},
                {waktu: "08:45:20"}
                
                // {"waktu": 08:10:20},
                // {"waktu": 08:15:20},
                // {"waktu": 08:20:20},
                // {"waktu": 08:25:20}
                // waktu: 08:10:20,
                // waktu: 08:11:20,
                // waktu: 08:12:20,
                // waktu: 08:13:20,


            ],
            datasets: [{
                label: "Data Payload (Download/Upload)",
                borderColor: "#1d7af3",
                pointBorderColor: "#FFF",
                pointBackgroundColor: "#1d7af3",
                pointBorderWidth: 2,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 1,
                pointRadius: 4,
                backgroundColor: 'rgba(62, 153, 250,0.4)',
                fill: true,
                borderWidth: 2,
                data: [

                    {
                        "08:00:20" : "10"
                    },
                    {
                        "08:15:20": "20"
                    },
                    {
                        "08:30:20" : "30" 
                    },
                    {
                        "08:45:20": "10"
                    }
                    // {
                    // nilai: "10",
                    // celcius: "C"    
                    // },
                    // {
                    // nilai: "20",
                    // celcius: "C"    
                    // },
                    // {
                    // nilai: "30",
                    // celcius: "C"    
                    // },
                    // {
                    // nilai: "10",
                    // celcius: "C"   
                    // }
            
                    // {
                    //     nilai : {"10", "15", "35", "10"},
                    //     celcius : {"10", "15", "35", "10"}
                    // }
                    // nilai : "15",
                    // nilai : "20",
                    // nilai : "15"

                ]
            }]
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
                    labelString: "Traffic (Mbps)",
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
        setInterval('autoRefreshPage()', 10000);
</script>
@endsection

{{-- @section('js')

@endsection --}}