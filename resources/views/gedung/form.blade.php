@extends("layouts.app")

@section("title") @if(!empty($data)) Edit @else Tambah @endif Gedung @endsection

@section("content")
<section class="section">
    <div class="section-header">
        <h1>@if(!empty($data)) Edit @else Tambah @endif Gedung</h1>
    </div>
</section>
<section class="section">
    <div class="section-body">
        @if (session('status'))
        <div class="alert alert-warning">
            {{ session('status') }}
        </div>
        @endif
        <form enctype="multipart/form-data" class="bg-white shadow-sm p-3" action="@if(!empty($data)) {{ route('gedung.update', [$data->id]) }} @else {{ route('gedung.store') }} @endif" method="POST">
            @csrf

            @if(!empty($data))
            <input type="hidden" value="PUT" name="_method">
            @endif
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Gedung</label>
                        @error('nama_gedung')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input class="form-control @error('nama_gedung') is-invalid @enderror" placeholder="Nama Gedung" type="text" name="nama_gedung" id="nama_gedung" value="@if(!empty($data)){{ $data->nama_gedung }}@endif" required />
                    </div>
             
                    <div class="form-group">
                        <label class="form-label" for="kode_gedung">Kode Gedung</label>
                        @error('kode_gedung')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input class="form-control @error('kode_gedung') is-invalid @enderror" placeholder="Kode Gedung" type="text" name="kode_gedung" id="kode_gedung" value="@if(!empty($data)){{ $data->kode_gedung }}@endif" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="photo">Photo</label><br>
                        @if(!empty($data))
                            <span>Current Photo : </span>
                            @if($data->photo)
                                <br><img src="{{ asset('storage/gedung/'.$data->photo) }}" width="120px" /><br>
                            @else 
                                <span>[No Photo]</span>
                            @endif
                        @endif
                        <input id="photo" name="photo" type="file" class="form-control">
                        @if(!empty($data))<small class="text-muted">* Kosongkan jika tidak ingin mengubah photo</small>@endif
                    </div>
                    <br>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <br>
                    <button class="btn btn-lg btn-primary float-right" type="submit">Save</button>
                    <a href="{{ url('gedung') }}" class="btn btn-lg btn-danger float-left">Cancel</a>
                </div>
            </div>

        </form>
    </div>
</section>
@endsection