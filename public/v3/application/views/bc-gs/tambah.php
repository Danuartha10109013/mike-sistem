<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola BC General Supply</a></div>
        <div class="breadcrumb-item">Tambah BC General Supply</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('tambah-bc-gs/'); ?>" method="post">
              <div class="card-header">
                <h4>Form Tambah BC General Supply</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Nama Vendor</label>
                  <input type="text" name="nama_vendor" class="form-control" value="<?= set_value('nama_vendor', 'PT. SINTESA NIAGA KARYA'); ?>" required="">
                  <?= form_error('nama_vendor', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" value="<?= set_value('tanggal'); ?>" required="">
                  <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>NPWP</label>
                  <input type="text" name="npwp" class="form-control" value="<?= set_value('npwp', '40.418.919.3-439.00'); ?>" required="">
                  <?= form_error('npwp', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('bc-gs');?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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