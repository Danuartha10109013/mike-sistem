<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail BC General Supply</a></div>
        <div class="breadcrumb-item">Edit Detail BC General Supply</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-detail-bc-gs/'.$id_detail_bc_gs.'/'.$id_bc_gs); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Detail BC General Supply</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Pilih Invoice</label>
                  <select class="form-control" name="id_gs" id="select-pegawai" data-live-search="true">
                    <option disabled selected>-- Pilih Invoice --</option>
                    <?php foreach ($invoice as $b):?>
                    <option value="<?= $b['id_gs']?>" <?= set_value('id_gs', $bc['id_gs']) == $b['id_gs'] ? 'selected' : '' ; ?> ><?= $b['no_invoice']; ?></option>
                    <?php endforeach;?>
                  </select>
                  <?= form_error('id_gs', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('detail-bc-gs/'.$id_bc_gs);?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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