<style>
.ug-flow {
    display: flex;
    align-items: stretch;
    gap: 14px;
    flex-wrap: wrap;
}
.ug-step {
    flex: 1 1 200px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: var(--ds-bg, #fff);
    border: 1px solid var(--ds-border, #b7d5c4);
    border-radius: 12px;
    padding: 14px 16px;
}
.ug-step-num {
    width: 34px;
    height: 34px;
    min-width: 34px;
    border-radius: 50%;
    background: var(--ds-grad-primary, linear-gradient(135deg,#2d6a4f,#40916c));
    color: #fff;
    font-weight: 800;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ug-step-title { font-weight: 700; font-size: 13.5px; margin-bottom: 3px; }
.ug-step-desc  { font-size: 12px; color: #5b6b63; line-height: 1.5; }
.ug-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #40916c;
    font-size: 20px;
    min-width: 18px;
}
.ug-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
    margin-top: 12px;
}
.ug-card {
    background: var(--ds-bg, #fff);
    border: 1px solid var(--ds-border, #b7d5c4);
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    transition: box-shadow .2s;
}
.ug-card:hover { box-shadow: 0 6px 20px rgba(45,106,79,.12); }
.ug-card-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg,#e8f5e9,#f1faf3);
    color: #2d6a4f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.ug-card-title { font-weight: 700; font-size: 13.5px; margin-bottom: 4px; }
.ug-card-desc  { font-size: 12.3px; color: #5b6b63; line-height: 1.55; }
.ug-section-title {
    font-weight: 700;
    font-size: 14px;
    margin: 18px 0 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ug-section-title i { color: #40916c; }
@media (max-width: 767px) {
    .ug-flow { flex-direction: column; }
    .ug-arrow { transform: rotate(90deg); min-height: 18px; }
}
</style>

<div class="ds-page-wrap">

    <!-- Header -->
    <div class="ds-page-header">
        <div class="ds-page-header-start">
            <div class="ds-page-header-icon">
                <i class="fa fa-book"></i>
            </div>
            <div>
                <h5 class="ds-page-header-title">User Guide</h5>
                <p class="ds-page-header-desc">Panduan penggunaan aplikasi Sahabat Apotek Care</p>
            </div>
        </div>
    </div>

    <!-- Flow Utama -->
    <div class="ds-card">
        <div class="ds-card-bd" style="padding: 20px 22px;">
            <div class="ug-section-title" style="margin-top:0;">
                <i class="fa fa-sitemap"></i> Flow Alur Pemeriksaan
            </div>
            <div class="ug-flow">
                <div class="ug-step">
                    <div class="ug-step-num">1</div>
                    <div class="ug-step-body">
                        <div class="ug-step-title">Daftar Pasien</div>
                        <div class="ug-step-desc">Cari pasien yang sudah pernah didaftarkan sebelumnya.</div>
                    </div>
                </div>
                <div class="ug-arrow"><i class="fa fa-arrow-right"></i></div>
                <div class="ug-step">
                    <div class="ug-step-num">2</div>
                    <div class="ug-step-body">
                        <div class="ug-step-title">Anamnesa</div>
                        <div class="ug-step-desc">Menginput hasil anamnesa pasien.</div>
                    </div>
                </div>
                <div class="ug-arrow"><i class="fa fa-arrow-right"></i></div>
                <div class="ug-step">
                    <div class="ug-step-num">3</div>
                    <div class="ug-step-body">
                        <div class="ug-step-title">Pemeriksaan Dokter</div>
                        <div class="ug-step-desc">Dokter melakukan pemeriksaan &amp; menerbitkan surat keterangan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Menu -->
    <div class="ug-section-title">
        <i class="fa fa-list-ul"></i> Detail Menu
    </div>
    <div class="ug-grid">

        <div class="ug-card">
            <div class="ug-card-icon"><i class="fa fa-user-plus"></i></div>
            <div>
                <div class="ug-card-title">Pendaftaran</div>
                <div class="ug-card-desc">Menambahkan pasien baru atau mencari pasien yang sudah pernah ditambahkan, lalu lakukan pendaftaran.</div>
            </div>
        </div>

        <div class="ug-card">
            <div class="ug-card-icon"><i class="fa fa-clipboard-list"></i></div>
            <div>
                <div class="ug-card-title">Anamnesa</div>
                <div class="ug-card-desc">Menginput hasil anamnesa pasien.</div>
            </div>
        </div>

        <div class="ug-card">
            <div class="ug-card-icon"><i class="fa fa-stethoscope"></i></div>
            <div>
                <div class="ug-card-title">Dokter</div>
                <div class="ug-card-desc">Menginput pemeriksaan pasien berupa diagnosa, obat, SKS, SKBS, SKMB dan memilih dokter pemeriksa.</div>
            </div>
        </div>

        <div class="ug-card">
            <div class="ug-card-icon"><i class="fa fa-pills"></i></div>
            <div>
                <div class="ug-card-title">Farmasi</div>
                <div class="ug-card-desc">Modul apoteker untuk melihat daftar obat pasien.</div>
            </div>
        </div>

        <div class="ug-card">
            <div class="ug-card-icon"><i class="fa fa-users"></i></div>
            <div>
                <div class="ug-card-title">Pengguna</div>
                <div class="ug-card-desc">Manajemen pengguna aplikasi berupa tambah, ubah, hapus dan reset password pengguna.</div>
            </div>
        </div>

        <div class="ug-card">
            <div class="ug-card-icon"><i class="fa fa-capsules"></i></div>
            <div>
                <div class="ug-card-title">Master Obat</div>
                <div class="ug-card-desc">Manajemen obat yang digunakan pada pemeriksaan.</div>
            </div>
        </div>

        <div class="ug-card">
            <div class="ug-card-icon"><i class="fa fa-file-medical"></i></div>
            <div>
                <div class="ug-card-title">Master Diagnosa</div>
                <div class="ug-card-desc">Manajemen diagnosa yang digunakan pada pemeriksaan.</div>
            </div>
        </div>

    </div>
</div>
