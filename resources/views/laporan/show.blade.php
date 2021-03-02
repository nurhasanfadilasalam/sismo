@extends("layouts.app")

@section("title") Detail Server Status @endsection

@section("content")
<section class="section">
    <div class="section-header">
        <h1>Detail Server</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div>

                            <a href="{{ url('status_perangkat') }}" class="btn btn-danger">
                                < Back</a> </p> &ensp; <h3>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">


                            {{-- left --}}
                            <div class=col-md-8>
                                <div class="card">

                                    <div class="card-header">

                                        <div class="col-md-8">


                                            {{$laporan->nama_perangkat}}

                                            <br>
                                            Gedung: {{$laporan->gedung}}
                                            <br>
                                            IP: {{$laporan->ip_perangkat}}
                                            </h3>

                                            <br>
                                            <center>
                                                @if (Ping::check($laporan->ip_perangkat) == 200)
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


                                        </div>



                                    </div>

                                    <div class="card-body">
                                        <div class="col-md-12">

                                            {{-- grafik --}}
                                            {{-- <div class="chart-container">
                                                <canvas id="lineChart"></canvas>
                                            </div> --}}
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>SSID</th>
                                                            <th>Transmitter (Byte)</th>
                                                            <th>Receiver (Byte)</th>

                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <td>{{$laporan->nama_perangkat}}</td>
                                                        <td>{{  $data_traffic_last_inoctet->nilai }}</td>
                                                        <td>{{  $data_traffic_last_outoctet->nilai }}</td>

                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </div>


                            {{-- Right --}}
                            {{-- <div class=col-md-6>
                            <div class="card">
                                <div class="card-header">
                                    
                                </div>
                                <div class="card-body">
                                    
                                </div>

                            </div>

                        </div> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- for grapik --}}
<script>
    var lineChart = document.getElementById('lineChart').getContext('2d');
    var myLineChart = new Chart(lineChart, {
        type: 'line',
        data: {
            labels: [
                @foreach($suhu as $waktu)
                "{{date('H:i:s',strtotime($waktu->created_at))}}",
                @endforeach

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

                    @foreach($suhuu as $nilai)
                    @php
                    $nilai = $nilai['nilai'];

                    @endphp '{{"$nilai"}}',
                    @endforeach

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
                        suggestedMax: 100,
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
    setInterval('autoRefreshPage()', 15000);
</script>
@endsection