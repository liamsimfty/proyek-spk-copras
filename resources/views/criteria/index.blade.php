@extends('layouts.app')

@section('title', 'Daftar Kriteria')

@section('content')
    <h2 class="page-title"><i class="bi bi-card-checklist me-2"></i>Daftar Kriteria</h2>

    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Data Kriteria</h4>
            <a href="{{ route('criteria.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Tambah Kriteria Baru
            </a>
        </div>
        <div class="card-body">
            @if ($criteria->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle-fill me-2"></i> Belum ada data kriteria. Silakan tambahkan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Kriteria</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Bobot</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($criteria as $key => $criterion)
                                <tr>
                                    <td>{{ $criteria->firstItem() + $key }}</td>
                                    <td>{{ $criterion->code }}</td>
                                    <td>{{ $criterion->name }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $criterion->type == 'benefit' ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }} border {{ $criterion->type == 'benefit' ? 'border-success-subtle' : 'border-danger-subtle' }} rounded-pill px-2">
                                            {{ ucfirst($criterion->type) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $criterion->weight }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('criteria.edit', $criterion->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('criteria.destroy', $criterion->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini? Pastikan kriteria ini tidak digunakan dalam data skor.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($criteria->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $criteria->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection