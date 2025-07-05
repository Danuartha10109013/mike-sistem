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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahBarangModal">Tambah Barang</button>

              </div>
            </div>

            <div class="card-body">
              <?php if (is_admin()): ?>
                <form action="<?= base_url('barang'); ?>" method="post">
                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label>Jenis</label>
                      <select name="jenis_barang" id="jenis-barang" class="form-control" required>
                        <option selected disabled>-- Pilih Jenis Barang --</option>
                        <option value="All" <?= $jenis_barang == 'All' ? 'selected' : '' ?>>All</option>
                        <?php foreach ($jenis as $key => $value) { ?>
                          <option value="<?= $value ?>" <?= $jenis_barang == $value ? 'selected' : '' ?>><?= $value ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-md-3 ">
                      <label>&nbsp;</label><br>
                      <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                    <div class="col-md-3 ">
                      <label>&nbsp;</label><br>
                      <a href="<?= base_url('barang'); ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Reset</a>
                    </div>
                  </div>
                </form>
              <?php endif; ?>
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan'); ?>
                <table class="table table-striped nowrap" width="100%" id="datatable-barang">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nama Barang</th>
                      <th>Kode Barang</th>
                      <th>Satuan Barang</th>
                      <th>Harga Barang</th>
                      <th>Jenis Barang</th>
                      <th>Poin Barang</th>
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
        <h5 class="modal-title" id="tambahBarangModalLabel">Tambah Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('Barang/tambah', array('id' => 'form-tambah-barang')); ?>
        <div class="form-group">
          <label for="nama_barang">Nama Barang</label>
          <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
        </div>
        <div class="form-group">
          <label for="kode_barang">Kode Barang</label>
          <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
        </div>
        <div class="form-group">
          <label for="satuan_barang">Satuan Barang</label>
          <select class="form-control" name="satuan_barang" id="satuan_barang" required>
            <option value="PCS">PCS</option>
            <option value="BOX">BOX</option>
          </select>
        </div>
        <div class="form-group">
          <label for="harga_barang">Harga Barang(Rp)</label>
          <input type="number" class="form-control" id="harga_barang" name="harga_barang" required>
        </div>
        <div class="form-group">
          <label for="poin_barang">Poin Barang</label>
          <input type="number" class="form-control" id="poin_barang" name="poin_barang" required>
        </div>
        <div class="form-group">
          <label for="jenis_barang">Jenis Barang</label>
          <select class="form-control" name="jenis_barang" id="jenis_barang" required>
            <option value="" selected disabled> -- Pilih Jenis --</option>
            <!-- <option value="Skincare">Skincare</option>
            <option value="Lipstick">Lipstick</option> -->
            <?php foreach ($jenis as $key => $value) { ?>
              <option value="<?= $value ?>" <?= $jenis_barang == $value ? 'selected' : '' ?>><?= $value ?></option>
            <?php } ?>
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
        <?= form_open('Barang/edit', array('id' => 'form-edit-barang')); ?>
        <input type="hidden" id="id_barang" name="id_barang">
        <div class="form-group">
          <label for="nama_barang">Nama Barang</label>
          <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
        </div>
        <div class="form-group">
          <label for="kode_barang">Kode Barang</label>
          <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
        </div>

        <div class="form-group">
          <label for="satuan_barang">Satuan Barang</label>
          <select class="form-control" name="satuan_barang" id="satuan_barang" required>
            <option value="PCS">PCS</option>
            <option value="BOX">BOX</option>
          </select>
        </div>
        <div class="form-group">
          <label for="harga_barang">Harga Barang(Rp)</label>
          <input type="number" class="form-control" id="harga_barang" name="harga_barang" required>
        </div>
        <div class="form-group">
          <label for="poin_barang">Poin Barang</label>
          <input type="number" class="form-control" id="poin_barang" name="poin_barang" required>
        </div>
        <div class="form-group">
          <label for="jenis_barang">Jenis Barang</label>
          <select class="form-control" name="jenis_barang" id="jenis_barang" required>
            <?php foreach ($jenis as $key => $value) { ?>
              <option value="<?= $value ?>" <?= $jenis_barang == $value ? 'selected' : '' ?>><?= $value ?></option>
            <?php } ?>
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
        "url": "<?= base_url('Barang/ajax_datatable') ?>",
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
          "data": "nama_barang"
        },
        {
          "data": "kode_barang"
        },
        {
          "data": "satuan_barang"
        },
        {
          "data": "harga_barang"
        },
        {
          "data": "jenis_barang"
        },
        {
          "data": "poin_barang"
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
      var id_barang = $(this).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url('Barang/get_barang') ?>',
        data: {
          id_barang: id_barang
        },
        dataType: 'json',
        success: function(data) {
          $('#form-edit-barang #id_barang').val(data.id_barang);
          $('#form-edit-barang #nama_barang').val(data.nama_barang);
          $('#form-edit-barang #kode_barang').val(data.kode_barang);
          $('#form-edit-barang #satuan_barang').val(data.satuan_barang).trigger('change');
          $('#form-edit-barang #harga_barang').val(data.harga_barang);
          $('#form-edit-barang #poin_barang').val(data.poin_barang);
          $('#form-edit-barang #jenis_barang').val(data.jenis_barang).trigger('change');
          $('#editBarangModal').modal('show');
        }
      });
    });

  });
</script>