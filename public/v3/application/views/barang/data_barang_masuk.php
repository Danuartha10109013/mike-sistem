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
              <h4>Data Barang Masuk</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-barang-masuk');?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
              
            </div>
            
            <div class="card-body">
              <form action="<?= base_url('barang-masuk'); ?>" method="post">
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
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nama Barang</th>
                      <th style="width: 200px;">Tgl. Masuk</th>
                      <th>Satuan</th>
                      <th>Jumlah Barang</th>
                      <th>Harga Beli</th>
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
                      <td><?= $u['tanggal_masuk'];?></td>
                      <td><?= $u['satuan_barang'];?></td>
                      <td><?= $u['jumlah'];?></td>
                      <td><?= 'Rp'. number_format($u['harga_beli'], 2,',','.');?></td>
                      <td class="text-center">
                        <a href="<?= base_url('edit-barang-masuk/'.$u['id_barang_masuk']);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-barang-masuk/'.$u['id_barang_masuk']); ?>';"><i class="fa fa-trash"></i> Delete</button>
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