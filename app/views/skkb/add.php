<div class="ds-form-group">
    <label>Nama <span class="text-danger">*</span></label>
    <input type="text" id="patient_name" name="patient_name" class="form-control"
           placeholder="Nama lengkap karyawan" maxlength="200" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur</label>
            <input type="text" id="age" name="age" class="form-control"
                   placeholder="Usia" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK <span class="text-danger">*</span></label>
            <input type="text" id="nik" name="nik" class="form-control"
                   placeholder="Nomor induk karyawan" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nama Perusahaan <span class="text-danger">*</span></label>
            <input type="text" id="company_name" name="company_name" class="form-control"
                   placeholder="Nama perusahaan" maxlength="200" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Departemen</label>
            <input type="text" id="bagian" name="bagian" class="form-control"
                   placeholder="Departemen / Bagian" maxlength="200" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jabatan <span class="text-danger">*</span></label>
            <input type="text" id="jabatan" name="jabatan" class="form-control"
                   placeholder="Jabatan" maxlength="200" autocomplete="off">
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="ds-form-group">
    <label>Catatan Dokter</label>
    <textarea id="catatan" name="catatan" class="form-control" rows="2"
              placeholder="Catatan dokter (jika ada)"></textarea>
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
            <select id="doctby" name="doctby" class="form-control autocomplete" <?= $disabled ?>>
                <option value="">-- Pilih --</option>
                <?php foreach ($dokter as $d): ?>
                    <option value="<?= $d->id_user ?>" <?= ($user_level == 3 && $d->id_user == $sess_id) ? 'selected' : '' ?>><?= htmlspecialchars($d->fullname) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen</label>
            <input type="text" id="docdate" name="docdate" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" value="<?= date('d-m-Y') ?>" readonly>
        </div>
    </div>
</div>
