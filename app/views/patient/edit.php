<input type="hidden" id="edit_id" name="id" value="<?= $row->id_patient ?>">

<div class="ds-form-group">
    <label>Nama Pasien <span class="text-danger">*</span></label>
    <input type="text" id="edit_patient_name" name="patient_name" class="form-control"
           value="<?= htmlspecialchars($row->patient_name) ?>" maxlength="100" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK</label>
            <input type="text" id="edit_patient_nik" name="patient_nik" class="form-control"
                   value="<?= htmlspecialchars($row->patient_nik) ?>" maxlength="10" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. KTP</label>
            <input type="text" id="edit_patient_ktp" name="patient_ktp" class="form-control"
                   value="<?= htmlspecialchars($row->patient_ktp) ?>" placeholder="Nomor KTP" maxlength="20" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan</label>
            <input type="text" id="edit_patient_company" name="patient_company" class="form-control"
                   value="<?= htmlspecialchars($row->patient_company) ?>" placeholder="Nama perusahaan" maxlength="100" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Bagian / Departemen</label>
            <input type="text" id="edit_patient_department" name="patient_department" class="form-control"
                   value="<?= htmlspecialchars($row->patient_department) ?>" placeholder="Bagian atau departemen" maxlength="100" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jenis Kelamin <span class="text-danger">*</span></label>
            <select id="edit_patient_gender" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="L" <?= $row->patient_gender === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= $row->patient_gender === 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Lahir <span class="text-danger">*</span></label>
            <input type="text" id="edit_patient_bod" name="patient_bod" class="form-control datepicker"
                   value="<?= !empty($row->patient_bod) ? date('d/m/Y', strtotime($row->patient_bod)) : '' ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. Telepon <span class="text-danger">*</span></label>
            <input type="text" id="edit_patient_phone" name="patient_phone" class="form-control"
                   value="<?= htmlspecialchars($row->patient_phone) ?>" placeholder="Nomor telepon / HP" maxlength="25" autocomplete="off">
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Provinsi <span class="text-danger">*</span></label>
            <input type="text" id="edit_provinsi" name="provinsi" class="form-control"
                   value="SULAWESI TENGAH" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Kabupaten <span class="text-danger">*</span></label>
            <input type="text" id="edit_kabupaten" name="kabupaten" class="form-control"
                   value="MOROWALI" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Kecamatan <span class="text-danger">*</span></label>
            <select id="edit_kecamatan" class="form-control autocomplete">
                <option value="">-- Pilih --</option>
                <?php 
                    $kecamatan = $this->M_patient->get_kecamatan();
                    foreach ($kecamatan as $k):
                ?>
                    <option value="<?= $k->id_city ?>" <?= ($row->patient_city_id == $k->id_city) ? 'selected' : '' ?>><?= htmlspecialchars($k->city_name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Kelurahan <span class="text-danger">*</span></label>
            <select id="edit_kelurahan" class="form-control autocomplete">
                <?php 
                    $kelurahan = $this->M_patient->get_kelurahan();
                    foreach ($kelurahan as $kl):
                ?>
                    <option value="<?= $kl->id_district ?>" <?= ($row->patient_district_id == $kl->id_district) ? 'selected' : '' ?>><?= htmlspecialchars($kl->district_name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Alamat <span class="text-danger">*</span></label>
            <textarea id="edit_patient_address" name="patient_address" class="form-control" rows="2"
                      placeholder="Alamat lengkap" maxlength="100"><?= htmlspecialchars($row->patient_address) ?></textarea>
        </div>
    </div>
</div>
