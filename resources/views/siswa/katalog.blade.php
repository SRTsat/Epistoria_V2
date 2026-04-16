@extends('layouts.siswa')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-2"><i class="bi bi-tags me-1"></i> Genre</label>
                    <select id="filter-genre" class="form-select select2" name="genres[]" multiple="multiple">
                        @foreach($genres as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#filter-genre').select2({ placeholder: "Pilih Genre...", allowClear: true });

    function doFilter() {
        let keyword = $('#live-search').val();
        let genres = $('#filter-genre').val(); // Sekarang isinya array ID [1, 2]
        
        let params = new URLSearchParams();
        if (keyword) params.append('search', keyword);
        if (genres && genres.length > 0) {
            genres.forEach(g => params.append('genres[]', g));
        }

        fetch(`{{ route('siswa.katalog') }}?${params.toString()}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.text())
        .then(html => { document.getElementById('container-buku').innerHTML = html; });
    }

    $('#live-search').on('input', doFilter);
    $('#filter-genre').on('change', doFilter);
});
</script>
@endpush