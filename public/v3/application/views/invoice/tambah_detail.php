<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4><?= $title2; ?></h4>
            </div>
            <div class="modal-body">
              <form id="formUser" action="<?= base_url('tambah-detail-invoice/'.$id_gs)?>" method="POST">
                <input type="hidden" name="id_detail_gs" id="gsDetailId">
                <input type="hidden" name="id_gs" id="id_gs" value="<?= $id_gs; ?>">

                <div class="form-group">
                  <label>Barang</label>
                  <select class="form-control search-option" name="id_detail_po_gs" id="detail_po_gs" data-live-search="true" required>
                    <option disabled selected>-- Pilih Barang --</option>
                    <?php foreach ($barang as $b):
                      $get_id_po = $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $b['id_detail_po_gs']])->row_array();
                      $get_po = $this->db->get_where('po_gs', ['id_po_gs' => $get_id_po['id_po_gs']])->row_array();
                    ?>
                      <option value="<?= $b['id_detail_po_gs'] ?>" <?= set_value('id_detail_po_gs') == $b['id_detail_po_gs'] ? 'selected' : ''; ?>><?= $b['nama_barang'] . ' | No. PO ' . $get_po['no_po'] . ' | '.$b['status_indent']; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?= form_error('id_detail_po_gs', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group" id="form_group_no_po">
                  <label>Nomor Order</label>
                  <div id="no_po">

                  </div>
                  <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
                </div>
                <div class="form-group" id="form_group_jumlah_po">
                  <label>Sisa Barang Dalam Order</label>
                  <div id="jumlah_po">

                  </div>
                  <?= form_error('jumlah_po', '<span class="text-danger small">', '</span>'); ?>
                </div>

                <div class="form-group" id="jumlah_barang">
                  
                  <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>

                <!-- <div class="form-group" id="form_group_jumlah_barang">
                  <label>Jumlah Barang</label>
                  <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" value="<?= set_value('jumlah_barang'); ?>" required="" placeholder="Masukkan Jumlah Barang">
                  <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
                </div> -->

                <div id="div_table_tambah_invoice">

                </div>

                <div class="form-group" id="form-group-harga">
                  <label>Harga Barang</label>
                  <div id="harga">

                  </div>
                  <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
                </div>
            </div>
            <div class="modal-footer">
              <a href="<?= base_url('detail-invoice/'.$id_gs)?>" class="btn btn-secondary">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->load->view('template/footer2'); ?>

<script>

</script>