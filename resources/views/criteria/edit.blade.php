@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
    <h2>Edit Kriteria: {{ $criterion->name }}</h2>

    <form action="{{ route('criteria.update', $criterion->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Method untuk update --}}
         <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Nama Kriteria</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $criterion->name) }}" required>
                 @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="code" class="form-label">Kode Kriteria (C1, C2, ...)</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $criterion->code) }}" required>
                 @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="type" class="form-label">Tipe Kriteria</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="benefit" {{ old('type', $criterion->type) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                    <option value="cost" {{ old('type', $criterion->type) == 'cost' ? 'selected' : '' }}>Cost</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
             <div class="col-md-6 mb-3">
                <label for="weight" class="form-label">Bobot Kriteria</label>
                <input type="number" step="any" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $criterion->weight) }}" required min="0">
                @error('weight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                 <div class="form-text">Masukkan nilai bobot (misal: 0.25). Pastikan total bobot semua kriteria sesuai preferensi Anda (tidak harus 1).</div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('criteria.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection