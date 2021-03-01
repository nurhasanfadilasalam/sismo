@extends("layouts.app")

@section("title") @if(!empty($data)) Edit @else Tambah @endif Kategori @endsection

@section("content")
<section class="section">
    <div class="section-header"><h1>@if(!empty($data)) Edit @else Tambah @endif Kategori</h1></div>
</section>
<section class="section">
    <div class="section-body">
        @if (session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
        @endif 
        <!-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif -->
        <form enctype="multipart/form-data" class="bg-white shadow-sm p-3" action="@if(!empty($data)) {{ route('categories.update', [$data->id]) }} @else {{ route('categories.store') }} @endif" method="POST">
            @csrf

            @if(!empty($data))
                <input type="hidden" value="PUT" name="_method">
            @endif

            <label for="name">Nama Kategori</label>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input class="form-control @error('name') is-invalid @enderror" placeholder="Nama Kategori" type="text" name="name" id="name" value="@if(!empty($data)){{ $data->name }}@endif"/>
            
            <br><br>
            <input class="btn btn-primary" type="submit" value="Save"/>
            <a href="{{ url('categories') }}" class="btn btn-danger">Cancel</a>
        </form> 
    </div>
</section>
@endsection