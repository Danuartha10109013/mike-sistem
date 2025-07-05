<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail Invoice General Supply</a></div>
        <div class="breadcrumb-item">Edit Detail Invoice General Supply</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-detail-invoice-gs/'.$fd['id_detail_gs'].'/'.$id_gs); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Detail Invoice General Supply</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Barang</label>
                  <input type="hidden" name="id_detail_po_gs" class="form-control" value="<?= $fd['id_detail_po_gs']; ?>" readonly="">
                  <input type="text" name="" class="form-control" value="<?= $fd['kode_barang'].' - '.$fd['nama_barang']; ?>" readonly="">
                  <?= form_error('id_detail_po_gs', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Jumlah Sisa Barang dalam PO</label>
                  <input type="number" name="jumlah_po" class="form-control" value="<?= $detail_po['jumlah_barang']; ?>" readonly="">
                  <?= form_error('jumlah_po', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Jumlah Pengiriman</label>
                  <input type="hidden" name="jumlah_barang_lama" class="form-control" value="<?= $fd['jumlah_barang']; ?>" required="">
                  <input type="number" name="jumlah_barang" class="form-control" value="<?= set_value('jumlah_barang', $fd['jumlah_barang']); ?>" required="">
                  <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Harga</label>
                  <input type="number" name="harga_barang" class="form-control" value="<?= set_value('harga_barang', $fd['harga_barang']); ?>" required="">
                  <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>No. PO</label>
                  <input type="number" name="no_po" class="form-control" value="<?= set_value('no_po', $fd['no_po']); ?>" required="">
                  <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('detail-invoice-gs/'.$id_gs);?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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