<input type="hidden" id="edit_id" name="id" value="<?= $row->id ?>">

<!-- Nomor Dokumen -->
<div class="ds-form-group">
    <label>No. Dokumen / Nomor SKMB <span class="text-danger">*</span></label>
    <input type="text" id="edit_skmb_docnumb" name="skmb_docnumb" class="form-control" maxlength="100"
           value="<?= htmlspecialchars(!empty($row->docnumb) ? $row->docnumb : ($skmb_docnumb_default ?? '00000/SKMB')) ?>">
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="ds-form-group">
    <label>Pengantar <span class="text-danger">*</span></label>
    <input type="text" id="edit_pengantar" name="pengantar" class="form-control"
           value="<?= htmlspecialchars($row->pengantar ?? '') ?>" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. ID Card Pengantar</label>
            <input type="text" id="edit_nik_pengantar" name="nik_pengantar" class="form-control"
                   value="<?= htmlspecialchars($row->nik_pengantar ?? '') ?>" maxlength="20" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan Pengantar</label>
            <input type="text" id="edit_company_pengantar" name="company_pengantar" class="form-control"
                   value="<?= htmlspecialchars($row->company_pengantar ?? '') ?>" maxlength="20" autocomplete="off">
        </div>
    </div>
</div>
<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="ds-form-group">
    <label>Nama Diantar <span class="text-danger">*</span></label>
    <input type="text" id="edit_patient_name" name="patient_name" class="form-control"
           value="<?= htmlspecialchars($row->patient_name ?? '') ?>" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. ID Card Diantar</label>
            <input type="text" id="edit_nik" name="nik" class="form-control"
                   value="<?= htmlspecialchars($row->nik ?? '') ?>" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan Diantar</label>
            <input type="text" id="edit_company_name" name="company_name" class="form-control"
                   value="<?= htmlspecialchars($row->company_name ?? '') ?>" placeholder="Nama perusahaan" maxlength="200" autocomplete="off">
        </div>
    </div>
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
                   value="<?= htmlspecialchars($row->jam ?? '') ?>"
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

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

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
            echo '<option value="'.$d->id_user.'" '.$selected.'>'.htmlspecialchars($d->fullname ?? '').'</option>';
            ?>
        <?php endforeach; ?>
    </select>
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
