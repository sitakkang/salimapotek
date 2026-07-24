<div class="ds-page-wrap">

    <!-- Header Panel -->
    <div class="ds-page-header">
        <div class="ds-page-header-start">
            <div class="ds-page-header-icon">
                <i class="fa fa-user-md"></i>
            </div>
            <div>
                <h5 class="ds-page-header-title">Dokter</h5>
                <p class="ds-page-header-desc">Daftar pasien yang siap diperiksa</p>
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
                            <label style="font-size: 12px; margin-bottom: 4px;">Tanggal Kunjungan</label>
                            <input type="text" id="filter_date" class="form-control datepicker"
                                   value="<?= date('d/m/Y') ?>" placeholder="dd/mm/yyyy" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 0 6px; display: flex; align-items: flex-end;">
                        <button class="ds-btn-action ds-btn-green" id="search_btn" style="padding: 7px 22px;">
                            <i class="fa fa-search"></i> Tampilkan
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
                <table id="tabel_dokter" class="ds-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th width="40" class="text-center">No</th>
                            <th width="110">NO. RM</th>
                            <th>Nama Pasien</th>
                            <th width="180">Perusahaan</th>
                            <th width="120">No. Telepon</th>
                            <th width="110" class="text-center">Tgl. Daftar</th>
                            <th width="210" class="text-center">Proses</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
