<!-- Nomor Dokumen -->
<div class="ds-form-group">
    <label>No. Dokumen / Nomor SKBS <span class="text-danger">*</span></label>
    <input type="text" id="skbs_docnumb" name="skbs_docnumb" class="form-control" maxlength="100"
           value="<?= htmlspecialchars($skbs_docnumb_default ?? '00000/SKBS/PDUKRWP/ASM') ?>">
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 12px;">

<!-- Data Pasien -->
<div class="row" style="margin: 0 -6px;">
    <div class="col-md-8" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nama Pasien <span class="text-danger">*</span></label>
            <input type="text" id="patient_name" name="patient_name" class="form-control"
                   placeholder="Nama lengkap pasien" maxlength="100" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. ID Card</label>
            <input type="text" id="patient_nik" name="patient_nik" class="form-control"
                   placeholder="No. ID Card" maxlength="20" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. KTP</label>
            <input type="text" id="patient_ktp" name="patient_ktp" class="form-control"
                   placeholder="No. KTP" maxlength="20" autocomplete="off">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jenis Kelamin</label>
            <select id="skbs_gender" name="skbs_gender" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tempat Lahir</label>
            <input type="text" id="skbs_birth_place" name="skbs_birth_place" class="form-control"
                   placeholder="Tempat lahir" maxlength="225" autocomplete="off">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Lahir</label>
            <input type="text" id="skbs_bod" name="skbs_bod" class="form-control date-mask"
                   placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off">
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Alamat</label>
    <textarea id="skbs_address" name="skbs_address" class="form-control" rows="2"
              placeholder="Alamat pasien" maxlength="255"></textarea>
</div>

<!-- Hasil Pemeriksaan -->
<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Hasil <span class="text-danger">*</span></label>
            <select id="skbs_result" name="skbs_result" class="form-control" onchange="toggleSkbsDesc(this)">
                <option value="">-- Pilih --</option>
                <option value="FIT">FIT</option>
                <option value="FIT DENGAN CATATAN">FIT dengan Catatan</option>
                <option value="UNFIT">UNFIT</option>
                <option value="KETERANGAN">Keterangan</option>
            </select>
        </div>
    </div>
    <div class="col-md-8" style="padding: 0 6px; display:none;" id="skbs_desc_wrap">
        <div class="ds-form-group">
            <label>Keterangan</label>
            <input type="text" id="skbs_desc" name="skbs_desc" class="form-control" placeholder="Keterangan hasil">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tekanan Darah</label>
            <div class="input-group">
                <input type="text" id="skbs_blood_press" name="skbs_blood_press" class="form-control" placeholder="cth: 120/80">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">mmHg</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nadi</label>
            <div class="input-group">
                <input type="text" id="skbs_pulse" name="skbs_pulse" class="form-control" placeholder="cth: 80">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">x/menit</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Respirasi</label>
            <div class="input-group">
                <input type="text" id="skbs_respirasi" name="skbs_respirasi" class="form-control" placeholder="cth: 20">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">x/menit</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Suhu Tubuh</label>
            <div class="input-group">
                <input type="text" id="skbs_temp" name="skbs_temp" class="form-control" placeholder="cth: 36.5">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">&deg;C</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tinggi Badan (TB)</label>
            <div class="input-group">
                <input type="text" id="skbs_tb" name="skbs_tb" class="form-control" placeholder="cth: 165">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">Cm</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Berat Badan (BB)</label>
            <div class="input-group">
                <input type="text" id="skbs_bb" name="skbs_bb" class="form-control" placeholder="cth: 65">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">Kg</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Buta Warna (BW)</label>
            <input type="text" id="skbs_bw" name="skbs_bw" class="form-control" placeholder="cth: Normal / Parsial">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px; display:none;" id="skbs_note_wrap">
        <div class="ds-form-group">
            <label>Catatan</label>
            <input type="text" id="skbs_note" name="skbs_note" class="form-control" placeholder="Catatan">
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 12px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-8" style="padding: 0 6px;">
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
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen</label>
            <input type="text" id="docdate" name="docdate" class="form-control date-mask"
                   placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off" value="<?= date('d-m-Y') ?>">
        </div>
    </div>
</div>
