<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEdit" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-header"><h5>Edit Data Buku</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <label>Judul</label><input type="text" name="judul" id="edit-judul" class="form-control mb-2" required>
                <label>Penulis</label><input type="text" name="penulis" id="edit-penulis" class="form-control mb-2" required>
                <label>Penerbit</label><input type="text" name="penerbit" id="edit-penerbit" class="form-control mb-2" required>
                <label>Genre</label>
                <select name="genre" id="edit-genre" class="form-select mb-2" required>
                    <option value="Fiksi">Fiksi</option><option value="Non-Fiksi">Non-Fiksi</option>
                    <option value="Novel">Novel</option><option value="Edukasi">Edukasi</option>
                    <option value="Teknologi">Teknologi</option>
                </select>
                <label>Stok</label><input type="number" name="stok" id="edit-stok" class="form-control mb-2" required>
                <label>Foto Cover (Biarkan kosong jika tidak diganti)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-warning">Simpan Perubahan</button></div>
        </form>
    </div>
</div>