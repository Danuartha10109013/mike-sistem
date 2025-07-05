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
                      <th>Nomor Invoice</th>
                      <th>Tanggal Invoice</th>
                      <th>Total Invoice</th>
                      <th>Status Invoice</th>
                      <th>Status Pengiriman</th>
                      <th>Customer</th>
                      <th>Distributor</th>
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
          <input type="hidden" name="id_gs" id="gsId">

          <div class="form-group">
            <label>Nomor Invoice</label>
            <input type="text" name="no_invoice" id="no_invoice" class="form-control" value="<?= set_value('no_invoice', $no_invoice); ?>" required="" readonly>
            <?= form_error('no_invoice', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= set_value('tanggal'); ?>" required="">
            <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group" id="customerInput">
            <label>Customer</label>
            <select class="form-control barang search-option" name="id_po_gs" id="id_po_gs" data-live-search="true" required="">
              <option value="" selected>-- Pilih Customer --</option>
              <?php foreach ($customer as $b): ?>
                <option value="<?= $b['id_po_gs'] ?>" <?= set_value('id_po_gs') == $b['id_po_gs'] ? 'selected' : ''; ?>><?= $b['nama_user'] . ' | ' . $b['kontak_customer']; ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_po_gs', '<span class="text-danger small">', '</span>'); ?>
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


<div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="editShipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editShipmentModalLabel">Edit Shipment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('Invoice/edit_shipment', array('id' => 'form-edit-shipment')); ?>
        <input type="hidden" class="form-control" id="id_gs" name="id_gs" required placeholder="">

        <div class="form-group">
          <label for="type_shipment">Tipe Pengiriman</label>
          <select class="form-control" name="type_shipment" id="type_shipment" onchange="ctype(this, '#form-edit-shipment')" required>
            <option value="0">Manual</option>
            <option value="1">Cargo</option>
          </select>
        </div>
        <div class="form-group" id="fdriver">
          <label for="driver">Nama Driver</label>
          <input type="text" class="form-control" id="driver" name="driver" required placeholder="Nama driver">
        </div>
        <div class="form-group" id="fexpeditor">
          <label for="expeditor">Expedisi</label>
          <input type="text" class="form-control" id="expeditor" name="expeditor" placeholder="Ketik Expedisi ">
        </div>
        <div class="form-group">
          <label for="status_kirim">Status Pengiriman</label>
          <select class="form-control" name="status_kirim" id="status_kirim" required>
            <option value="Dikemas">Dikemas</option>
            <option value="Dikirim">Dikirim</option>
            <option value="Diterima">Diterima</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <?= form_close(); ?>
      </div>
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
        "url": "<?= base_url('Invoice/server_side') ?>",
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
          "data": "tanggal"
        },
        {
          "data": "total_harga_barang_sum"
        },
        {
          "data": "status_gs"
        },
        {
          "data": "status_kirim"
        },
        {
          "data": "nama_user"
        },
        {
          "data": "distributor"
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



    $('#datatable-gs').on('click', '.btn-edit-shipment', function() {

      var idGs = $(this).data('id');
      var typeShipment = $(this).find('i').data('types');
      var driver = $(this).find('i').data('drivers');
      var expeditor = $(this).find('i').data('expeditors');
      var statusKirim = $(this).find('i').data('statuss');

      var editfexpeditor = $('#form-edit-shipment #fexpeditor');
      var editfdriver = $('#form-edit-shipment #fdriver');
      var editexpeditor = $('#form-edit-shipment #expeditor');
      var editdriver = $('#form-edit-shipment #driver');


      if (typeShipment == '1') {
        editfdriver.hide();
        editdriver.removeAttr('required');

        editfexpeditor.show();
        editexpeditor.attr('required', 'required');
      } else {
        editfdriver.show();
        editdriver.attr('required', 'required');

        editfexpeditor.hide();
        editexpeditor.removeAttr('required');
      }

      $('#form-edit-shipment #id_gs').val(idGs).change();
      $('#form-edit-shipment #status_kirim').val(statusKirim).change();
      $('#form-edit-shipment #type_shipment').val(typeShipment);

      $('#form-edit-shipment #driver').val(driver);
      $('#form-edit-shipment #expeditor').val(expeditor);

      $('#editShipmentModal').modal('show');

    });

  });


  function ctype(data, form) {
    var fexpeditor = $(form + ' #fexpeditor');
    var fdriver = $(form + ' #fdriver');
    var expeditor = $(form + ' #expeditor');
    var driver = $(form + ' #driver');

    driver.val('');
    expeditor.val('');

    if ($(data).val() == '1') {
      fdriver.hide();
      driver.removeAttr('required');
      driver.val('');

      fexpeditor.show();
      expeditor.attr('required', 'required');
      expeditor.val('');

    } else {
      fdriver.show();
      driver.attr('required', 'required');

      fexpeditor.hide();
      expeditor.removeAttr('required');
    }
  }
</script>

<script>
  $(document).ready(function() {
    $('.btn-tambah').on('click', function() {
      $('#modalUserLabel').text('Form Tambah');
      $('#formUser').attr('action', '<?= base_url("Invoice/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    // $('.btn-edit').on('click', function() {
    $('#datatable-gs').on('click', '.btn-edit', function() {
      var id_gs = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("Invoice/edit"); ?>');
      $('#tambah').modal('show');
      $('#customerInput').hide();

      $.ajax({
        url: '<?= base_url("Invoice/get_by_id"); ?>',
        data: {
          id_gs: id_gs
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#gsId').val(data.id_gs);
          $('#no_invoice').val(data.no_invoice);
          $('#tanggal').val(data.tanggal);
          $('#id_po_gs').val(data.id_po_gs);
        }
      });
    });
  });
</script>