@extends('layouts.app')

@section('title', ' | Pembayaran')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Form Pembayaran</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Pembayaran Invoice : {{ $sales->transaction_number }}</h6>
                                    <h6>Partner : {{ $sales->partner->name }}</h6>
                                    <h6>Tanggal Transaksi : {{ $sales->date_select }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <h6>Total Transaksi : {{ number_format($total,0,'.','.') }}</h6>
                                    <h6>Total Pembayaran : {{ number_format($paymenttotal,0,'.','.') }}</h6>
                                    <h6>Sisa : {{ number_format($minus,0,'.','.') }}</h6>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <form method="POST" action="{{ route('sales.storepayments') }}">
                                @csrf
                                <input type="hidden" name="sales_id" value="{{ $sales->id }}">
                                <input type="hidden" name="minus" value="{{ $minus }}">
                                @if(!empty($payment))
                                    <input type="hidden" name="do" value="edit">
                                    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Tanggal</label>
                                            <input type="date" name="date" id="date" value="@if(!empty($payment)){{ $payment->date_select }}@else{{ date('Y-m-d') }}@endif" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pay">Jumlah Bayar</label>
                                            <input type="number" name="pay" id="pay" min="1" class="form-control @error('pay') is-invalid @enderror" value="@if(!empty($payment)){{ $payment->pay }}@else{{ old('pay') }}@endif">
                                            @error('pay')
                                                <div class="invalid-feedback">* {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Keterangan</label>
                                            <textarea name="notes" id="notes" placeholder="Keterangan tambahan" class="form-control" style="min-height: 80px;">@if(!empty($payment)){{ $payment->notes }}@else{{ old('notes') }}@endif</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <a class="btn btn-lg btn-danger" href="{{ route('sales.payments', [$sales->id]) }}">Batal</a>
                                        <button type="submit" class="btn btn-lg btn-success float-right btnGrandSave">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection