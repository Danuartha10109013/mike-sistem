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
              <div class="card-header-action">
                <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
              </div>
            </div>

            <div class="card-body">
              <!-- <div class="row">
                  <div class="col-md-4"><h6>Total Nilai Pesanan</h6></div>
                  <div class="col-md-8"><h6>: Rp <?= number_format($total_outstanding, 2, ',', '.') ?></h6></div>
                  <div class="col-md-4"><h6>Ekspor to Excel</h6></div>
                  <div class="col-md-8 mb-2">: 
                    <a href="<?= base_url('ekspor-outstanding-gs/' . $cabang); ?>" class="btn btn-info mr-2"><i class="fa fa-upload"></i> Eksport</a>
                  </div>
              </div>                -->
              <div class="table-responsive mt-5">
                <?= $this->session->flashdata('pesan'); ?>
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nomor Order</th>
                      <th>Tanggal</th>
                      <th>Sisa Tagihan</th>
                      <th>Konsumen</th>
                      <th>Kontak</th>
                      <!-- <th>Status Order Di Invoice</th> -->
                      <th class="text-center" style="width: 260px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($outstanding as $i):
                      $cek_data = $this->db->get_where('riwayat_po_gs', ['no_po' => $i['no_po']])->num_rows();
                      $cek_data_po = $this->db->get_where('detail_po_gs', ['id_po_gs' => $i['id_po_gs']])->num_rows();
                      $sisa_outstanding = $this->db->select_sum('total_harga_barang')->from('detail_po_gs')->where('id_po_gs', $i['id_po_gs'])->get()->row_array();
                      if ($cek_data > 0 && $cek_data_po == 0) {
                        continue;
                      }
                    ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $i['no_po']; ?></td>
                        <td><?= $i['tanggal']; ?></td>
                        <td><?= number_format($sisa_outstanding['total_harga_barang'], 2, ',', '.') ?></td>
                        <td><?= $i['nama_user']; ?></td>
                        <td><?= $i['kontak_customer']; ?></td>
                        <!-- <td><?= $i['status_order_invoice']; ?></td> -->
                        <td class="text-center">
                          <!-- <button type="button" class="btn btn-info btn-status-order-invoice" data-id="<?= $i['id_po_gs']; ?>"><i class="fa fa-check"></i></button> -->
                          <a href="<?= base_url('detail-order/' . $i['id_po_gs']); ?>" class="btn btn-light"><i class="fa fa-list"></i></a>
                          <!-- <?php if ($i['status_order_invoice'] === 'Belum Invoice') { ?> -->
                          <button type="button" class="btn btn-success btn-edit" data-id="<?= $i['id_po_gs']; ?>"><i class="fa fa-edit"></i></button>
                          <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-order/' . $i['id_po_gs']); ?>';"><i class="fa fa-trash"></i></button>
                          <!-- <?php } ?> -->
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel">Form Tambah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUser" action="" method="POST">
          <input type="hidden" name="id_po_gs" id="poGsId">

          <div class="form-group">
            <label>Nomor Order</label>
            <input type="text" name="no_po" id="no_po" class="form-control" value="<?= set_value('no_po', $no_po); ?>" required="" readonly placeholder="Masukkan Nomor Order...">
            <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= set_value('tanggal'); ?>" required="" placeholder="Masukkan Tanggal...">
            <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Konsumen</label>
            <input type="text" name="nama_user" id="nama_user" class="form-control" value="<?= set_value('nama_user'); ?>" required="" placeholder="Masukkan Nama Konsumen/Pemesan">
            <?= form_error('nama_user', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Kontak</label>
            <input type="number" name="kontak_customer" id="kontak_customer" class="form-control" value="<?= set_value('kontak_customer'); ?>" required="" placeholder="Masukkan Kontak Konsumen/Pemesan">
            <?= form_error('kontak_customer', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea name="address_customer" id="address_customer" class="form-control" placeholder="Alamat Konsumen/Pemesan"><?= set_value('address_customer'); ?></textarea>

          </div>
          <div class="form-group">
            <label>Pilih Distributor</label>
            <select class="form-control barang search-option" name="departemen" id="departemen" data-live-search="true" required>
              <option disabled>-- Pilih Distributor --</option>
              <?php foreach ($departemen as $item): ?>
                <option value="<?= $item['id_departemen'] ?>"><?= $item['nama_departemen']; ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('departemen', '<span class="text-danger small">', '</span>'); ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="statusOrderInvoice" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusOrderInvoiceLabel">Ubah Status Order Di Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formStatusOrderInvoice" action="" method="POST">
          <input type="hidden" name="id_po_gs" id="poId">

          <div class="form-group">
            <label>Pilih Status</label>
            <select class="form-control barang search-option" name="status_order_invoice" id="status_order_invoice" data-live-search="true" required>
              <option disabled>-- Pilih Status --</option>
              <option value="Belum Invoice">Belum Invoice</option>
              <option value="Sudah Invoice">Sudah Invoice</option>
              <option value="Selesai Invoice">Selesai Invoice</option>
            </select>
            <?= form_error('order_status_invoice', '<span class="text-danger small">', '</span>'); ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php $this->load->view('template/footer2'); ?>

<script>
  $(document).ready(function() {
    $('.btn-tambah').on('click', function() {
      $('#modalUserLabel').text('Form Tambah');
      $('#formUser').attr('action', '<?= base_url("Order/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    $('.btn-edit').on('click', function() {
      var id_po_gs = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("Order/edit"); ?>');
      $('#tambah').modal('show');
      $.ajax({
        url: '<?= base_url("Order/get_by_id"); ?>',
        data: {
          id_po_gs: id_po_gs
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#poGsId').val(data.id_po_gs);
          $('#no_po').val(data.no_po);
          $('#tanggal').val(data.tanggal);
          $('#nama_user').val(data.nama_user);
          $('#kontak_customer').val(data.kontak_customer);
          $('#address_customer').val(data.address_customer);
        }
      });
    });

    $('.btn-status-order-invoice').on('click', function() {
      var id_po_gs = $(this).data('id');
      $('#formStatusOrderInvoice').attr('action', '<?= base_url("Order/edit_status_order_invoice"); ?>');
      $('#statusOrderInvoice').modal('show');

      $.ajax({
        url: '<?= base_url("Order/get_by_id"); ?>',
        data: {
          id_po_gs: id_po_gs
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#poId').val(data.id_po_gs);
          $('#status_order_invoice').val(data.status_order_invoice).trigger('change');
        }
      });
    });
  });
</script>