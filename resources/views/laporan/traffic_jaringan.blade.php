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
                        <a class="nav-link {{ request()->is('status_perangkat') ? 'active' : null }}" href="{{ url('status_perangkat') }}"
                            role="tab"><i class="fa fa-list mr-2"></i>Status Perangkat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('traffic_jaringan') ? 'active' : null }}"
                            href="{{ url('traffic_jaringan') }}" role="tab"><i class="far fa-file-alt"></i>Traffic Jaringan</a>
                    </li>
                    
                </ul>

                <!-- tab panel -->

            </div>

            <!-- body card -->
            <div class="card-body">
            <div class="tab-content">

                <div class="tab-pane {{ request()->is('traffic_jaringan') ? 'active' : null }}" id="{{!! url('traffic_jaringan') !!}}" role="tabpanel"></div>
                <div class="card">
                    <h1>Traffic Jaringan</h1>
                </div>
            </div>

        </div>    

        </div>

        


       


        </div>


    </div>
    @endsection