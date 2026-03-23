@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-collection-fill text-primary me-2"></i>Kelola Koleksi Buku
                    </h3>
                    <p class="text-muted small mb-0">Total koleksi: <strong>{{ $bukus->count() }}</strong> judul buku terdaftar.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('buku.exportPdf') }}" class="btn btn-outline-danger shadow-sm px-3 rounded-pill">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                    <button class="btn btn-primary shadow-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Buku
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2">
                            <i class="bi bi-funnel text-primary" style="font-size: 0.8rem;"></i>
                        </div>
                        <label class="small fw-bold text-muted mb-0">Filter Genre</label>
                    </div>
                    <select id="admin-filter-genre" class="form-select select2" name="genres[]" multiple="multiple" data-placeholder="Semua Genre...">
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Novel">Novel</option>
                        <option value="Edukasi">Edukasi</option>
                        <option value="Teknologi">Teknologi</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info bg-opacity-10 p-2 rounded-3 me-2">
                            <i class="bi bi-search text-info" style="font-size: 0.8rem;"></i>
                        </div>
                        <label class="small fw-bold text-muted mb-0">Pencarian Cepat</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted rounded-start-3"><i class="bi bi-search"></i></span>
                        <input type="text" id="admin-live-search" class="form-control bg-light border-0 shadow-none rounded-end-3" placeholder="Cari judul, penulis, atau penerbit...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th width="80" class="border-0 px-4 py-3">COVER</th>
                            <th class="border-0 py-3">DETAIL BUKU</th>
                            <th class="border-0 py-3">GENRE</th>
                            <th class="border-0 py-3">STOK</th>
                            <th width="150" class="text-center border-0 py-3 px-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-buku">
                        @include('admin.buku._table_buku')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.buku._modal_tambah')
@include('admin.buku._modal_edit')

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Custom Select2 Styling */
    .select2-container--default .select2-selection--multiple {
        border: none !important;
        background-color: #f8f9fa !important;
        border-radius: 8px !important;
        min-height: 38px !important;
        padding: 2px 5px !important;
    }
    .select2-container--default .select2-selection__choice {
        background-color: #e9ecef !important;
        color: #495057 !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 6px !important;
        font-size: 0.75rem !important;
        font-weight: 600;
        margin-top: 4px !important;
    }

    /* Table & Hover Effects */
    #tabel-buku tr {
        transition: all 0.2s;
        animation: fadeIn 0.4s ease-in-out;
    }
    #tabel-buku tr:hover {
        background-color: #fcfcfc;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Button Hover Elevate Fix */
    .btn-light.shadow-sm {
        transition: all 0.2s;
    }
    .btn-light.shadow-sm:hover {
        transform: translateY(-2px);
        background-color: #fff !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Init Select2
        $('#admin-filter-genre').select2({
            placeholder: "Pilih genre...",
            allowClear: true
        });

        const searchInput = document.getElementById('admin-live-search');
        const tableBody = document.getElementById('tabel-buku');

        // Fungsi AJAX Fetch Data
        function fetchAdminData() {
            let keyword = searchInput.value;
            let genres = $('#admin-filter-genre').val();

            let params = new URLSearchParams();
            if(keyword) params.append('search', keyword);
            if(genres && genres.length > 0) {
                genres.forEach(g => params.append('genres[]', g));
            }

            fetch(`{{ route('buku.index') }}?${params.toString()}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.text())
            .then(html => {
                tableBody.innerHTML = html;
            })
            .catch(err => console.error("Error:", err));
        }

        // Event Listeners
        searchInput.addEventListener('input', fetchAdminData);
        $('#admin-filter-genre').on('change', fetchAdminData);

        // Modal Edit Data Passing
        const modalEdit = document.getElementById('modalEdit');
        if (modalEdit) {
            modalEdit.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                const form = document.getElementById('formEdit');
                
                form.action = `/admin/buku/${btn.getAttribute('data-id')}`;
                document.getElementById('edit-judul').value = btn.getAttribute('data-judul');
                document.getElementById('edit-penulis').value = btn.getAttribute('data-penulis');
                document.getElementById('edit-penerbit').value = btn.getAttribute('data-penerbit');
                document.getElementById('edit-genre').value = btn.getAttribute('data-genre');
                document.getElementById('edit-stok').value = btn.getAttribute('data-stok');
            });
        }
    });
</script>
@endpush