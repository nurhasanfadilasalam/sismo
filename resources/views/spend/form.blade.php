@extends("layouts.app")

@section('title', '| Pengeluaran Toko')
@section("content")
<link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
<style>
    .select2-container {
        width: 90% !important;
    }

    .select2-selection {
        padding: 5px 10px !important;
    }

    @media only screen and (max-width: 1024px) {
        .select2-container {
            width: 70% !important;
        }
    }
</style>
<section class="section">
    <div class="section-header">
        <h1>Pengeluaran Toko</h1>
    </div>
    <div class="section-body">
        @if (session('status'))
            <div class="alert alert-info alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                {{ session('status') }}
            </div>
        @endif 
        <form id="spendForm" action="@if(!empty($data)) {{route('spend.update', [$data->id])}} @else {{ route('spend.store') }} @endif"  method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3">
            @csrf
            
            @if(!empty($data))
                <input type="hidden" value="PUT" name="_method">
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="user">Tanggal</label>
                        <input type="date" class="form-control @error('date_select') is-invalid @enderror" name="date_select" id="date_select" value="@if(!empty($data)){{ $data->date_select }}@elseif(old('date_select')){{ old('date_select') }}@else{{ date('Y-m-d') }}@endif">
                        @error('date_select')
                        <div class="invalid-feedback">* {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="total">Jumlah</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('total') is-invalid @enderror" name="total" id="total" value="@if(!empty($data)){{ $data->total }}@else{{ old('total') }}@endif" placeholder="Jumlah Pengeluaran">
                            @error('total')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                        </div>
                    </div>                    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="notes">Pengeluaran</label>
                        <div class="input-group">
                            <input type="text" placeholder="Pengeluaran" class="form-control @error('notes') is-invalid @enderror" name="notes" id="catatan" value="@if(!empty($data)){{ $data->notes }}@else{{ old('notes') }}@endif">
                            @error('notes')
                                <div class="invalid-feedback">* {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="coloum"> 
                <a class="btn btn-lg btn-danger" href="{{ url('spend') }}">Batal</a>
                <button type="submit"class="btn btn-lg btn-success float-right">Simpan Data</button>
            </div>
        </form>
    </div>
</section>

@endsection

@section('footer-scripts')
<script>    
    $('#spendForm').submit(function(ev) {
        ev.preventDefault();

        if (!$('#date_select').val()) alert('Mohon input tanggal')
        else if (!$('#catatan').val()) alert('Mohon Input Pengeluaran')
        else if (!$('#total').val()) alert('Mohon Input Total')     
        else {
            spinner.show();
            $("#spendForm :input").prop("readonly", true);
            $("#spendForm :button").prop("disabled", true);

            $.post($(this).attr('action'), $(this).serialize(), function(res) {
                console.log(res);
                spinner.hide();
                    
                if (res.status) {
                    alert(res.msg)
                    $("#spendForm :input").prop("readonly", false);
                    $("#spendForm :button").prop("disabled", false);

                    if (res.link) location.href = res.link;
                    else location.reload();
                } 
                else {
                    alert(res.msg);
                    $("#spendForm :input").prop("readonly", false);
                    $("#spendForm :button").prop("disabled", false);
                }
            }).fail(function(xhr, status, error) {
                console.log(error);
                spinner.hide();
                alert('Gagal, silahkan reload dan coba lagi.');
                $("#spendForm :input").prop("readonly", false);
                $("#spendForm :button").prop("disabled", false);
            });
        }
    })
</script>
@endsection