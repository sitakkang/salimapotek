<!DOCTYPE html>
<html>
<head>
    <title>Cetak SKBS</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; margin: 0; padding: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 8px; }
        .header h2 { margin: 0; color: #2d6a4f; font-size: 15px; }
        .header p { margin: 1px 0 0; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        table tr td { padding: 4px 8px; vertical-align: top; }
        table tr td:first-child { width: 175px; color: #666; font-weight: 600; white-space: nowrap; }
        table tr td:nth-child(2) { width: 8px; color: #999; }
        .section { margin-bottom: 10px; }
        .section-title { font-weight: 700; font-size: 14px; color: #2d6a4f; margin-bottom: 6px; padding-bottom: 3px; border-bottom: 1px solid #dceee4; }
        .footer { margin-top: 12px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 6px; }

        .hasil-box { margin: 10px 0; }
        .hasil-item { display: inline-block; margin-right: 20px; font-size: 15px; }
        .hasil-item input { margin-right: 4px; }

        .vital-grid { width: 100%; }
        .vital-grid td { padding: 4px 8px; font-size: 15px; }
        .vital-grid td:first-child, .vital-grid td:nth-child(3) { width: 140px; color: #666; font-weight: 600; }
        .vital-grid td:nth-child(2), .vital-grid td:nth-child(4) { width: auto; color: #333; }

        .ttd-wrap { display: flex; justify-content: flex-end; margin-top: 40px; }
        .ttd-box  { text-align: center; min-width: 220px; }
        .ttd-place { margin: 0 0 4px; font-size: 15px; color: #333; }
        .ttd-role  { margin: 0 0 4px; font-size: 14px; color: #666; }
        .ttd-date  { margin: 0 0 14px; font-size: 14px; color: #333; }
        .ttd-qr    { margin: 6px 0; }
        .ttd-qr img { width: 130px; height: 130px; display: block; margin: 0 auto; }
        .ttd-name  { margin: 4px 0 0; font-size: 15px; font-weight: 700; color: #333; border-top: 1px solid #333; padding-top: 6px; display: inline-block; min-width: 200px; }
        .ttd-nip   { margin: 2px 0 0; font-size: 13px; color: #666; }

        .btn-print { display: inline-block; margin-bottom: 16px; padding: 8px 20px; background: #2d6a4f; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
        .btn-print:hover { background: #1b4332; }

        @page {
            margin: 0;
        }
        @media print {
            body { padding: 15mm 18mm 12mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()"><i class="fa fa-print"></i> Print / PDF</button>
    <button class="btn-print no-print" onclick="window.close()" style="background:#666;margin-left:6px;">Tutup</button>

    <div class="header">
        <hr style="border:1px solid #2d6a4f;margin:0 0 4px;">
        <div style="font-size:16px;font-weight:700;color:#2d6a4f;">PRAKTEK DOKTER UMUM</div>
        <div style="font-size:13px;color:#555;font-weight:600;">dr. Mutmainnah Hasanuddin</div>
        
        <div style="font-size:12px;color:#888;margin-top:1px;">
            SIP : MR72062607007791 &nbsp;|&nbsp; STR : WN00001241279556
        </div>
        <div style="font-size:12px;color:#888;margin-top:1px;">
            Alamat : Jl. Trans Sulawesi, Desa Keurea, Kec. Bahodopi, Morowali
        </div>
        <div style="font-size:12px;color:#888;margin-top:1px;">
            Telp/WA : 0821-5277-0277
        </div>
        <hr style="border:1px solid #2d6a4f;margin:4px 0 5px;">
        <h2 style="font-size:17px;margin:0;">SURAT KETERANGAN BERBADAN SEHAT (SKBS)</h2>
        <p style="font-size:15px;font-weight:bold;margin:2px 0 0;">No. Dokumen: <?= htmlspecialchars($docnumb ?? '') ?></p>
    </div>

    <p style="font-size:14px;margin:8px 0 6px;text-align:justify;">
        Yang bertanda tangan dibawah ini, menerangkan bahwa :
    </p>

    <div class="section">
        <div class="section-title">Identitas Pasien</div>
        <table>
            <tr><td>Nama</td><td class="lbl">:</td><td><strong><?= htmlspecialchars($row->skbs_patient_name ?? '') ?></strong></td></tr>
            <tr><td>Tempat dan Tanggal Lahir</td><td class="lbl">:</td><td><?= htmlspecialchars($row->skbs_birth_place ?: '-') ?> / <?= !empty($row->skbs_bod) ? date('d-m-Y', strtotime($row->skbs_bod)) : '-' ?></td></tr>
            <tr><td>Umur / Jenis Kelamin</td><td class="lbl">:</td><td><?= htmlspecialchars($row->skbs_patient_age ?: '-') ?> Tahun / <?= strtoupper($row->skbs_gender ?? '') == 'L' ? 'Laki-laki' : (strtoupper($row->skbs_gender ?? '') == 'P' ? 'Perempuan' : '-') ?></td></tr>
            <tr><td>Alamat</td><td class="lbl">:</td><td><?= htmlspecialchars(ucwords(strtolower($row->skbs_address ?: '-'))) ?></td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Hasil Pemeriksaan</div>
        <div class="hasil-box">
            <span class="hasil-item"><input type="checkbox" <?= $row->skbs_result_name == 'FIT' ? 'checked' : '' ?> disabled> Fit</span>
            <span class="hasil-item"><input type="checkbox" <?= $row->skbs_result_name == 'FIT DENGAN CATATAN' ? 'checked' : '' ?> disabled> Fit dengan Catatan</span>
            <span class="hasil-item"><input type="checkbox" <?= $row->skbs_result_name == 'UNFIT' ? 'checked' : '' ?> disabled> Unfit</span>
        </div>
        <?php if (strtoupper($row->skbs_result_name ?? '') == 'FIT DENGAN CATATAN' && !empty($row->skbs_note)): ?>
            <p style="font-size:13px;margin:6px 0 0;font-style:italic;">Catatan: <?= htmlspecialchars($row->skbs_note) ?></p>
        <?php elseif (strtoupper($row->skbs_result_name ?? '') == 'KETERANGAN' && !empty($row->skbs_desc)): ?>
            <p style="font-size:13px;margin:6px 0 0;font-style:italic;">Keterangan: <?= htmlspecialchars($row->skbs_desc) ?></p>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Pemeriksaan Fisik</div>
        <table class="vital-grid">
            <tr>
                <td><strong>Tekanan Darah</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_blood_press ?: '-') ?> mmHg</td>
                <td><strong>Nadi</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_pulse ?: '-') ?> x/menit</td>
            </tr>
            <tr>
                <td><strong>Respirasi</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_respirasi ?: '-') ?> x/menit</td>
                <td><strong>Suhu Tubuh</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_temp ?: '-') ?> &deg;C</td>
            </tr>
            <tr>
                <td><strong>Tinggi Badan</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_tb ?: '-') ?> Cm</td>
                <td><strong>Berat Badan</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_bb ?: '-') ?> Kg</td>
            </tr>
            <tr>
                <td><strong>Buta Warna</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_bw ?: '-') ?></td>
                <td><strong>Visus R / L</strong></td>
                <td>: <?= htmlspecialchars($row->skbs_r ?: '-') ?> / <?= htmlspecialchars($row->skbs_l ?: '-') ?></td>
            </tr>
        </table>
        <?php if (!empty($row->skbs_note)): ?>
            <p style="font-size:13px;margin:6px 0 0;"><strong>Catatan:</strong> <?= htmlspecialchars($row->skbs_note) ?></p>
        <?php endif; ?>
    </div>

    <div class="ttd-wrap" style="margin-top:28px;">
        <div class="ttd-box">
            <p class="ttd-date"><strong>Morowali, <?= !empty($row->skbs_doc_date) ? date('d/m/Y', strtotime($row->skbs_doc_date)) : date('d/m/Y') ?></strong></p>
            <p class="ttd-role">Dokter Pemeriksa</p>
            <div class="ttd-qr"><?= isset($qrcode) ? '<img src="'.$qrcode.'" alt="QR Code">' : '' ?></div>
            <p class="ttd-name"><?= htmlspecialchars($row->skbs_doct_name ?: '_________________') ?></p>
            <?php if (!empty($row->nip)): ?>
            <p class="ttd-nip">SIP. <?= htmlspecialchars($row->nip ?? '') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        Dokumen ini diterbitkan secara elektronik dan valid tanpa tanda tangan basah.
    </div>
</body>
</html>
