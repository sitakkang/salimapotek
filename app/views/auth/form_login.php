<style>
/* ── Login page — modern professional split design ── */
html, body { margin: 0; padding: 0; height: 100%; background: #fff; }

.lp-wrapper {
    display: grid;
    min-height: 100vh;
    grid-template-columns: 1fr;
}
@media (min-width: 992px) {
    .lp-wrapper { grid-template-columns: 1fr 1fr; }
}

/* ── Left panel ── */
.lp-left {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding: 2rem;
    background: #fff;
}
@media (min-width: 768px) { .lp-left { padding: 2.5rem; } }

.lp-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    justify-content: center;
}
@media (min-width: 768px) { .lp-brand { justify-content: flex-start; } }

.lp-brand-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #fff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.lp-brand-icon img {
    width: 34px;
    height: 34px;
    object-fit: contain;
}
.lp-brand-text {
    font-weight: 700;
    font-size: 1rem;
    color: #111;
    line-height: 1.2;
}
.lp-brand-text small {
    display: block;
    font-weight: 500;
    font-size: 0.7rem;
    color: #6b7280;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.lp-form-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}
.lp-form-inner {
    width: 100%;
    max-width: 360px;
}
.lp-form-inner h2 {
    font-size: 1.75rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 0.3rem;
    letter-spacing: -0.01em;
}
.lp-form-inner .lp-subtitle {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 1.75rem;
}
.lp-form-inner .form-group { margin-bottom: 1.1rem; }
.lp-form-inner .form-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.4rem;
}
.lp-form-inner .form-control {
    width: 100%;
    border-radius: 10px;
    font-size: 0.92rem;
    padding: 0.65rem 0.85rem;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}
.lp-form-inner .form-control:focus {
    border-color: #0e8f7a;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(14,143,122,0.12);
    outline: none;
}
.lp-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    background: linear-gradient(135deg, #0d2e57 0%, #0e8f7a 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0.72rem 1rem;
    font-size: 0.92rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.2s;
    margin-top: 0.5rem;
}
.lp-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(13,46,87,0.25);
}
.lp-foot {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.78rem;
    color: #9ca3af;
}

/* ── Right panel – brand cover ── */
.lp-cover {
    display: none;
    position: relative;
    overflow: hidden;
    color: #fff;
    background:
        radial-gradient(1100px 600px at 85% -10%, rgba(14,143,122,0.35), transparent 60%),
        radial-gradient(900px 500px at -10% 110%, rgba(250,204,21,0.16), transparent 60%),
        linear-gradient(160deg, #081b36 0%, #0d2e57 55%, #0b4a6f 100%);
}
@media (min-width: 992px) { .lp-cover { display: flex; } }

.lp-cover-inner {
    position: relative;
    z-index: 2;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1.3rem;
    padding: 3rem 2.5rem;
    text-align: center;
    overflow: auto;
}
.lp-cover-logo {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: #fff;
    padding: 10px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.35);
    display: flex;
    align-items: center;
    justify-content: center;
}
.lp-cover-logo img { width: 100%; height: 100%; object-fit: contain; }

.lp-cover h3 {
    font-size: 1.65rem;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.01em;
}
.lp-cover .lp-cover-tagline {
    font-size: 0.92rem;
    color: rgba(255,255,255,0.8);
    margin: 0.5rem 0 0;
    max-width: 400px;
}

.lp-cover-poster {
    width: 88%;
    max-width: 420px;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 24px 55px rgba(0,0,0,0.45);
    margin-top: 0.3rem;
}
.lp-cover-poster img {
    display: block;
    width: 100%;
    height: auto;
    object-fit: contain;   /* poster utuh, tanpa potong */
}

.lp-cover-feats {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem 1.2rem;
    justify-content: center;
    margin-top: 0.5rem;
}
.lp-cover-feats span {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.88);
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.16);
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
}
.lp-cover-feats i { color: #facc15; }
</style>

<div class="lp-wrapper">

    <!-- Left: Form -->
    <div class="lp-left">
        <div class="lp-brand">
            <div class="lp-brand-icon">
                <img src="<?=base_url($this->l_skin->apps_config('logo'));?>" alt="logo">
            </div>
            <div class="lp-brand-text">
                <?=$this->l_skin->apps_config('title');?>
                <small>Pharmacy Management System</small>
            </div>
        </div>

        <div class="lp-form-wrap">
            <div class="lp-form-inner">
                <h2>Selamat Datang</h2>
                <p class="lp-subtitle">Masukkan username dan password Anda untuk melanjutkan.</p>

                <?=$this->session->tempdata('notif_login');?>

                <form action="<?=site_url('login');?>" method="POST">
                    <div class="form-group">
                        <label for="inp_username"><i class="fa fa-user"></i> Username</label>
                        <input class="form-control" type="text" id="inp_username"
                               placeholder="Masukkan username" name="username" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="inp_password"><i class="fa fa-key"></i> Password</label>
                        <input class="form-control" type="password" id="inp_password"
                               placeholder="Masukkan password" name="password" required>
                    </div>
                    <?=$token;?>
                    <button type="submit" class="lp-btn">
                        <i class="fa fa-sign-in-alt"></i> Login
                    </button>
                </form>

                <p class="lp-foot">&copy; <?=date('Y');?> <?=$this->l_skin->apps_config('title');?></p>
            </div>
        </div>
    </div>

    <!-- Right: Brand cover -->
    <div class="lp-cover">
        <div class="lp-cover-inner">
            <div class="lp-cover-logo">
                <img src="<?=base_url($this->l_skin->apps_config('logo'));?>" alt="logo">
            </div>
            <div>
                <h3><?=$this->l_skin->apps_config('title');?></h3>
                <p class="lp-cover-tagline">Sistem informasi apotek modern untuk pengelolaan obat, transaksi, dan pelaporan yang akurat &amp; cepat.</p>
            </div>
            <div class="lp-cover-poster">
                <img src="<?=base_url($this->l_skin->apps_config('login'));?>" alt="Cover">
            </div>
            <div class="lp-cover-feats">
                <span><i class="fa fa-check-circle"></i> Manajemen Transaksi</span>
                <span><i class="fa fa-check-circle"></i> Transaksi Cepat</span>
                <span><i class="fa fa-check-circle"></i> Laporan Otomatis</span>
            </div>
        </div>
    </div>

</div>