<!DOCTYPE html>
<html>
<head>
    <title>Cetak SKS</title>
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

        /* Signature block — bottom right */
        .ttd-wrap { display: flex; justify-content: flex-end; margin-top: 16px; }
        .ttd-box  { text-align: center; min-width: 180px; }
        .ttd-place { margin: 0 0 2px; font-size: 12px; color: #333; }
        .ttd-role  { margin: 0 0 2px; font-size: 11px; color: #666; }
        .ttd-qr    { margin: 3px 0; }
        .ttd-qr img { width: 60px; height: 60px; display: block; margin: 0 auto; }
        .ttd-name  { margin: 2px 0 0; font-size: 12px; font-weight: 700; color: #333; border-top: 1px solid #333; padding-top: 4px; display: inline-block; min-width: 160px; }
        .ttd-nip   { margin: 1px 0 0; font-size: 10px; color: #666; }

        .btn-print { display: inline-block; margin-bottom: 8px; padding: 6px 16px; background: #2d6a4f; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; }
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
        <hr style="border:1px solid #2d6a4f;margin:0 0 4px;">
        <div style="font-size:14px;font-weight:700;color:#2d6a4f;">PRAKTEK DOKTER UMUM</div>
        <div style="font-size:11px;color:#555;font-weight:600;">dr. Steve Kojongian</div>
        <div style="font-size:9px;color:#888;margin-top:1px;">
            Jl. Trans Sulawesi, Ds. Bahodopi, Kec Bahodopi, Morowali &nbsp;|&nbsp; Telp. 081342161194
        </div>
        <hr style="border:1px solid #2d6a4f;margin:4px 0 5px;">
        <h2 style="font-size:15px;margin:0;">SURAT KETERANGAN SAKIT (SKS)</h2>
        <p style="font-size:10px;margin:0;">No. Dokumen: <?= htmlspecialchars($row->docnumb) ?></p>
    </div>

    <div class="section">
        <div class="section-title">Identitas Pasien</div>
        <table>
            <tr><td>Nama Pasien</td><td>:</td><td><?= htmlspecialchars($row->patient_name) ?></td></tr>
            <tr><td>Umur</td><td>:</td><td><?= htmlspecialchars($row->age) ?: '-' ?> Tahun</td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td><?= $row->gender === 'L' ? 'Laki-laki' : ($row->gender === 'P' ? 'Perempuan' : '-') ?></td></tr>
            <tr><td>Perusahaan</td><td>:</td><td><?= htmlspecialchars($row->company_name) ?: '-' ?></td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Alamat</div>
        <table>
            <tr>
                <td>Alamat</td><td>:</td>
                <td>
                    <?php
                    $alamat = array();
                    if (!empty($row->desa))      $alamat[] = 'Desa ' . htmlspecialchars($row->desa);
                    if (!empty($row->kelurahan)) $alamat[] = 'Kelurahan ' . htmlspecialchars($row->kelurahan);
                    if (!empty($row->kecamatan)) $alamat[] = 'Kecamatan ' . htmlspecialchars($row->kecamatan);
                    if (!empty($row->kabupaten)) $alamat[] = 'Kabupaten ' . htmlspecialchars($row->kabupaten);
                    if (!empty($row->provinsi))  $alamat[] = 'Provinsi ' . htmlspecialchars($row->provinsi);
                    echo !empty($alamat) ? implode(', ', $alamat) : '-';
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Diagnosa</div>
        <table>
            <tr><td>Diagnosa</td><td>:</td><td><?= nl2br(htmlspecialchars($row->diagnosa)) ?></td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Terapi</div>
        <table>
            <tr><td>Terapi</td><td>:</td><td><?= nl2br(htmlspecialchars($row->terapi)) ?></td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Masa Berlaku</div>
        <table>
            <tr><td>Dari Tanggal</td><td>:</td><td><?= !empty($row->datefrom) ? date('d/m/Y', strtotime($row->datefrom)) : '-' ?></td></tr>
            <tr><td>Sampai Tanggal</td><td>:</td><td><?= !empty($row->dateto) ? date('d/m/Y', strtotime($row->dateto)) : '-' ?></td></tr>
            <!-- <tr><td>Tanggal Dokumen</td><td>:</td><td><?= !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '-' ?></td></tr> -->
        </table>
    </div>

    <!-- Medical Certificate Statement -->
    <div class="section">
        <div class="section-title">Keterangan Dokter</div>
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

        $datefrom = !empty($row->datefrom) ? tgl_indo($row->datefrom) : '';
        $dateto   = !empty($row->dateto)   ? tgl_indo($row->dateto)   : '';

        // Hitung lama istirahat (hari)
        $lama = 0;
        if (!empty($row->datefrom) && !empty($row->dateto)) {
            $t1 = strtotime($row->datefrom);
            $t2 = strtotime($row->dateto);
            $lama = round(($t2 - $t1) / (60 * 60 * 24)) + 1; // +1 agar termasuk hari pertama
            if ($lama < 0) $lama = 0;
        }
        ?>
        <p style="font-size: 12px; line-height: 1.5; text-align: justify; margin: 4px 0;">
            Berdasarkan hasil pemeriksaan medis bahwa benar yang bersangkutan dalam keadaan sakit dan membutuhkan waktu istirahat selama <?= $lama ?> (hari),
            terhitung tanggal <?= $datefrom ?> s/d <?= $dateto ?>.
        </p>
        <p style="font-size: 12px; line-height: 1.5; text-align: justify; margin: 2px 0;">
            Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana perlunya.
        </p>
    </div>

    <!-- Tanda Tangan -->
    <?php
    $tgl_doc = !empty($row->docdate) ? tgl_indo($row->docdate) : date('d/m/Y');
    $fullname = !empty($row->doctby) ? htmlspecialchars($row->doctby) : ( !empty($row->insertby) ? htmlspecialchars($row->insertby) : '_________________' );
    ?>
    <div class="ttd-wrap">
        <div class="ttd-box">
            <p class="ttd-place">Bahodopi, <?= $tgl_doc ?></p>
            <p class="ttd-role">Dokter Pemeriksa</p>
            <div class="ttd-qr"><img src="<?= $qrcode ?>" alt="QR Code"></div>
            <p class="ttd-name">( <?= htmlspecialchars($row->fullname) ?: $fullname ?> )</p>
            <?php if (!empty($row->nip)): ?>
            <p class="ttd-nip">NIP. <?= htmlspecialchars($row->nip) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
    </div>

    <script>
        // Auto-trigger print dialog on load (uncomment if desired)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>