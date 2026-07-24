<div class="ds-page-wrap">

    <!-- Header Panel -->
    <div class="ds-page-header">
        <div class="ds-page-header-start">
            <div class="ds-page-header-icon">
                <i class="fa fa-pills"></i>
            </div>
            <div>
                <h5 class="ds-page-header-title">Laporan Obat</h5>
                <p class="ds-page-header-desc">Rekap penggunaan obat berdasarkan tanggal dan nama obat</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="ds-card" style="margin-bottom: 16px;">
        <div class="ds-card-bd" style="padding: 14px 20px; overflow: visible;">
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
                    <div class="col-md-3" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">Nama Obat</label>
                            <select id="filter_obat" class="form-control autocomplete" data-placeholder="Semua Obat">
                                <option value="">Semua Obat</option>
                            </select>
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
            <div class="ds-wide-table-wrap" style="margin: 0; overflow: visible;">
                <table id="tabel_reportobat" class="ds-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Nama Obat</th>
                            <th width="80">Satuan</th>
                            <th width="60">Qty</th>
                            <th width="120">Dosis</th>
                            <th width="100">Harga</th>
                            <th width="110">Total Harga</th>
                            <th width="150">Pasien</th>
                            <th width="100">No. RM</th>
                            <th width="90">Tgl</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
