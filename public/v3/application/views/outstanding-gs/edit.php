<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Outstanding General Supply</a></div>
        <div class="breadcrumb-item">Edit Outstanding General Supply</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-outstanding-gs/'.$o['id_po_gs'].'/'.$cabang); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Outstanding General Supply</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>No. PO</label>
                  <input type="text" name="no_po" class="form-control" value="<?= set_value('no_po', $o['no_po']); ?>" required="">
                  <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Tanggal Tagihan</label>
                  <input type="date" name="tanggal" class="form-control" value="<?= set_value('tanggal', $o['tanggal']); ?>" required="">
                  <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Nama Departemen</label>
                  <input type="text" name="nama_user" class="form-control" value="<?= set_value('nama_user', $o['nama_user']); ?>" required="">
                  <?= form_error('nama_user', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('outstanding-gs/'.$cabang);?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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