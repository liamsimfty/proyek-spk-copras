@extends('layouts.app')
@section('title', 'Input Nilai Matriks Keputusan')
@section('content')
<h2>Input Nilai Matriks Keputusan</h2>

@if($alternatives->isEmpty() || $criteria->isEmpty())
    <div class="alert alert-warning">
        Harap tambahkan <a href="{{ route('alternatives.index') }}">alternatif</a> dan <a href="{{ route('criteria.index') }}">kriteria</a> terlebih dahulu.
    </div>
@else
    <form action="{{ route('copras.saveScores') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Alternatif</th>
                    @foreach ($criteria as $criterion)
                        <th>{{ $criterion->name }} ({{ $criterion->code }})</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatives as $alternative)
                    <tr>
                        <td>{{ $alternative->name }}</td>
                        @foreach ($criteria as $criterion)
                            <td>
                                <input type="number" step="any" class="form-control" 
                                       name="scores[{{ $alternative->id }}][{{ $criterion->id }}]"
                                       value="{{ $scores[$alternative->id][$criterion->id] ?? '' }}" 
                                       required>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary mt-3">Simpan Nilai</button>
    </form>
@endif
@endsection