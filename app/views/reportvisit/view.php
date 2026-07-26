<div class="ds-page-wrap">

    <!-- Header Panel -->
    <div class="ds-page-header">
        <div class="ds-page-header-start">
            <div class="ds-page-header-icon">
                <i class="fa fa-clipboard-list"></i>
            </div>
            <div>
                <h5 class="ds-page-header-title">Laporan Kunjungan</h5>
                <p class="ds-page-header-desc">Rekap jumlah kunjungan pasien per hari berdasarkan rentang tanggal</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="ds-card" style="margin-bottom: 16px;">
        <div class="ds-card-bd" style="padding: 14px 20px;">
            <form id="search_form" onsubmit="return false;">
                <div class="row" style="margin: 0 -6px; align-items: flex-end;">
                    <div class="col-md-3" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">Tanggal Awal</label>
                            <input type="text" id="filter_date_from" class="form-control datepicker"
                                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="col-md-3" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">Tanggal Akhir</label>
                            <input type="text" id="filter_date_to" class="form-control datepicker"
                                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="col-md-3" style="padding: 0 6px; display: flex; align-items: flex-end; gap: 6px;">
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
            <div class="ds-wide-table-wrap" style="margin: 0;">
                <table id="tabel_reportvisit" class="ds-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Tanggal</th>
                            <th width="150">Jumlah Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
