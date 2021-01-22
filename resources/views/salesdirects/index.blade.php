@extends('layouts.app')

@section('title', ' | Penjualan Langsung')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Penjualan Langsung</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('salesdirects') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('salesdirects.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Penjualan Langsung Baru</a>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-warning alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                {{ session('status') }}
                            </div>
                        @endif 
                        <hr class="my-3">
                        <div class="table-responsive">
                            <table class="table table-stripped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>No Invoice</th>
                                        <th>Pembeli</th>
                                        <th>Komponen</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $data->date_select }}</td>
                                        <td>{{ $data->transaction_number }}</td>
                                        <td>
                                        <b>{{ $data->customer_type }}</b><br>
                                        @if($data->customer_type == 'Langganan')
                                        <span>Nama Pelanggan: {{ ucwords($data->name_customer) }}</span>
                                        @endif
                                        </td>
                                        <td>
                                            @if(!empty($data->details))
                                                <ul class="pl-3">
                                                @foreach($data->details as $detail)
                                                    <li>
                                                        <b>{{ $detail->salesInventory->name }},</b><br>
                                                        <span>Jumlah: {{ $detail->qty }} {{ $detail->unit }} = {{ number_format($detail->subtotal,0,'.','.') }}</span>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>{{ $data->notes }}</td>
                                        <td>
                                        <form onsubmit="return confirm('Delete data?')" class="d-inline" action="{{route('salesdirects.destroy', [$data->id])}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger text-white btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ $datas->appends(Request::all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection