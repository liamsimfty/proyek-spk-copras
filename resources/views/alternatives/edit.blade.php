@extends('layouts.app')

@section('title', 'Edit Alternatif')

@section('content')
    <h2>Edit Alternatif: {{ $alternative->name }}</h2>

    <form action="{{ route('alternatives.update', $alternative->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Method untuk update --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nama Alternatif</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $alternative->name) }}" required>
             @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('alternatives.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection