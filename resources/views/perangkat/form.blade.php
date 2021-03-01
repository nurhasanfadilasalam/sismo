

@extends("layouts.app")

@section("title") @if(!empty($data)) Edit @else Tambah @endif Perangkat @endsection

@section("content")
<section class="section">
    <div class="section-header">
        <h1>@if(!empty($data)) Edit @else Tambah @endif Perangkat</h1>
    </div>
</section>
<section class="section">
    <div class="section-body">
        @if (session('status'))
        <div class="alert alert-warning">
            {{ session('status') }}
        </div>
        @endif
        <form enctype="multipart/form-data" class="bg-white shadow-sm p-3" action="@if(!empty($data)) {{ route('perangkat.update', [$data->id]) }} @else {{ route('perangkat.store') }} @endif" method="POST">
            @csrf

            @if(!empty($data))
            <input type="hidden" value="PUT" name="_method">
            @endif
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Perangkat</label>
                        @error('nama_perangkat')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input class="form-control @error('nama_perangkat') is-invalid @enderror" placeholder="Nama Perangkat" type="text" name="nama_perangkat" id="nama_perangkat" value="@if(!empty($data)){{ $data->nama_perangkat }}@endif" required />
                    </div>
             
                    <div class="form-group">
                        <label class="form-label" for="ip_perangkat">IP Perangkat</label>
                        @error('ip_perangkat')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input class="form-control @error('ip_perangkat') is-invalid @enderror" placeholder="IP Perangkat" type="text" name="ip_perangkat" id="ip_perangkat" value="@if(!empty($data)){{ $data->ip_perangkat }}@endif" required/>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="name">Gedung </label>
                        @error('gedung')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <select name="gedung" id="gedung" class="form-control" onchange="this.form.value" required>
                            @foreach ($listGedung as $gedung)
                            {{-- <option @if($gedung == '') @endif value="">-- Pilih Gedung --</option> --}}
                            <option> 
                                {{-- @if($gedung != null) --}}
                                {{ $gedung }}
                                {{-- @endif --}}
                            </option> 
                            @endforeach
                        </select>
                        {{-- <input class="form-control @error('gedung') is-invalid @enderror" placeholder="Gedung" type="text" name="gedung" id="gedung" value="@if(!empty($data)){{ $data->gedung }}@endif" required/> --}}
                        <br>
                        <a href="{{ route('gedung.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Tambah Data Gedung</a>
                        
                    </div>

                    
                    <br>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <br>
                    <button class="btn btn-lg btn-primary float-right" type="submit">Save</button>
                    <a href="{{ url('perangkat') }}" class="btn btn-lg btn-danger float-left">Cancel</a>
                </div>
            </div>

        </form>
    </div>
</section>
@endsection