@extends('layouts.app')

@section('title', '| Master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Data Master</h4></div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ url('perangkat') }}?do=order" class="btn btn-block btn-info btn-lg linkMaster">Data Status Perangkat</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('perangkat') }}?do=payment" class="btn btn-block btn-info btn-lg linkMaster">Data IP Perangkat</a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('perangkat') }}?do=puskom" class="btn btn-block btn-info btn-lg linkMaster">Data Perangkat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
