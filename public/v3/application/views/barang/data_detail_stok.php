<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Barang</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Stok Barang</h4>
              <div class="card-header-action">
                <!-- <a href="<?= base_url('tambah-barang');?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a> -->
              </div>
              
            </div>
            
            <div class="card-body">   
              <div class="row">
                  <div class="col-md-2"><h6>Nama Barang</h6></div>
                  <div class="col-md-10">: <?= $b['nama_barang']?></div>
                  <div class="col-md-2"><h6>Kode Barang</h6></div>
                  <div class="col-md-10">: <?= $b['kode_barang']?></div>
                  <div class="col-md-2"><h6>Stok Keseluruhan</h6></div>
                  <div class="col-md-10">: <?= $b['stok']?></div>
              </div>                 
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Stok Barang</th>
                      <th>Harga Beli</th><!-- 
                      <th class="text-center" style="width: 160px;">Aksi</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($barang as $u):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $u['stok'];?></td>
                      <td><?= 'Rp'. number_format($u['harga_beli'], 2,',','.');?></td>
                      <!-- <td class="text-center">
                        <a href="<?= base_url('detail-stok-barang/'.$u['id_barang']);?>" class="btn btn-light"><i class="fa fa-list"></i> Details</a>
                      </td> -->
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-md-2"><a href="<?= base_url('stok-barang');?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer');?>