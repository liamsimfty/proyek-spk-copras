@extends('layouts.app')

@section('title', 'Daftar Alternatif')

@section('content')
    <h2>Daftar Alternatif</h2>
    <a href="{{ route('alternatives.create') }}" class="btn btn-primary mb-3">Tambah Alternatif Baru</a>

    @if ($alternatives->isEmpty())
        <div class="alert alert-info">
            Belum ada data alternatif.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Alternatif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alternatives as $key => $alternative)
                    <tr>
                        {{-- Menampilkan nomor urut berdasarkan paginasi --}}
                        <td>{{ $alternatives->firstItem() + $key }}</td>
                        <td>{{ $alternative->name }}</td>
                        <td>
                            <a href="{{ route('alternatives.edit', $alternative->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('alternatives.destroy', $alternative->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini? Data skor terkait mungkin juga akan terpengaruh.');">
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
            {{ $alternatives->links() }}
        </div>
    @endif
@endsection