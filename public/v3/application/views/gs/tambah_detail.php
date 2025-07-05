<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail Invoice General Supply</a></div>
        <div class="breadcrumb-item">Tambah Detail Invoice General Supply</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('tambah-detail-invoice-gs/'.$id_gs); ?>" method="post">
              <div class="card-header">
                <h4>Form Tambah Detail Invoice General Supply</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Pilih Barang</label>
                  <select class="form-control" name="id_detail_po_gs" id="detail_po_gs" data-live-search="true">
                    <option disabled selected>-- Pilih Barang --</option>
                    <?php foreach ($barang as $b):
                      $get_id_po = $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $b['id_detail_po_gs']])->row_array();
                      $get_po = $this->db->get_where('po_gs', ['id_po_gs' => $get_id_po['id_po_gs']])->row_array();
                    ?>
                    ?>
                    <option value="<?= $b['id_detail_po_gs']?>" <?= set_value('id_detail_po_gs') == $b['id_detail_po_gs'] ? 'selected' : '' ; ?> ><?= $b['kode_barang'].' - '.$b['nama_barang'].' - '.$get_po['no_po']; ?></option>
                    <?php endforeach;?>
                  </select>
                  <?= form_error('id_detail_po_gs', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Jumlah Sisa Barang dalam PO</label>
                  <div id="jumlah_po">
                    
                  </div>
                  
                  <?= form_error('jumlah_po', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Jumlah Pengiriman</label>
                  <input type="number" name="jumlah_barang" class="form-control" value="<?= set_value('jumlah_barang'); ?>" required="">
                  <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>Harga</label>
                  <div id="harga">
                    
                  </div>
                  <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group">
                  <label>No. PO</label>
                  <div id="no_po">
                    
                  </div>
                  
                  <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
                </div>
              </div>

              <div class="card-footer text-right">
                <a href="<?= base_url('detail-invoice-gs/'.$id_gs);?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a>
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