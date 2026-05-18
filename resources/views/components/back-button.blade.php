@props(['url' => url()->previous(), 'title' => 'Kembali'])

<a href="{{ $url }}" aria-label="{{ $title }}" title="{{ $title }}" class="back-button me-2">
    <!-- left arrow SVG -->
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</a>

<style scoped>
    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffffff;
        color: #0f172a; /* text/icon color */
        text-decoration: none;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px rgba(2,6,23,0.06);
        transition: background-color 0.12s ease, transform 0.08s ease;
    }
    .back-button:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
    }
    .back-button svg { display: block; }
</style>
