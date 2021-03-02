
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
                            <div class=col-md-6>
                                <div class="card">

                                    <div class="card-header">
                                        <p class="pull-right">
                                           
                                                    {{$laporan->nama_perangkat}}
                                                    
                                                    <br>
                                                    Gedung: {{$laporan->gedung}}
                                                    <br>
                                                    IP: {{$laporan->ip_perangkat}}
                                                    </h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="col-md-6">


                                            <div class="chart-container">
                                                <canvas id="lineChart"></canvas>
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