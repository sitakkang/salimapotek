<div class="ds-detail-wrap">

    <!-- Row 1: Data Pasien + Hasil Pemeriksaan -->
    <div class="ds-detail-row">
        <!-- Pasien Identity -->
        <div class="ds-detail-card ds-detail-patient">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-user-circle"></i></span>
                <span>Data Pasien</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Nama</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_patient_name ?? '') ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Umur</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_patient_age ?? '') ?: '<span class="text-muted">-</span>' ?> Tahun</span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">No. ID Card</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_patient_nik ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">No. KTP</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_patient_ktp ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Pemeriksaan -->
        <div class="ds-detail-card ds-detail-doc">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-file-medical"></i></span>
                <span>Hasil Pemeriksaan</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Hasil</span>
                        <span class="ds-detail-value"><strong><?= htmlspecialchars($row->skbs_result_name ?? '') ?: '<span class="text-muted">-</span>' ?></strong></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Dokter</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_doct_name ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field" style="grid-column: span 2;">
                        <span class="ds-detail-label">Keterangan</span>
                        <span class="ds-detail-value"><?= nl2br(htmlspecialchars($row->skbs_desc ?? '')) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Pemeriksaan Fisik -->
    <div class="ds-detail-row">
        <div class="ds-detail-card" style="width: 100%;">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-thermometer-half"></i></span>
                <span>Pemeriksaan Fisik</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Tekanan Darah</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_blood_press ?? '') ?: '-' ?> mmHg</span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Nadi</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_pulse ?? '') ?: '-' ?> x/menit</span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Respirasi</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_respirasi ?? '') ?: '-' ?> x/menit</span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Suhu Tubuh</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_temp ?? '') ?: '-' ?> &deg;C</span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Buta Warna</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_bw ?? '') ?: '-' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Tinggi Badan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_tb ?? '') ?: '-' ?> Cm</span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Berat Badan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_bb ?? '') ?: '-' ?> Kg</span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Visus R</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_r ?? '') ?: '-' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Visus L</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->skbs_l ?? '') ?: '-' ?></span>
                    </div>
                </div>
                <?php if (!empty($row->skbs_note)): ?>
                <div class="ds-detail-field" style="margin-top: 10px;">
                    <span class="ds-detail-label">Catatan</span>
                    <span class="ds-detail-value"><?= nl2br(htmlspecialchars($row->skbs_note)) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Row 3: Audit Trail -->
    <div class="ds-detail-audit" style="margin-top: 12px;">
        <div class="ds-detail-audit-hd">
            <i class="fa fa-history"></i> Audit Trail
        </div>
        <div class="ds-detail-audit-bd">
            <div class="ds-detail-audit-item">
                <span class="ds-audit-icon"><i class="fa fa-plus-circle"></i></span>
                <span class="ds-audit-text">Dibuat oleh <strong><?= htmlspecialchars($row->insert_name ?? '') ?: '-' ?></strong> pada <strong><?= !empty($row->insert_dt) ? date('d/m/Y H:i', strtotime($row->insert_dt)) : '-' ?></strong></span>
            </div>
            <?php if (!empty($row->update_name)): ?>
            <div class="ds-detail-audit-item">
                <span class="ds-audit-icon ds-audit-upd"><i class="fa fa-pencil-square-o"></i></span>
                <span class="ds-audit-text">Diperbarui oleh <strong><?= htmlspecialchars($row->update_name) ?></strong> pada <strong><?= date('d/m/Y H:i', strtotime($row->update_dt)) ?></strong></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
