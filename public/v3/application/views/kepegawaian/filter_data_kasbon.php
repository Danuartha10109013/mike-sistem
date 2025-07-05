<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/sidebar'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#"><?= $title ?></a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Silahkan lihat data kasbon dengan periode yang Anda pilih yaitu dari tanggal <?= date('d-m-Y', strtotime($dari_tanggal)); ?> sampai <?= date('d-m-Y', strtotime($sampai_tanggal)); ?></h4>
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
                      <th>Total Kasbon</th>
                      <th>Jenis Potongan</th>
                      <th>Total Cicilan</th>
                      <th>Potongan Percicilan</th>
                      <th>Status Kasbon</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($kasbon as $item) : ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $item['name']; ?></td>
                        <td><?= 'Rp ' . number_format($item['total_kasbon'], 0, ',', '.'); ?></td>
                        <td><?= $item['jenis_potongan']; ?></td>
                        <td><?= $item['cicilan']; ?>x</td>
                        <td><?= 'Rp ' . number_format($item['potongan_percicilan'], 0, ',', '.'); ?></td>
                        <td><?= $item['status_kasbon'] == 1 ? 'Lunas' : 'Belum Lunas'; ?></td>
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