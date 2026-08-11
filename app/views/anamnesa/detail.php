<div class="ds-detail-wrap">

    <!-- Row 1: Patient Info -->
    <div class="ds-detail-row">
        <div class="ds-detail-card ds-detail-patient">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-user-circle"></i></span>
                <span>Identitas Pasien</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">NO. RM</span>
                        <span class="ds-detail-value ds-detail-code"><?= htmlspecialchars($row->trans_patient_code ?? '') ?: '-' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Nama Pasien</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_name ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">NIK</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_nik ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">KTP</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_ktp ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Tanggal Lahir</span>
                        <span class="ds-detail-value"><?= !empty($row->patient_bod) ? date('d/m/Y', strtotime($row->patient_bod)) : '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Telepon</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->trans_patient_phone ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="ds-detail-card ds-detail-doc">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-building"></i></span>
                <span>Perusahaan &amp; Kunjungan</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Perusahaan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->trans_patient_company ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Departemen</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->trans_patient_department ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Tanggal Daftar</span>
                        <span class="ds-detail-value"><?= !empty($row->trans_doc) ? date('d/m/Y', strtotime($row->trans_doc)) : '-' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Jam Daftar</span>
                        <span class="ds-detail-value"><?= !empty($row->trans_insert_dt) ? date('H:i', strtotime($row->trans_insert_dt)) : '-' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Address -->
    <div class="ds-detail-card" style="margin-top: 12px;">
        <div class="ds-detail-card-hd">
            <span class="ds-detail-icon"><i class="fa fa-map-marker-alt"></i></span>
            <span>Alamat</span>
        </div>
        <div class="ds-detail-card-bd">
            <div class="ds-detail-grid-2col">
                <div class="ds-detail-field ds-detail-field-full">
                    <span class="ds-detail-label">Alamat</span>
                    <span class="ds-detail-value">
                        <?php
                        $alamat = array();
                        if (!empty($row->patient_address)) $alamat[] = htmlspecialchars($row->patient_address);
                        if (!empty($row->trans_patient_district_name)) $alamat[] = '<span class="ds-addr-item"><span class="ds-addr-label">Kel</span>' . htmlspecialchars($row->trans_patient_district_name) . '</span>';
                        if (!empty($row->trans_patient_city_name)) $alamat[] = '<span class="ds-addr-item"><span class="ds-addr-label">Kec</span>' . htmlspecialchars($row->trans_patient_city_name) . '</span>';
                        echo !empty($alamat) ? implode('', $alamat) : '<span class="text-muted">-</span>';
                        ?>
                    </span>
                </div>
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
                <span class="ds-audit-text">Didaftarkan oleh <strong><?= htmlspecialchars($row->insert_fullname ?? '') ?: htmlspecialchars($row->trans_insert_by ?? '') ?: '-' ?></strong> pada <strong><?= !empty($row->trans_insert_dt) ? date('d/m/Y H:i', strtotime($row->trans_insert_dt)) : '-' ?></strong></span>
            </div>
            <?php if (!empty($row->mrd_insert_by)): ?>
            <div class="ds-detail-audit-item">
                <span class="ds-audit-icon ds-audit-upd"><i class="fa fa-pencil-square-o"></i></span>
                <span class="ds-audit-text">Rekam medis oleh <strong><?= htmlspecialchars($row->mrd_insert_fullname ?? '') ?: htmlspecialchars($row->mrd_insert_by ?? '') ?></strong> pada <strong><?= !empty($row->mrd_insert_dt) ? date('d/m/Y H:i', strtotime($row->mrd_insert_dt)) : '-' ?></strong></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
