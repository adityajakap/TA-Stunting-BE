@extends('layouts.app')

@section('title', 'Tahapan Perkembangan')

@section('content')
<style>
    .badge {
        display: inline-block;
        background: #e0f2fe;
        color: #0284c7;
        border-radius: 0.5rem;
        padding: 0.2rem 0.7rem;
        font-size: 0.85rem;
        margin-right: 0.3rem;
        margin-bottom: 0.2rem;
    }

    .table {
        width: 100%;
        /* Use separate borders to allow rounded table corners inside the card */
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
        background-color: white;
    }

    .table thead th {
        background-color: #f9fafb;
        padding: 0.75rem;
        font-weight: 600;
        color: #1f2937;
        text-align: left;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
    }

    .table tbody tr {
        border-bottom: 1px solid #e5e7eb;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
    }

    .table tbody td {
        padding: 0.75rem;
        color: #374151;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
    }

    /* Rounded corners for the table inside the card */
    .table thead th:first-child { border-top-left-radius: 0.75rem; }
    .table thead th:last-child { border-top-right-radius: 0.75rem; }
    .table tbody tr:last-child td:first-child { border-bottom-left-radius: 0.75rem; }
    .table tbody tr:last-child td:last-child { border-bottom-right-radius: 0.75rem; }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #6b7280;
    }

    .table-responsive {
        margin-top: 1.5rem;
        overflow-x: auto;
    }
</style>

<div class="container px-0">
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4" style="max-width: 1280px; margin: 0 auto; ">
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <x-back-button />
                    <h1 class="main-title mb-0" style="color: #005f77; font-size: 1.75rem;">Tahapan Perkembangan</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('orangtua.tahapan_perkembangan.create') }}"
                        class="btn text-white"
                        style="background-color: #005f77;">+ Tambah Pencapaian
                    </a>    
                </div>
            </div>

            {{-- MODAL FILTER --}}
            <div id="filterModal" class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-10 z-50 flex items-center justify-center">
                <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
                    <form method="GET" action="{{ route('orangtua.tahapan_perkembangan.index') }}">
                        <div class="mb-4 font-semibold text-sky-900">Filter Status</div>
                        <div class="flex flex-wrap gap-x-4 gap-y-2 mb-6">
                            @foreach($kategoris as $kategori)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="kategori[]" value="{{ $kategori->id }}"
                                        class="form-checkbox accent-sky-600"
                                        {{ in_array($kategori->id, (array) $kategoriIds) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sky-800">{{ $kategori->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="submit" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Terapkan</button>
                            <a href="{{ route('orangtua.tahapan_perkembangan.index') }}" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Reset</a>
                            <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')" class="px-4 py-2 rounded text-white" style="background-color: #005f77;">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Tahapan</th>
                            <th>Tanggal Pencapaian</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td>{{ $item->tahapanPerkembangan->nama_tahapan }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pencapaian)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $item->status == 'tercapai' ? '#dcfce7' : '#fef9c3' }};
                                                            color: {{ $item->status == 'tercapai' ? '#15803d' : '#92400e' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ $item->catatan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada pencapaian yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-4">
                    {{ $data->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection
