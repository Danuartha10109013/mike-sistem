<?php
$id_user = $this->session->userdata('id_user');
$get_user = $this->db->get_where('user', ['id_user' => $id_user])->row_array();
?>
<style>
  /* .navbar-secondary > .sidebar-wrapper > .sidebar-brand > a { */
  .main-sidebar .sidebar-brand a {
    display: flex !important;
    -ms-flex-wrap: wrap !important;
    flex-wrap: wrap !important;
    margin-right: -15px !important;
    margin-left: -15px !important;
    justify-content: center !important;
  }

  .ismobile {
    display: none !important;
  }

  @media (max-width: 700px) {
    .navbar-secondary .container ul {
      overflow-x: auto !important;
      overflow-y: hidden !important;
    }

    .ismobile {
      display: block !important;
    }
  }
</style>

<div id="app">
  <div class="main-wrapper container">
    <nav class="navbar navbar-expand-lg main-navbar bg-primary">
      <a href="#" class="navbar-brand sidebar-gone-hide ">
        <img src="<?= base_url() ?>assets/img/logo-white.png" class="logo d-none d-md-none d-sm-none d-lg-block d-xl-block w-50" alt="Merlin Store">
        <img src="<?= base_url() ?>assets/img/logoside.png" class="logo  d-block d-md-block d-sm-block d-lg-none d-xl-none pt-2 w-75" alt="Merlin Store">
      </a>
      <a href="#" class="nav-link sidebar-gone-show align-content-center" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
      <ul class="navbar-nav navbar-right ml-auto">
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?= base_url('assets/img/profile/user.png'); ?>" class="rounded mr-1">
            <div class="d-sm-none d-lg-inline-block"></div>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-title"><?= $get_user['nama']; ?></div>
            <hr class="m-0 mb-1">
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item has-icon text-danger" data-confirm-text-yes="Logout" data-confirm="Apakah Anda yakin akan keluar?" data-confirm-yes="document.location.href='<?= base_url('logout'); ?>';">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>

    <nav class="navbar navbar-secondary navbar-expand-lg">
      <div class="container mb-5 mb-md-0">
        <ul class="navbar-nav">
          <li class="nav-item d-none d-sm-none d-lg-block d-xl-block <?= $title == 'Dashboard' ? 'active' : ''; ?>">
            <a href="<?= base_url('dashboard'); ?>" class="nav-link"><i class="far bi bi-speedometer2"></i><span>Dashboard</span></a>
          </li>
          <li class="nav-item dropdown d-none d-sm-none d-lg-block d-xl-block
             <?= $title == 'Data Riwayat' || $title == 'Data Barang' || $title == 'Data Paket' ? 'active' : ''; ?>">
            <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="far bi bi-inbox"></i><span>Inventory</span></a>
            <ul class="dropdown-menu">
              <?php if (in_array("Admin", $this->session->userdata('role'))): ?>
                <li class="nav-item"><a href="<?= base_url('barang'); ?>" class="nav-link">Master barang</a></li>
                <li class="nav-item "><a href="<?= base_url('barang-masuk'); ?>" class="nav-link">Barang Masuk</a></li>
                <li class="nav-item dropdown">
                  <a href="#" class="nav-link has-dropdown">Stock</a>
                  <ul class="dropdown-menu">
                    <li class="nav-item"><a href="<?= base_url('stok-barang') ?>" class="nav-link">Stock</a></li>
                    <li class="nav-item"><a href="<?= base_url('stok-opname') ?>" class="nav-link">Stock Opname</a></li>
                    <!-- <li class="nav-item"><a href="<?= base_url('inden-keluar'); ?>" class="nav-link">Indent Keluar</a></li> -->
                  </ul>
                </li>
                <li class="nav-item"><a href="<?= base_url('tracking-barang'); ?>" class="nav-link">Tracking Barang</a></li>

                <li class="nav-item"><a href="<?= base_url('package'); ?>" class="nav-link">Data Paket</a></li>

              <?php endif; ?>
            </ul>
          </li>
          <li class="nav-item dropdown d-none d-sm-none d-lg-block d-xl-block
             <?= $title == 'Invoice & Surat Jalan' || $title == 'Order' ? 'active' : ''; ?>">
            <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="far bi bi-cart-check-fill"></i><span>Sales</span></a>
            <ul class="dropdown-menu">
              <?php if (in_array("Admin", $this->session->userdata('role'))): ?>
                <li class="nav-item"><a href="<?= base_url('data-order'); ?>" class="nav-link">Order</a></li>
                <li class="nav-item "><a href="<?= base_url('invoice'); ?>" class="nav-link">Invoice</a></li>
                <li class="nav-item "><a href="<?= base_url('return'); ?>" class="nav-link">Return</a></li>

              <?php endif; ?>
            </ul>
          </li>

          <?php if (in_array("Finance", $this->session->userdata('role'))): ?>
            <li class="nav-item dropdown d-none d-sm-none d-lg-block d-xl-block
             <?= $title == 'Pembelian' || $title == 'Penjualan' ? 'active' : ''; ?>">
              <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="far bi bi-newspaper"></i><span>Finance</span></a>
              <ul class="dropdown-menu">

                <li class="nav-item"><a href="<?= base_url('pembelian'); ?>" class="nav-link">Pembelian</a></li>
                <li class="nav-item "><a href="<?= base_url('penjualan'); ?>" class="nav-link">Penjualan</a></li>

              </ul>
            </li>
          <?php endif; ?>








          <!-- mobile -->
          <?php if (in_array("Admin", $this->session->userdata('role'))): ?>
            <li class="nav-item ismobile d-lg-none d-xl-none <?= $title == 'Dashboard' ? 'active' : ''; ?>">
              <a href="<?= base_url('dashboard'); ?>" class="nav-link"><i class="far bi bi-speedometer2"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item  ismobile d-lg-none d-xl-none dropdown bg-light"> <b class="ml-3 "> Inventory</b></li>

            <li class="nav-item ismobile d-lg-none d-xl-none "><a href="<?= base_url('barang'); ?>" class="nav-link"><i class="far bi bi-box"></i>Master Barang</a></li>
            <li class="nav-item ismobile d-lg-none d-xl-none "><a href="<?= base_url('barang-masuk'); ?>" class="nav-link"><i class="far bi bi-box"></i>Barang Masuk</a></li>
            <!-- <li class="nav-item  ismobile d-lg-none d-xl-none dropdown">
              <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="far bi bi-box"></i><span>Stock</span></a>
              <ul class="dropdown-menu">
                <li class="nav-item"><a href="<?= base_url('stok-barang') ?>" class="nav-link">Stock</a></li>
                <li class="nav-item"><a href="<?= base_url('stok-opname') ?>" class="nav-link">Stock Opname</a></li>
                <li class="nav-item"><a href="<?= base_url('inden-keluar'); ?>" class="nav-link">Indent Keluar</a></li>
              </ul>
            </li> -->
            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('stok-barang'); ?>" class="nav-link"><i class="far bi-search"></i><span>Stock</span></a></li>
            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('stok-opname'); ?>" class="nav-link"><i class="far bi-search"></i><span>Stock Opname</span></a></li>
            <!-- <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('inden-keluar'); ?>" class="nav-link"><i class="far bi-search"></i><span>Indent Keluar</span></a></li> -->

            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('tracking-barang'); ?>" class="nav-link"><i class="far bi-search"></i><span>Tracking Barang</span></a></li>

            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('package'); ?>" class="nav-link"><i class="far bi-card-checklist"></i><span>Data Paket</span></a></li>
            <!-- //mobile sales -->
            <li class="nav-item  ismobile d-lg-none d-xl-none dropdown bg-light"> <b class="ml-3 "> Sales</b></li>

            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('data-order'); ?>" class="nav-link"><i class="far bi bi-cart-check-fill"></i><span>Order</span></a></li>
          <?php endif; ?>
          <?php if (in_array("Finance", $this->session->userdata('role'))): ?>
            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('invoice'); ?>" class="nav-link"><i class="far bi bi-newspaper"></i><span>Invoice</span></a></li>
            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('return'); ?>" class="nav-link"><i class="far bi bi-arrow-counterclockwise"></i><span>Return</span></a></li>
            <!-- //mobile finance -->
            <li class="nav-item  ismobile d-lg-none d-xl-none dropdown bg-light"> <b class="ml-3 "> Finance</b></li>

            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('pembelian'); ?>" class="nav-link"><i class="far bi bi-cart-check-fill"></i><span>Pembelian</span></a></li>
            <li class="nav-item ismobile d-lg-none d-xl-none"><a href="<?= base_url('penjualan'); ?>" class="nav-link"><i class="far bi bi-cart-check-fill"></i><span>Penjualan</span></a></li>
          <?php endif; ?>

          <?php if (in_array("Super_Admin", $this->session->userdata('role'))): ?>
            <li class="nav-item dropdown <?= $title == 'Data User' || $title == 'Data Distributor' ? 'active' : ''; ?>">
              <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="far bi bi-database-fill-gear"></i><span>Data Master</span></a>
              <ul class="dropdown-menu">
                <li class="nav-item"><a href="<?= base_url('kelola-user'); ?>" class="nav-link">Kelola User All</a></li>
                <li class="nav-item"><a href="<?= base_url('kelola-user-agen'); ?>" class="nav-link">Kelola Distributor</a></li>
                <li class="nav-item"><a href="<?= base_url('user/activity_logs'); ?>" class="nav-link">Log Aktivitas</a></li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
        <h4 class="text-dark d-none d-lg-block d-xl-block"><?= $title; ?></h4>
      </div>
    </nav>