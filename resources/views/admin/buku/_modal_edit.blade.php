<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="formEdit" method="POST" class="modal-content border-0 shadow-lg rounded-4" enctype="multipart/form-data">
            @csrf 
            @method('PUT')
            
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Data Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Judul Lengkap</label>
                            <input type="text" name="judul" id="edit-judul" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Judul Buku" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Penulis</label>
                                <input type="text" name="penulis" id="edit-penulis" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Nama Penulis" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Penerbit</label>
                                <input type="text" name="penerbit" id="edit-penerbit" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="Nama Penerbit" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Genre</label>
                                <select name="genre" id="edit-genre" class="form-select rounded-3 bg-light border-0 px-3 py-2" required>
                                    <option value="Fiksi">Fiksi</option>
                                    <option value="Non-Fiksi">Non-Fiksi</option>
                                    <option value="Novel">Novel</option>
                                    <option value="Edukasi">Edukasi</option>
                                    <option value="Teknologi">Teknologi</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Stok Buku</label>
                                <input type="number" name="stok" id="edit-stok" class="form-control rounded-3 bg-light border-0 px-3 py-2" placeholder="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Update Cover</label>
                        <div class="border rounded-4 p-3 text-center bg-light border-dashed h-100 d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-image text-warning mb-2" style="font-size: 2.5rem;"></i>
                            <input type="file" name="foto" class="form-control mt-2 border-0 bg-white" accept="image/*">
                            <div class="mt-2 small text-muted px-2 text-center" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengganti cover.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning px-4 rounded-pill shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Tambahan dikit biar border upload kelihatan putus-putus */
    .border-dashed {
        border: 2px dashed #dee2e6 !important;
    }
</style>