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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">Tambah Paket</button>

              </div>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped nowrap" width="100%" id="datatable-barang">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nama Paket</th>
                      <th>Harga Paket</th>
                      <th>Item</th>
                      <th>Status</th>
                      <th>Poin</th>
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

<div class="modal fade" id="tambahBarangModal" tabindex="-1" role="dialog" aria-labelledby="tambahBarangModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahBarangModalLabel">Tambah Paket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('Package/tambah', array('id' => 'form-tambah-barang')); ?>
        <div class="form-group">
          <label for="package_name">Nama Paketan</label>
          <input type="text" class="form-control" id="package_name" name="package_name" required>
        </div>
        <div class="form-group">
          <label for="item_package">Barang</label>
          <select class="form-control select-barang" name="item_package[]" id="item_package" required data-live-search="true" multiple>
            <?php foreach ($barang as $key => $value) { ?>
              <option value="<?= $value['id_barang'] ?>"><?= $value['nama_barang'] ?></option>

            <?php  } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="harga_package">Harga Paket(Rp)</label>
          <input type="number" class="form-control" id="harga_package" name="harga_package" required>
        </div>
        <div class="form-group">
          <label for="point_package">Point Paket</label>
          <input type="number" min="0" class="form-control" id="point_package" name="point_package" required>

        </div>
        <div class="form-group">
          <label for="status_package">Status Paket</label>
          <select class="form-control" name="status_package" id="status_package" required>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <?= form_close(); ?>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editBarangModal" tabindex="-1" role="dialog" aria-labelledby="editBarangModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBarangModalLabel">Edit Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('Package/edit', array('id' => 'form-edit-paket')); ?>
        <input type="hidden" id="id_package" name="id_package">
        <div class="form-group">
          <label for="package_name">Nama Paketan</label>
          <input type="text" class="form-control" id="package_name" name="package_name" required>
        </div>
        <div class="form-group">
          <label for="item_package">Barang</label>
          <select class="form-control select-barang" name="item_package[]" id="item_package" required data-live-search="true" multiple>
            <?php foreach ($barang as $key => $value) { ?>
              <option value="<?= $value['id_barang'] ?>"><?= $value['nama_barang'] ?></option>

            <?php  } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="harga_package">Harga Paket(Rp)</label>
          <input type="number" class="form-control" id="harga_package" name="harga_package" required>
        </div>
        <div class="form-group">
          <label for="point_package">Point Paket</label>
          <input type="number" min="0" class="form-control" id="point_package" name="point_package" required>

        </div>
        <div class="form-group">
          <label for="status_package">Status Paket</label>
          <select class="form-control" name="status_package" id="status_package" required>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
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
    $('#datatable-barang').DataTable({
      scrollX: true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('package/ajax_datatable') ?>",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {
          d.jenis_barang = $('#jenis-barang').val();
        }
      },
      "columns": [{
          "data": "#"
        },
        {
          "data": "package_name"
        },
        {
          "data": "harga_package"
        },
        {
          "data": "item_package"
        },
        {
          "data": "status_package"
        },
        {
          "data": "point_package"
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


    $('#datatable-barang').on('click', '.btn-edit', function() {
      var id_package = $(this).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url('Package/get_package') ?>',
        data: {
          id_package: id_package
        },
        dataType: 'json',
        success: function(data) {
          $('#form-edit-paket #id_package').val(data.id_package);
          $('#form-edit-paket #package_name').val(data.package_name);
          $('#form-edit-paket #harga_package').val(data.harga_package);
          $('#form-edit-paket #point_package').val(data.point_package);
          $('#form-edit-paket #status_package').val(data.status_package);

          var itemp = data.item_package.split(',').map(item_package => item_package.trim());

          $('.selectpicker').selectpicker();
          $('#form-edit-paket #item_package').selectpicker('val', itemp);

          $('#form-edit-paket #item_package').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = $(this).val(); // Get selected options
            $('#output').text(`Selected roles: ${selectedRoles.join(',')}`);
          });

          $('#editBarangModal').modal('show');
        }
      });
    });

  });
</script>