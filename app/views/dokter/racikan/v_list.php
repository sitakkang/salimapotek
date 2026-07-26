<?php if (!empty($pulv_list)): ?>
<?php foreach ($pulv_list as $p): ?>
<div class="pulv-card" style="border:1px solid var(--ds-border);border-radius:8px;margin-bottom:12px;overflow:hidden;">
    <div style="background:linear-gradient(135deg,#e8f5e9,#f1faf3);padding:10px 14px;display:flex;align-items:center;gap:10px;border-bottom:1px solid var(--ds-border);">
        <div style="width:32px;height:32px;border-radius:50%;background:var(--ds-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;">
            <i class="fa fa-mortar-pestle"></i>
        </div>
        <div style="flex:1;">
            <strong style="font-size:14px;color:#1b5e20;"><?= htmlspecialchars($p->pulv_name) ?></strong>
            <span style="font-size:11px;color:#666;margin-left:10px;">
                Dosis: <?= htmlspecialchars($p->pulv_dosis) ?> &bull; Jml: <?= intval($p->pulv_qty) ?> bks
            </span>
        </div>
        <div style="display:flex;gap:4px;">
            <button class="ds-act-btn ds-act-edit btn-edit-pulv" data-id="<?= $p->id_pulv ?>" title="Edit Racikan" style="padding:3px 8px;">
                <i class="fa fa-pen"></i>
            </button>
            <button class="ds-act-btn ds-act-delete btn-delete-pulv" data-id="<?= $p->id_pulv ?>" title="Hapus Racikan" style="padding:3px 8px;">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
    <div style="padding:8px 14px;">
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
                $no = 1;
                $items = $pulv_items[$p->id_pulv] ?? array();
                foreach ($items as $o): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($o->trans_obat_name) ?></td>
                    <td class="text-center"><?= intval($o->trans_obat_qty) ?></td>
                    <td><?= htmlspecialchars($o->trans_obat_satuan) ?></td>
                    <td><?= htmlspecialchars($o->trans_obat_dosis ?: '-') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($p->pulv_notes)): ?>
        <div style="margin-top:6px;padding:6px 10px;background:#f8f9fa;border-radius:4px;font-size:11px;color:#555;">
            <strong>Catatan:</strong> <?= nl2br(htmlspecialchars($p->pulv_notes)) ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<p class="text-muted" style="margin:10px 0 0;font-size:13px;">
    <i class="fa fa-info-circle"></i> Belum ada racikan. Centang obat lalu klik "Buat Racikan".
</p>
<?php endif; ?>
