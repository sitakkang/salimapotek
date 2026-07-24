<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi SKS — <?= htmlspecialchars($row->patient_name) ?></title>
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
            transition: transform .2s;
        }
        /* ── Header ── */
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
        .v-badge i {
            font-size: 12px;
        }
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
        /* ── Body ── */
        .v-body {
            padding: 24px 30px 20px;
        }
        /* Status bar */
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
        /* Info grid */
        .v-grid {
            display: grid;
            gap: 0;
        }
        .v-row {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 0;
            padding: 10px 0;
            border-bottom: 1px solid #f0f4f2;
        }
        .v-row:last-child {
            border-bottom: none;
        }
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
        .v-value-mono {
            font-family: 'SF Mono', 'Courier New', monospace;
            font-size: 12.5px;
            background: #f5f7fa;
            padding: 2px 8px;
            border-radius: 5px;
            display: inline-block;
        }
        /* Diagnosa card */
        .v-diagnosa {
            background: #f4faf6;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            line-height: 1.65;
            color: #1c2e22;
            border-left: 3px solid #52b788;
            font-weight: 500;
            margin-top: 2px;
        }
        /* Gender badge */
        .v-gender {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .3px;
        }
        .v-gender-m {
            background: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }
        .v-gender-f {
            background: #fce4ec;
            color: #c62828;
            border: 1px solid #f8bbd0;
        }
        /* Date range */
        .v-date {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .v-date-sep {
            font-size: 10px;
            font-weight: 400;
            color: #8aa89a;
        }
        /* ── Footer ── */
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
        .v-footer-seal {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .v-footer-seal i {
            font-size: 10px;
        }
        /* ── Responsive ── */
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
            <h1>Surat Keterangan Sakit</h1>
            <div class="v-docnum"><?= htmlspecialchars($row->docnumb) ?></div>
        </div>

        <!-- Body -->
        <div class="v-body">
            

            <!-- Info -->
            <div class="v-grid">
                <div class="v-row">
                    <div class="v-label">Pasien</div>
                    <div class="v-value"><?= htmlspecialchars($row->patient_name) ?></div>
                </div>
                <div class="v-row">
                    <div class="v-label">Perusahaan</div>
                    <div class="v-value"><?= htmlspecialchars($row->company_name) ?: '<span style="color:#b7d5c4">—</span>' ?></div>
                </div>
                <div class="v-row">
                    <div class="v-label">Umur</div>
                    <div class="v-value"><?= htmlspecialchars($row->age) ?: '<span style="color:#b7d5c4">—</span>' ?> Tahun</div>
                </div>
                <div class="v-row">
                    <div class="v-label">Jenis Kelamin</div>
                    <div class="v-value">
                        <?php if ($row->gender === 'L'): ?>
                            <span class="v-gender v-gender-m">Laki-laki</span>
                        <?php elseif ($row->gender === 'P'): ?>
                            <span class="v-gender v-gender-f">Perempuan</span>
                        <?php else: ?>
                            <span style="color:#b7d5c4">—</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="v-row" style="border-bottom:none;">
                    <div class="v-label">Diagnosa</div>
                    <div class="v-value">
                        <?= nl2br(htmlspecialchars($row->diagnosa)) ?>
                    </div>
                </div>
            </div>

            <!-- Separator -->
            <div style="height:1px;background:linear-gradient(90deg,transparent,#e2f0e8,transparent);margin:18px 0 14px;"></div>

            <!-- Meta info compact -->
            <div class="v-grid">
                <div class="v-row">
                    <div class="v-label">Masa Berlaku</div>
                    <div class="v-value">
                        <span class="v-date">
                            <span><?= !empty($row->datefrom) ? date('d-m-Y', strtotime($row->datefrom)) : '<span style="color:#b7d5c4">—</span>' ?></span>
                            <span class="v-date-sep">→</span>
                            <span><?= !empty($row->dateto) ? date('d-m-Y', strtotime($row->dateto)) : '<span style="color:#b7d5c4">—</span>' ?></span>
                        </span>
                    </div>
                </div>
                <div class="v-row">
                    <div class="v-label">Dokter</div>
                    <div class="v-value">
                        <?= htmlspecialchars($row->fullname) ?: htmlspecialchars($row->doctby) ?: '<span style="color:#b7d5c4">—</span>' ?>
                        <?php if (!empty($row->nip)): ?>
                        <br><span style="font-size:11.5px;color:#8aa89a;font-weight:400;">NIP. <?= htmlspecialchars($row->nip) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="v-footer">
            <span>Diterbitkan oleh Apotek Alif Medika</span>
            <span class="v-footer-seal">
                <span>&#9679;</span>
                <?= !empty($row->insertdt) ? date('d-m-Y H:i', strtotime($row->insertdt)) : '-' ?>
            </span>
        </div>
    </div>
</body>
</html>
