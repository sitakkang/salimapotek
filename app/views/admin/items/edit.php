<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Kode Item <span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="kodeitem" name="kodeitem" value="<?=$id->kodeitem;?>" placeholder="Masukkan kode item" maxlength="10" required>
            <small class="form-text text-muted">Maksimal 10 karakter</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Nama Item <span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="namaitem" name="namaitem" value="<?=$id->namaitem;?>" placeholder="Masukkan nama item" maxlength="50" required>
            <small class="form-text text-muted">Maksimal 50 karakter</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Harga Satuan <span class="text-danger">*</span></label>
            <input class="form-control" type="number" id="hargasatuan" name="hargasatuan" value="<?=$id->hargasatuan;?>" placeholder="0" min="0" step="0.01" required>
            <small class="form-text text-muted">Dalam Rupiah</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Gambar Item</label>
            <input class="form-control" type="file" id="itemimage" name="itemimage" accept="image/*">
            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Max: 2MB</small>
            <?php if(!empty($id->itemimage)): ?>
                <div class="mt-2">
                    <img src="<?=base_url($id->itemimage);?>" class="img-thumbnail" style="max-width: 200px;">
                    <p class="text-muted small">Gambar saat ini</p>
                </div>
            <?php endif; ?>
            <div id="image_preview"></div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label">Deskripsi</label>
    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi item" maxlength="225"><?=$id->deskripsi;?></textarea>
    <small class="form-text text-muted">Maksimal 225 karakter</small>
</div>

<input type="hidden" id="id" value="<?=$id->id;?>">
