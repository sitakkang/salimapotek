<input type="hidden" id="daftar_id" value="<?= $row->id_patient ?>">

<div class="ds-detail-wrap" style="margin-bottom: 14px;">
    <div class="ds-detail-row">
        <div class="ds-detail-card ds-detail-patient" style="width: 100%;">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-user-circle"></i></span>
                <span>Data Pasien</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">NO. RM</span>
                        <span class="ds-detail-value ds-detail-code"><?= htmlspecialchars($row->patient_code) ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Nama Pasien</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_name) ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Perusahaan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_company) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Telepon</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_phone) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 0 0 14px;">

<div class="ds-form-group">
    <label>Tanggal &amp; Jam Daftar <span class="text-danger">*</span></label>
    <input type="text" id="trans_doc" name="trans_doc" class="form-control datetimepicker"
           placeholder="dd/mm/Y HH:ii" autocomplete="off" readonly>
</div>
