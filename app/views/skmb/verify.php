<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi SKMB — <?= htmlspecialchars($row->patient_name) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', -apple-system, Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .v-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 8px 32px rgba(0,0,0,.08);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
        }
        .v-header {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 100%);
            color: #fff;
            padding: 28px 30px 22px;
            text-align: center;
            position: relative;
        }
        .v-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #52b788, #40916c, #52b788);
        }
        .v-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 100px;
            padding: 3px 14px 3px 10px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 12px;
        }
        .v-badge i { font-size: 12px; }
        .v-header h1 {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.3px;
            margin-bottom: 6px;
        }
        .v-docnum {
            font-size: 12px;
            opacity: .75;
            font-weight: 500;
            letter-spacing: .2px;
        }
        .v-body { padding: 24px 30px 20px; }
        .v-status {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #e8f5e9, #f1faf3);
            border: 1px solid #c8e6c9;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }
        .v-status-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2e7d32, #43a047);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(46,125,50,.25);
        }
        .v-status-text h3 {
            font-size: 14px;
            font-weight: 700;
            color: #1b5e20;
            margin: 0;
        }
        .v-status-text p {
            font-size: 12px;
            color: #388e3c;
            margin: 2px 0 0;
            line-height: 1.4;
        }
        .v-grid { display: grid; gap: 0; }
        .v-row {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 0;
            padding: 10px 0;
            border-bottom: 1px solid #f0f4f2;
        }
        .v-row:last-child { border-bottom: none; }
        .v-label {
            font-size: 11.5px;
            font-weight: 600;
            color: #8aa89a;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding-top: 1px;
        }
        .v-value {
            font-size: 13.5px;
            font-weight: 600;
            color: #1c2e22;
            line-height: 1.5;
        }
        .v-section-title {
            font-weight: 700;
            font-size: 12px;
            color: #2d6a4f;
            margin: 14px 0 4px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e2f0e8;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .v-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 30px;
            border-top: 1px solid #edf5f0;
            font-size: 10.5px;
            color: #a0b8ac;
            background: #fafcfa;
        }
        .v-footer-seal { display: flex; align-items: center; gap: 4px; }
        .v-footer-seal i { font-size: 10px; }
        @media (max-width: 480px) {
            .v-header { padding: 22px 20px 18px; }
            .v-body   { padding: 18px 20px 16px; }
            .v-footer { padding: 12px 20px; flex-direction: column; gap: 4px; text-align: center; }
            .v-row { grid-template-columns: 80px 1fr; }
            .v-label { font-size: 10px; }
            .v-value { font-size: 12.5px; }
        }
    </style>
</head>
<body>
    <div class="v-card">
        <!-- Header -->
        <div class="v-header">
            <div class="v-badge">
                <span>&#10003;</span>
                <span>Terverifikasi</span>
            </div>
            <h1>Surat Keterangan Mengantar Berobat</h1>
            <div class="v-docnum"><?= htmlspecialchars($row->docnumb) ?></div>
        </div>

        <!-- Body -->
        <div class="v-body">

            <!-- Info -->
            <div class="v-grid">
                <div class="v-section-title">Data Diantar</div>
                <div class="v-row">
                    <div class="v-label">Nama</div>
                    <div class="v-value"><?= htmlspecialchars($row->patient_name) ?></div>
                </div>
                <div class="v-row">
                    <div class="v-label">NIK</div>
                    <div class="v-value"><?= htmlspecialchars($row->nik) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>
                <div class="v-row">
                    <div class="v-label">Perusahaan</div>
                    <div class="v-value"><?= htmlspecialchars($row->company_name) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>
                <div class="v-row">
                    <div class="v-label">Hubungan</div>
                    <div class="v-value"><?= htmlspecialchars($row->hubungan) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>

                <div class="v-section-title">Data Pengantar</div>
                <div class="v-row">
                    <div class="v-label">Pengantar</div>
                    <div class="v-value"><?= htmlspecialchars($row->pengantar) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>
                <div class="v-row">
                    <div class="v-label">NIK Pengantar</div>
                    <div class="v-value"><?= htmlspecialchars($row->nik_pengantar) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>
                <div class="v-row">
                    <div class="v-label">Perush. Pengantar</div>
                    <div class="v-value"><?= htmlspecialchars($row->company_pengantar) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>
            </div>

            <!-- Separator -->
            <div style="height:1px;background:linear-gradient(90deg,transparent,#e2f0e8,transparent);margin:18px 0 14px;"></div>

            <!-- Meta info compact -->
            <div class="v-grid">
                <div class="v-row">
                    <div class="v-label">Tanggal</div>
                    <div class="v-value"><?= !empty($row->tgl_datang) ? date('d-m-Y', strtotime($row->tgl_datang)) : '<span style="color:#b7d5c4">—</span>' ?> &nbsp; <strong><?= htmlspecialchars($row->jam) ?: '' ?></strong></div>
                </div>
                <div class="v-row">
                    <div class="v-label">Dokter</div>
                    <div class="v-value"><?= htmlspecialchars($row->fullname) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="v-footer">
            <span>Diterbitkan oleh Sahabat Apotek Care</span>
            <span class="v-footer-seal">
                <span>&#9679;</span>
                <?= !empty($row->insertdt) ? date('d-m-Y H:i', strtotime($row->insertdt)) : '-' ?>
            </span>
        </div>
    </div>
</body>
</html>
