<div class="ds-form-group">
    <label>Nama Pengantar <span class="text-danger">*</span></label>
    <input type="text" id="patient_name" name="patient_name" class="form-control"
           placeholder="Nama lengkap pengantar" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK <span class="text-danger">*</span></label>
            <input type="text" id="nik" name="nik" class="form-control"
                   placeholder="Nomor induk karyawan" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nama Perusahaan <span class="text-danger">*</span></label>
            <input type="text" id="company_name" name="company_name" class="form-control"
                   placeholder="Nama perusahaan" maxlength="200" autocomplete="off">
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Bagian</label>
    <input type="text" id="bagian" name="bagian" class="form-control"
           placeholder="Bagian / Department" maxlength="200" autocomplete="off">
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

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

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="ds-form-group">
    <label>Nama Pasien Yang Diantar <span class="text-danger">*</span></label>
    <input type="text" id="patient_diantar" name="patient_diantar" class="form-control"
           placeholder="Nama lengkap pasien yang diantar" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur Pasien</label>
            <input type="text" id="age_diantar" name="age_diantar" class="form-control"
                   placeholder="Usia pasien" maxlength="50" autocomplete="off">
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
            <select id="doctby" name="doctby" class="form-control autocomplete" <?= $disabled ?>>
                <option value="">-- Pilih --</option>
                <?php foreach ($dokter as $d): ?>
                    <option value="<?= $d->id_user ?>" <?= ($user_level == 3 && $d->id_user == $sess_id) ? 'selected' : '' ?>><?= htmlspecialchars($d->fullname) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Alamat Pasien</label>
    <textarea id="alamat_diantar" name="alamat_diantar" class="form-control" rows="2"
              placeholder="Alamat lengkap pasien"></textarea>
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
