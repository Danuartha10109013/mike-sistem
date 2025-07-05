<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Laporan Omset Fabrikasi</a></div>
        <div class="breadcrumb-item">By Departemen</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('laporan-omset-fabrikasi-by-departemen'); ?>" method="post">
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
                <div class="form-group">
                  <label>Pilih Departemen</label>
                  <select class="form-control barang" name="departemen" id="select-bidang" data-live-search="true" required>
                    <option disabled selected>-- Pilih Departemen --</option>
                    <?php foreach ($departemen as $item):?>
                    <option value="<?= $item['id_departemen']?>" <?= set_value('departemen') == $item['id_departemen'] ? 'selected' : '' ; ?> ><?= $item['nama_departemen']; ?></option>
                    <?php endforeach;?>
                  </select>
                  <?= form_error('departemen', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <button type="submit" name="filter" value="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                <button type="submit" name="cetak" value="cetak" formtarget="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer');?>