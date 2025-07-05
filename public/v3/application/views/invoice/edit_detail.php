<?php $this->load->view('template/header2');?>
<?php $this->load->view('template/sidebar2');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-detail-invoice/'.$fd['id_detail_gs'].'/'.$id_gs); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Detail Invoice</h4>
              </div>
              <div class="card-body">
              <?= $this->session->flashdata('pesan'); ?>  
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Barang</label>
                          <input type="hidden" name="no_po" id="no_po" value="<?= $fd['no_po']; ?>">
                          <input type="hidden" name="id_detail_po_gs" class="form-control" value="<?= $fd['id_detail_po_gs']; ?>" readonly="">
                          <input type="text" name="" class="form-control" value="<?= $fd['kode_barang'].' - '.$fd['nama_barang']; ?>" readonly="">
                          <?= form_error('id_detail_po_gs', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Sisa Barang Dalam Order</label>
                          <input type="number" name="jumlah_po" class="form-control" value="<?= $detail_po['jumlah_barang'] ? $detail_po['jumlah_barang'] : 0; ?>" readonly="">
                          <?= form_error('jumlah_po', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Jumlah Barang</label>
                          <input type="hidden" name="jumlah_barang_lama" class="form-control" value="<?= $fd['jumlah_barang']; ?>" required="">
                          <input type="number" name="jumlah_barang" class="form-control" value="<?= set_value('jumlah_barang', $fd['jumlah_barang']); ?>" required="" placeholder="Masukkan Jumlah Barang">
                          <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Harga Barang</label>
                          <input type="number" name="harga_barang" class="form-control" value="<?= set_value('harga_barang', $fd['harga_barang']); ?>" required="" placeholder="Masukkan Harga Barang">
                          <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>
                </div>
              </div>
              <div class="card-footer text-right">
                <a href="<?= base_url('detail-invoice/'.$id_gs);?>" class="btn btn-light">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer2');?>