@extends('layouts.app')

@section('title', 'BMI')

@section('content')

<style>
    .main-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1280px;
        margin: 2rem auto 1rem;
        padding: 0 1rem;
    }

    .main-title {
        color: #1e5a6e;
        font-size: 1.75rem;
        margin: 0;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon-mini {
        background-color: #005f77;
        color: white;
        padding: 0.3rem 0.6rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .btn-icon-mini:hover { background-color: #014f66; }

    .card-wrapper { max-width: 1280px; margin: 0 auto; padding-bottom: 2rem; }

    .card { background-color: #ffffff; border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
    .card-body { padding: 2rem; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1; }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        width: 100%;
        background-color: #fff;
    }

    .form-control:focus {
        border-color: #005f77;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 95, 119, 0.1);
    }

    .mb-3 {
        margin-bottom: 1.5rem;
    }

    .button-group {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #005f77;
        color: white;
    }

    .btn-primary:hover {
        background-color: #014f66;
    }

    .btn-success {
        background-color: #16a34a;
        color: white;
    }

    .btn-success:hover {
        background-color: #15803d;
    }

    .btn-danger {
        background-color: #dc2626;
        color: white;
    }

    .btn-danger:hover {
        background-color: #b91c1c;
    }

    .empty-message { text-align: center; font-size: 1rem; color: #6b7280; margin: 3rem 0; }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e5a6e;
        margin-bottom: 1.5rem;
        margin-top: 2rem;
    }

    .table {
        width: 100%;
        /* allow rounded corners by using separate borders */
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
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

    .table tbody td {
        padding: 0.75rem;
        color: #374151;
        border: 1px solid #e5e7eb;
        font-size: 0.95rem;
    }

    /* Rounded corners for the table when placed inside a card */
    .table thead th:first-child { border-top-left-radius: 0.75rem; }
    .table thead th:last-child { border-top-right-radius: 0.75rem; }
    .table tbody tr:last-child td:first-child { border-bottom-left-radius: 0.75rem; }
    .table tbody tr:last-child td:last-child { border-bottom-right-radius: 0.75rem; }

    .table tbody tr:hover {
        background-color: #f9fafb;
    }

    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: capitalize;
    }

    .bg-success {
        background-color: #10b981;
        color: white;
    }

    .bg-danger {
        background-color: #ef4444;
        color: white;
    }

    .bg-warning {
        background-color: #f59e0b;
        color: white;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #6b7280;
    }

    .py-3 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .btn-outline-danger {
        background-color: transparent;
        border: 1.5px solid #f43f5e;
        color: #f43f5e;
        padding: 0.4rem 0.8rem;
        border-radius: 0.375rem;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background-color: #f43f5e;
        color: white;
    }

    /* Custom Confirmation Modal */
    .confirm-modal {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .confirm-modal.active {
        opacity: 1;
        visibility: visible;
    }

    .confirm-modal-content {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        text-align: center;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .confirm-modal-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .confirm-modal-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .confirm-modal-message {
        color: #6b7280;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .confirm-modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .confirm-btn {
        background-color: #ef4444;
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 0.375rem;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .confirm-btn:hover {
        background-color: #dc2626;
    }

    .cancel-btn {
        background-color: #e5e7eb;
        color: #374151;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 0.375rem;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .cancel-btn:hover {
        background-color: #d1d5db;
    }
</style>

<div class="main-header">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <x-back-button />
        <h1 class="main-title">Hitung BMI</h1>
    </div>
    <div class="action-buttons"></div>
</div>

<div class="card-wrapper">
    {{-- Card for BMI form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form id="bmiForm" method="POST" action="{{ route('hitung-bmi') }}">
                @csrf

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="pria" {{ old('gender', session('gender')) == 'pria' ? 'selected' : '' }}>Pria</option>
                        <option value="wanita" {{ old('gender', session('gender')) == 'wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tinggi" class="form-label">Tinggi Badan (Cm)</label>
                    <input type="number" name="tinggi" id="tinggi" class="form-control" value="{{ old('tinggi', session('tinggi')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="berat" class="form-label">Berat Badan (Kg)</label>
                    <input type="number" name="berat" id="berat" class="form-control" value="{{ old('berat', session('berat')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="bmi" class="form-label">BMI Score</label>
                    <input type="text" name="bmi" id="bmi" class="form-control" value="{{ session('bmi') }}" readonly style="background-color: #f3f4f6;">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" name="status" id="status" class="form-control" value="{{ session('status') }}" readonly style="background-color: #f3f4f6;">
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-primary" onclick="validateAndSubmit('{{ route('hitung-bmi') }}')">Hitung</button>
                    <button type="button" class="btn btn-success" onclick="validateAndSubmit('{{ route('simpan-bmi') }}', true)">Simpan</button>
                    <button type="button" class="btn btn-danger" onclick="setFormAction(event, '{{ route('reset-bmi') }}')">Reset</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Riwayat BMI (table like Deteksi) --}}
    <h2 class="section-title">Riwayat Data BMI</h2>
    <div class="card mb-4">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Gender</th>
                        <th>Tinggi (cm)</th>
                        <th>Berat (kg)</th>
                        <th>BMI</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($bmiRecords) && $bmiRecords->isNotEmpty())
                        @foreach($bmiRecords as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->tanggal }}</td>
                            <td>{{ ucfirst($data->gender ?? 'tidak diketahui') }}</td>
                            <td>{{ $data->tinggi }}</td>
                            <td>{{ $data->berat }}</td>
                            <td>{{ $data->bmi }}</td>
                            <td>
                                <span class="badge {{ $data->status == 'Overweight' ? 'bg-warning' : ($data->status == 'Underweight' ? 'bg-danger' : 'bg-success') }}">
                                    {{ $data->status }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn-outline-danger" onclick="showDeleteConfirm('{{ route('hapus-bmi-row', $data->id) }}')">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-3 text-muted">Belum ada data BMI.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Grafik BMI --}}
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="section-title" style="margin-top: 0;">Grafik Perkembangan BMI</h2>
            <canvas id="bmiChart" height="100"></canvas>
        </div>
    </div>
</div>

{{-- Chart & JS (re-use earlier logic) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bmiData = @json(isset($bmiRecords) ? $bmiRecords->map(fn($d) => ['tanggal' => $d->tanggal, 'bmi' => $d->bmi]) : []);
    const labels = bmiData.map(item => item.tanggal);
    const data = bmiData.map(item => parseFloat(item.bmi));

    const ctx = document.getElementById('bmiChart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'BMI',
                    data: data,
                    borderColor: 'rgba(37, 99, 235, 1)',
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false,
                        suggestedMin: 10,
                        suggestedMax: 40
                    }
                }
            }
        });
    }

    function validateAndSubmit(actionUrl, isSave = false) {
        const form = document.getElementById('bmiForm');
        const genderSelect = document.getElementById('gender');
        const tinggiInput = document.getElementById('tinggi');
        const beratInput = document.getElementById('berat');

        const gender = genderSelect ? genderSelect.value : '';
        const tinggi = tinggiInput ? tinggiInput.value : '';
        const berat = beratInput ? beratInput.value : '';

        if (!gender || !tinggi || !berat) {
            alert('Harap lengkapi semua data terlebih dahulu.');
            return false;
        }

        form.action = actionUrl;

        if (isSave) {
            localStorage.setItem('bmi_gender', gender);
            localStorage.setItem('bmi_tinggi', tinggi);
            localStorage.setItem('bmi_berat', berat);
        }

        form.submit();
    }

    function setFormAction(event, resetUrl) {
        event.preventDefault();
        const form = document.getElementById('bmiForm');
        form.action = resetUrl;
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {

        // restore saved bmi values (if any)
        const savedGender = localStorage.getItem('bmi_gender');
        const savedTinggi = localStorage.getItem('bmi_tinggi');
        const savedBerat = localStorage.getItem('bmi_berat');

        if (savedGender) {
            const genderSelect = document.getElementById('gender');
            if (genderSelect) {
                for (let i = 0; i < genderSelect.options.length; i++) {
                    if (genderSelect.options[i].value === savedGender) {
                        genderSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }

        if (savedTinggi) {
            const t = document.getElementById('tinggi');
            if (t) t.value = savedTinggi;
        }
        if (savedBerat) {
            const b = document.getElementById('berat');
            if (b) b.value = savedBerat;
        }

        // clear saved values
        localStorage.removeItem('bmi_gender');
        localStorage.removeItem('bmi_tinggi');
        localStorage.removeItem('bmi_berat');
    });

    // Delete confirmation modal
    let deleteUrl = '';

    function showDeleteConfirm(url) {
        deleteUrl = url;
        const modal = document.getElementById('confirmModal');
        modal.classList.add('active');
    }

    function closeDeleteConfirm() {
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('active');
        deleteUrl = '';
    }

    function confirmDelete() {
        if (deleteUrl) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('confirmModal');
        if (event.target === modal) {
            closeDeleteConfirm();
        }
    });
</script>

<!-- Delete Confirmation Modal -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-modal-content">
        <div class="confirm-modal-icon">⚠️</div>
        <div class="confirm-modal-title">Hapus Data BMI?</div>
        <div class="confirm-modal-message">Anda yakin ingin menghapus data BMI ini? Tindakan ini tidak dapat dibatalkan.</div>
        <div class="confirm-modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeDeleteConfirm()">Batal</button>
            <button type="button" class="confirm-btn" onclick="confirmDelete()">Hapus</button>
        </div>
    </div>
</div>

@endsection