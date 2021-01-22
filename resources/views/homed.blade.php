@extends('layouts.app')

@section('title', '')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Dashboard</h4></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
            <h6>Data Hari ini</h6>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Stok</h4>
                            </div>
                            <div class="card-body">{{ number_format($stocks,0,'.','.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Partner</h4>
                            </div>
                            <div class="card-body">{{ number_format($partners,0,'.','.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-file"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Penjualan</h4>
                            </div>
                            <div class="card-body">{{ number_format($sales,0,'.','.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-cart-plus"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Barang Masuk</h4>
                            </div>
                            <div class="card-body">{{ number_format($purchases,0,'.','.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Tidak Layak</h4>
                            </div>
                            <div class="card-body">{{ number_format($brokens,0,'.','.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                  <div class="summary">
                    <div class="summary-item">
                        <h6>Penjualan Hari ini</h6>
                        <ul class="list-unstyled list-unstyled-border">
                            @if(count($lastSales))
                                @foreach($lastSales as $sale)
                                <li class="media">
                                    <a href="#">
                                        <img class="mr-3 rounded" width="50" src="/img/products/product-2-50.png" alt="product">
                                    </a>
                                    <div class="media-body">
                                        <div class="media-right">
                                            @php($total = 0)
                                            @foreach($sale->details as $detail)
                                                @php($total += $detail->subtotal)
                                            @endforeach
                                            {{ number_format($total,0,'.','.') }}
                                        </div>
                                        <div class="media-title"><a href="#">{{ $sale->partner->name }}</a></div>
                                        <div class="text-muted text-small">by <a href="#">{{ $sale->createdUser->name }}</a> <div class="bullet"></div> {{ $sale->date_select }}</div>
                                    </div>
                                </li>
                                @endforeach
                            @else
                                <li class="pl-3"><small>Belum ada data</small></li>
                            @endif
                        </ul>
                        <h6><a class="float-right" href="{{ url('sales') }}">lihat lebih banyak</a></h6>
                    </div>
                  </div>
                </div>
            </div>      
        </div>
    </div>
</div>
@endsection
