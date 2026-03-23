@extends('layouts.siswa')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2"><i class="bi bi-tags me-1"></i> Genre (Bisa Pilih Banyak)</label>
                    <select id="filter-genre" class="form-select select2" name="genres[]" multiple="multiple">
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Novel">Novel</option>
                        <option value="Edukasi">Edukasi</option>
                        <option value="Teknologi">Teknologi</option>
                    </select>
                </div>

                <div class="col-md-9">
                    <label class="small fw-bold text-muted mb-2"><i class="bi bi-search me-1"></i> Cari Buku</label>
                    <div class="input-group bg-light rounded-3 p-1">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="live-search" class="form-control bg-transparent border-0 shadow-none ps-0" placeholder="Ketik judul atau penulis...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" id="container-buku">
        @include('siswa._buku_list')
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Bikin Select2 mirip sama input-group kita */
    .select2-container--default .select2-selection--multiple {
        border: none !important;
        background-color: #f8f9fa !important;
        border-radius: 8px !important;
        min-height: 45px !important;
        padding-top: 4px !important;
    }
    .select2-container--default .select2-selection__choice {
        background-color: #0d6efd !important;
        color: white !important;
        border: none !important;
        border-radius: 5px !important;
        font-size: 12px !important;
    }
    .select2-container--default .select2-selection__choice__remove {
        color: white !important;
        margin-right: 5px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#filter-genre').select2({
        placeholder: "Pilih Genre...",
        allowClear: true
    });

    const searchInput = document.getElementById('live-search');
    const container = document.getElementById('container-buku');

    function doFilter() {
        let keyword = searchInput.value;
        let genres = $('#filter-genre').val(); // Ini bakal dapet Array [ "Fiksi", "Novel" ]
        
        let params = new URLSearchParams();
        if (keyword) params.append('search', keyword);
        
        // PENTING: Loop array genre supaya masuk ke params sebagai genres[]
        if (genres && genres.length > 0) {
            genres.forEach(g => params.append('genres[]', g));
        }

        fetch(`{{ route('siswa.katalog') }}?${params.toString()}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch(err => console.error("Filter Error:", err));
    }

    searchInput.addEventListener('input', doFilter);
    $('#filter-genre').on('change', function() {
        doFilter(); // Select2 butuh trigger change manual
    });
});
</script>
@endpush