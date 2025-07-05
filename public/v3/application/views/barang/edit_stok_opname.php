<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Data Barang</a></div>
        <div class="breadcrumb-item">Edit Stok Opname</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-stok-opname/'.$so['id_stok_opname']); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Stok Opname</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="hidden" name="id_stok_barang_old" value="<?= $so['id_stok_barang'] ?>">
                  <input type="hidden" name="jumlah_old" value="<?= $so['jumlah'] ?>">
                  <input type="date" name="tanggal" class="form-control" value="<?= set_value('tanggal', $so['tanggal']); ?>" required="">
                  <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Pilih Barang</label>
                  <select class="form-control barang" name="id_stok_barang" id="detail_po_fabrikasi" data-live-search="true">
                    <option disabled selected>-- Pilih Barang --</option>
                    <?php foreach ($barang as $b): ?>

                    <option value="<?= $b['id_stok_barang']?>" <?= set_value('id_stok_barang', $so['id_stok_barang']) == $b['id_stok_barang'] ? 'selected' : '' ; ?> >
                      <?= $b['kode_barang'].' - '.$b['nama_barang']; ?> || Stok: <?= $b['stok'] ?> || Harga Beli: Rp <?= number_format($b['harga_beli'], 0, '.', '.') ?>
                    </option>
                    <?php endforeach;?>
                  </select>
                  <?= form_error('id_stok_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Jumlah</label>
                  <input type="number" name="jumlah" class="form-control" value="<?= set_value('jumlah', $so['jumlah']); ?>" required="">
                  <?= form_error('jumlah', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <input type="text" name="keterangan" class="form-control" value="<?= set_value('keterangan', $so['keterangan']); ?>" required="">
                  <?= form_error('keterangan', '<span class="text-danger small">', '</span>'); ?>
                </div>
                
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('stok-opname');?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
                <button type="reset" class="btn btn-danger"><i class="fa fa-sync"></i> Reset</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer');?>