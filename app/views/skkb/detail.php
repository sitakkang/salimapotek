<div class="ds-detail-wrap">

    <!-- Row 1: Data Karyawan + Dokumen -->
    <div class="ds-detail-row">
        <!-- Karyawan Identity -->
        <div class="ds-detail-card ds-detail-patient">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-user-circle"></i></span>
                <span>Data Karyawan</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Nama</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_name) ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Umur</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->age) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">NIK</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->nik) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Perusahaan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->company_name) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Departemen</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->bagian) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Jabatan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->jabatan) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen Info -->
        <div class="ds-detail-card ds-detail-doc">
            <div class="ds-detail-card-hd">
                <span class="ds-detail-icon"><i class="fa fa-file-signature"></i></span>
                <span>Dokumen</span>
            </div>
            <div class="ds-detail-card-bd">
                <div class="ds-detail-grid-2col">
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">No. Dokumen</span>
                        <span class="ds-detail-value ds-detail-code"><?= htmlspecialchars($row->docnumb) ?: '-' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Tgl. Dokumen</span>
                        <span class="ds-detail-value"><?= !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '-' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Dokter Pemeriksa</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->fullname) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Catatan Dokter</span>
                        <span class="ds-detail-value"><?= nl2br(htmlspecialchars($row->catatan)) ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Audit Trail -->
    <div class="ds-detail-audit" style="margin-top: 12px;">
        <div class="ds-detail-audit-hd">
            <i class="fa fa-history"></i> Audit Trail
        </div>
        <div class="ds-detail-audit-bd">
            <div class="ds-detail-audit-item">
                <span class="ds-audit-icon"><i class="fa fa-plus-circle"></i></span>
                <span class="ds-audit-text">Dibuat oleh <strong><?= htmlspecialchars($row->insertby) ?: '-' ?></strong> pada <strong><?= !empty($row->insertdt) ? date('d/m/Y H:i', strtotime($row->insertdt)) : '-' ?></strong></span>
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
