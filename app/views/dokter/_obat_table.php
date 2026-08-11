<table class="ds-table" id="tabel_obat" style="margin-top:0;">
    <thead>
        <tr>
            <th width="40" class="text-center">Pilih</th>
            <th>Obat</th>
            <th width="70">Satuan</th>
            <th width="60">Qty</th>
            <th width="110">Dosis</th>
            <th width="50" class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($obat_terpilih as $o): ?>
        <tr>
            <td class="text-center">
                <input type="checkbox" class="check-terapi" data-id="<?= $o->id_trans_obat ?>"
                    <?= !empty($o->trans_obat_pulv_id) ? 'disabled' : '' ?>>
            </td>
            <td><?= htmlspecialchars($o->trans_obat_name ?? '') ?>
                <?php if (!empty($o->trans_obat_pulv_id)): ?>
                    <span class="badge badge-success" style="font-size:9px;">Racikan</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($o->trans_obat_satuan ?? '') ?></td>
            <td class="text-center"><?= intval($o->trans_obat_qty ?: 0) ?></td>
            <td><?= htmlspecialchars($o->trans_obat_dosis ?: '-') ?></td>
            <td class="text-center">
                <?php if (empty($o->trans_obat_pulv_id)): ?>
                <button class="ds-act-btn ds-act-delete del-obat-btn" data-id="<?= $o->id_trans_obat ?>" style="padding:3px 8px;">
                    <i class="fa fa-trash"></i>
                </button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div style="margin-top:8px;display:flex;justify-content:space-between;align-items:center;">
    <span style="font-size:12px;color:#999;">
        <i class="fa fa-info-circle"></i> Centang obat untuk dibuat racikan
    </span>
    <button class="ds-btn-action ds-btn-green" id="btn-add-pulv" style="padding:6px 16px;font-size:12px;">
        <i class="fa fa-mortar-pestle"></i> Buat Racikan
    </button>
</div>
