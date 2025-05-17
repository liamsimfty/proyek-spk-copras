@extends('layouts.app')

@section('title', 'Daftar Alternatif')

@section('content')
    <h2 class="page-title">Daftar Alternatif</h2>

    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Data Alternatif</h4>
            <a href="{{ route('alternatives.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Tambah Alternatif Baru
            </a>
        </div>
        <div class="card-body">
            @if ($alternatives->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle-fill me-2"></i> Belum ada data alternatif. Silakan tambahkan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Alternatif</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatives as $key => $alternative)
                                <tr>
                                    <td>{{ $alternatives->firstItem() + $key }}</td>
                                    <td>{{ $alternative->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('alternatives.edit', $alternative->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('alternatives.destroy', $alternative->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini? Data skor terkait mungkin juga akan terpengaruh.');">
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

                @if ($alternatives->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $alternatives->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection