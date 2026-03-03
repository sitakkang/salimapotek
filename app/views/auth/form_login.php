<style>
/* ── Login page overrides ── */
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
    gap: 1rem;
    padding: 2rem;
}
@media (min-width: 768px) { .lp-left { padding: 2.5rem; } }

.lp-brand { display: flex; justify-content: center; }
@media (min-width: 768px) { .lp-brand { justify-content: flex-start; } }

.lp-brand a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    color: #111;
}
.lp-brand-icon {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    background: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.lp-brand-icon img {
    width: 22px;
    height: 22px;
    object-fit: contain;
    filter: brightness(0) invert(1);
}

.lp-form-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}
.lp-form-inner {
    width: 100%;
    max-width: 340px;
}
.lp-form-inner h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #111;
    margin-bottom: 0.2rem;
}
.lp-form-inner .lp-subtitle {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1.75rem;
}
.lp-form-inner .form-group label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.35rem;
}
.lp-form-inner .form-control {
    border-radius: 8px;
    font-size: 0.9rem;
    padding: 0.55rem 0.75rem;
    border: 1px solid #d1d5db;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.lp-form-inner .form-control:focus {
    border-color: #111;
    box-shadow: 0 0 0 3px rgba(17,17,17,0.08);
}
.lp-btn {
    display: block;
    width: 100%;
    background: #111;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.65rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 0.5rem;
}
.lp-btn:hover { background: #333; }

/* ── Right panel – cover image ── */
.lp-cover {
    display: none;
    position: relative;
    overflow: hidden;
    background: #1f2937;
}
@media (min-width: 992px) { .lp-cover { display: block; } }

.lp-cover img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.6;
}
.lp-cover-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-end;
    padding: 2.5rem;
    color: #fff;
}
.lp-cover-overlay h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
}
.lp-cover-overlay p { font-size: 0.9rem; opacity: 0.8; margin: 0; }
</style>

<div class="lp-wrapper">

    <!-- Left: Form -->
    <div class="lp-left">
        <!-- <div class="lp-brand">
            <a href="<?=site_url();?>">
                <div class="lp-brand-icon">
                    <img src="<?=base_url($this->l_skin->apps_config('logo'));?>" alt="logo">
                </div>
                <?=$this->l_skin->apps_config('title');?>
            </a>
        </div> -->

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
            </div>
        </div>
    </div>

    <!-- Right: Cover -->
    <div class="lp-cover">
        <img src="<?=base_url($this->l_skin->apps_config('login'));?>" alt="Cover">
        <div class="lp-cover-overlay">
            <h3><?=$this->l_skin->apps_config('title');?></h3>
            <p>Sistem kasir modern untuk kemudahan transaksi Anda.</p>
        </div>
    </div>

</div>