@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
    <h2 class="page-title"><i class="bi bi-pencil-fill me-2"></i>Edit Kriteria: {{ $criterion->name }}</h2>

    <div class="card card-custom">
        <div class="card-header">
            <h4>Formulir Edit Kriteria</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('criteria.update', $criterion->id) }}" method="POST">
                @csrf
                @method('PUT')
                 <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $criterion->name) }}" required>
                         @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="code" class="form-label">Kode Kriteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $criterion->code) }}" placeholder="C1, C2, ..." required>
                         @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Tipe Kriteria <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="benefit" {{ old('type', $criterion->type) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                            <option value="cost" {{ old('type', $criterion->type) == 'cost' ? 'selected' : '' }}>Cost</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="weight" class="form-label">Bobot Kriteria <span class="text-danger">*</span></label>
                        <input type="number" step="any" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $criterion->weight) }}" required min="0">
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                         <div class="form-text">Masukkan nilai bobot (misal: 0.25 atau 25 jika menggunakan skala persentase). Pastikan konsisten.</div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Update Kriteria
                    </button>
                    <a href="{{ route('criteria.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection