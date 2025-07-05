<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail Outstanding General Supply</a></div>
        <div class="breadcrumb-item">Edit Detail Outstanding General Supply</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('edit-detail-outstanding-gs/'.$o_detail['id_detail_po_gs'].'/'.$id_po_gs.'/'.$cabang); ?>" method="post">
              <div class="card-header">
                <h4>Form Edit Detail Outstanding General Supply</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Pilih Barang</label>
                  <select class="form-control" name="id_barang" id="select-pegawai" data-live-search="true">
                    <option disabled selected>-- Pilih Barang --</option>
                    <?php foreach ($barang as $b):?>
                    <option value="<?= $b['id_barang']?>" <?= set_value('id_barang', $o_detail['id_barang']) == $b['id_barang'] ? 'selected' : '' ; ?> ><?= $b['kode_barang'].' - '.$b['nama_barang']; ?></option>
                    <?php endforeach;?>
                  </select>
                  <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Jumlah</label>
                  <input type="number" name="jumlah_barang" class="form-control" value="<?= set_value('jumlah_barang', $o_detail['jumlah_barang']); ?>" required="">
                  <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Harga</label>
                  <input type="number" name="harga_barang" class="form-control" value="<?= set_value('harga_barang', $o_detail['harga_barang']); ?>" required="">
                  <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Pilih Departemen</label>
                  <select class="form-control" name="departemen" id="select-bidang" data-live-search="true">
                    <option disabled selected>-- Pilih Departemen --</option>
                    <?php foreach ($departemen as $item):?>
                    <option value="<?= $item['id_departemen']?>" <?= set_value('departemen', $o_detail['departemen']) == $item['id_departemen'] ? 'selected' : '' ; ?> ><?= $item['nama_departemen']; ?></option>
                    <?php endforeach;?>
                  </select>
                  <?= form_error('departemen', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('detail-outstanding-gs/'.$id_po_gs.'/'.$cabang);?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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