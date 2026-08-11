<input type="hidden" id="anamnesa_id" value="<?= $anamnesa ? $anamnesa->id_trans_anm : '0' ?>">
<input type="hidden" id="medical_record_id" value="<?= $row->id_medical_record ?>">

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
                        <span class="ds-detail-value ds-detail-code"><?= htmlspecialchars($row->trans_patient_code ?? '') ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Nama Pasien</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_name ?? '') ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Perusahaan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->trans_patient_company ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Telepon</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->trans_patient_phone ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 0 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Suhu Tubuh (Temperature)</label>
            <input type="text" id="anm_temp" name="anm_temp" class="form-control"
                   placeholder="Cth: 36.5" maxlength="10" autocomplete="off"
                   value="<?= $anamnesa ? htmlspecialchars($anamnesa->anm_temp ?? '') : '' ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nadi (Pulse)</label>
            <input type="text" id="anm_pulse" name="anm_pulse" class="form-control"
                   placeholder="Cth: 80" maxlength="10" autocomplete="off"
                   value="<?= $anamnesa ? htmlspecialchars($anamnesa->anm_pulse ?? '') : '' ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Pernapasan (Respirasi)</label>
            <input type="text" id="anm_respirasi" name="anm_respirasi" class="form-control"
                   placeholder="Cth: 20" maxlength="10" autocomplete="off"
                   value="<?= $anamnesa ? htmlspecialchars($anamnesa->anm_respirasi ?? '') : '' ?>">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tekanan Darah (Blood Press)</label>
            <input type="text" id="anm_blood_press" name="anm_blood_press" class="form-control"
                   placeholder="Cth: 120/80" maxlength="10" autocomplete="off"
                   value="<?= $anamnesa ? htmlspecialchars($anamnesa->anm_blood_press ?? '') : '' ?>">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tinggi Badan (Height)</label>
            <input type="text" id="anm_height" name="anm_height" class="form-control"
                   placeholder="Cth: 165" maxlength="10" autocomplete="off"
                   value="<?= $anamnesa ? htmlspecialchars($anamnesa->anm_height ?? '') : '' ?>">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Berat Badan (Weight)</label>
            <input type="text" id="anm_weight" name="anm_weight" class="form-control"
                   placeholder="Cth: 65" maxlength="10" autocomplete="off"
                   value="<?= $anamnesa ? htmlspecialchars($anamnesa->anm_weight ?? '') : '' ?>">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Lingkar Perut (Stomatch)</label>
            <input type="text" id="anm_stomatch_wide" name="anm_stomatch_wide" class="form-control"
                   placeholder="Cth: 90" maxlength="10" autocomplete="off"
                   value="<?= $anamnesa ? htmlspecialchars($anamnesa->anm_stomatch_wide ?? '') : '' ?>">
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Catatan (Anamnesa)</label>
    <textarea id="anm_note" name="anm_note" class="form-control" rows="3"
              placeholder="Catatan anamnesa / keluhan pasien" maxlength="225"><?= $anamnesa ? htmlspecialchars($anamnesa->anm_note ?? '') : '' ?></textarea>
</div>
