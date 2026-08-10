<input type="hidden" id="edit_id" name="id" value="<?= $row->id_skbs ?>">

<!-- Data Pasien -->
<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Nama Pasien <span class="text-danger">*</span></label>
            <input type="text" id="edit_patient_name" name="patient_name" class="form-control"
                   value="<?= htmlspecialchars($row->skbs_patient_name) ?>" maxlength="100" autocomplete="off">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK <span class="text-danger">*</span></label>
            <input type="text" id="edit_patient_nik" name="patient_nik" class="form-control"
                   value="<?= htmlspecialchars($row->skbs_patient_nik) ?>" maxlength="20" autocomplete="off">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur <span class="text-danger">*</span></label>
            <input type="text" id="edit_patient_age" name="patient_age" class="form-control"
                   value="<?= htmlspecialchars($row->skbs_patient_age) ?>" maxlength="10" autocomplete="off">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. KTP</label>
            <input type="text" id="edit_patient_ktp" name="patient_ktp" class="form-control"
                   value="<?= htmlspecialchars($row->skbs_patient_ktp) ?>" maxlength="20" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan</label>
            <input type="text" id="edit_patient_company" name="patient_company" class="form-control"
                   value="<?= htmlspecialchars($row->skbs_patient_company) ?>" maxlength="100" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Departemen</label>
            <input type="text" id="edit_patient_department" name="patient_department" class="form-control"
                   value="<?= htmlspecialchars($row->skbs_patient_department) ?>" maxlength="100" autocomplete="off">
        </div>
    </div>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 12px;">

<!-- Hasil Pemeriksaan -->
<?php
$result_name = isset($row->skbs_result_name) ? $row->skbs_result_name : '';
$show_desc   = ($result_name == 'KETERANGAN') ? '' : 'display:none;';
$show_note   = ($result_name == 'FIT DENGAN CATATAN') ? '' : 'display:none;';
?>
<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Hasil <span class="text-danger">*</span></label>
            <select id="edit_skbs_result" name="skbs_result" class="form-control" onchange="toggleSkbsDesc(this)">
                <option value="">-- Pilih --</option>
                <option value="FIT" <?= $result_name == 'FIT' ? 'selected' : '' ?>>FIT</option>
                <option value="FIT DENGAN CATATAN" <?= $result_name == 'FIT DENGAN CATATAN' ? 'selected' : '' ?>>FIT dengan Catatan</option>
                <option value="UNFIT" <?= $result_name == 'UNFIT' ? 'selected' : '' ?>>UNFIT</option>
                <option value="KETERANGAN" <?= $result_name == 'KETERANGAN' ? 'selected' : '' ?>>Keterangan</option>
            </select>
        </div>
    </div>
    <div class="col-md-8" style="padding: 0 6px;<?= $show_desc ?>" id="skbs_desc_wrap">
        <div class="ds-form-group">
            <label>Keterangan</label>
            <input type="text" id="edit_skbs_desc" name="skbs_desc" class="form-control" placeholder="Keterangan hasil"
                   value="<?= htmlspecialchars($row->skbs_desc) ?>">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tekanan Darah (TD)</label>
            <div class="input-group">
                <input type="text" id="edit_skbs_td" name="skbs_td" class="form-control" placeholder="cth: 120/80"
                       value="<?= htmlspecialchars($row->skbs_td) ?>">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">mmHg</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tinggi Badan (TB)</label>
            <div class="input-group">
                <input type="text" id="edit_skbs_tb" name="skbs_tb" class="form-control" placeholder="cth: 165"
                       value="<?= htmlspecialchars($row->skbs_tb) ?>">
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
                <input type="text" id="edit_skbs_bb" name="skbs_bb" class="form-control" placeholder="cth: 65"
                       value="<?= htmlspecialchars($row->skbs_bb) ?>">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">Kg</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Buta Warna (BW)</label>
            <input type="text" id="edit_skbs_bw" name="skbs_bw" class="form-control" placeholder="cth: Normal / Parsial"
                   value="<?= htmlspecialchars($row->skbs_bw) ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Visus Kanan (R)</label>
            <input type="text" id="edit_skbs_r" name="skbs_r" class="form-control" placeholder="cth: 6/6"
                   value="<?= htmlspecialchars($row->skbs_r) ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Visus Kiri (L)</label>
            <input type="text" id="edit_skbs_l" name="skbs_l" class="form-control" placeholder="cth: 6/6"
                   value="<?= htmlspecialchars($row->skbs_l) ?>">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Visus Koreksi (R)</label>
            <input type="text" id="edit_skbs_koreksi_r" name="skbs_koreksi_r" class="form-control" placeholder="cth: 6/6"
                   value="<?= htmlspecialchars($row->skbs_koreksi_r) ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Visus Koreksi (L)</label>
            <input type="text" id="edit_skbs_koreksi_l" name="skbs_koreksi_l" class="form-control" placeholder="cth: 6/6"
                   value="<?= htmlspecialchars($row->skbs_koreksi_l) ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;<?= $show_note ?>" id="skbs_note_wrap">
        <div class="ds-form-group">
            <label>Catatan</label>
            <input type="text" id="edit_skbs_note" name="skbs_note" class="form-control" placeholder="Catatan"
                   value="<?= htmlspecialchars($row->skbs_note) ?>">
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
            <select id="edit_doctby" name="doctby" class="form-control autocomplete" <?= $disabled ?>>
                <option value="">-- Pilih --</option>
                <?php foreach ($dokter as $d): ?>
                    <?php
                    $selected = ($row->skbs_doct_id == $d->id_user) ? 'selected' : '';
                    // Jika level 3, paksa pilih user yang login
                    if ($user_level == 3) {
                        $selected = ($d->id_user == $sess_id) ? 'selected' : '';
                    }
                    echo '<option value="'.$d->id_user.'" '.$selected.'>'.htmlspecialchars($d->fullname).'</option>';
                    ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Dokumen</label>
            <input type="text" id="edit_docdate" name="docdate" class="form-control datepicker"
                   value="<?= !empty($row->skbs_doc_date) ? date('d/m/Y', strtotime($row->skbs_doc_date)) : date('d/m/Y') ?>"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
        </div>
    </div>
</div>
