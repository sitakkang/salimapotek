<!DOCTYPE html>
<html>
<head>
    <title>Cetak SKKB</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2d6a4f; padding-bottom: 12px; }
        .header h2 { margin: 0; color: #2d6a4f; font-size: 20px; }
        .header p { margin: 4px 0 0; font-size: 13px; color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        table tr td { padding: 6px 8px; vertical-align: top; }
        table tr td:first-child { width: 130px; color: #666; font-weight: 600; }
        table tr td:nth-child(2) { width: 10px; color: #999; }
        .section { margin-bottom: 16px; }
        .section-title { font-weight: 700; font-size: 14px; color: #2d6a4f; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #dceee4; }

        .ket-text { font-size: 13px; line-height: 1.8; text-align: justify; margin: 10px 0; }

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
    <button class="btn-print no-print" onclick="window.close()" style="background: #666; margin-left: 6px;">Tutup</button>

    <div class="header">
        <h2>SURAT KETERANGAN BEKERJA KEMBALI</h2>
        <p>No. Dokumen: <?= htmlspecialchars($row->docnumb) ?></p>
    </div>

    <div class="section">
        <div class="section-title">Keterangan</div>
        <p class="ket-text">
            Yang bertanda tangan di bawah ini menerangkan bahwa:
        </p>
        <table>
            <tr><td>Nama</td><td>:</td><td><?= htmlspecialchars($row->patient_name) ?></td></tr>
            <tr><td>Umur</td><td>:</td><td><?= htmlspecialchars($row->age) ?: '-' ?> Thn</td></tr>
            <tr><td>NIK</td><td>:</td><td><?= htmlspecialchars($row->nik) ?: '-' ?></td></tr>
            <tr><td>PT / Dept</td><td>:</td><td><?= htmlspecialchars($row->company_name) ?: '-' ?> / <?= htmlspecialchars($row->bagian) ?: '-' ?></td></tr>
            <tr><td>Jabatan</td><td>:</td><td><?= htmlspecialchars($row->jabatan) ?: '-' ?></td></tr>
        </table>
    </div>

    <div class="section">
        <p class="ket-text">
            Berdasarkan hasil pemeriksaan yang kami lakukan, maka karyawan tersebut dinyatakan layak untuk
            <strong>BEKERJA KEMBALI</strong>.
        </p>
        <p class="ket-text">
            Demikian surat pernyataan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana semestinya.
        </p>
    </div>

    <?php if (!empty($row->catatan)): ?>
    <div class="section">
        <div class="section-title">Catatan Dokter</div>
        <p class="ket-text"><?= nl2br(htmlspecialchars($row->catatan)) ?></p>
    </div>
    <?php endif; ?>

    <!-- Tanda Tangan -->
    <?php
    function tgl_indo($date) {
        $bulan_indo = array(
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember',
        );
        $t = strtotime($date);
        $d = date('d', $t);
        $m = $bulan_indo[date('F', $t)];
        $y = date('Y', $t);
        return $d . ' ' . $m . ' ' . $y;
    }

    $tgl_doc = !empty($row->docdate) ? tgl_indo($row->docdate) : tgl_indo(date('Y-m-d'));
    $fullname = !empty($row->doctby) ? htmlspecialchars($row->doctby) : ( !empty($row->insertby) ? htmlspecialchars($row->insertby) : '_________________' );
    ?>
    <div class="ttd-wrap">
        <div class="ttd-box">
            <p class="ttd-place">Fatufia, <?= $tgl_doc ?></p>
            <p class="ttd-role">Dokter Pemeriksa</p>
            <div class="ttd-qr"><img src="<?= $qrcode ?>" alt="QR Code"></div>
            <p class="ttd-name">( <?= htmlspecialchars($row->fullname) ?: $fullname ?> )</p>
            <?php if (!empty($row->nip)): ?>
            <p class="ttd-nip">SIP. <?= htmlspecialchars($row->nip) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
    </div>

    <script>
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
