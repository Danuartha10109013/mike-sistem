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
                <div class="col-md-3"><h6>Total Asset Barang</h6></div>
                <div class="col-md-9"><h6>: <?= 'Rp'. number_format($nab['nilai_asset'], 2,',','.')?></h6></div>
                <div class="col-md-3"><h6>Ekspor to Excel</h6></div>
                <div class="col-md-9 mb-2">: 
                  <a href="<?= base_url('ekspor-stok-barang');?>" class="btn btn-info mr-2" ><i class="fa fa-upload"></i> Eksport</a>
                </div>
                <div class="col-md-12">
                  <form action="<?= base_url('stok-barang'); ?>" method="post">
                    <div class="row">
                      <div class="col-md-6 form-group">
                        <label>Jenis Barang</label>
                        <select name="jenis_barang" class="form-control" required>
                          <option selected disabled>-- Pilih Jenis Barang --</option>
                          <option value="All" <?= $jenis_barang == 'All' ? 'selected' : '' ?>>All</option>
                          <option value="Cleaning Supply" <?= $jenis_barang == 'Cleaning Supply' ? 'selected' : '' ?>>Cleaning Supply</option>
                          <option value="General Supply" <?= $jenis_barang == 'General Supply' ? 'selected' : '' ?>>General Supply</option>
                        </select>
                      </div>
                      <div class="col-md-6 form-group">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                      </div>
                    </div>
                  </form>     
                </div>
              </div>                  
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nama Barang</th>
                      <th>Kode Barang</th>
                      <th>Satuan</th>
                      <th>Stok Barang</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($barang as $u):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $u['nama_barang'];?></td>
                      <td><?= $u['kode_barang'];?></td>
                      <td><?= $u['satuan_barang'];?></td>
                      <td><?= $u['stok'];?></td>
                      <td class="text-center">
                        <a href="<?= base_url('detail-stok-barang/'.$u['id_barang']);?>" class="btn btn-light"><i class="fa fa-list"></i> Details</a>
                      </td>
                    </tr>
                    <?php endforeach;?>
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
<?php $this->load->view('template/footer');?>