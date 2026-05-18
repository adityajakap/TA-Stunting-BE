@extends('layouts.app')

@section('title', 'Riwayat Imunisasi Anak')

@section('content')
<div class="p-4">
    <h2>Fitur Imunisasi Telah Dihapus</h2>
    <p>Fitur riwayat imunisasi telah dihapus dari aplikasi. Kembali ke <a href="{{ route('orangtua.dashboard') }}">dashboard</a>.</p>
</div>
@endsection
