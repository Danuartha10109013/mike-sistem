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
              <?= $this->session->flashdata('pesan'); ?>
              <div class="table-responsive">
                <table class="table table-striped" width="100%" id="datatable-gs">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>No Invoice</th>
                      <th>Nama Barang</th>
                      <th>QTY</th>
                      <th>Tanggal Return</th>
                      <th>Deskripsi</th>
                      <th class="text-center" style="min-width: 140px !important;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
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
          <input type="hidden" name="id_retur" id="returId">

          <div class="form-group">
            <label>No Invoice</label>
            <input type="text" name="no_invoice" id="no_invoice" class="form-control" value="<?= set_value('no_invoice'); ?>" required="" placeholder="Masukkan No Invoice">
            <?= form_error('no_invoice', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Pilih Barang</label>
            <select class="form-control barang" name="id_barang" id="id_barang" data-live-search="true" required>
              <option disabled selected>-- Pilih Barang --</option>
              <?php foreach ($barang as $b): ?>
                <option value="<?= $b['id_barang'] ?>" <?= set_value('id_barang') == $b['id_barang'] ? 'selected' : ''; ?>><?= $b['kode_barang'] . ' - ' . $b['nama_barang']; ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>QTY</label>
            <input type="number" name="stock_barang" id="stock_barang" class="form-control" value="<?= set_value('stock_barang'); ?>" required="" placeholder="Masukkan QTY">
            <?= form_error('stock_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal Return</label>
            <input type="date" name="tanggal_retur" id="tanggal_retur" class="form-control" value="<?= set_value('tanggal_retur'); ?>" required="" placeholder="Masukkan Tanggal Return">
            <?= form_error('tanggal_retur', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Desckripsi</label>
            <input type="text" name="desc" id="desc" class="form-control" value="<?= set_value('desc'); ?>" required="" placeholder="Masukkan Deskripsi">
            <?= form_error('desc', '<span class="text-danger small">', '</span>'); ?>
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
    $.fn.dataTable.ext.errMode = 'throw';
    $('#datatable-gs').DataTable({
      scrollX: true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('Retur/server_side') ?>",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {}
      },
      "columns": [{
          "data": "no"
        },
        {
          "data": "no_invoice"
        },
        {
          "data": "nama_barang"
        },
        {
          "data": "stock_barang"
        },
        {
          "data": "tanggal_retur"
        },
        {
          "data": "desc"
        },
        {
          "data": "actions"
        },
      ],
      "columnDefs": [{
        targets: "_all",
        orderable: false
      }],
      "searching": true
    });

  });

</script>

<script>
  $(document).ready(function() {
    $('.btn-tambah').on('click', function() {
      $('#modalUserLabel').text('Form Tambah');
      $('#formUser').attr('action', '<?= base_url("Retur/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    // $('.btn-edit').on('click', function() {
    $('#datatable-gs').on('click', '.btn-edit', function() {
      var id_retur = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("Retur/edit"); ?>');
      $('#tambah').modal('show');
      $('#customerInput').hide();

      $.ajax({
        url: '<?= base_url("Retur/get_by_id"); ?>',
        data: {
          id_retur: id_retur
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#returId').val(data.id_retur);
          $('#no_invoice').val(data.no_invoice);
          $('#id_barang').val(data.id_barang).trigger('change');
          $('#stock_barang').val(data.stock_barang);
          $('#tanggal_retur').val(data.tanggal_retur);
          $('#desc').val(data.desc);
        }
      });
    });
  });
</script>