<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Invoice General Supply</a></div>
        <div class="breadcrumb-item">Edit Invoice General Supply</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-invoice-gs/'.$f['id_gs']); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Invoice General Supply</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>No. Invoice</label>
                  <input type="text" name="no_invoice" class="form-control" value="<?= set_value('no_invoice', $f['no_invoice']); ?>" required="">
                  <?= form_error('no_invoice', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" value="<?= set_value('tanggal', $f['tanggal']); ?>" required="">
                  <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('invoice-gs');?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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