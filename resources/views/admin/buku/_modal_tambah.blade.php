<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('buku.store') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5>Tambah Buku Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Judul Buku</label>
                <input type="text" name="judul" class="form-control mb-2" required>
                
                <label class="form-label">Penulis</label>
                <input type="text" name="penulis" class="form-control mb-2" required>
                
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control mb-2" required>

                <label class="form-label">Genre</label>
                <select name="genre" class="form-control mb-2" required>
                    <option value="">-- Pilih Genre --</option>
                    <option value="Fiksi">Fiksi</option>
                    <option value="Non-Fiksi">Non-Fiksi</option>
                    <option value="Novel">Novel</option>
                    <option value="Edukasi">Edukasi</option>
                    <option value="Teknologi">Teknologi</option>
                </select>
                
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="stok" class="form-control mb-2" required>
                
                <label class="form-label">Upload Foto Cover</label>
                <input type="file" name="foto" class="form-control mb-2" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Buku</button>
            </div>
        </form>
    </div>
</div>