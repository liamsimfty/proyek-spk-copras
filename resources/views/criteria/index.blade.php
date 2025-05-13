@extends('layouts.app')

@section('title', 'Daftar Kriteria')

@section('content')
    <h2>Daftar Kriteria</h2>
    <a href="{{ route('criteria.create') }}" class="btn btn-primary mb-3">Tambah Kriteria Baru</a>

    @if ($criteria->isEmpty())
        <div class="alert alert-info">
            Belum ada data kriteria.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Kriteria</th>
                    <th>Tipe</th>
                    <th>Bobot</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($criteria as $key => $criterion)
                    <tr>
                        <td>{{ $criteria->firstItem() + $key }}</td>
                        <td>{{ $criterion->code }}</td>
                        <td>{{ $criterion->name }}</td>
                        <td>
                            <span class="badge {{ $criterion->type == 'benefit' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($criterion->type) }}
                            </span>
                        </td>
                        <td>{{ $criterion->weight }}</td>
                        <td>
                            <a href="{{ route('criteria.edit', $criterion->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('criteria.destroy', $criterion->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini? Pastikan kriteria ini tidak digunakan dalam data skor.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

         {{-- Link Paginasi --}}
        <div class="d-flex justify-content-center">
            {{ $criteria->links() }}
        </div>
    @endif
@endsection