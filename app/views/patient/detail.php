<div class="ds-detail-wrap">

    <!-- Row 1: Patient Info -->
    <div class="ds-detail-row">
        <!-- Patient Identity -->
        <div class="ds-detail-card ds-detail-patient">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-user-circle"></i></span>
                <span>Identitas Pasien</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">No.RM</span>
                        <span class="ds-detail-value ds-detail-code"><?= htmlspecialchars($row->patient_code) ?: '-' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Nama Pasien</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_name) ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">NIK</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_nik) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">No. KTP</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_ktp) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Jenis Kelamin</span>
                        <span class="ds-detail-value"><?php if ($row->patient_gender === 'L'): ?><span class="ds-badge ds-badge-blue">Laki-laki</span><?php elseif ($row->patient_gender === 'P'): ?><span class="ds-badge ds-badge-pink">Perempuan</span><?php else: ?><span class="text-muted">-</span><?php endif; ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Tanggal Lahir</span>
                        <span class="ds-detail-value"><?= !empty($row->patient_bod) ? date('d/m/Y', strtotime($row->patient_bod)) : '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">No. Telepon</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_phone) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Info -->
        <div class="ds-detail-card ds-detail-doc">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-building"></i></span>
                <span>Perusahaan</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Perusahaan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_company) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Bagian / Dept</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_department) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Status</span>
                        <span class="ds-detail-value"><?= $row->patient_status == 1 ? '<span class="ds-badge ds-badge-blue">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>' ?></span>
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
                    <span class="ds-detail-label">Alamat Lengkap</span>
                    <span class="ds-detail-value">
                        <?php
                        $alamat = array();
                        if (!empty($row->patient_address)) $alamat[] = htmlspecialchars($row->patient_address);
                        if (!empty($row->patient_district_name)) $alamat[] = '<span class="ds-addr-item"><span class="ds-addr-label">Kel</span>' . htmlspecialchars($row->patient_district_name) . '</span>';
                        if (!empty($row->patient_city_name)) $alamat[] = '<span class="ds-addr-item"><span class="ds-addr-label">Kec</span>' . htmlspecialchars($row->patient_city_name) . '</span>';
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
                <span class="ds-audit-text">Dibuat oleh <strong><?= htmlspecialchars($row->insert_name) ?: '-' ?></strong> pada <strong><?= !empty($row->insert_dt) ? date('d/m/Y H:i', strtotime($row->insert_dt)) : '-' ?></strong></span>
            </div>
            <?php if (!empty($row->updateby)): ?>
            <div class="ds-detail-audit-item">
                <span class="ds-audit-icon ds-audit-upd"><i class="fa fa-pencil-square-o"></i></span>
                <span class="ds-audit-text">Diperbarui oleh <strong><?= htmlspecialchars($row->updateby) ?></strong> pada <strong><?= date('d/m/Y H:i', strtotime($row->updatedt)) ?></strong></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
