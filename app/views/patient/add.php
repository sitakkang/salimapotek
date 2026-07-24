<div class="ds-form-group">
    <label>Nama Pasien <span class="text-danger">*</span></label>
    <input type="text" id="patient_name" name="patient_name" class="form-control"
           placeholder="Nama lengkap pasien" maxlength="100" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK</label>
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
            <label>Bagian / Departemen</label>
            <input type="text" id="patient_department" name="patient_department" class="form-control"
                   placeholder="Bagian atau departemen" maxlength="100" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jenis Kelamin <span class="text-danger">*</span></label>
            <select id="patient_gender" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Lahir <span class="text-danger">*</span></label>
            <input type="text" id="patient_bod" name="patient_bod" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. Telepon <span class="text-danger">*</span></label>
            <input type="text" id="patient_phone" name="patient_phone" class="form-control"
                   placeholder="Nomor telepon / HP" maxlength="25" autocomplete="off">
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Provinsi <span class="text-danger">*</span></label>
            <input type="text" id="provinsi" name="provinsi" class="form-control"
                   value="SULAWESI TENGAH" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Kabupaten <span class="text-danger">*</span></label>
            <input type="text" id="kabupaten" name="kabupaten" class="form-control"
                   value="MOROWALI" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Kecamatan <span class="text-danger">*</span></label>
            <select id="select_kecamatan" class="form-control autocomplete">
                <option value="">-- Pilih --</option>
                <?php echo $kecamatan; ?>
            </select>
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Kelurahan <span class="text-danger">*</span></label>
            <select id="select_kelurahan" class="form-control autocomplete">
                <?php echo $kelurahan; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Alamat <span class="text-danger">*</span></label>
            <textarea id="patient_address" name="patient_address" class="form-control" rows="2"
                      placeholder="Alamat lengkap" maxlength="100"></textarea>
        </div>
    </div>
</div>
