<div class="ds-page-wrap">

    <!-- Header Panel -->
    <div class="ds-page-header">
        <div class="ds-page-header-start">
            <div class="ds-page-header-icon">
                <i class="fa fa-chart-bar"></i>
            </div>
            <div>
                <h5 class="ds-page-header-title">Laporan Obat Bulanan</h5>
                <p class="ds-page-header-desc">Rekap pengeluaran obat per periode</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="ds-card" style="margin-bottom: 16px;">
        <div class="ds-card-bd" style="padding: 14px 20px;">
            <form id="search_form" onsubmit="return false;">
                <div class="row" style="margin: 0 -6px; align-items: flex-end;">
                    <div class="col-md-2" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">Tahun</label>
                            <select id="filter_year" class="form-control">
                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">Bulan</label>
                            <select id="filter_month" class="form-control">
                                <?php $m_now = date('n'); ?>
                                <option value="1" <?= $m_now == 1 ? 'selected' : '' ?>>Januari</option>
                                <option value="2" <?= $m_now == 2 ? 'selected' : '' ?>>Februari</option>
                                <option value="3" <?= $m_now == 3 ? 'selected' : '' ?>>Maret</option>
                                <option value="4" <?= $m_now == 4 ? 'selected' : '' ?>>April</option>
                                <option value="5" <?= $m_now == 5 ? 'selected' : '' ?>>Mei</option>
                                <option value="6" <?= $m_now == 6 ? 'selected' : '' ?>>Juni</option>
                                <option value="7" <?= $m_now == 7 ? 'selected' : '' ?>>Juli</option>
                                <option value="8" <?= $m_now == 8 ? 'selected' : '' ?>>Agustus</option>
                                <option value="9" <?= $m_now == 9 ? 'selected' : '' ?>>September</option>
                                <option value="10" <?= $m_now == 10 ? 'selected' : '' ?>>Oktober</option>
                                <option value="11" <?= $m_now == 11 ? 'selected' : '' ?>>November</option>
                                <option value="12" <?= $m_now == 12 ? 'selected' : '' ?>>Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">Mode</label>
                            <select id="filter_mode" class="form-control">
                                <option value="monthly">Bulanan</option>
                                <option value="weekly">Mingguan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 0 6px; display: flex; align-items: flex-end; gap: 6px;">
                        <button class="ds-btn-action ds-btn-green" id="search_btn" style="padding: 7px 22px;">
                            <i class="fa fa-search"></i> Tampilkan
                        </button>
                        <button class="ds-btn-action ds-btn-blue" id="download_excel_btn" style="padding: 7px 22px;">
                            <i class="fa fa-file-excel"></i> Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="ds-card">
        <div class="ds-card-bd" style="padding: 18px 20px;">
            <div class="ds-wide-table-wrap" style="margin: 0; overflow: visible;">
                <table id="tabel_monthlyobat" class="ds-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Periode</th>
                            <th>Nama Obat</th>
                            <th width="80">Satuan</th>
                            <th width="90">Total Qty</th>
                            <th width="130">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr style="font-weight:700;background:#f8f9fa;">
                            <td colspan="5" style="text-align:right;padding:6px 10px;">Grand Total</td>
                            <td style="text-align:right;padding:6px 10px;" id="grand_total">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
