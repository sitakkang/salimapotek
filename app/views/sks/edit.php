<input type="hidden" id="edit_id" name="id" value="<?= $row->id ?>">

<div class="ds-form-group">
    <label>No. Dokumen <span class="text-danger">*</span></label>
    <input type="text" id="edit_docnumb" name="docnumb" class="form-control"
           value="<?= htmlspecialchars($row->docnumb ?? '') ?>" maxlength="100" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>Nama Pasien <span class="text-danger">*</span></label>
    <input type="text" id="edit_patient_name" name="patient_name" class="form-control"
           value="<?= htmlspecialchars($row->patient_name ?? '') ?>" maxlength="200" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>NIK / No. ID Card</label>
    <input type="text" id="edit_sks_nik" name="sks_nik" class="form-control"
           value="<?= htmlspecialchars($row->sks_nik ?? '') ?>" maxlength="100" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur <span class="text-danger">*</span></label>
            <input type="text" id="edit_age" name="age" class="form-control"
                   value="<?= htmlspecialchars($row->age ?? '') ?>" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Pekerjaan</label>
            <input type="text" id="edit_patient_job" name="patient_job" class="form-control"
                   value="<?= htmlspecialchars($row->patient_job ?? '') ?>" placeholder="Pekerjaan pasien" maxlength="100" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jenis Kelamin <span class="text-danger">*</span></label>
            <select id="edit_gender" name="gender" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="L" <?= $row->gender === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= $row->gender === 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Dokter / Petugas <span class="text-danger">*</span></label>
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
                    // Jika level 3, paksa pilih user yang login
                    if ($user_level == 3) {
                        $selected = ($d->id_user == $sess_id) ? 'selected' : '';
                    }
                    echo '<option value="'.$d->id_user.'" '.$selected.'>'.htmlspecialchars($d->fullname ?? '').'</option>';
                    ?>
                <?php endforeach; ?>
            </select>
            </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="ds-form-group">
    <label>Diagnosa <span class="text-danger">*</span></label>
    <textarea id="edit_diagnosa" name="diagnosa" class="form-control" rows="3"><?= htmlspecialchars($row->diagnosa ?? '') ?></textarea>
</div>

<div class="ds-form-group">
    <label>Terapi / Obat</label>
    <textarea id="edit_terapi" name="terapi" class="form-control" rows="3"
              placeholder="Nama Terapi dan Dosis"><?= htmlspecialchars($row->terapi ?? '') ?></textarea>
</div>

<div class="ds-form-group">
    <label>Alamat <span class="text-danger">*</span></label>
    <textarea id="edit_alamat" name="alamat" class="form-control" rows="2"
              placeholder="Alamat lengkap pasien"><?= htmlspecialchars($row->alamat ?? '') ?></textarea>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Mulai</label>
            <input type="text" id="edit_datefrom" name="datefrom" class="form-control datepicker"
                   value="<?= !empty($row->datefrom) ? date('d/m/Y', strtotime($row->datefrom)) : '' ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Selesai</label>
            <input type="text" id="edit_dateto" name="dateto" class="form-control datepicker"
                   value="<?= !empty($row->dateto) ? date('d/m/Y', strtotime($row->dateto)) : '' ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen</label>
            <input type="text" id="edit_docdate" name="docdate" class="form-control datepicker"
                   value="<?= !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '' ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
</div>
