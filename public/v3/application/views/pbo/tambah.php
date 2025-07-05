<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Data Project Non PO</a></div>
        <div class="breadcrumb-item">Tambah Project Non PO</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('tambah-pbo/'); ?>" method="post">
              <div class="card-header">
                <h4>Form Tambah Project Non PO</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Pilih Barang</label>
                  <select class="form-control barang-pbo" name="id_barang" id="barang-pbo" data-live-search="true">
                    <option disabled selected>-- Pilih Barang --</option>
                    <option value="0" <?= set_value('id_barang') == '0' ? 'selected' : '' ; ?> >NEW ITEM</option>
                    <?php foreach ($barang as $b):?>
                    <option value="<?= $b['id_barang']?>" <?= set_value('id_barang') == $b['id_barang'] ? 'selected' : '' ; ?> ><?= $b['kode_barang'].' - '.$b['nama_barang']; ?></option>
                    <?php endforeach;?>
                  </select>
                  <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Deskripsi</label>
                  <input type="text" name="deskripsi" class="form-control" id="deskripsi" value="<?= set_value('deskripsi'); ?>" required="">
                  <?= form_error('deskripsi', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>UOM</label>
                  <input type="text" name="uom" class="form-control" id="uom" value="<?= set_value('uom'); ?>" required="">
                  <?= form_error('uom', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>QTY</label>
                  <input type="text" name="qty" class="form-control" value="<?= set_value('qty'); ?>" required="">
                  <?= form_error('qty', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>PO</label>
                  <input type="text" name="po" class="form-control" value="<?= set_value('po'); ?>" required="">
                  <?= form_error('po', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Amount</label>
                  <div id="amount">
                    
                  </div>
                  
                  <?= form_error('amount', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Department</label>
                  <input type="text" name="department" class="form-control" value="<?= set_value('department'); ?>" required="">
                  <?= form_error('department', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <input type="text" name="status" class="form-control" value="<?= set_value('status'); ?>" required="">
                  <?= form_error('status', '<span class="text-danger small">', '</span>'); ?>
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