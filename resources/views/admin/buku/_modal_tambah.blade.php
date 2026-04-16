<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('buku.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Koleksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label small fw-bold text-muted">Judul Lengkap</label>
                        <input type="text" name="judul" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Nama buku" required>
                        
                        <div class="row mt-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Penulis</label>
                                <input type="text" name="penulis" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Nama Penulis" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Nama Penerbit" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Genre</label>
                                <select name="genre_id" id="edit-genre" class="form-select" required>
                                    <option value="">Pilih Genre</option>
                                    @foreach($genres as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Stok Buku</label>
                                <input type="number" name="stok" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Upload Cover</label>
                        <div class="border rounded-4 p-3 text-center bg-light border-dashed">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                            <input type="file" name="foto" class="form-control mt-2 border-0 bg-white" accept="image/*">
                            <p class="small text-muted mt-2">Format: JPG, PNG (Max 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4 rounded-pill">Simpan Koleksi</button>
            </div>
        </form>
    </div>
</div>