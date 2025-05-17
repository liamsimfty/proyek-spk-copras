@extends('layouts.app')

@section('title', 'Tambah Alternatif')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Tambah Alternatif Baru</h2>
        <a href="{{ route('alternatives.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle-fill me-2"></i>Kembali ke Daftar
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            Formulir Tambah Alternatif
        </div>
        <div class="card-body">
            <form action="{{ route('alternatives.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Alternatif <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Laptop A" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <hr>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-save-fill me-2"></i>Simpan
                    </button>
                    <a href="{{ route('alternatives.index') }}" class="btn btn-light border">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection