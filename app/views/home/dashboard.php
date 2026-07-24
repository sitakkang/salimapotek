<?php
$hour = (int)date('H');
if ($hour < 12)       $greeting = 'Selamat Pagi';
elseif ($hour < 17)   $greeting = 'Selamat Siang';
else                  $greeting = 'Selamat Malam';
?>

<div class="ds-dashboard">

  <!-- Page Header -->
  <div class="ds-dash-header">
    <div class="ds-dash-header-left">
      <h1 class="ds-dash-title"><?=$greeting?>, <?=explode(' ', $this->m_auth->get_session('name'))[0]; ?></h1>
      <p class="ds-dash-subtitle"><?=date('l, d F Y');?> · <?=$this->l_skin->apps_config('title');?></p>
    </div>
    <div class="ds-dash-header-right">
      <span class="ds-dash-badge"><i class="fa fa-calendar-alt"></i> <?=date('d/m/Y');?></span>
    </div>
  </div>

  <!-- Stat Cards -->
  <div class="ds-dash-grid">
    <div class="ds-dash-card">
      <div class="ds-dash-card-icon ds-icon-green"><i class="fa fa-file-medical"></i></div>
      <div class="ds-dash-card-body">
        <div class="ds-dash-card-value"><?=isset($sks_hari_ini) ? $sks_hari_ini : '&mdash;';?></div>
        <div class="ds-dash-card-label">SKS Hari Ini</div>
        <div class="ds-dash-card-desc">Surat keterangan sakit hari ini</div>
      </div>
    </div>
    <div class="ds-dash-card">
      <div class="ds-dash-card-icon ds-icon-blue"><i class="fa fa-file-invoice"></i></div>
      <div class="ds-dash-card-body">
        <div class="ds-dash-card-value"><?=isset($sks_bulan_ini) ? $sks_bulan_ini : '&mdash;';?></div>
        <div class="ds-dash-card-label">SKS Bulan Ini</div>
        <div class="ds-dash-card-desc">Total penerbitan bulan berjalan</div>
      </div>
    </div>
    <div class="ds-dash-card">
      <div class="ds-dash-card-icon ds-icon-orange"><i class="fa fa-users"></i></div>
      <div class="ds-dash-card-body">
        <div class="ds-dash-card-value"><?=isset($total_pasien) ? $total_pasien : '&mdash;';?></div>
        <div class="ds-dash-card-label">Total Pasien</div>
        <div class="ds-dash-card-desc">Seluruh pasien terdaftar</div>
      </div>
    </div>
    <div class="ds-dash-card">
      <div class="ds-dash-card-icon ds-icon-red"><i class="fa fa-user-md"></i></div>
      <div class="ds-dash-card-body">
        <div class="ds-dash-card-value"><?=isset($total_doctor) ? $total_doctor : '&mdash;';?></div>
        <div class="ds-dash-card-label">Dokter</div>
        <div class="ds-dash-card-desc">Tenaga medis tersedia</div>
      </div>
    </div>
  </div>

  <!-- Quick Links -->
  <div class="ds-dash-section-title">Akses Cepat</div>
  <div class="ds-dash-grid ds-dash-grid-3">
    <a href="<?=site_url('pengguna');?>" class="ds-dash-link-card">
      <i class="fa fa-users"></i>
      <span>Manajemen Pengguna</span>
    </a>
    <a href="<?=site_url('patient');?>" class="ds-dash-link-card">
      <i class="fa fa-user-injured"></i>
      <span>Pendaftaran Pasien</span>
    </a>
    <a href="<?=site_url('anamnesa');?>" class="ds-dash-link-card">
      <i class="fa fa-stethoscope"></i>
      <span>Anamnesa</span>
    </a>
    <a href="<?=site_url('dokter');?>" class="ds-dash-link-card">
      <i class="fa fa-user-md"></i>
      <span>Dokter</span>
    </a>
    
    <a href="<?=site_url('sks');?>" class="ds-dash-link-card">
      <i class="fa fa-file-signature"></i>
      <span>SKS</span>
    </a>
    <a href="<?=site_url('skbs');?>" class="ds-dash-link-card">
      <i class="fa fa-heartbeat"></i>
      <span>SKBS</span>
    </a>
    <a href="<?=site_url('skmb');?>" class="ds-dash-link-card">
      <i class="fa fa-ambulance"></i>
      <span>SKMB</span>
    </a>
  </div>

</div>
