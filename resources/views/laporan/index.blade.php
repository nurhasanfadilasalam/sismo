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
                <div class="col-md-6 grid-margin stretch-card">
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
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        </div>
                        <div class="col-md-6 mt-4 mt-md-0">
                        <div class="d-flex align-items-center pb-2">
                            <div class="dot-indicator bg-success mr-2"></div>
                            <p class="mb-0">Perangkat Status Up</p>
                        </div>
                        <h4 class="font-weight-semibold">5</h4>
                        <div class="progress progress-md">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 80%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="20"></div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
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
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Keuangan Hari ini</h4>
                    </div>
                    <h3 class="font-weight-medium mb-4">Rp. 1.000.000 </h3>
                    </div>
                    <canvas class="mt-n4" height="90" id="total-revenue"></canvas>
                </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Pemeriksaan Hari ini</h4>
                    </div>
                    <h3 class="font-weight-medium">20</h3>
                    </div>
                    <canvas class="mt-n3" height="90" id="total-transaction"></canvas>
                </div>
                </div>
                <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title mb-0">10 Diagnosa Terbanyak</h4>
                    <canvas class="mt-4" height="100" id="market-overview-chart"></canvas>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title mb-4">Data Pemeriksaan Terakhir</h1>
                            <div class="row">
                                <div class="col-12">
                                    
                                    <div class="wrapper border-bottom mb-2 pb-2">
                                        <h5 class="font-weight-semibold mb-0">Cisco</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0">
                                                <span>Router</span><br>
                                                <small>Keluhan : Down Server</small>
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
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Warning Stok Farmasi</h4>
                            
                            <div class="d-flex mt-3 py-2 border-bottom">
                                <span class="img-sm rounded-circle bg-danger text-white text-avatar"><i class="mdi mdi-pill"></i></span>
                                <div class="wrapper ml-2">
                                    <p class="mb-n1 font-weight-semibold">CPU</p>
                                    <small class="text-muted ml-auto">Stock : 10</small>
                                </div>
                            </div>
                            
                            <small><i><a href="{{ url('transactions/pharmacies') }}">Data Selengkapnya</a></i></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection