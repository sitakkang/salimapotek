<input type="hidden" id="sks_id" value="<?= $sks ? $sks->id : '' ?>">

<p class="text-muted" style="margin-bottom:12px;">
    <i class="fa fa-info-circle"></i>
    Data diri pasien akan diambil secara otomatis dari data pendaftaran.
    <?php if ($sks): ?>
        <br><strong>SKS sudah ada:</strong> No. <?= htmlspecialchars($sks->docnumb ?? '') ?>
    <?php endif; ?>
</p>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. ID Card</label>
            <input type="text" id="sks_nik" class="form-control" maxlength="100"
                   value="<?= htmlspecialchars($sks && !empty($sks->sks_nik) ? $sks->sks_nik : ($row->patient_nik ?? '')) ?>">
        </div>
    </div>
    <div class="col-md-8" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. Dokumen / Nomor SKS <span class="text-danger">*</span></label>
            <input type="text" id="sks_docnumb" class="form-control" maxlength="100"
                   value="<?= htmlspecialchars($sks && !empty($sks->docnumb) ? $sks->docnumb : ($sks_docnumb_default ?? '00000/SKS/PMDMH')) ?>">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Dokter / Petugas <span class="text-danger">*</span></label>
            <input type="hidden" id="sks_doctby" value="<?= intval($row->trans_doct_by) ?>">
            <input type="text" class="form-control" value="<?= htmlspecialchars($row->trans_doct_name ?: 'Belum ditentukan') ?>" readonly>
        </div>
    </div>
    <div class="col-md-8" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Diagnosa / Keterangan <span class="text-danger">*</span></label>
            <textarea id="sks_diagnosa" class="form-control" rows="2" placeholder="Diagnosa"><?= htmlspecialchars(($sks ? $sks->diagnosa : $sks_diagnosa_default) ?? '') ?></textarea>
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-9" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Obat / Terapi</label>
            <textarea id="sks_terapi" class="form-control" rows="2" placeholder="Obat dan dosis"><?= htmlspecialchars(($sks ? $sks->terapi : $sks_terapi_default) ?? '') ?></textarea>
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tgl Dokumen <span class="text-danger">*</span></label>
            <input type="text" id="sks_docdate" class="form-control date-mask" placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off" value="<?= $sks && !empty($sks->docdate) ? date('d-m-Y', strtotime($sks->docdate)) : date('d-m-Y') ?>">
        </div>
    </div>
</div>
<div class="row" style="margin: 0 -6px;">
    
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Berlaku Dari <span class="text-danger">*</span></label>
            <input type="text" id="sks_datefrom" class="form-control date-mask" value="<?= $sks && !empty($sks->datefrom) ? date('d-m-Y', strtotime($sks->datefrom)) : date('d-m-Y') ?>" placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Sampai <span class="text-danger">*</span></label>
            <input type="text" id="sks_dateto" class="form-control date-mask" value="<?= $sks && !empty($sks->dateto) ? date('d-m-Y', strtotime($sks->dateto)) : date('d-m-Y') ?>" placeholder="dd-mm-yyyy" maxlength="10" autocomplete="off">
        </div>
    </div>
</div>

<hr style="border-color:var(--ds-border);margin:10px 0 14px;">

<div class="text-right">
    <button class="ds-btn-action ds-btn-green" id="btn_buat_sks" style="padding:8px 24px;">
        <i class="fa fa-<?= $sks ? 'save' : 'file' ?>"></i> <?= $sks ? 'Simpan Perubahan SKS' : 'Buat SKS' ?>
    </button>
</div>

<?php if (!empty($sks_list)): ?>
<hr style="border-color:var(--ds-border);margin:16px 0 12px;">
<h6 style="font-weight:700;margin-bottom:10px;">
    <i class="fa fa-list"></i> SKS yang sudah dibuat
</h6>
<div class="ds-wide-table-wrap" style="margin:0;">
    <table class="ds-table" style="margin-top:0;">
        <thead>
            <tr>
                <th width="140">No. Dokumen</th>
                <th>Diagnosa</th>
                <th width="110">Tgl Dokumen</th>
                <th width="110">Berlaku</th>
                <th width="100" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sks_list as $s): ?>
            <tr>
                <td style="vertical-align: top;"><?= htmlspecialchars($s->docnumb ?? '') ?></td>
                <td style="vertical-align: top;"><?= nl2br($s->diagnosa) ?></td>
                <td class="text-center" style="vertical-align: top;"><?= !empty($s->docdate) ? date('d/m/Y', strtotime($s->docdate)) : '-' ?></td>
                <td class="text-center" style="vertical-align: top;"><?= !empty($s->datefrom) ? date('d/m/Y', strtotime($s->datefrom)) : '-' ?> s/d <?= !empty($s->dateto) ? date('d/m/Y', strtotime($s->dateto)) : '-' ?></td>
                <td class="text-center" style="vertical-align: top;">
                    <a href="<?= site_url('dokter/cetak_sks/' . $s->id) ?>" target="_blank" class="ds-act-btn ds-act-print" title="Cetak SKS" style="display:inline-flex;padding:3px 8px;">
                        <i class="fa fa-print"></i>
                    </a>
                    <button class="ds-act-btn ds-act-delete del-sks-btn" data-id="<?= $s->id ?>" style="padding:3px 8px;" title="Hapus SKS">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
