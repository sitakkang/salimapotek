<table class="ds-table" id="tabel_diagnosa" style="margin-top:0;">
    <thead>
        <tr>
            <th width="100">Kode</th>
            <th>Diagnosa</th>
            <th>Catatan Dokter</th>
            <th width="50" class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($diagnosa_terpilih as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d->trans_dgn_cat) ?: '-' ?></td>
            <td><?= htmlspecialchars($d->trans_dgn_name) ?></td>
            <td><?= htmlspecialchars($d->trans_dgn_note ?: '') ?></td>
            <td class="text-center">
                <button class="ds-act-btn ds-act-delete del-diagnosa-btn" data-id="<?= $d->id_trans_dgn ?>" style="padding:3px 8px;">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
