@extends('layouts.app')
@section('title', 'Hasil Perhitungan COPRAS')
@section('content')
<h2>Hasil Perhitungan Metode COPRAS</h2>

@if(empty($alternatives) || empty($criteria))
    <div class="alert alert-warning">
        Data alternatif atau kriteria kosong. Harap isi data terlebih dahulu.
        <br>
        <a href="{{ route('copras.inputScores') }}" class="btn btn-info mt-2">Input Nilai Sekarang</a>
    </div>
@elseif(session('error')) {{-- Tambahkan ini untuk menampilkan error dari controller --}}
    <div class="alert alert-danger">
        {{ session('error') }}
        <br>
        @if(Route::has('copras.inputScores'))
            <a href="{{ route('copras.inputScores') }}" class="btn btn-info mt-2">Kembali ke Input Nilai</a>
        @endif
    </div>
@elseif(empty($results) || !isset($decisionMatrix)) {{-- Periksa juga apakah decisionMatrix ada --}}
     <div class="alert alert-info">
        Belum ada perhitungan yang dilakukan atau data nilai belum lengkap.
        <br>
        <a href="{{ route('copras.inputScores') }}" class="btn btn-info mt-2">Input Nilai Sekarang</a>
    </div>
@else
    <h4>Matriks Keputusan (X)</h4>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm table-hover">
            <thead class="table-dark">
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
                            <td>{{ $decisionMatrix[$alternative->id][$criterion->id] ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h4>Matriks Normalisasi (R)</h4>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Alternatif</th>
                    @foreach ($criteria as $criterion)
                        <th>{{ $criterion->code }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                 @foreach ($alternatives as $alternative)
                    <tr>
                        <td>{{ $alternative->name }}</td>
                        @foreach ($criteria as $criterion)
                            <td>{{ number_format($normalizedMatrix[$alternative->id][$criterion->id] ?? 0, 4) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <h4>Matriks Normalisasi Terbobot (D)</h4>
    <div class="table-responsive mb-4">
         <table class="table table-bordered table-sm table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Alternatif</th>
                    @foreach ($criteria as $criterion)
                        <th>{{ $criterion->code }} (Bobot: {{ $criterion->weight }})</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                 @foreach ($alternatives as $alternative)
                    <tr>
                        <td>{{ $alternative->name }}</td>
                        @foreach ($criteria as $criterion)
                            <td>{{ number_format($weightedNormalizedMatrix[$alternative->id][$criterion->id] ?? 0, 4) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h4>Perhitungan P_i (Benefit) dan R_i (Cost)</h4>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Alternatif</th>
                    <th>P_i (Benefit)</th>
                    <th>R_i (Cost)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatives as $alternative)
                    <tr>
                        <td>{{ $alternative->name }}</td>
                        <td>{{ number_format($sumP[$alternative->id] ?? 0, 4) }}</td>
                        <td>{{ number_format($sumR[$alternative->id] ?? 0, 4) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <h4>Hasil Akhir: Nilai Signifikansi Relatif (Q_i) dan Utilitas Kuantitatif (N_i)</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Peringkat</th>
                    <th>Alternatif</th>
                    <th>Q_i</th>
                    <th>N_i / U_i (%)</th>
                </tr>
            </thead>
            <tbody>
                @php $rank = 1; @endphp
                @forelse ($results as $result)
                    <tr>
                        <td>{{ $rank++ }}</td>
                        <td>{{ $result['alternative_name'] }}</td>
                        <td>{{ number_format($result['Q'], 4) }}</td>
                        <td>{{ number_format($result['N'], 2) }} %</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada hasil untuk ditampilkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif
@endsection