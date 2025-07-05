<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/sidebar'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?> | <?= $title2 ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#"><?= $title ?> | <?= $title2 ?></a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Silahkan lihat data laporan pinjaman kasbon dengan periode yang Anda pilih yaitu dari tanggal <?= date('d-m-Y', strtotime($dari_tanggal)); ?> sampai <?= date('d-m-Y', strtotime($sampai_tanggal)); ?></h4>
              <div class="card-header-action">
              </div>

            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nama Pegawai</th>
                      <th>Total Pinjam</th>
                      <th>Tanggal Pinjaman</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($pinjaman_kasbon as $item) : ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $item['name']; ?></td>
                        <td><?= 'Rp ' . number_format($item['total_pinjam'], 0, ',', '.'); ?></td>
                        <td><?= date('d-m-Y', strtotime($item['tanggal_pinjaman'])); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer'); ?>