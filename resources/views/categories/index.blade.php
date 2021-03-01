@extends("layouts.app")

@section("title") Data Kategori @endsection

@section("content")
<section class="section">
    <div class="section-header"><h1>Data Kategori</h1></div>
</section>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="buttons">
                            <a href="{{ url('categories') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Reload</a>
                            <a href="{{ route('categories.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Data</a>
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
                                        <th>#</th>
                                        <th class="th-md">Nama Kategori</th>
                                        <th class="action2">Action</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="trFilter">
                                        <th></th>
                                        <th>
                                            <div class="input-group">
                                                <input value="{{ Request::get('name') }}" id="nameFind" class="form-control col-md-10" type="text"placeholder="Filter nama"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-insearch btnFindNama" type="button"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if (!empty($datas))
                                    @foreach($datas as $key => $category)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $key }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <a class="btn btn-info text-white btn-sm" href="{{ route('categories.edit', [$category->id]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form onsubmit="return confirm('Delete data?')" class="d-inline" action="{{route('categories.destroy', [$category->id])}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger text-white btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
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
        $('.btnFindNama, .btnFindEmail').click(function(e) {
            filter();
        });

        $('#nameFind, #emailFind').on('keypress',function(e) {
            if(e.which == 13) {
                filter();
            }
        });
    })

    function filter() {
        let name = $('#nameFind').val();
        name = name ? name : '';
        
        location.href = "{{ url('categories') }}?name=" + name;
    }
</script>
@endsection