<input type="hidden" id="edit_id" name="id" value="<?= $row->id ?>">

<div class="ds-form-group">
    <label>Nama Pengantar <span class="text-danger">*</span></label>
    <input type="text" id="edit_patient_name" name="patient_name" class="form-control"
           value="<?= htmlspecialchars($row->patient_name) ?>" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK <span class="text-danger">*</span></label>
            <input type="text" id="edit_nik" name="nik" class="form-control"
                   value="<?= htmlspecialchars($row->nik) ?>" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nama Perusahaan <span class="text-danger">*</span></label>
            <input type="text" id="edit_company_name" name="company_name" class="form-control"
                   value="<?= htmlspecialchars($row->company_name) ?>" placeholder="Nama perusahaan" maxlength="200" autocomplete="off">
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Bagian</label>
    <input type="text" id="edit_bagian" name="bagian" class="form-control"
           value="<?= htmlspecialchars($row->bagian) ?>" maxlength="200" autocomplete="off">
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Datang <span class="text-danger">*</span></label>
            <input type="text" id="edit_tgl_datang" name="tgl_datang" class="form-control datepicker"
                   value="<?= !empty($row->tgl_datang) ? date('d/m/Y', strtotime($row->tgl_datang)) : '' ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jam <span class="text-danger">*</span></label>
            <input type="text" id="edit_jam" name="jam" class="form-control clockpicker"
                   value="<?= htmlspecialchars($row->jam) ?>"
                   placeholder="--:--" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Hubungan <span class="text-danger">*</span></label>
            <select id="edit_hubungan" name="hubungan" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="SUAMI" <?= $row->hubungan === 'SUAMI' ? 'selected' : '' ?>>Suami</option>
                <option value="ISTRI" <?= $row->hubungan === 'ISTRI' ? 'selected' : '' ?>>Istri</option>
                <option value="ANAK" <?= $row->hubungan === 'ANAK' ? 'selected' : '' ?>>Anak</option>
                <option value="ORANG TUA" <?= $row->hubungan === 'ORANG TUA' ? 'selected' : '' ?>>Orang Tua</option>
                <option value="SAUDARA" <?= $row->hubungan === 'SAUDARA' ? 'selected' : '' ?>>Saudara</option>
                <option value="LAINNYA" <?= $row->hubungan === 'LAINNYA' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="ds-form-group">
    <label>Nama Pasien Yang Diantar <span class="text-danger">*</span></label>
    <input type="text" id="edit_patient_diantar" name="patient_diantar" class="form-control"
           value="<?= htmlspecialchars($row->patient_diantar) ?>" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur Pasien</label>
            <input type="text" id="edit_age_diantar" name="age_diantar" class="form-control"
                   value="<?= htmlspecialchars($row->age_diantar) ?>" maxlength="50" autocomplete="off">
        </div>
    </div>
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
                    $selected = ($row->doct_by_id == $d->id_user) ? 'selected' : '';
                    if ($user_level == 3) {
                        $selected = ($d->id_user == $sess_id) ? 'selected' : '';
                    }
                    echo '<option value="'.$d->id_user.'" '.$selected.'>'.htmlspecialchars($d->fullname).'</option>';
                    ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Alamat Pasien</label>
    <textarea id="edit_alamat_diantar" name="alamat_diantar" class="form-control" rows="2"><?= htmlspecialchars($row->alamat_diantar) ?></textarea>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen</label>
            <input type="text" id="edit_docdate" name="docdate" class="form-control datepicker"
                   value="<?= !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '' ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
</div>
