<input type="hidden" id="pulv_medical_record_id" value="<?= $medical_record_id ?>">

<div class="ds-form-group">
    <label>Opsi Racikan</label>
    <select id="option_pulv" class="form-control">
        <option value="">-- Pilih --</option>
        <option value="new">--- Buat Racikan Baru ---</option>
        <?php if (!empty($pulv_list)): ?>
        <optgroup label="Racikan yang sudah ada">
            <?php foreach ($pulv_list as $p): ?>
            <option value="<?= $p->id_pulv ?>"><?= htmlspecialchars($p->pulv_name ?? '') ?></option>
            <?php endforeach; ?>
        </optgroup>
        <?php endif; ?>
    </select>
</div>

<div id="pulv_add_area" style="display:none;">
    <hr style="border-color:var(--ds-border);margin:10px 0 14px;">

    <div class="ds-form-group">
        <label>Nama Racikan <span class="text-danger">*</span></label>
        <input type="text" id="pulv_name" class="form-control" placeholder="Misal: Puyer Demam, Racikan Batuk" maxlength="255">
    </div>

    <div class="row" style="margin:0 -6px;">
        <div class="col-md-6" style="padding:0 6px;">
            <div class="ds-form-group">
                <label>Jumlah Bungkus <span class="text-danger">*</span></label>
                <input type="number" id="pulv_qty" class="form-control" value="1" min="1">
            </div>
        </div>
        <div class="col-md-6" style="padding:0 6px;">
            <div class="ds-form-group">
                <label>Dosis / Aturan Pakai <span class="text-danger">*</span></label>
                <input type="text" id="pulv_dosis" class="form-control" placeholder="Misal: 3x1, 3x sehari 1 bungkus" maxlength="50">
            </div>
        </div>
    </div>

    <div class="ds-form-group">
        <label>Catatan Formula</label>
        <textarea id="pulv_notes" class="form-control" rows="6" placeholder="Cara meracik / catatan dokter..."></textarea>
    </div>
</div>
