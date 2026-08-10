<!DOCTYPE html>
<html>
<head>
    <title>Cetak SKBS</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 8px; }
        .header h2 { margin: 0; color: #2d6a4f; font-size: 15px; }
        .header p { margin: 1px 0 0; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        table tr td { padding: 2px 6px; vertical-align: top; }
        table tr td:first-child { width: 120px; color: #666; font-weight: 600; }
        table tr td:nth-child(2) { width: 8px; color: #999; }
        .section { margin-bottom: 8px; }
        .section-title { font-weight: 700; font-size: 12px; color: #2d6a4f; margin-bottom: 4px; padding-bottom: 2px; border-bottom: 1px solid #dceee4; }
        .footer { margin-top: 12px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 6px; }

        .hasil-box { margin: 10px 0; }
        .hasil-item { display: inline-block; margin-right: 20px; font-size: 13px; }
        .hasil-item input { margin-right: 4px; }

        .vital-grid { width: 100%; }
        .vital-grid td { padding: 4px 8px; font-size: 13px; }
        .vital-grid td:first-child { width: 140px; }
        .vital-grid td:nth-child(2) { width: auto; color: #333; }
        .vital-grid td:nth-child(3) { width: 140px; }
        .vital-grid td:nth-child(4) { width: auto; color: #333; }

        .ttd-wrap { display: flex; justify-content: flex-end; margin-top: 40px; }
        .ttd-box  { text-align: center; min-width: 220px; }
        .ttd-place { margin: 0 0 4px; font-size: 13px; color: #333; }
        .ttd-role  { margin: 0 0 4px; font-size: 12px; color: #666; }
        .ttd-qr    { margin: 6px 0; }
        .ttd-qr img { width: 80px; height: 80px; display: block; margin: 0 auto; }
        .ttd-name  { margin: 4px 0 0; font-size: 13px; font-weight: 700; color: #333; border-top: 1px solid #333; padding-top: 6px; display: inline-block; min-width: 200px; }
        .ttd-nip   { margin: 2px 0 0; font-size: 11px; color: #666; }

        .btn-print { display: inline-block; margin-bottom: 16px; padding: 8px 20px; background: #2d6a4f; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
        .btn-print:hover { background: #1b4332; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()"><i class="fa fa-print"></i> Print / PDF</button>
    <button class="btn-print no-print" onclick="window.close()" style="background:#666;margin-left:6px;">Tutup</button>

    <div class="header">
        <hr style="border:1px solid #2d6a4f;margin:0 0 4px;">
        <div style="font-size:14px;font-weight:700;color:#2d6a4f;">PRAKTEK DOKTER UMUM</div>
        <div style="font-size:11px;color:#555;font-weight:600;"><?= htmlspecialchars($row->skbs_doct_name)?></div>
        <div style="font-size:9px;color:#888;margin-top:1px;">
            Jl. Trans Sulawesi, Desa Keurea, Kec. Bahodopi, Morowali
        </div>
        <hr style="border:1px solid #2d6a4f;margin:4px 0 5px;">
        <h2 style="font-size:15px;margin:0;">SURAT KETERANGAN BERBADAN SEHAT</h2>
        <p style="font-size:10px;margin:0;">No. Dokumen: <?= htmlspecialchars($docnumb) ?></p>
    </div>

    <div class="section">
        <div class="section-title">Identitas Pasien</div>
        <table>
            <tr><td>Nama</td><td class="lbl">:</td><td><strong><?= htmlspecialchars($row->skbs_patient_name) ?></strong></td></tr>
            <tr><td>Umur</td><td class="lbl">:</td><td><?= htmlspecialchars($row->skbs_patient_age ?: '-') ?> Tahun</td></tr>
            <tr><td>NIK</td><td class="lbl">:</td><td><?= htmlspecialchars($row->skbs_patient_nik ?: '-') ?></td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Hasil Pemeriksaan</div>
        <div class="hasil-box">
            <span class="hasil-item"><input type="checkbox" <?= $row->skbs_result_name == 'FIT' ? 'checked' : '' ?> disabled> Fit</span>
            <span class="hasil-item"><input type="checkbox" <?= $row->skbs_result_name == 'FIT DENGAN CATATAN' ? 'checked' : '' ?> disabled> Fit dengan Catatan</span>
            <span class="hasil-item"><input type="checkbox" <?= $row->skbs_result_name == 'UNFIT' ? 'checked' : '' ?> disabled> Unfit</span>
        </div>
        <?php if (strtoupper($row->skbs_result_name) == 'FIT DENGAN CATATAN' && !empty($row->skbs_note)): ?>
            <p style="font-size:13px;margin:6px 0 0;font-style:italic;">Catatan: <?= htmlspecialchars($row->skbs_note) ?></p>
        <?php elseif (strtoupper($row->skbs_result_name) == 'KETERANGAN' && !empty($row->skbs_desc)): ?>
            <p style="font-size:13px;margin:6px 0 0;font-style:italic;">Keterangan: <?= htmlspecialchars($row->skbs_desc) ?></p>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Pemeriksaan Fisik</div>
        <table class="vital-grid">
            <tr>
                <td><strong>Tekanan Darah</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_td ?: '-') ?> mmHg</td>
                <td><strong>Tinggi Badan</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_tb ?: '-') ?> Cm</td>
            </tr>
            <tr>
                <td><strong>Berat Badan</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_bb ?: '-') ?> Kg</td>
                <td><strong>Buta Warna</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_bw ?: '-') ?></td>
            </tr>
            <tr>
                <td><strong>Visus R</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_r ?: '-') ?></td>
                <td><strong>Visus L</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_l ?: '-') ?></td>
            </tr>
            <tr>
                <td><strong>Visus Koreksi R</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_koreksi_r ?: '-') ?></td>
                <td><strong>Visus Koreksi L</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_koreksi_l ?: '-') ?></td>
            </tr>
        </table>
        <?php if (!empty($row->skbs_note)): ?>
            <p style="font-size:13px;margin:6px 0 0;"><strong>Catatan:</strong> <?= htmlspecialchars($row->skbs_note) ?></p>
        <?php endif; ?>
    </div>

    <div style="margin-top:20px;font-size:13px;">
        <strong>Morowali, <?= !empty($row->skbs_doc_date) ? date('d/m/Y', strtotime($row->skbs_doc_date)) : date('d/m/Y') ?></strong>
    </div>

    <div class="ttd-wrap">
        <div class="ttd-box">
            <p class="ttd-role">Dokter Pemeriksa</p>
            <div class="ttd-qr"><?= isset($qrcode) ? '<img src="'.$qrcode.'" alt="QR Code">' : '' ?></div>
            <p class="ttd-name"><?= htmlspecialchars($row->skbs_doct_name ?: '_________________') ?></p>
            <?php if (!empty($row->nip)): ?>
            <p class="ttd-nip">NIP. <?= htmlspecialchars($row->nip) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        Dokumen ini diterbitkan secara elektronik dan valid tanpa tanda tangan basah.
    </div>
</body>
</html>
