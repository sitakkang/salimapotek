<div class="ds-form-group">
    <label>Nama Pasien <span class="text-danger">*</span></label>
    <input type="text" id="patient_name" name="patient_name" class="form-control"
           placeholder="Nama lengkap pasien" maxlength="100" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. ID Card</label>
            <input type="text" id="patient_nik" name="patient_nik" class="form-control"
                   placeholder="Nomor Induk Karyawan" maxlength="10" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. KTP</label>
            <input type="text" id="patient_ktp" name="patient_ktp" class="form-control"
                   placeholder="Nomor KTP" maxlength="20" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan</label>
            <input type="text" id="patient_company" name="patient_company" class="form-control"
                   placeholder="Nama perusahaan" maxlength="100" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Pekerjaan</label>
            <input type="text" id="patient_job" name="patient_job" class="form-control"
                   value="KARYAWAN" placeholder="Pekerjaan pasien" maxlength="100" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tempat Lahir</label>
            <input type="text" id="patient_birth_place" name="patient_birth_place" class="form-control"
                   placeholder="Tempat lahir" maxlength="225" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Lahir <span class="text-danger">*</span></label>
            <input type="text" id="patient_bod" name="patient_bod" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jenis Kelamin <span class="text-danger">*</span></label>
            <select id="patient_gender" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. Telepon <span class="text-danger">*</span></label>
            <input type="text" id="patient_phone" name="patient_phone" class="form-control"
                   placeholder="Nomor telepon / HP" maxlength="25" autocomplete="off">
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Alamat <span class="text-danger">*</span></label>
    <select id="patient_address" name="patient_address" class="form-control autocomplete" data-placeholder="Pilih kecamatan">
        <option value="">-- Pilih Kecamatan --</option>
        <?php foreach ($districts as $d): ?>
            <option value="<?= htmlspecialchars($d->district_name ?? '') ?>"><?= htmlspecialchars($d->district_name ?? '') ?></option>
        <?php endforeach; ?>
    </select>
</div>
