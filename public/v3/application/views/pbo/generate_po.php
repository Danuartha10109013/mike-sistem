<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Data Project Non PO</a></div>
        <div class="breadcrumb-item">Generate PO</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('generate-po/'.$p['id_pbo']); ?>" method="post">
              <div class="card-header">
                <h4>Form Generate PO</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>No. PO</label>
                  <input type="text" name="no_po" class="form-control" value="<?= set_value('no_po'); ?>" required="">
                  <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Kode Barang</label>
                  <input type="text" name="kode_barang" class="form-control" id="kode_barang" value="<?= set_value('kode_barang', $p['kode_barang']); ?>" required="">
                  <?= form_error('deskripsi', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Deskripsi</label>
                  <input type="text" name="deskripsi" class="form-control" id="deskripsi" value="<?= set_value('deskripsi', $p['deskripsi']); ?>" required="">
                  <?= form_error('deskripsi', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>UOM</label>
                  <input type="text" name="uom" class="form-control" id="uom" value="<?= set_value('uom', $p['uom']); ?>" required="">
                  <?= form_error('uom', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>QTY</label>
                  <input type="text" name="qty" class="form-control" value="<?= set_value('qty', $p['qty']); ?>" required="">
                  <?= form_error('qty', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Harga Unit</label>
                  <input type="text" name="harga_unit" class="form-control" value="<?= set_value('harga_unit'); ?>" >
                  <span class="text-danger small">*) Kosongkan jika tidak perlu</span>
                  <?= form_error('harga_unit', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Harga Instalasi</label>
                  <input type="text" name="harga_instalasi" class="form-control" value="<?= set_value('harga_instalasi'); ?>" >
                  <span class="text-danger small">*) Kosongkan jika tidak perlu</span>
                  <?= form_error('harga_instalasi', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Harga Barang</label>
                  <input type="text" name="amount" class="form-control" id="value-amount" value="<?= set_value('amount', $p['amount']); ?>" required="">
                  <?= form_error('amount', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Department</label>
                  <input type="text" name="department" class="form-control" value="<?= set_value('department', $p['department']); ?>" required="">
                  <?= form_error('department', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('pbo');?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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