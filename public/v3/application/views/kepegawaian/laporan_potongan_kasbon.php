<?php $this->load->view('template/header'); ?>
<?php $this->load->view('template/sidebar'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?> | <?= $title2 ?> Kasbon</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Data Potongan Kasbon</a></div>
        <div class="breadcrumb-item">Laporan Kasbon</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('kepegawaian-potongan-kasbon'); ?>" method="post">
              <div class="card-header">
                <h4>Set Tanggal</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Dari Tanggal</label>
                  <input type="date" name="dari_tanggal" class="form-control" value="<?= set_value('dari_tanggal'); ?>" required="">
                  <?= form_error('dari_tanggal', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Sampai Tanggal</label>
                  <input type="date" name="sampai_tanggal" class="form-control" value="<?= set_value('sampai_tanggal'); ?>" required="">
                  <?= form_error('sampai_tanggal', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <button type="submit" name="filter" value="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer'); ?>