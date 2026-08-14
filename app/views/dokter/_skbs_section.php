<input type="hidden" id="skbs_id" value="<?= $skbs ? $skbs->id_skbs : '' ?>">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-12" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>No. Dokumen / Nomor SKBS <span class="text-danger">*</span></label>
            <input type="text" id="skbs_docnumb" class="form-control" maxlength="100"
                   value="<?= htmlspecialchars($skbs && !empty($skbs->skbs_docnumb) ? $skbs->skbs_docnumb : ($skbs_docnumb_default ?? '00000/SKBS/PDUKRWP')) ?>">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Hasil <span class="text-danger">*</span></label>
            <select id="skbs_result" class="form-control" onchange="toggleSkbsDesc(this)">
                <option value="">-- Pilih --</option>
                <option value="FIT" <?= $skbs && $skbs->skbs_result_name == 'FIT' ? 'selected' : '' ?>>FIT</option>
                <option value="FIT DENGAN CATATAN" <?= $skbs && $skbs->skbs_result_name == 'FIT DENGAN CATATAN' ? 'selected' : '' ?>>FIT dengan Catatan</option>
                <option value="UNFIT" <?= $skbs && $skbs->skbs_result_name == 'UNFIT' ? 'selected' : '' ?>>UNFIT</option>
                <option value="KETERANGAN" <?= $skbs && $skbs->skbs_result_name == 'KETERANGAN' ? 'selected' : '' ?>>Keterangan</option>
            </select>
        </div>
    </div>
    <div class="col-md-8" style="padding: 0 6px;<?= $skbs && $skbs->skbs_result_name == 'KETERANGAN' ? '' : 'display:none;' ?>" id="skbs_desc_wrap">
        <div class="ds-form-group">
            <label>Keterangan</label>
            <input type="text" id="skbs_desc" class="form-control" placeholder="Keterangan hasil" value="<?= $skbs ? htmlspecialchars($skbs->skbs_desc ?? '') : '' ?>">
        </div>
    </div>
</div>

<script>
function toggleSkbsDesc(el) {
    var wrapDesc = document.getElementById('skbs_desc_wrap');
    var wrapNote = document.getElementById('skbs_note_wrap');
    if (wrapDesc) {
        wrapDesc.style.display = el.value === 'KETERANGAN' ? '' : 'none';
    }
    if (wrapNote) {
        wrapNote.style.display = el.value === 'FIT DENGAN CATATAN' ? '' : 'none';
    }
}
</script>

<hr style="border-color:var(--ds-border);margin:6px 0 12px;">

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-3" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Tekanan Darah</label>
            <div class="input-group">
                <input type="text" id="skbs_blood_press" class="form-control" placeholder="cth: 120/80" value="<?= $skbs ? htmlspecialchars($skbs->skbs_blood_press ?? '') : '' ?>">
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
                <input type="text" id="skbs_pulse" class="form-control" placeholder="cth: 80" value="<?= $skbs ? htmlspecialchars($skbs->skbs_pulse ?? '') : '' ?>">
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
                <input type="text" id="skbs_respirasi" class="form-control" placeholder="cth: 20" value="<?= $skbs ? htmlspecialchars($skbs->skbs_respirasi ?? '') : '' ?>">
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
                <input type="text" id="skbs_temp" class="form-control" placeholder="cth: 36.5" value="<?= $skbs ? htmlspecialchars($skbs->skbs_temp ?? '') : '' ?>">
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
                <input type="text" id="skbs_tb" class="form-control" placeholder="cth: 165" value="<?= $skbs ? htmlspecialchars($skbs->skbs_tb ?? '') : '' ?>">
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
                <input type="text" id="skbs_bb" class="form-control" placeholder="cth: 65" value="<?= $skbs ? htmlspecialchars($skbs->skbs_bb ?? '') : '' ?>">
                <div class="input-group-append">
                    <span class="input-group-text" style="font-size:11px;padding:4px 8px;">Kg</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" style="padding: 0 6px;">
        <div class="ds-form-group">
            <label>Buta Warna (BW)</label>
            <input type="text" id="skbs_bw" class="form-control" placeholder="cth: Normal / Parsial" value="<?= $skbs ? htmlspecialchars($skbs->skbs_bw ?? '') : '' ?>">
        </div>
    </div>
</div>

<div class="row" style="margin: 0 -6px;">
    <div class="col-md-4" style="padding: 0 6px;<?= $skbs && $skbs->skbs_result_name == 'FIT DENGAN CATATAN' ? '' : 'display:none;' ?>" id="skbs_note_wrap">
        <div class="ds-form-group">
            <label>Catatan</label>
            <input type="text" id="skbs_note" class="form-control" placeholder="Catatan" value="<?= $skbs ? htmlspecialchars($skbs->skbs_note ?? '') : '' ?>">
        </div>
    </div>
</div>

<hr style="border-color:var(--ds-border);margin:6px 0 12px;">

<div class="text-right">
    <button class="ds-btn-action ds-btn-green" id="btn_simpan_skbs" data-visit-id="<?= $row->id_visit ?>" style="padding:7px 22px;">
        <i class="fa fa-save"></i> <?= $skbs ? 'Simpan Perubahan SKBS' : 'Simpan SKBS' ?>
    </button>
</div>

<?php if ($skbs): ?>
<hr style="border-color:var(--ds-border);margin:16px 0 12px;">
<h6 style="font-weight:700;margin-bottom:10px;">
    <i class="fa fa-list"></i> Data SKBS
</h6>
<div class="ds-wide-table-wrap" style="margin:0;">
    <table class="ds-table" style="margin-top:0;">
        <thead>
            <tr>
                <th>Hasil</th>
                <th>Tekanan Darah</th>
                <th>Nadi</th>
                <th>Respirasi</th>
                <th>Suhu</th>
                <th>TB</th>
                <th>BB</th>
                <th>BW</th>
                <th width="80" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($skbs->skbs_result_name ?? '') ?></td>
                <td><?= htmlspecialchars($skbs->skbs_blood_press ?? '') ?: '-' ?></td>
                <td><?= htmlspecialchars($skbs->skbs_pulse ?? '') ?: '-' ?></td>
                <td><?= htmlspecialchars($skbs->skbs_respirasi ?? '') ?: '-' ?></td>
                <td><?= htmlspecialchars($skbs->skbs_temp ?? '') ?: '-' ?></td>
                <td><?= htmlspecialchars($skbs->skbs_tb ?? '') ?: '-' ?> cm</td>
                <td><?= htmlspecialchars($skbs->skbs_bb ?? '') ?: '-' ?> kg</td>
                <td><?= htmlspecialchars($skbs->skbs_bw ?? '') ?: '-' ?></td>
                <td class="text-center">
                    <a href="<?= site_url('dokter/cetak_skbs/' . $skbs->id_skbs) ?>" target="_blank" class="ds-act-btn ds-act-print" title="Cetak SKBS" style="display:inline-flex;padding:3px 8px;">
                        <i class="fa fa-print"></i>
                    </a>
                    <button class="ds-act-btn ds-act-delete del-skbs-btn" data-id="<?= $skbs->id_skbs ?>" style="padding:3px 8px;" title="Hapus SKBS">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php endif; ?>
