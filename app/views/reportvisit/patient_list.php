<div class="ds-detail-wrap">

    <div style="margin-bottom:12px;">
        <strong style="font-size:14px;">Tanggal: <?= htmlspecialchars($tgl) ?></strong>
        <span style="font-size:12px;color:#999;margin-left:8px;">
            Total: <?= $rows->num_rows() ?> pasien
        </span>
    </div>

    <?php if ($rows->num_rows() > 0): ?>
    <div class="ds-wide-table-wrap" style="margin:0;">
        <table class="ds-table" style="margin-top:0;">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="100">No. RM</th>
                    <th>Nama Pasien</th>
                    <th width="80">JK</th>
                    <th width="160">Perusahaan</th>
                    <th width="110">Telepon</th>
                    <th width="140">Dokter</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach ($rows->result() as $r): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($r->trans_patient_code) ?></td>
                    <td><?= htmlspecialchars($r->patient_name) ?></td>
                    <td><?= $r->patient_gender === 'L' ? 'Laki-laki' : ($r->patient_gender === 'P' ? 'Perempuan' : '-') ?></td>
                    <td><?= htmlspecialchars($r->trans_patient_company) ?: '-' ?></td>
                    <td><?= htmlspecialchars($r->trans_patient_phone) ?: '-' ?></td>
                    <td><?= htmlspecialchars($r->doct_name) ?: '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="text-muted"><i class="fa fa-info-circle"></i> Tidak ada data pasien.</p>
    <?php endif; ?>

</div>
