<div class="ds-form-group">
    <label>No. Dokumen <span class="text-danger">*</span></label>
    <input type="text" id="docnumb" name="docnumb" class="form-control"
           value="<?= isset($docnumb) ? htmlspecialchars($docnumb) : '' ?>"
           maxlength="100" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>Nama Pasien <span class="text-danger">*</span></label>
    <input type="text" id="patient_name" name="patient_name" class="form-control"
           placeholder="Nama lengkap pasien" maxlength="200" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>No. ID Card</label>
    <input type="text" id="sks_nik" name="sks_nik" class="form-control"
           placeholder="No. ID Card" maxlength="100" autocomplete="off">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur <span class="text-danger">*</span></label>
            <input type="text" id="age" name="age" class="form-control"
                   placeholder="Usia pasien" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Pekerjaan</label>
            <input type="text" id="patient_job" name="patient_job" class="form-control"
                   placeholder="Pekerjaan pasien" maxlength="100" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jenis Kelamin <span class="text-danger">*</span></label>
            <select id="gender" name="gender" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
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
            <select id="doctby" name="doctby" class="form-control autocomplete" <?= $disabled ?>>
                <option value="">-- Pilih --</option>
                <?php foreach ($dokter as $d): ?>
                    <option value="<?= $d->id_user ?>" <?= ($user_level == 3 && $d->id_user == $sess_id) ? 'selected' : '' ?>><?= htmlspecialchars($d->fullname ?? '') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 10px 0 14px;">

<div class="ds-form-group">
    <label>Diagnosa <span class="text-danger">*</span></label>
    <textarea id="diagnosa" name="diagnosa" class="form-control" rows="3"
              placeholder="Diagnosa / keterangan medis"></textarea>
</div>

<div class="ds-form-group">
    <label>Terapi / Obat</label>
    <textarea id="terapi" name="terapi" class="form-control" rows="3"
              placeholder="Nama Terapi dan Dosis"></textarea>
</div>

<div class="ds-form-group">
    <label>Alamat <span class="text-danger">*</span></label>
    <textarea id="alamat" name="alamat" class="form-control" rows="2"
              placeholder="Alamat lengkap pasien"></textarea>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Mulai <span class="text-danger">*</span></label>
            <input type="text" id="datefrom" name="datefrom" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Selesai <span class="text-danger">*</span></label>
            <input type="text" id="dateto" name="dateto" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen <span class="text-danger">*</span></label>
            <input type="text" id="docdate" name="docdate" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" value="<?= date('d-m-Y') ?>" readonly>
        </div>
    </div>
</div>
