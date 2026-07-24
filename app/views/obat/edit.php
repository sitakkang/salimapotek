<input type="hidden" id="edit_id" name="id" value="<?= $row->id_obat ?>">

<div class="ds-form-group">
    <label>Nama Obat <span class="text-danger">*</span></label>
    <input type="text" id="edit_obat_name" name="obat_name" class="form-control"
           value="<?= htmlspecialchars($row->obat_name) ?>" maxlength="100" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Satuan <span class="text-danger">*</span></label>
            <input type="text" id="edit_obat_satuan" name="obat_satuan" class="form-control"
                   value="<?= htmlspecialchars($row->obat_satuan) ?>" maxlength="20" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Harga <span class="text-danger">*</span></label>
            <input type="text" id="edit_obat_price" name="obat_price" class="form-control"
                   value="<?= number_format($row->obat_price, 0, ',', '.') ?>" autocomplete="off">
        </div>
    </div>
</div>
