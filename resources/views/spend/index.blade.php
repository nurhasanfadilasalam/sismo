@extends('layouts.app')

@section('title', '| Pengeluaran Toko')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pengeluaran Toko</h1>
    </div>
</section>

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('spend') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('spend.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Pengeluaran Toko</a>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-info alert-dismissible">
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
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Total</th>
                                        <th>Oleh</th>
                                        <th>Aksi</th>

                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="trFilter">
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('notes') }}" id="notesFind" class="form-control form-control-sm col-md-10" type="text" placeholder="Filter nama"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btn-sm btnFindNotes" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th></th>
                                        <th></th>                                   
                                    </tr>
                                </thead>
                                <tbody>
                                    @php( $userRoles = json_decode(Auth::user()->roles) )
                                    @if (!empty($datas))
                                    @foreach($datas as $key => $data)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $data->date_select }}</td>
                                        <td>{{ $data->notes }}</td>
                                        <td>{{ $data->total }}</td>
                                        <td>{{ $data->createdUser->name }}</td>
                                        <td>
                                            @if(in_array("OWNER", $userRoles) || in_array("ADMIN", $userRoles ))
                                            <a class="btn btn-info text-white btn-sm" href="{{ route('spend.edit', [$data->id]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form onsubmit="return confirm('Hapus Data?')" class="d-inline" action="{{route('spend.destroy', [$data->id])}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger text-white btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endif                                    
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6">Data Kosong</td>
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
</section>
<script>
    $(document).ready(function(e) {
        $('.btnFindNotes').click(function(e) {
            filter();
        });

        $('#notesFind').on('keypress',function(e) {
            if(e.which == 13) {
                filter();
            }
        });
    })

    function filter() {
        let notes = $('#notesFind').val();
        notes = notes ? notes : '';       

        location.href = "{{ url('spend') }}?notes=" + notes;
    }
</script>
@endsection