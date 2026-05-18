@props(['action'])
<div id="filterModal" class="modal-overlay">
    <div class="modal-content">
        <h2>Filter</h2>
        <form method="GET" action="{{ $action }}">
            {{-- kategori filter removed --}}
            <div class="flex justify-end gap-2 mt-4">
                <x-button type="submit">Apply</x-button>
                <x-button :href="$action" color="gray">Reset</x-button>
                <x-button color="red" onclick="toggleFilter()">Tutup</x-button>
            </div>
        </form>
    </div>
</div>