<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/sidebar'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Master Departemen</a></div>
        <div class="breadcrumb-item"><a href="#">Kelola Departemen</a></div>
        <div class="breadcrumb-item">Edit Departemen</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-departemen/' . $departemen['id_departemen']); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Departemen</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Nama Departemen</label>
                  <input type="text" name="nama_departemen" class="form-control" value="<?= set_value('nama_departemen', $departemen['nama_departemen']); ?>" required="">
                  <?= form_error('nama_departemen', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('departemen'); ?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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
<?php $this->load->view('template/footer'); ?>