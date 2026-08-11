<input type="hidden" id="edit_pulv_id" value="<?= $pulv->id_pulv ?>">

<div class="ds-form-group">
    <label>Nama Racikan <span class="text-danger">*</span></label>
    <input type="text" id="edit_pulv_name" class="form-control" value="<?= htmlspecialchars($pulv->pulv_name ?? '') ?>" maxlength="255">
</div>

<div class="row" style="margin:0 -6px;">
    <div class="col-md-6" style="padding:0 6px;">
        <div class="ds-form-group">
            <label>Jumlah Bungkus <span class="text-danger">*</span></label>
            <input type="number" id="edit_pulv_qty" class="form-control" value="<?= intval($pulv->pulv_qty) ?>" min="1">
        </div>
    </div>
    <div class="col-md-6" style="padding:0 6px;">
        <div class="ds-form-group">
            <label>Dosis / Aturan Pakai <span class="text-danger">*</span></label>
            <input type="text" id="edit_pulv_dosis" class="form-control" value="<?= htmlspecialchars($pulv->pulv_dosis ?? '') ?>" maxlength="50">
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Catatan Formula</label>
    <textarea id="edit_pulv_notes" class="form-control" rows="6"><?= htmlspecialchars($pulv->pulv_notes ?? '') ?></textarea>
</div>
