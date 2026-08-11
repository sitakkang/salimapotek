<div class="ds-detail-wrap">

    <!-- Patient Info -->
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
                        <span class="ds-detail-value"><?= htmlspecialchars($row->patient_name ?? '') ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Perusahaan</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->trans_patient_company ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Telepon</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->trans_patient_phone ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                    <div class="ds-detail-field">
                        <span class="ds-detail-label">Dokter</span>
                        <span class="ds-detail-value"><?= htmlspecialchars($row->doct_name ?? '') ?: '<span class="text-muted">-</span>' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Obat Non-Racikan -->
    <div class="ds-detail-card" style="margin-top: 12px;">
        <div class="ds-detail-card-hd">
            <span class="ds-detail-icon"><i class="fa fa-pills"></i></span>
            <span>Resep Obat</span>
        </div>
        <div class="ds-detail-card-bd">
            <?php if (!empty($obat)): ?>
            <div class="ds-wide-table-wrap" style="margin:0;">
                <table class="ds-table" style="margin-top:0;">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Nama Obat</th>
                            <th width="70">Satuan</th>
                            <th width="60">Qty</th>
                            <th width="110">Dosis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($obat as $o): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($o->trans_obat_name ?? '') ?></td>
                            <td><?= htmlspecialchars($o->trans_obat_satuan ?? '') ?></td>
                            <td class="text-center"><?= intval($o->trans_obat_qty) ?></td>
                            <td><?= htmlspecialchars($o->trans_obat_dosis ?: '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted" style="margin:0;"><i class="fa fa-info-circle"></i> Tidak ada resep obat.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Racikan -->
    <div class="ds-detail-card" style="margin-top: 12px;">
        <div class="ds-detail-card-hd">
            <span class="ds-detail-icon"><i class="fa fa-mortar-pestle"></i></span>
            <span>Racikan</span>
        </div>
        <div class="ds-detail-card-bd">
            <?php if (!empty($racikan)): ?>
                <?php foreach ($racikan as $p): ?>
                <div style="border:1px solid var(--ds-border);border-radius:6px;margin-bottom:10px;overflow:hidden;">
                    <div style="background:linear-gradient(135deg,#e8f5e9,#f1faf3);padding:8px 12px;font-weight:600;font-size:13px;color:#1b5e20;border-bottom:1px solid var(--ds-border);">
                        <i class="fa fa-mortar-pestle"></i> <?= htmlspecialchars($p->pulv_name ?? '') ?>
                        <span style="font-size:11px;color:#666;margin-left:10px;font-weight:400;">
                            Dosis: <?= htmlspecialchars($p->pulv_dosis ?? '') ?> &bull; Jml: <?= intval($p->pulv_qty) ?> bks
                        </span>
                    </div>
                    <div style="padding:6px 12px;">
                        <table class="ds-table" style="margin:0;font-size:12px;">
                            <thead>
                                <tr>
                                    <th width="30">No</th>
                                    <th>Obat</th>
                                    <th width="60">Qty</th>
                                    <th width="80">Satuan</th>
                                    <th width="110">Dosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no=1;
                                $items = $pulv_items[$p->id_pulv] ?? array();
                                foreach ($items as $o): 
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($o->trans_obat_name ?? '') ?></td>
                                    <td class="text-center"><?= intval($o->trans_obat_qty) ?></td>
                                    <td><?= htmlspecialchars($o->trans_obat_satuan ?? '') ?></td>
                                    <td><?= htmlspecialchars($o->trans_obat_dosis ?: '-') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (!empty($p->pulv_notes)): ?>
                        <div style="margin-top:4px;padding:4px 8px;background:#f8f9fa;border-radius:4px;font-size:11px;color:#555;">
                            <strong>Catatan:</strong> <?= nl2br(htmlspecialchars($p->pulv_notes)) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <p class="text-muted" style="margin:0;"><i class="fa fa-info-circle"></i> Tidak ada racikan.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
