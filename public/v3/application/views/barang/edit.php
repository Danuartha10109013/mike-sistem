<?php $this->load->view('template/header');?>

<?php $this->load->view('template/sidebar');?>

<!-- Main Content -->

<div class="main-content">

  <section class="section">

    <div class="section-header">

      <h1><?= $title?></h1>

      <div class="section-header-breadcrumb">

        <div class="breadcrumb-item active"><a href="#">Data Barang</a></div>

        <div class="breadcrumb-item">Edit Barang</div>

      </div>

    </div>



    <div class="section-body">

      <div class="row">

        <div class="col-12">

          <div class="card">

            <form action="<?= base_url('edit-barang/'.$b['id_barang']); ?>" method="post">

              <div class="card-header">

                <h4>Form Edit Barang</h4>

              </div>

              <div class="card-body">

                <div class="form-group">

                  <label>Nama Barang</label>

                  <input type="text" name="nama_barang" class="form-control" value="<?= set_value('nama_barang', $b['nama_barang']); ?>" required="">

                  <?= form_error('nama_barang', '<span class="text-danger small">', '</span>'); ?>

                </div>

                <div class="form-group">

                  <label>Kode Barang</label>

                  <input type="text" name="kode_barang" class="form-control" value="<?= set_value('kode_barang', $b['kode_barang']); ?>" required="">

                  <?= form_error('kode_barang', '<span class="text-danger small">', '</span>'); ?>

                </div>

                <div class="form-group">

                  <label>Satuan</label>

                  <input type="text" name="satuan_barang" class="form-control" value="<?= set_value('satuan_barang', $b['satuan_barang']); ?>" required="">

                  <?= form_error('satuan_barang', '<span class="text-danger small">', '</span>'); ?>

                </div>

                <div class="form-group">

                  <label>Harga Barang</label>

                  <input type="text" name="harga_barang" class="form-control" value="<?= set_value('harga_barang', $b['harga_barang']); ?>" required="">

                  <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>

                </div>

                <div class="form-group">

                  <label>Poin Barang</label>

                  <input type="number" name="poin_barang" class="form-control" value="<?= set_value('poin_barang', $b['poin_barang']); ?>" required="">

                  <?= form_error('poin_barang', '<span class="text-danger small">', '</span>'); ?>

                </div>

                <div class="form-group">

                  <label>Jenis Barang</label>

                  <select class="form-control" name="jenis_barang" >

                    <option disabled selected>-- Jenis Barang --</option>

                   

                    <option value="Fabrikasi" <?= set_value('jenis_barang', $b['jenis_barang']) == 'Fabrikasi' ? 'selected' : '' ; ?> >Fabrikasi</option>

                    <option value="Cleaning Supply" <?= set_value('jenis_barang', $b['jenis_barang']) == 'Cleaning Supply' ? 'selected' : '' ; ?> >Cleaning Supply</option>

                    <option value="General Supply" <?= set_value('jenis_barang', $b['jenis_barang']) == 'General Supply' ? 'selected' : '' ; ?> >General Supply</option>

                  </select>

                  <?= form_error('jenis_barang', '<span class="text-danger small">', '</span>'); ?>

                </div>

              </div>



              <div class="card-footer text-right">

                <a href="<?= base_url('barang');?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>

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