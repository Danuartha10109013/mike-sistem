<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>
<!-- Main Content -->
<style>
  .dt-body-right {
    text-align: right;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="col-12 row justify-content-center ">

                <div class="col-lg-12 row mb-2">
                  <div class=" col-6">

                    <h4><?= $title2; ?></h4>
                  </div>
                  <div class=" col-6 text-right">

                    <div class="card-header-action">
                      <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12 row">
                  <div class="col-lg-2">
                    <h6>Total Hutang</h6>
                  </div>
                  <div class="col-lg-10">: Rp.<?= number_format($allpembelian[0]['total_hutang'], 2, ',', '.'); ?></div>
                  <div class="col-lg-2">
                    <h6>Total Bayar</h6>
                  </div>
                  <div class="col-lg-10">: Rp.<?= number_format($allpembelian[0]['total_bayar_hutang'], 2, ',', '.'); ?></div>
                  <div class="col-lg-2">
                    <h6>Sisa Hutang</h6>
                  </div>
                  <div class="col-lg-10">: Rp.<?= number_format($allpembelian[0]['total_hutang'] - $allpembelian[0]['total_bayar_hutang'], 2, ',', '.'); ?></div>

                </div>
              </div>
            </div>

            <div class="card-body">
              <?= $this->session->flashdata('pesan'); ?>
              <div class="table-responsive">
                <table class="table table-striped" width="100%" id="datatable-pembelian">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>No Pembelian</th>
                      <th>No Ref</th>
                      <th>Pabrik/Produsen</th>
                      <th>Upload Bukti</th>
                      <th>Jatuh Tempo</th>
                      <th>Total Hutang</th>
                      <th>Total Bayar</th>
                      <th>Sisa Hutang</th>
                      <th class="text-center" style="min-width: 140px !important;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr class="bg-secondary text-dark font-weight-bold">
                      <td class="text-center" colspan="6">Total</td>
                      <td class="text-right">Rp.<?= number_format($allpembelian[0]['total_hutang'], 2, ',', '.'); ?></td>
                      <td class="text-right">Rp.<?= number_format($allpembelian[0]['total_bayar_hutang'], 2, ',', '.'); ?></td>
                      <td class="text-right">Rp.<?= number_format($allpembelian[0]['total_hutang'] - $allpembelian[0]['total_bayar_hutang'], 2, ',', '.'); ?></td>
                      <td></td>

                  </tfoot>
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
        <form id="formUser" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_pembelian" id="pembelianId">

          <div class="form-group">
            <label>Nomor Pembelian</label>
            <input type="text" name="no_pembelian" id="no_pembelian" class="form-control" value="<?= set_value('no_pembelian', $no_pembelian); ?>" required="" readonly>
            <?= form_error('no_pembelian', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal Pembelian</label>
            <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" class="form-control" value="<?= set_value('tanggal_pembelian'); ?>" required="" placeholder="Masukkan Tanggal Pembelian">
            <?= form_error('tanggal_pembelian', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Pabrik/Produsen</label>
            <input type="text" name="nama_pabrik" id="nama_pabrik" class="form-control" value="<?= set_value('nama_pabrik'); ?>" required="" placeholder="Masukkan Pabrik/Produsen">
            <?= form_error('nama_pabrik', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>No. Ref</label>
            <input type="text" name="no_ref" id="no_ref" class="form-control" value="<?= set_value('no_ref'); ?>" required="" placeholder="Masukkan No Ref">
            <?= form_error('no_ref', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Upload Bukti</label>
            <input type="file" name="upload_bukti" id="upload_bukti" class="form-control" value="<?= set_value('upload_bukti'); ?>" required="">
            <?= form_error('upload_bukti', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Jatuh Tempo</label>
            <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="form-control" value="<?= set_value('jatuh_tempo'); ?>" required="" placeholder="Masukkan Jatuh Tempo">
            <?= form_error('jatuh_tempo', '<span class="text-danger small">', '</span>'); ?>
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
    $('#datatable-pembelian').DataTable({
      scrollX: true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('Pembelian/server_side') ?>",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {}
      },
      "columns": [{
          "data": "no"
        },
        {
          "data": "no_pembelian"
        },
        {
          "data": "no_ref"
        },
        {
          "data": "nama_pabrik"
        },
        {
          "data": "upload_bukti"
        },
        {
          "data": "jatuh_tempo"
        },
        {
          "data": "total_hutang"
        },
        {
          "data": "total_bayar"
        },
        {
          "data": "sisa_hutang"
        },
        {
          "data": "actions"
        }
      ],
      "columnDefs": [{
        targets: "_all",
        orderable: false,
        targets: [6, 7, 8],
        className: 'dt-body-right'
      }],
      "searching": true
    });

  });
</script>

<script>
  $(document).ready(function() {

    $('.btn-tambah').on('click', function() {
      $('#modalUserLabel').text('Form Tambah');
      $('#formUser').attr('action', '<?= base_url("Pembelian/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    $('#datatable-pembelian').on('click', '.btn-edit', function() {
      var id_pembelian = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("Pembelian/edit"); ?>');
      $('#tambah').modal('show');

      $.ajax({
        url: '<?= base_url("Pembelian/get_by_id"); ?>',
        data: {
          id_pembelian: id_pembelian
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#pembelianId').val(data.id_pembelian);
          $('#no_pembelian').val(data.no_pembelian);
          $('#nama_pabrik').val(data.nama_pabrik);
          $('#no_ref').val(data.no_ref);
          $('#jatuh_tempo').val(data.jatuh_tempo);
          $('#tanggal_pembelian').val(data.tanggal_pembelian);

          var uploadBukti = $('#upload_bukti')
          uploadBukti.removeAttr('required')
        }
      });
    });
  });
</script>