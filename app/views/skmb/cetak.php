<!DOCTYPE html>
<html>
<head>
    <title>Cetak SKMB</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; margin: 0; padding: 10px; color: #333; }
        .header { text-align: center; margin: 16px 0 26px; }
        .header h2 { margin: 0; color: #2d6a4f; font-size: 15px; }
        .header p { margin: 1px 0 0; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        table tr td { padding: 2px 6px; vertical-align: top; }
        table tr td:first-child { width: 120px; color: #666; font-weight: 600; }
        table tr td:nth-child(2) { width: 8px; color: #999; }
        .section { margin-bottom: 8px; }
        .section-title { font-weight: 700; font-size: 14px; color: #2d6a4f; margin-bottom: 4px; padding-bottom: 2px; border-bottom: 1px solid #dceee4; }

        /* Keterangan text */
        .ket-text { font-size: 14px; line-height: 1.5; text-align: justify; margin: 4px 0; }

        /* Signature block */
        .ttd-wrap { display: flex; justify-content: flex-end; margin-top: 16px; }
        .ttd-box  { text-align: center; min-width: 180px; }
        .ttd-place { margin: 0 0 2px; font-size: 14px; color: #333; }
        .ttd-role  { margin: 0 0 2px; font-size: 13px; color: #666; }
        .ttd-qr    { margin: 3px 0; }
        .ttd-qr img { width: 130px; height: 130px; display: block; margin: 0 auto; }
        .ttd-name  { margin: 2px 0 0; font-size: 14px; font-weight: 700; color: #333; border-top: 1px solid #333; padding-top: 4px; display: inline-block; min-width: 160px; }
        .ttd-nip   { margin: 1px 0 0; font-size: 12px; color: #666; }

        .btn-print { display: inline-block; margin-bottom: 8px; padding: 6px 16px; background: #2d6a4f; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; }
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
    <button class="btn-print no-print" onclick="window.close()" style="background: #666; margin-left: 6px;">Tutup</button>

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
        <h2 style="font-size:17px;margin:0;">SURAT KETERANGAN MEMBAWA BEROBAT (SKMB)</h2>
        <p style="font-size:15px;font-weight:bold;margin:2px 0 0;">(No.): <?= htmlspecialchars($row->docnumb ?? '-') ?></p>
    </div>

    <div class="section">
        <div class="section-title">Keterangan</div>
        <p class="ket-text">
            Yang bertanda tangan di bawah ini menerangkan bahwa:
        </p>
        
        <table>
            <tr><td>Nama Pengantar</td><td>:</td><td><?= !empty($row->pengantar) ? htmlspecialchars($row->pengantar) : '-' ?></td></tr>
            <tr><td>No. ID Card Pengantar</td><td>:</td><td><?= !empty($row->nik_pengantar) ? htmlspecialchars($row->nik_pengantar) : '-' ?></td></tr>
            <tr><td>Pekerjaan</td><td>:</td><td><?= !empty($row->pekerjaan_pengantar) ? htmlspecialchars(ucwords(strtolower($row->pekerjaan_pengantar))) : '-' ?></td></tr>
        </table>
    </div>

    <div class="section">
        <p class="ket-text">
            Pada tanggal <strong><?= !empty($row->tgl_datang) ? date('d-m-Y', strtotime($row->tgl_datang)) : '-' ?></strong>
            Jam : <strong><?= !empty($row->jam) ? htmlspecialchars($row->jam) : '-' ?> WITA</strong>.
            Telah datang ke Praktek Dokter Umum untuk mengantar berobat
            <?php
            $hubungan = htmlspecialchars($row->hubungan ?? '');
            $hubungan_label = '';
            switch ($hubungan) {
                case 'SUAMI':    $hubungan_label = 'Suami';    break;
                case 'ISTRI':    $hubungan_label = 'Istri';    break;
                case 'ANAK':     $hubungan_label = 'Anak';     break;
                case 'ORANG TUA':$hubungan_label = 'Orang Tua';break;
                case 'SAUDARA':  $hubungan_label = 'Saudara';  break;
                default:         $hubungan_label = $hubungan;   break;
            }
            ?>
            <strong><?= $hubungan_label ?: '-' ?></strong>
        </p>
        
        <table>
            <tr><td>Nama Diantar</td><td>:</td><td><?= htmlspecialchars($row->patient_name ?? '') ?></td></tr>
            <tr><td>Diagnosa</td><td>:</td><td><?= !empty($row->patient_diagnosa) ? htmlspecialchars($row->patient_diagnosa) : '-' ?></td></tr>
        </table>



    </div>

    <div class="section">
        <p class="ket-text">
            Demikian surat keterangan ini dibuat untuk digunakan semestinya.
        </p>
    </div>

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
    $fullname = !empty($row->doct_by_name) ? htmlspecialchars($row->doct_by_name) : ( !empty($row->insertby) ? htmlspecialchars($row->insertby) : '_________________' );
    ?>
    <div class="ttd-wrap">
        <div class="ttd-box">
            <p class="ttd-place">Fatufia, <?= $tgl_doc ?></p>
            <p class="ttd-role">Dokter Pemeriksa</p>
            <div class="ttd-qr"><img src="<?= $qrcode ?>" alt="QR Code"></div>
            <p class="ttd-name">( <?= htmlspecialchars($row->doct_name ?? $row->fullname ?? '') ?: $fullname ?> )</p>
            <?php if (!empty($row->nip)): ?>
            <p class="ttd-nip">SIP. <?= htmlspecialchars($row->nip ?? '') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer" style="margin-top:12px;text-align:center;font-size:10px;color:#999;border-top:1px solid #ddd;padding-top:6px;">
        Dokumen ini diterbitkan secara elektronik.
    </div>
</body>
</html>
