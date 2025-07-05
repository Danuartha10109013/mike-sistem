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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahShipmentModal">Tambah Pengiriman</button>

              </div>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped nowrap" width="100%" id="datatable-shipment">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>No Invoice</th>
                      <th>Type</th>
                      <th>Driver</th>
                      <th>Ekspedisi</th>
                      <th>Status</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
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

<div class="modal fade" id="tambahShipmentModal" tabindex="-1" role="dialog" aria-labelledby="tambahShipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahShipmentModalLabel">Tambah Pengiriman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('Shipment/tambah', array('id' => 'form-tambah-shipment')); ?>
        <div class="form-group">
          <label for="id_gs">No Invoice</label>
          <select class="form-control " name="id_gs" id="id_gs" required>
            <option value="" disabled selected>-- Pilih Invoice --</option>

            <?php foreach ($gs as $key => $value) { ?>
              <option value="<?= $value['id_gs'] ?>"><?= $value['no_invoice'] . '-' .  $value['nama_user'] ?></option>

            <?php  } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="type_shipment">Tipe Pengiriman</label>
          <select class="form-control" name="type_shipment" id="type_shipment" onchange="ctype(this,'#form-tambah-shipment')" required>

            <option value="0" selected>Manual</option>
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
          <label for="status">Status Pengiriman</label>
          <select class="form-control" name="status" id="status" required>
            <option value="" disabled selected>Status Pengiriman</option>
            <option value="0">Dikemas</option>
            <option value="1">Dikirim</option>
            <option value="2">Diterima</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <?= form_close(); ?>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="editShipmentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editShipmentModalLabel">Edit Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('Shipment/edit', array('id' => 'form-edit-shipment')); ?>
        <input type="hidden" id="id_shipment" name="id_shipment">
        <div class="form-group">
          <label for="id_gs">No Invoice</label>
          <select class="form-control " name="id_gs" id="id_gs" required>

            <?php foreach ($gsall as $key => $value) { ?>
              <option value="<?= $value['id_gs'] ?>"><?= $value['no_invoice'] . '-' .  $value['nama_user'] ?></option>

            <?php  } ?>
          </select>
        </div>
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
          <label for="status">Status Pengiriman</label>
          <select class="form-control" name="status" id="status" required>
            <option value="0">Dikemas</option>
            <option value="1">Dikirim</option>
            <option value="2">Diterima</option>
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

    var addtype_shipment = $('#form-tambah-shipment #type_shipment');
    var addfexpeditor = $('#form-tambah-shipment #fexpeditor');
    var addfdriver = $('#form-tambah-shipment #fdriver');
    var addexpeditor = $('#form-tambah-shipment #expeditor');
    var adddriver = $('#form-tambah-shipment #driver');


    if ($(addtype_shipment).val() == '1') {
      addfdriver.hide();
      adddriver.removeAttr('required');

      addfexpeditor.show();
      addexpeditor.attr('required', 'required');
    } else {
      addfdriver.show();
      adddriver.attr('required', 'required');

      addfexpeditor.hide();
      addexpeditor.removeAttr('required');
    }

    $.fn.dataTable.ext.errMode = 'throw';
    $('#datatable-shipment').DataTable({
      scrollX: true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('shipment/ajax_datatable') ?>",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {
          // d.jenis_barang = $('#jenis-barang').val();
        }
      },
      "columns": [{
          "data": "#"
        },
        {
          "data": "no_invoice"
        },
        {
          "data": "type_shipment"
        },
        {
          "data": "driver"
        },
        {
          "data": "expeditor"
        },
        {
          "data": "status_shipment"
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


    $('#datatable-shipment').on('click', '.btn-edit', function() {
      var id_shipment = $(this).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url('Shipment/get_shipment') ?>',
        data: {
          id_shipment: id_shipment
        },
        dataType: 'json',
        success: function(data) {
          var editfexpeditor = $('#form-edit-shipment #fexpeditor');
          var editfdriver = $('#form-edit-shipment #fdriver');
          var editexpeditor = $('#form-edit-shipment #expeditor');
          var editdriver = $('#form-edit-shipment #driver');


          if (data.type_shipment == '1') {
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

          $('#form-edit-shipment #id_shipment').val(data.id_shipment);
          $('#form-edit-shipment #id_gs').val(data.id_gs).change();
          $('#form-edit-shipment #status').val(data.status).change();
          $('#form-edit-shipment #type_shipment').val(data.type_shipment);

          $('#form-edit-shipment #driver').val(data.driver);

          $('#form-edit-shipment #expeditor').val(data.expeditor);


          $('#editShipmentModal').modal('show');
        }
      });
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