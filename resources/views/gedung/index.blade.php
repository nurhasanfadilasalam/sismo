@extends('layouts.app')

@section('title', '| Data Gedung')
@section('content')
<section class="section">
    <div class="section-header"><h1>Data Gedung</h1></div>
</section>


<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('gedung') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('gedung.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Data</a>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-info alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                {{ session('status') }}
                            </div>
                        @endif 
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="th-md">Nama Gedung</th>
                                        <th>Kode Gedung</th>
                                        <th class="th-md">Photo</th>
                                        <th class="action2">Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="trFilter">
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('nama_gedung') }}" id="nameFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter nama"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindNama" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                          
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('kode_gedung') }}" id="kodeFind" class="form-control form-control-sm col-md-10" type="text"placeholder="Filter kode"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindKode" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>

                                        
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(!empty($datas))
                                    @foreach($datas as $key => $gedung)
                                        <tr>
                                            <td>{{ $datas->firstItem() + $key }}</td>
                                            <td>{{ $gedung->nama_gedung }}</td>
                                            <td>{{ $gedung->kode_gedung }}</td>
                                            <td>
                                                @if($gedung->photo)
                                                    <a href="{{ asset('storage/gedung/'.$gedung->photo) }}" target="_blank" title="Click for more">
                                                        <img src="{{ asset('storage/gedung/'.$gedung->photo) }}" width="70px"/> 
                                                    </a>
                                                @else 
                                                    <span>[No Image]</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-info text-white btn-sm" href="{{ url('gedung/'.$gedung->id.'/edit' ) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form onsubmit="return confirm('Delete data?')" class="d-inline" action="{{ route('gedung.destroy', [$gedung->id]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger text-white btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">Data Kosong</td>
                                    </tr>
                                @endif
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
    </div>
</section>

<script>
    $(document).ready(function(e) {
        $('.btnFindNama, .btnFindKode').click(function(e) {
            filter();
        });

        $('#nameFind, #kodeFind).on('keypress',function(e) {
            if(e.which == 13) {
                filter();
            }
        });
    })

    function filter() {
        let nama_gedung = $('#nameFind').val();
        nama_gedung = nama_gedung ? nama_gedung : '';

        let kode_gedung = $('#kodeFind').val();
        kode_gedung = kode_gedung ? kode_gedung : '';

        location.href = "{{ url('gedung') }}?nama_gedung=" + nama_gedung + "&kode_gedung=" + kode_gedung;
    }
</script>
@endsection
