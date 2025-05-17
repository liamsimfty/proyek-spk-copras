@extends('layouts.app')

@section('title', 'Input Nilai Matriks Keputusan')

@section('content')
    <h2 class="page-title"><i class="bi bi-pencil-square me-2"></i>Input Nilai Matriks Keputusan</h2>

    <div class="card card-custom">
        <div class="card-header">
            <h4>Formulir Input Nilai</h4>
        </div>
        <div class="card-body">
            @if($alternatives->isEmpty() || $criteria->isEmpty())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Harap tambahkan <a href="{{ route('alternatives.index') }}" class="alert-link">alternatif</a> dan <a href="{{ route('criteria.index') }}" class="alert-link">kriteria</a> terlebih dahulu sebelum menginput nilai.
                </div>
            @else
                <form action="{{ route('copras.saveScores') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="bg-info text-white">Alternatif</th>
                                    @foreach ($criteria as $criterion)
                                        <th class="text-center bg-info text-white" title="{{ $criterion->type == 'benefit' ? 'Benefit (Semakin besar semakin baik)' : 'Cost (Semakin kecil semakin baik)' }}">
                                            {{ $criterion->name }} ({{ $criterion->code }})
                                            <i class="bi {{ $criterion->type == 'benefit' ? 'bi-arrow-up-circle-fill text-success' : 'bi-arrow-down-circle-fill text-danger' }} ms-1"></i>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alternatives as $alternative)
                                    <tr>
                                        <td><strong>{{ $alternative->name }}</strong></td>
                                        @foreach ($criteria as $criterion)
                                            <td>
                                                <input type="number" step="any" class="form-control form-control-sm @error('scores.'.$alternative->id.'.'.$criterion->id) is-invalid @enderror"
                                                       name="scores[{{ $alternative->id }}][{{ $criterion->id }}]"
                                                       value="{{ old('scores.'.$alternative->id.'.'.$criterion->id, $scores[$alternative->id][$criterion->id] ?? '') }}"
                                                       required placeholder="Nilai">
                                                @error('scores.'.$alternative->id.'.'.$criterion->id)
                                                    <div class="invalid-feedback d-block" style="font-size: .8em;">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Semua Nilai
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection