<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/sidebar'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola User</a></div>
        <div class="breadcrumb-item">Tambah User</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('tambah-user'); ?>" method="post">
              <div class="card-header">
                <h4>Form Tambah User</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Nama</label>
                  <input type="text" name="nama" class="form-control" value="<?= set_value('nama'); ?>" required="">
                  <?= form_error('nama', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>No Handphone</label>
                  <input type="text" name="no_hp" class="form-control" value="<?= set_value('no_hp'); ?>" required="">
                  <?= form_error('no_hp', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" name="email" class="form-control" value="<?= set_value('email'); ?>" required="">
                  <?= form_error('email', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" value="<?= set_value('username'); ?>" required="">
                  <?= form_error('username', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" value="<?= set_value('password'); ?>" required="">
                  <?= form_error('password', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Konfirmasi Password</label>
                  <input type="password" name="password2" class="form-control" value="<?= set_value('password2'); ?>" required="">
                  <?= form_error('password2', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label class="form-label">Role</label>
                  <select name="role[]" class="form-control" id="select-user" data-live-search="true" multiple>
                    <option value="administrator">Direktur</option>
                    <option value="fabrikasi">Fabrikasi</option>
                    <option value="supplier">Supplier</option>
                  </select>
                  <?= form_error('role[]', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('user'); ?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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