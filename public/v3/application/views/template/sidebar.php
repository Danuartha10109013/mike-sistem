<?php
$id_user = $this->session->userdata('id_user');
$get_user = $this->db->get_where('user', ['id_user' => $id_user])->row_array();
?>
<div id="app">
  <div class="main-wrapper">
    <div class="navbar-bg"></div>
    <nav class="navbar navbar-expand-lg main-navbar">
      <ul class="navbar-nav mr-auto">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      </ul>
      <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?= base_url('assets/img/profile/user.png'); ?>" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block"><?= $get_user['nama'] ?></div>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="<?= base_url('setting'); ?>" class="dropdown-item has-icon">
              <i class="fas fa-cog"></i> Edit Akun
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item has-icon text-danger" data-confirm="Logout|Anda yakin ingin keluar?" data-confirm-yes="document.location.href='<?= base_url('logout'); ?>';"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </li>
      </ul>
    </nav>

    <div class="main-sidebar">
      <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
          <a href="#">SINTESA</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
          <a href="#">S</a>
        </div>
        <?php
        $judul = explode(' ', $title);
        ?>
        <ul class="sidebar-menu">
          <li class="menu-header">Menu</li>
          <li class="<?= $title == 'Dashboard' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('dashboard'); ?>"><i class="fas fa-circle"></i> <span>Dashboard</span></a></li>

          <li class="menu-header">Data Master</li>

          <?php if (is_admin()) : ?>
            <li class="<?= $title == 'Kelola User' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('user'); ?>"><i class="fas fa-circle"></i> <span>Kelola User</span></a></li>
          <?php endif; ?>
          <?php if (is_admin()) : ?>
            <li class="<?= $title == 'Kelola Cabang' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('cabang'); ?>"><i class="fas fa-circle"></i> <span>Kelola Cabang</span></a></li>
          <?php endif; ?>

          <li class="<?= $title == 'Kelola Departemen' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('departemen'); ?>"><i class="fas fa-circle"></i> <span>Kelola Departemen</span></a></li>

          <li class="nav-item dropdown <?= $title == 'Data Barang' ? 'active' : ''; ?>">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Barang</span></a>
            <ul class="dropdown-menu">
              <li class=" <?= $title2 == 'Data Barang' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('barang'); ?>">Master Barang</a></li>
              <li class=" <?= $title2 == 'Barang Masuk' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('barang-masuk'); ?>">Barang Masuk</a></li>
              <li class=" <?= $title2 == 'Stok Barang' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('stok-barang'); ?>">Stok Barang</a></li>
              <li class=" <?= $title2 == 'Stok Opname' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('stok-opname'); ?>">Stok Opname</a></li>
              <li class=" <?= $title2 == 'Tracking Barang' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('tracking-barang'); ?>">Tracking Barang</a></li>
            </ul>
          </li>

          <?php if (is_admin() || is_fabrikasi()) : ?>
            <li class="<?= $title == 'Data Project Non PO' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('pbo'); ?>"><i class="fas fa-circle"></i> <span>Data Project Non PO</span></a></li>
          <?php endif; ?>

          <li class="nav-item dropdown <?= $title == 'Quotation' ? 'active' : ''; ?>">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Quotation</span></a>
            <ul class="dropdown-menu">
              <li class=" <?= $title2 == 'Quotation' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('no-quotation'); ?>">Quotation</a></li>
              <li class=" <?= $title2 == 'All Quotation' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('all-quotation'); ?>">All Quotation</a></li>
            </ul>
          </li>

          <?php foreach($this->session->userdata('cabang_user') as $item) :?>
            <li class="menu-header"><?= $item['nama_cabang']; ?></li>
            <?php if (is_fabrikasi()) : ?>
              <li class="<?= $title == 'Outstanding Fabrikasi '.$item['nama_cabang'] ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('outstanding-fabrikasi/'.$item['slug']); ?>"><i class="fas fa-circle"></i> <span>Outstanding Fabrikasi</span></a></li>
            <?php endif; ?>

            <?php if (is_supplier()) : ?>
              <li class="<?= $title == 'Outstanding General Supply '.$item['nama_cabang'] ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('outstanding-gs/'.$item['slug']); ?>"><i class="fas fa-circle"></i> <span>Outstanding General Supply</span></a></li>
            <?php endif; ?>
          <?php endforeach; ?>

          <?php if (is_fabrikasi() || is_supplier()) : ?>
            <li class="menu-header">Invoice & Surat Jalan</li>
          <?php endif; ?>

          <?php if (is_fabrikasi()) : ?>
            <?php foreach($this->session->userdata('cabang_user') as $item) : ?>
              <li class="nav-item dropdown <?= $title == 'Fabrikasi '.$item['nama_cabang'] ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Fabrikasi <?= $item['nama_cabang']; ?></span></a>
                <ul class="dropdown-menu">
                  <li class=" <?= $title2 == 'Invoice & Surat Jalan '.$item['nama_cabang'] ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('invoice-fabrikasi/'.$item['slug']); ?>">Invoice & Surat Jalan</a></li>
                  <li class=" <?= $title2 == 'BC '.$item['nama_cabang'] ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('bc-fabrikasi/'.$item['slug']); ?>">Kelola BC</a></li>
                  <li class=" <?= $title2 == 'BAP '.$item['nama_cabang'] ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('bap-fabrikasi/'.$item['slug']); ?>">Kelola BAP</a></li>
                </ul>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (is_supplier()) : ?>
            <!-- hide 2 -->
            <!-- <li class="nav-item dropdown <?= $title == 'Cs' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Cleaning Supply</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'Invoice & Surat Jalan' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('invoice-cs'); ?>">Invoice & Surat Jalan</a></li>
                <li class=" <?= $title2 == 'BC' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('bc-cs'); ?>">Kelola BC</a></li>
              </ul>
            </li> -->

            <li class="nav-item dropdown <?= $title == 'Gs' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>General Supply</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'Invoice & Surat Jalan' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('invoice-gs'); ?>">Invoice & Surat Jalan</a></li>
                <li class=" <?= $title2 == 'BC' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('bc-gs'); ?>">Kelola BC</a></li>
              </ul>
            </li>
          <?php endif; ?>

          <li class="menu-header">Riwayat & Laporan</li>

          <?php if (is_supplier() || is_fabrikasi()) : ?>
            <li class="nav-item dropdown <?= $title == 'Riwayat Outstanding Fabrikasi' || $title == 'Riwayat Outstanding Cleaning Supply' || $title == 'Riwayat Outstanding General Supply' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Riwayat Outstanding</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'a' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('outstanding-fabrikasi/riwayat'); ?>">Outstanding Fabrikasi</a></li>
                <!-- hide 3 -->
                <!-- <li class=" <?= $title2 == 'b' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('outstanding-cs/riwayat'); ?>">Outstanding Cleaning</a></li> -->
                <li class=" <?= $title2 == 'c' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('outstanding-gs/riwayat'); ?>">Outstanding General</a></li>
              </ul>
            </li>
          <?php endif; ?>

          <?php if (is_admin() || is_fabrikasi()) : ?>
            <li class="nav-item dropdown <?= $title == 'Laporan Invoice Fabrikasi' || $title == 'Laporan Invoice Cleaning Supply' || $title == 'Laporan All Invoice' || $title == 'Laporan Invoice General Supply' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Laporan Invoice</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'c' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-fabrikasi'); ?>">Invoice Fabrikasi</a></li>
                <!-- hide 4 -->
                <!-- <li class=" <?= $title2 == 'd' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-cs'); ?>">Invoice Cleaning Supply</a></li> -->
                <li class=" <?= $title2 == 'f' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-gs'); ?>">Invoice General Supply</a></li>
                <li class=" <?= $title2 == 'e' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-all'); ?>">All Invoice</a></li>
              </ul>
            </li>
          <?php endif; ?>

          <?php if (is_admin()) : ?>
            <li class="nav-item dropdown <?= $title == 'Omset Fabrikasi By Departemen' || $title == 'Omset Fabrikasi By User' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Laporan Omset Fabrikasi</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'By Departemen' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-omset-fabrikasi-by-departemen'); ?>">By Departemen</a></li>
                <li class=" <?= $title2 == 'By User' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-omset-fabrikasi-by-user'); ?>">By User</a></li>
              </ul>
            </li>
          <?php endif; ?>

          <?php if (is_admin()) : ?>
            <li class="nav-item dropdown <?= $title == 'Omset General Supply By Departemen' || $title == 'Omset General Supply By User' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Laporan Omset GS</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'By Departemen' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-omset-gs-by-departemen'); ?>">By Departemen</a></li>
                <li class=" <?= $title2 == 'By User' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-omset-gs-by-user'); ?>">By User</a></li>
              </ul>
            </li>
          <?php endif; ?>

          <?php if (is_admin()) : ?>
            <li class="nav-item dropdown <?= $title == 'Laporan Harga Jual-Beli' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Laporan Harga Jual Beli</span></a>
              <ul class="dropdown-menu">
                <!-- hide 5 -->
                <!-- <li class=" <?= $title2 == 'c' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-harga-jual-beli'); ?>">Cleaning Supply</a></li> -->
                <li class=" <?= $title2 == 'gs' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-harga-jual-beli-gs'); ?>">General Supply</a></li>
              </ul>
            </li>

            <li class="menu-header">Kepegawaian</li>

            <li class="nav-item <?= $title == 'Dashboard Kepegawaian' ? 'active' : ''; ?>">
              <a href="<?= base_url('kepegawaian-dashboard'); ?>" class="nav-link"><i class="fas fa-circle"></i><span>Informasi</span></a>
            </li>

            <li class="nav-item <?= $title == 'Data Kasbon' ? 'active' : ''; ?>">
              <a href="<?= base_url('kepegawaian-data-kasbon'); ?>" class="nav-link"><i class="fas fa-circle"></i><span>Data Kasbon</span></a>
            </li>

            <li class="nav-item dropdown <?= $title == 'Laporan Kasbon' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Laporan Kasbon</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'Pinjaman' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('kepegawaian-pinjaman-kasbon'); ?>">Pinjaman</a></li>
                <li class=" <?= $title2 == 'Potongan' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('kepegawaian-potongan-kasbon'); ?>">Potongan</a></li>
              </ul>
            </li>
          <?php endif; ?>

          <!-- <li class="<?= $title == 'Laporan Harga Jual-Beli' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-harga-jual-beli'); ?>"><i class="fas fa-circle"></i> <span>Laporan Harga Jual-Beli</span></a></li>  -->



          <!-- <?php if (is_fabrikasi()) : ?>
            <li class="<?= $title == 'Master Barang' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('barang'); ?>"><i class="fas fa-circle"></i> <span>Master Barang</span></a></li>

            <li class="<?= $title == 'Quotation' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('no-quotation'); ?>"><i class="fas fa-circle"></i> <span>Quotation</span></a></li>

            <li class="<?= $title == 'Outstanding Fabrikasi' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('outstanding-fabrikasi'); ?>"><i class="fas fa-circle"></i> <span>Outstanding Fabrikasi</span></a></li>

            <li class="menu-header">Invoice & Surat Jalan</li>
            <li class="nav-item dropdown <?= $title == 'Fabrikasi' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Fabrikasi</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'Invoice & Surat Jalan' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('invoice-fabrikasi'); ?>">Invoice & Surat Jalan</a></li>
                <li class=" <?= $title2 == 'BC' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('bc-fabrikasi'); ?>">Kelola BC</a></li>
                <li class=" <?= $title2 == 'BAP' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('bap-fabrikasi'); ?>">Kelola BAP</a></li>
              </ul>
            </li>
          <?php endif; ?> -->

          <!-- <?php if (is_supplier()) : ?>
            <li class="nav-item dropdown <?= $title == 'Data Barang' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Barang</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'Data Barang' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('barang'); ?>">Master Barang</a></li>
                <li class=" <?= $title2 == 'Barang Masuk' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('barang-masuk'); ?>">Barang Masuk</a></li>
                <li class=" <?= $title2 == 'Stok Barang' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('stok-barang'); ?>">Stok Barang</a></li>

              </ul>
            </li>

            <li class="<?= $title == 'Outstanding Cleaning Supply' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('outstanding-cs'); ?>"><i class="fas fa-circle"></i> <span>Outstanding Cleaning Supply</span></a></li>

            <li class="nav-item dropdown <?= $title == 'Cs' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Cleaning Supply</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'Invoice & Surat Jalan' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('invoice-cs'); ?>">Invoice & Surat Jalan</a></li>
                <li class=" <?= $title2 == 'BC' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('bc-cs'); ?>">Kelola BC</a></li>
              </ul>
            </li>

            <li class="nav-item dropdown <?= $title == 'Laporan Harga Jual-Beli' ? 'active' : ''; ?>">
              <a href="#" class="nav-link has-dropdown"><i class="fas fa-circle"></i><span>Laporan Harga Jual Beli</span></a>
              <ul class="dropdown-menu">
                <li class=" <?= $title2 == 'c' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-harga-jual-beli'); ?>">Cleaning Supply</a></li>
                <li class=" <?= $title2 == 'gs' ? 'active' : ''; ?>"><a class="nav-link" href="<?= base_url('laporan-harga-jual-beli-gs'); ?>">General Supply</a></li>
              </ul>
            </li>
          <?php endif; ?> -->
        </ul>


        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
          <button class="btn btn-danger btn-lg btn-block btn-icon-split" data-confirm="Logout|Anda yakin ingin keluar?" data-confirm-yes="document.location.href='<?= base_url('logout'); ?>';"><i class="fa fa-sign-out-alt"></i> Logout</button>
        </div>
      </aside>
    </div>