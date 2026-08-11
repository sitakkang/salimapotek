<div class="ds-page-wrap">

    <!-- Header Panel — compact & professional -->
    <div class="ds-page-header">
        <div class="ds-page-header-start">
            <div class="ds-page-header-icon">
                <i class="fa fa-user-injured"></i>
            </div>
            <div>
                <h5 class="ds-page-header-title">Manajemen Pendaftaran</h5>
                <p class="ds-page-header-desc">Cari dan kelola data pasien</p>
            </div>
        </div>
        <div class="ds-page-header-end">
            <button class="ds-btn-header" id="add_btn">
                <i class="fa fa-plus-circle"></i> Tambah Pasien
            </button>
        </div>
    </div>

    <!-- Search Card -->
    <div class="ds-card" style="margin-bottom: 16px;">
        <div class="ds-card-bd" style="padding: 14px 20px;">
            <form id="search_form" onsubmit="return false;">
                <div class="row" style="margin: 0 -6px;">
                    <div class="col-md-3" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">NO. RM</label>
                            <input type="text" id="search_rm" class="form-control" placeholder="Cari No. RM" maxlength="25" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">KTP</label>
                            <input type="text" id="search_ktp" class="form-control" placeholder="Cari No. KTP / No. ID Card" maxlength="20" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3" style="padding: 0 6px;">
                        <div class="ds-form-group" style="margin-bottom: 0;">
                            <label style="font-size: 12px; margin-bottom: 4px;">NAMA</label>
                            <input type="text" id="search_nama" class="form-control" placeholder="Cari Nama Pasien" maxlength="100" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3" style="padding: 0 6px; display: flex; align-items: flex-end;">
                        <button class="ds-btn-action ds-btn-green" id="search_btn" style="padding: 7px 22px;">
                            <i class="fa fa-search"></i> Cari
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
                <table id="tabel_patient" class="ds-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th width="40" class="text-center">No</th>
                            <th width="80" class="text-center">NO. RM</th>
                            <th class="text-center">Nama Pasien</th>
                            <th width="80" class="text-center">JK</th>
                            <th width="150" class="text-center">KTP</th>
                            <th width="90" class="text-center">No. ID Card</th>
                            <th width="80" class="text-center">Perusahaan</th>
                            <th width="100" class="text-center">No. Telepon</th>
                            <th width="80" class="text-center">Status</th>
                            <th width="120" class="text-center">Aksi</th>
                            <th width="80" class="text-center">Daftar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
