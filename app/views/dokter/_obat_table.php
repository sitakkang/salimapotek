<table class="ds-table" id="tabel_obat" style="margin-top:0;">
    <thead>
        <tr>
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
            <td><?= htmlspecialchars($o->trans_obat_name) ?></td>
            <td><?= htmlspecialchars($o->trans_obat_satuan) ?></td>
            <td class="text-center"><?= intval($o->trans_obat_qty ?: 0) ?></td>
            <td><?= htmlspecialchars($o->trans_obat_dosis ?: '-') ?></td>
            <td class="text-center">
                <button class="ds-act-btn ds-act-delete del-obat-btn" data-id="<?= $o->id_trans_obat ?>" style="padding:3px 8px;">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
