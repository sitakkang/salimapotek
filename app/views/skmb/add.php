<!-- Nomor Dokumen -->
<div class="ds-form-group">
    <label>No. Dokumen / Nomor SKMB <span class="text-danger">*</span></label>
    <input type="text" id="skmb_docnumb" name="skmb_docnumb" class="form-control" maxlength="100"
           value="<?= htmlspecialchars($skmb_docnumb_default ?? '00000/SKMB') ?>">
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="ds-form-group">
    <label>Pengantar <span class="text-danger">*</span></label>
    <input type="text" id="pengantar" name="pengantar" class="form-control"
           placeholder="Nama lengkap pengantar" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. ID Card Pengantar</label>
            <input type="text" id="nik_pengantar" name="nik_pengantar" class="form-control"
                   placeholder="No. ID Card pengantar" maxlength="20" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan Pengantar</label>
            <input type="text" id="company_pengantar" name="company_pengantar" class="form-control"
                   placeholder="Perusahaan pengantar" maxlength="20" autocomplete="off">
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="ds-form-group">
    <label>Nama Diantar <span class="text-danger">*</span></label>
    <input type="text" id="patient_name" name="patient_name" class="form-control"
           placeholder="Nama lengkap yang diantar" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. ID Card Diantar</label>
            <input type="text" id="nik" name="nik" class="form-control"
                   placeholder="No. ID Card yang diantar" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan Diantar</label>
            <input type="text" id="company_name" name="company_name" class="form-control"
                   placeholder="Nama perusahaan" maxlength="200" autocomplete="off">
        </div>
    </div>
</div>



<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Datang <span class="text-danger">*</span></label>
            <input type="text" id="tgl_datang" name="tgl_datang" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jam <span class="text-danger">*</span></label>
            <input type="text" id="jam" name="jam" class="form-control clockpicker"
                   placeholder="--:--" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Hubungan <span class="text-danger">*</span></label>
            <select id="hubungan" name="hubungan" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="SUAMI">Suami</option>
                <option value="ISTRI">Istri</option>
                <option value="ANAK">Anak</option>
                <option value="ORANG TUA">Orang Tua</option>
                <option value="SAUDARA">Saudara</option>
                <option value="LAINNYA">Lainnya</option>
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
    <select id="doctby" name="doctby" class="form-control autocomplete" <?= $disabled ?>>
        <option value="">-- Pilih --</option>
        <?php foreach ($dokter as $d): ?>
            <option value="<?= $d->id_user ?>" <?= ($user_level == 3 && $d->id_user == $sess_id) ? 'selected' : '' ?>><?= htmlspecialchars($d->fullname ?? '') ?></option>
        <?php endforeach; ?>
    </select>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen</label>
            <input type="text" id="docdate" name="docdate" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" value="<?= date('d-m-Y') ?>" readonly>
        </div>
    </div>
</div>
