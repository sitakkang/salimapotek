<input type="hidden" id="skmb_id" value="<?= $skmb ? $skmb->id : '' ?>">

<?php
// Set timezone WITA untuk jam & tanggal default
$now_tz = new DateTime('now', new DateTimeZone('Asia/Makassar'));
?>

<p class="text-muted" style="margin-bottom:12px;">
    <i class="fa fa-info-circle"></i>
    Data pengantar akan diambil secara otomatis dari data pendaftaran.
</p>

<div class="ds-form-group">
    <label>Nama Pengantar <span class="text-danger">*</span></label>
    <input type="text" id="skmb_patient_name" class="form-control"
           placeholder="Nama lengkap pengantar" maxlength="200" autocomplete="off"
           value="<?= $skmb ? htmlspecialchars($skmb->patient_name) : htmlspecialchars($row->patient_name) ?>">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>NIK <span class="text-danger">*</span></label>
            <input type="text" id="skmb_nik" class="form-control"
                   placeholder="Nomor induk karyawan" maxlength="50" autocomplete="off"
                   value="<?= $skmb ? htmlspecialchars($skmb->nik) : htmlspecialchars($row->patient_nik) ?>">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Perusahaan <span class="text-danger">*</span></label>
            <input type="text" id="skmb_company_name" class="form-control"
                   placeholder="Nama perusahaan" maxlength="200" autocomplete="off"
                   value="<?= $skmb ? htmlspecialchars($skmb->company_name) : htmlspecialchars($row->trans_patient_company) ?>">
        </div>
    </div>
</div>

<div class="ds-form-group">
    <label>Bagian</label>
    <input type="text" id="skmb_bagian" class="form-control"
           placeholder="Bagian / Department" maxlength="200" autocomplete="off"
           value="<?= $skmb ? htmlspecialchars($skmb->bagian) : htmlspecialchars($row->trans_patient_department) ?>">
</div>

<hr style="border-color:var(--ds-border);margin:10px 0 14px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tanggal Datang <span class="text-danger">*</span></label>
            <input type="text" id="skmb_tgl_datang" class="form-control datepicker"
                   placeholder="dd/mm/yyyy" autocomplete="off" readonly
                   value="<?= $skmb && !empty($skmb->tgl_datang) ? date('d/m/Y', strtotime($skmb->tgl_datang)) : $now_tz->format('d/m/Y') ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Jam <span class="text-danger">*</span></label>
            <input type="text" id="skmb_jam" class="form-control clockpicker"
                   placeholder="--:--" autocomplete="off" readonly
                   value="<?= $skmb ? htmlspecialchars($skmb->jam) : $now_tz->format('H:i') ?>">
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Hubungan <span class="text-danger">*</span></label>
            <select id="skmb_hubungan" class="form-control">
                <option value="">-- Pilih --</option>
                <option value="SUAMI" <?= $skmb && $skmb->hubungan == 'SUAMI' ? 'selected' : '' ?>>Suami</option>
                <option value="ISTRI" <?= $skmb && $skmb->hubungan == 'ISTRI' ? 'selected' : '' ?>>Istri</option>
                <option value="ANAK" <?= $skmb && $skmb->hubungan == 'ANAK' ? 'selected' : '' ?>>Anak</option>
                <option value="ORANG TUA" <?= $skmb && $skmb->hubungan == 'ORANG TUA' ? 'selected' : '' ?>>Orang Tua</option>
                <option value="SAUDARA" <?= $skmb && $skmb->hubungan == 'SAUDARA' ? 'selected' : '' ?>>Saudara</option>
                <option value="LAINNYA" <?= $skmb && $skmb->hubungan == 'LAINNYA' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>
    </div>
</div>

<hr style="border-color:var(--ds-border);margin:10px 0 14px;">

<div class="ds-form-group">
    <label>Nama Pasien Yang Diantar <span class="text-danger">*</span></label>
    <input type="text" id="skmb_patient_diantar" class="form-control"
           placeholder="Nama lengkap pasien yang diantar" maxlength="200" autocomplete="off"
           value="<?= $skmb ? htmlspecialchars($skmb->patient_diantar) : '' ?>">
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Umur Pasien</label>
            <input type="text" id="skmb_age_diantar" class="form-control"
                   placeholder="Usia pasien" maxlength="50" autocomplete="off"
                   value="<?= $skmb ? htmlspecialchars($skmb->age_diantar) : '' ?>">
        </div>
    </div>
    <div class="col-md-6" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Alamat Pasien</label>
            <input type="text" id="skmb_alamat_diantar" class="form-control"
                   placeholder="Alamat pasien" maxlength="200" autocomplete="off"
                   value="<?= $skmb ? htmlspecialchars($skmb->alamat_diantar) : htmlspecialchars($row->patient_address) ?>">
        </div>
    </div>
</div>

<hr style="border-color:var(--ds-border);margin:6px 0 12px;">

<div class="text-right">
    <button class="ds-btn-action ds-btn-green" id="btn_simpan_skmb" data-visit-id="<?= $row->id_visit ?>" style="padding:7px 22px;">
        <i class="fa fa-save"></i> <?= $skmb ? 'Simpan Perubahan SKMB' : 'Simpan SKMB' ?>
    </button>
</div>

<?php if ($skmb): ?>
<hr style="border-color:var(--ds-border);margin:16px 0 12px;">
<h6 style="font-weight:700;margin-bottom:10px;">
    <i class="fa fa-list"></i> Data SKMB
</h6>
<div class="ds-wide-table-wrap" style="margin:0;">
    <table class="ds-table" style="margin-top:0;">
        <thead>
            <tr>
                <th>Pengantar</th>
                <th>Pasien Diantar</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>No. Dokumen</th>
                <th width="80" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($skmb->patient_name) ?></td>
                <td><?= htmlspecialchars($skmb->patient_diantar) ?></td>
                <td><?= !empty($skmb->tgl_datang) ? date('d/m/Y', strtotime($skmb->tgl_datang)) : '-' ?></td>
                <td><?= htmlspecialchars($skmb->jam) ?: '-' ?></td>
                <td><?= htmlspecialchars($skmb->docnumb) ?></td>
                <td class="text-center">
                    <a href="<?= site_url('dokter/cetak_skmb/' . $skmb->id) ?>" target="_blank" class="ds-act-btn ds-act-print" title="Cetak SKMB" style="display:inline-flex;padding:3px 8px;">
                        <i class="fa fa-print"></i>
                    </a>
                    <button class="ds-act-btn ds-act-delete del-skmb-btn" data-id="<?= $skmb->id ?>" style="padding:3px 8px;" title="Hapus SKMB">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php endif; ?>
