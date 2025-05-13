@extends('layouts.app')

@section('title', 'Tambah Alternatif')

@section('content')
    <h2>Tambah Alternatif Baru</h2>

    <form action="{{ route('alternatives.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Alternatif</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('alternatives.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection