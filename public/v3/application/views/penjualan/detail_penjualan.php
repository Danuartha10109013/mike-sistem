<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>
<style>
  b {
    color: #000 !important;
  }

  .dt-body-right {
    text-align: right;
  }
</style>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <a href="<?= base_url('penjualan') ?>" class="btn btn-secondary mb-2 text-dark"><i class="bi bi-arrow-left"></i> Kembali</a>

          <div class="card">
            <div class="card-header justify-content-center">
              <div class="row justify-content-lg-center w-100">

                <h4 class="col-lg-12"><?= $title2; ?></h4>
                <!-- <div class="col-lg-6 justify-content-lg-end row"><button class="btn btn-primary btn-sm col-lg-4">Tambah Penjualan</button></div> -->
                <div class="row justify-content-center rounded bg-secondary pt-2 mt-2 w-100">
                  <div class="row justify-content-center  pt-2 w-100">
                    <div class="col col-6">
                      <div class="col col-12 form-group mb-2">
                        <label class="col-lg-4 p-0">Distributor</label>
                        <b>: <?= $departemen['nama_departemen'] ?></b>
                      </div>
                      <div class="col col-12 form-group mb-2">
                        <label class="col-lg-4 p-0">Total Invoice</label>
                        <b>: <?= $invoice ?></b>
                      </div>
                    </div>
                    <div class="col col-6">
                      <div class="col col-12 form-group mb-2">

                        <label class="col-lg-6 p-0">Total Tagihan</label>
                        <b>: <?= 'Rp ' . number_format((int)$total_tagihan, 2, ',', '.') ?></b>
                      </div>
                      <div class="col col-12 form-group mb-2">
                        <label class="col-lg-6 p-0">Total Sisa Tagihan</label>
                        <b>: <?= 'Rp ' . number_format((int)$total_tagihan - $bayar_penjualan_sum, 2, ',', '.') ?></b>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body">
              <?= $this->session->flashdata('pesan'); ?>
              <div class="table-responsive">
                <table class="table table-striped" width="100%" id="datatable-gs">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Invoice</th>
                      <th>Jatuh Tempo</th>
                      <th>Total Tagihan Invoice</th>
                      <th>Total Bayar Invoice</th>
                      <th>Total Sisa Tagihan Invoices</th>
                      <th class="text-center" style="min-width: 140px !important;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot class="bg-secondary">
                    <tr>
                      <td colspan="3" class="text-center"><b>Total</b></td>
                      <td class="text-right"><b><?= 'Rp ' . number_format((int)$total_tagihan, 2, ',', '.') ?></b></td>
                      <td class="text-right"><b><?= 'Rp ' . number_format($bayar_penjualan_sum, 2, ',', '.') ?></b></td>
                      <td class="text-right"><b><?= 'Rp ' . number_format((int)$total_tagihan - $bayar_penjualan_sum, 2, ',', '.') ?></b></td>
                      <td></td>
                    </tr>
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


<div class="modal fade" id="editInvModal" tabindex="-1" role="dialog" aria-labelledby="editInvModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editInvModalLabel">Edit Shipment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open('edit-inv-pembayaran', array('id' => 'form-edit-inv')); ?>
        <input type="hidden" class="form-control" id="id_gs" name="id_gs" required placeholder="">

        <div class="form-group">
          <label for="jatuh_tempo_tagihan">Jatuh Tempo</label>
          <input type="date" class="form-control" id="jatuh_tempo_tagihan" name="jatuh_tempo_tagihan" required>
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
        "url": "<?= base_url('Penjualan/server_side_detail?dep=') . $departemen['idd'] ?>",
        "dataType": "json",
        "type": "GET",
        "data": function(d) {}
      },
      "columns": [{
          "data": "no"
        },
        {
          "data": "invoice"
        },
        {
          "data": "jatuh_tempo"
        },
        {
          "data": "total_tagihan"
        }, {
          "data": "total_bayar"
        },
        {
          "data": "sisa_tagihan"
        },
        {
          "data": "actions"
        },
      ],
      "columnDefs": [{
        targets: "_all",
        orderable: false,
        targets: [3, 4, 5],
        className: 'dt-body-right'
      }],
      "searching": true
    });

  });
</script>

<script>
  $(document).ready(function() {


    $('#datatable-gs').on('click', '.btn-edit', function() {

      $('#editInvModalLabel').text('Form Edit Invoice');
      $('#form-edit-inv')[0].reset();

      var id_gs = $(this).data('id');
      var tgl = $(this).data('tgl');

      $('#id_gs').val(id_gs)
      $('#jatuh_tempo_tagihan').val(tgl);
      $('#editInvModal').modal('show');
    });

  });
</script>