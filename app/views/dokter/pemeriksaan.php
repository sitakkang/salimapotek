<?php
// Helper hitung usia
$usia = '';
if (!empty($row->patient_bod)) {
    $tgl_lahir = new DateTime($row->patient_bod);
    $today = new DateTime();
    $diff = $today->diff($tgl_lahir);
    $usia = $diff->y . ' Thn';
}
?>
<div class="ds-page-wrap">

    <!-- Header -->
    <div class="ds-page-header">
        <div class="ds-page-header-start">
            <div class="ds-page-header-icon">
                <i class="fa fa-stethoscope"></i>
            </div>
            <div>
                <h5 class="ds-page-header-title">Pemeriksaan Pasien</h5>
                <p class="ds-page-header-desc">Input diagnosa, obat, dan pembuatan SKS</p>
            </div>
        </div>
        <div class="ds-page-header-end">
            <a href="<?= site_url('dokter') ?>" class="ds-btn-header" style="background:#6c757d;color:#fff;">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row" style="margin: 0 -8px;">

        <!-- ============================================================ -->
        <!-- LEFT COLUMN — Data Pasien -->
        <!-- ============================================================ -->
        <div class="col-md-4 pem-compact" style="padding: 0 8px;">
            <div class="ds-card" style="margin-bottom: 16px;">
                <div class="ds-card-hd" style="background:#f8f9fa;border-bottom:2px solid var(--ds-primary);">
                    <span class="hd-icon"><i class="fa fa-user-circle" style="color:var(--ds-primary);"></i></span>
                    <span style="font-weight:700;">Data Pasien</span>
                </div>
                <div class="ds-card-bd" style="padding:16px 18px;">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;width:110px;">No.RM</td>
                            <td style="padding:5px 0;font-weight:600;color:#333;"><?= htmlspecialchars($row->trans_patient_code) ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Nama</td>
                            <td style="padding:5px 0;font-weight:600;color:#333;"><?= htmlspecialchars($row->patient_name) ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Tgl Lahir</td>
                            <td style="padding:5px 0;color:#333;"><?= !empty($row->patient_bod) ? date('d-m-Y', strtotime($row->patient_bod)) : '-' ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Usia</td>
                            <td style="padding:5px 0;color:#333;"><?= $usia ?: '-' ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Perusahaan</td>
                            <td style="padding:5px 0;color:#333;"><?= htmlspecialchars($row->trans_patient_company) ?: '-' ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Departemen</td>
                            <td style="padding:5px 0;color:#333;"><?= htmlspecialchars($row->trans_patient_department) ?: '-' ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Telepon</td>
                            <td style="padding:5px 0;color:#333;"><?= htmlspecialchars($row->trans_patient_phone) ?: '-' ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Alamat</td>
                            <td style="padding:5px 0;color:#333;"><?= htmlspecialchars($row->patient_address) ?: '-' ?></td>
                        </tr>
                        <tr>
                            <td style="padding:5px 8px 5px 0;color:#6c757d;font-size:12px;white-space:nowrap;vertical-align:top;">Tgl Periksa</td>
                            <td style="padding:5px 0;color:#333;font-weight:600;"><?= date('d-m-Y') ?></td>
                        </tr>
                    </table>

                    <!-- Anamnesa Summary -->
                    <div style="background:#f0f7ff;border-radius:6px;padding:10px 12px;margin-top:12px;">
                        <div style="font-size:11px;font-weight:700;color:var(--ds-primary);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">
                            <i class="fa fa-notes-medical"></i> Anamnesa
                        </div>
                        <div style="font-size:12px;color:#555;line-height:1.7;">
                            <?php
                            $vitals = array();
                            if (!empty($row->anm_temp)) $vitals[] = 'Suhu: ' . htmlspecialchars($row->anm_temp);
                            if (!empty($row->anm_pulse)) $vitals[] = 'Nadi: ' . htmlspecialchars($row->anm_pulse);
                            if (!empty($row->anm_respirasi)) $vitals[] = 'RR: ' . htmlspecialchars($row->anm_respirasi);
                            if (!empty($row->anm_blood_press)) $vitals[] = 'TD: ' . htmlspecialchars($row->anm_blood_press);
                            if (!empty($row->anm_height)) $vitals[] = 'TB: ' . htmlspecialchars($row->anm_height) . ' cm';
                            if (!empty($row->anm_weight)) $vitals[] = 'BB: ' . htmlspecialchars($row->anm_weight) . ' kg';
                            echo !empty($vitals) ? implode(' &bull; ', $vitals) : '<span class="text-muted">-</span>';
                            ?>
                            <?php if (!empty($row->anm_note)): ?>
                                <br><strong>Catatan:</strong> <?= nl2br(htmlspecialchars($row->anm_note)) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokter Pemeriksa Card -->
            <div class="ds-card" style="margin-bottom: 16px;">
                <div class="ds-card-hd" style="background:#f8f9fa;border-bottom:2px solid var(--ds-primary);">
                    <span class="hd-icon"><i class="fa fa-user-md" style="color:var(--ds-primary);"></i></span>
                    <span style="font-weight:700;">Dokter Pemeriksa</span>
                </div>
                <div class="ds-card-bd" style="padding:14px 18px;">
                    <input type="hidden" id="mrd_doct_by" value="<?= intval($row->mrd_doct_by) ?>">
                    <a href="javascript:;" id="change_doctor_btn" style="text-decoration:none;display:flex;align-items:center;gap:10px;">
                        <div style="width:38px;height:38px;border-radius:50%;background:var(--ds-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;">
                            <?= !empty($row->mrd_doct_name) ? strtoupper(substr($row->mrd_doct_name, 0, 1)) : '<i class="fa fa-user-md"></i>' ?>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:600;color:#333;" id="doctor_name_display">
                                <?= htmlspecialchars($row->mrd_doct_name) ?: '<span class="text-muted">Belum ditentukan</span>' ?>
                            </div>
                            <div style="font-size:11px;color:#999;">Klik untuk mengganti dokter</div>
                        </div>
                        <div style="margin-left:auto;color:var(--ds-primary);"><i class="fa fa-chevron-right"></i></div>
                    </a>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- RIGHT COLUMN — Pemeriksaan (Tabs) -->
        <!-- ============================================================ -->
        <div class="col-md-8 pem-compact" style="padding: 0 8px;">
            <div class="ds-card" style="margin-bottom: 16px;">
                <div class="ds-card-hd" style="background:#f8f9fa;border-bottom:2px solid var(--ds-primary);padding:0;">
                    <ul class="nav nav-tabs pem-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-diagnosa" role="tab">
                                <i class="fa fa-stethoscope"></i> Diagnosa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" id="obat-tab" href="#tab-obat" role="tab">
                                <i class="fa fa-pills"></i> Obat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" id="sks-tab" href="#tab-sks" role="tab">
                                <i class="fa fa-file-signature"></i> SKS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" id="skbs-tab" href="#tab-skbs" role="tab">
                                <i class="fa fa-heartbeat"></i> SKBS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" id="skmb-tab" href="#tab-skmb" role="tab">
                                <i class="fa fa-ambulance"></i> SKMB
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="ds-card-bd" style="padding:18px 20px;">

                    <div class="tab-content" id="pemeriksaanTabsContent">

                        <!-- ============================================ -->
                        <!-- TAB 1: Diagnosa -->
                        <!-- ============================================ -->
                        <div class="tab-pane fade show active" id="tab-diagnosa" role="tabpanel">
                            <input type="hidden" id="mrd_id_diagnosa" value="<?= $row->id_medical_record ?>">

                            <div class="row" style="margin: 0 -4px;">
                                <div class="col-md-6" style="padding: 0 4px;">
                                    <select id="select_diagnosa" class="form-control autocomplete" data-placeholder="Cari & pilih diagnosa...">
                                        <option value=""></option>
                                        <?php foreach ($diagnosa_list as $d): ?>
                                            <option value="<?= $d->id_diagnosa ?>" data-cat="<?= htmlspecialchars($d->dgn_cat) ?>">
                                                <?= htmlspecialchars($d->dgn_cat ? '['.$d->dgn_cat.'] ' : '') . htmlspecialchars($d->dgn_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4" style="padding: 0 4px;">
                                    <input type="text" id="dgn_note" class="form-control" placeholder="Catatan dokter..." maxlength="225">
                                </div>
                                <div class="col-md-2" style="padding: 0 4px;">
                                    <button class="ds-btn-action ds-btn-green" id="btn_add_diagnosa" style="padding:7px 14px;width:100%;">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>

                            <hr style="border-color:var(--ds-border);margin:14px 0;">

                            <div class="ds-wide-table-wrap" style="margin:0;" id="diagnosa-table-wrap">
                                <?php $this->load->view('dokter/_diagnosa_table', array('diagnosa_terpilih' => $diagnosa_terpilih)); ?>
                            </div>
                        </div>

                        <!-- ============================================ -->
                        <!-- TAB 2: Obat -->
                        <!-- ============================================ -->
                        <div class="tab-pane fade" id="tab-obat" role="tabpanel">
                            <input type="hidden" id="mrd_id_obat" value="<?= $row->id_medical_record ?>">

                            <div class="row" style="margin: 0 -4px;">
                                <div class="col-md-6" style="padding: 0 4px;">
                                    <select id="select_obat" class="form-control autocomplete" data-placeholder="Cari & pilih obat...">
                                        <option value=""></option>
                                        <?php foreach ($obat_list as $o): ?>
                                            <option value="<?= $o->id_obat ?>" data-price="<?= $o->obat_price ?>">
                                                <?= htmlspecialchars($o->obat_name) ?> — <?= htmlspecialchars($o->obat_satuan) ?> (Rp <?= number_format($o->obat_price, 0, ',', '.') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2" style="padding: 0 4px;">
                                    <input type="number" id="obat_qty" class="form-control" value="1" min="1" placeholder="Qty">
                                </div>
                                <div class="col-md-2" style="padding: 0 4px;">
                                    <input type="text" id="obat_dosis" class="form-control" placeholder="Dosis" maxlength="100">
                                </div>
                                <div class="col-md-2" style="padding: 0 4px;">
                                    <button class="ds-btn-action ds-btn-green" id="btn_add_obat" style="padding:7px 14px;width:100%;">
                                        <i class="fa fa-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>

                            <hr style="border-color:var(--ds-border);margin:14px 0;">

                            <div class="ds-wide-table-wrap" style="margin:0;" id="obat-table-wrap">
                                <?php $this->load->view('dokter/_obat_table', array('obat_terpilih' => $obat_terpilih)); ?>
                            </div>

                            <hr style="border-color:var(--ds-border);margin:14px 0;">

                            <h6 style="font-weight:700;margin-bottom:10px;">
                                <i class="fa fa-mortar-pestle"></i> Racikan
                            </h6>
                            <div id="pulv-list-wrap">
                                <?php $this->load->view('dokter/racikan/v_list', array(
                                    'pulv_list' => $pulv_list,
                                    'pulv_items' => $pulv_items,
                                )); ?>
                            </div>
                        </div>

                        <!-- ============================================ -->
                        <!-- TAB 3: SKS -->
                        <!-- ============================================ -->
                        <div class="tab-pane fade" id="tab-sks" role="tabpanel">
                            <input type="hidden" id="visit_id_sks" value="<?= $row->id_visit ?>">
                            <input type="hidden" id="sks_patient_name" value="<?= htmlspecialchars($row->patient_name) ?>">
                            <input type="hidden" id="sks_company_name" value="<?= htmlspecialchars($row->trans_patient_company) ?>">

                            <div id="sks-section-wrap" data-visit-id="<?= $row->id_visit ?>">
                                <?php $this->load->view('dokter/_sks_section', array(
                                    'row' => $row,
                                    'sks' => $sks,
                                    'sks_list' => $sks ? array($sks) : array(),
                                    'dokter_list' => $dokter_list,
                                    'sks_diagnosa_default' => $sks_diagnosa_default,
                                    'sks_terapi_default' => $sks_terapi_default,
                                    'sks_docnumb_default' => $sks_docnumb_default,
                                )); ?>
                            </div>
                        </div>

                        <!-- ============================================ -->
                        <!-- TAB 4: SKBS -->
                        <!-- ============================================ -->
                        <div class="tab-pane fade" id="tab-skbs" role="tabpanel">
                            <input type="hidden" id="visit_id_skbs" value="<?= $row->id_visit ?>">
                            <div id="skbs-section-wrap" data-visit-id="<?= $row->id_visit ?>">
                                <?php $this->load->view('dokter/_skbs_section', array(
                                    'row' => $row,
                                    'skbs' => $skbs,
                                    'dokter_list' => $dokter_list,
                                )); ?>
                            </div>
                        </div>

                        <!-- ============================================ -->
                        <!-- TAB 5: SKMB -->
                        <!-- ============================================ -->
                        <div class="tab-pane fade" id="tab-skmb" role="tabpanel">
                            <input type="hidden" id="visit_id_skmb" value="<?= $row->id_visit ?>">
                            <div id="skmb-section-wrap" data-visit-id="<?= $row->id_visit ?>">
                                <?php $this->load->view('dokter/_skmb_section', array(
                                    'row' => $row,
                                    'skmb' => $skmb,
                                    'dokter_list' => $dokter_list,
                                )); ?>
                            </div>
                        </div>

                    </div><!-- /tab-content -->

                </div><!-- /ds-card-bd -->
            </div><!-- /ds-card -->
        </div><!-- /col-md-8 -->

    </div><!-- /row -->

</div>

<!-- Modal Ganti Dokter Pemeriksa -->
<div class="modal fade" id="modalGantiDokter" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm ds-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user-md"></i> Pilih Dokter Pemeriksa</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="padding:0;">
                <div class="list-group" style="border-radius:0;margin:0;">
                    <?php foreach ($dokter_level6 as $d): ?>
                    <a href="javascript:;" class="list-group-item list-group-item-action pilih-dokter-item"
                       data-id="<?= $d->id_user ?>"
                       data-name="<?= htmlspecialchars($d->fullname) ?>"
                       style="border-left:0;border-right:0;padding:12px 16px;display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:50%;background:var(--ds-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;">
                            <?= strtoupper(substr($d->fullname, 0, 1)) ?>
                        </div>
                        <div>
                            <div style="font-weight:600;color:#333;font-size:13px;"><?= htmlspecialchars($d->fullname) ?></div>
                            <div style="font-size:11px;color:#999;">Dokter Pemeriksa</div>
                        </div>
                        <div class="ml-auto">
                            <i class="fa fa-chevron-right" style="color:#ccc;"></i>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php if (empty($dokter_level6)): ?>
                    <div class="text-muted text-center" style="padding:20px;">Tidak ada dokter tersedia</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
