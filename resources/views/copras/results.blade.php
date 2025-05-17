@extends('layouts.app')
@section('title', 'Hasil Perhitungan COPRAS')
@section('content')
<h2 class="page-title"><i class="bi bi-bar-chart-line-fill me-2"></i>Hasil Perhitungan Metode COPRAS</h2>

@if(session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <br>
        @if(Route::has('copras.inputScores'))
            <a href="{{ route('copras.inputScores') }}" class="btn btn-info btn-sm mt-2">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Input Nilai
            </a>
        @endif
    </div>
@elseif(empty($alternatives) || empty($criteria))
    <div class="card card-custom">
        <div class="card-body">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Data alternatif atau kriteria kosong. Harap isi data terlebih dahulu.
                <br>
                <a href="{{ route('copras.inputScores') }}" class="btn btn-info btn-sm mt-2">
                   <i class="bi bi-pencil-square me-1"></i> Input Nilai Sekarang
                </a>
            </div>
        </div>
    </div>
@elseif(empty($results) || !isset($decisionMatrix))
    <div class="card card-custom">
        <div class="card-body">
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle-fill me-2"></i> Belum ada perhitungan yang dilakukan atau data nilai belum lengkap.
                <br>
                <a href="{{ route('copras.inputScores') }}" class="btn btn-info btn-sm mt-2">
                    <i class="bi bi-pencil-square me-1"></i> Input Nilai Sekarang
                </a>
            </div>
        </div>
    </div>
@else
    <div class="card card-custom mb-4">
        <div class="card-header">
            <h4><i class="bi bi-table me-2"></i>Matriks Keputusan (X)</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="bg-light">Alternatif</th>
                            @foreach ($criteria as $criterion)
                                <th>{{ $criterion->name }} ({{ $criterion->code }})</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                            <tr>
                                <td class="fw-bold text-start">{{ $alternative->name }}</td>
                                @foreach ($criteria as $criterion)
                                    <td>{{ $decisionMatrix[$alternative->id][$criterion->id] ?? '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-header">
            <h4><i class="bi bi-aspect-ratio me-2"></i>Matriks Normalisasi (R)</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="bg-light">Alternatif</th>
                            @foreach ($criteria as $criterion)
                                <th>{{ $criterion->code }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($alternatives as $alternative)
                            <tr>
                                <td class="fw-bold text-start">{{ $alternative->name }}</td>
                                @foreach ($criteria as $criterion)
                                    <td>{{ number_format($normalizedMatrix[$alternative->id][$criterion->id] ?? 0, 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card card-custom mb-4">
        <div class="card-header">
            <h4><i class="bi bi-gem me-2"></i>Matriks Normalisasi Terbobot (D)</h4>
        </div>
         <div class="card-body">
            <div class="table-responsive">
                 <table class="table table-bordered table-sm table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="bg-light">Alternatif</th>
                            @foreach ($criteria as $criterion)
                                <th>{{ $criterion->code }} <small>(Bobot: {{ $criterion->weight }})</small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($alternatives as $alternative)
                            <tr>
                                <td class="fw-bold text-start">{{ $alternative->name }}</td>
                                @foreach ($criteria as $criterion)
                                    <td>{{ number_format($weightedNormalizedMatrix[$alternative->id][$criterion->id] ?? 0, 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-header">
            <h4><i class="bi bi-calculator me-2"></i>Perhitungan P<sub>i</sub> (Benefit) dan R<sub>i</sub> (Cost)</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="bg-light">Alternatif</th>
                            <th>P<sub>i</sub> <small>(Benefit)</small></th>
                            <th>R<sub>i</sub> <small>(Cost)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                            <tr>
                                <td class="fw-bold text-start">{{ $alternative->name }}</td>
                                <td>{{ number_format($sumP[$alternative->id] ?? 0, 4) }}</td>
                                <td>{{ number_format($sumR[$alternative->id] ?? 0, 4) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card card-custom">
        <div class="card-header bg-primary text-white"> <!-- Highlight final result card -->
            <h4 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>Hasil Akhir Peringkat</h4>
        </div>
        <div class="card-body">
            <p>Berdasarkan perhitungan Metode COPRAS, berikut adalah peringkat alternatif dari yang terbaik:</p>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered text-center">
                    <thead class="table-info">
                        <tr>
                            <th>Peringkat</th>
                            <th>Alternatif</th>
                            <th>Nilai Signifikansi (Q<sub>i</sub>)</th>
                            <th>Utilitas Kuantitatif (N<sub>i</sub> / U<sub>i</sub>) (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rank = 1; @endphp
                        @forelse ($results as $result)
                            <tr class="{{ $rank == 1 ? 'table-success fw-bold' : '' }}">
                                <td>
                                    @if($rank == 1)
                                        <i class="bi bi-award-fill text-warning me-1"></i>
                                    @endif
                                    {{ $rank++ }}
                                </td>
                                <td class="text-start">{{ $result['alternative_name'] }}</td>
                                <td>{{ number_format($result['Q'], 4) }}</td>
                                <td>
                                    <div class="progress" role="progressbar" aria-label="Utility value {{ $result['alternative_name'] }}" aria-valuenow="{{ number_format($result['N'], 2) }}" aria-valuemin="0" aria-valuemax="100" style="height: 20px;">
                                      <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ number_format($result['N'], 2) }}%">{{ number_format($result['N'], 2) }} %</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center fst-italic py-4">Tidak ada hasil untuk ditampilkan. Pastikan data dan nilai sudah diinput dengan benar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="alert alert-light mt-3 border">
                <strong>Catatan:</strong> Alternatif dengan nilai Utilitas Kuantitatif (N<sub>i</sub>) tertinggi adalah pilihan terbaik.
            </div>
        </div>
    </div>
@endif
@endsection