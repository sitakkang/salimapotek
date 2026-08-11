<input type="hidden" id="edit_id" name="id" value="<?= $row->id ?>">

<div class="ds-form-group">
    <label>Nama <span class="text-danger">*</span></label>
    <input type="text" id="edit_patient_name" name="patient_name" class="form-control"
           value="<?= htmlspecialchars($row->patient_name ?? '') ?>" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur</label>
            <input type="text" id="edit_age" name="age" class="form-control"
                   value="<?= htmlspecialchars($row->age ?? '') ?>" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK <span class="text-danger">*</span></label>
            <input type="text" id="edit_nik" name="nik" class="form-control"
                   value="<?= htmlspecialchars($row->nik ?? '') ?>" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nama Perusahaan <span class="text-danger">*</span></label>
            <input type="text" id="edit_company_name" name="company_name" class="form-control"
                   value="<?= htmlspecialchars($row->company_name ?? '') ?>" placeholder="Nama perusahaan" maxlength="200" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Departemen</label>
            <input type="text" id="edit_bagian" name="bagian" class="form-control"
                   value="<?= htmlspecialchars($row->bagian ?? '') ?>" maxlength="200" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jabatan <span class="text-danger">*</span></label>
            <input type="text" id="edit_jabatan" name="jabatan" class="form-control"
                   value="<?= htmlspecialchars($row->jabatan ?? '') ?>" maxlength="200" autocomplete="off">
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="ds-form-group">
    <label>Catatan Dokter</label>
    <textarea id="edit_catatan" name="catatan" class="form-control" rows="2"><?= htmlspecialchars($row->catatan ?? '') ?></textarea>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Dokter Pemeriksa <span class="text-danger">*</span></label>
            <?php
            $user_level = $this->session->userdata('sess_level');
            $sess_id    = $this->session->userdata('sess_id');
            $disabled   = ($user_level == 3) ? 'disabled' : '';
            ?>
            <select id="edit_doctby" name="doctby" class="form-control autocomplete" <?= $disabled ?>>
                <option value="">-- Pilih --</option>
                <?php foreach ($dokter as $d): ?>
                    <?php
                    $selected = ($row->doctby == $d->id_user) ? 'selected' : '';
                    if ($user_level == 3) {
                        $selected = ($d->id_user == $sess_id) ? 'selected' : '';
                    }
                    echo '<option value="'.$d->id_user.'" '.$selected.'>'.htmlspecialchars($d->fullname ?? '').'</option>';
                    ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen</label>
            <input type="text" id="edit_docdate" name="docdate" class="form-control datepicker"
                   value="<?= !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '' ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
</div>
