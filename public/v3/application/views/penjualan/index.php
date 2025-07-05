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

              <div class="row">
                <h4 class="col-12 mb-2"><?= $title2; ?></h4>

                <div class="col-lg-2">
                  <h6>Total Tagihan</h6>
                </div>
                <div class="col-lg-10">:Rp.<?= number_format($total_tagihan, 2, ',', '.'); ?></div>
                <div class="col-lg-2">
                  <h6>Total Bayar</h6>
                </div>
                <div class="col-lg-10">:Rp.<?= number_format($total_bayar, 2, ',', '.'); ?></div>
                <div class="col-lg-2">
                  <h6>Sisa</h6>
                </div>
                <div class="col-lg-10"> :Rp.<?= number_format($total_sisa, 2, ',', '.'); ?></div>

              </div>
            </div>

            <div class="card-body">
              <?= $this->session->flashdata('pesan'); ?>
              <div class="table-responsive">
                <table class="table table-striped" width="100%" id="datatable-gs">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Distributor</th>
                      <th>Jatuh Tempo Terdekat</th>
                      <th>Banyak Invoice</th>
                      <th>Total Tagihan</th>
                      <th>Total Bayar</th>
                      <th>Sisa Tagihan</th>
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

<?php $this->load->view('template/footer2'); ?>

<script>
  $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'throw';
    $('#datatable-gs').DataTable({
      scrollX: true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('Penjualan/server_side') ?>",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {}
      },
      "columns": [{
          "data": "no"
        },
        {
          "data": "nama_departemen"
        },
        {
          "data": "jatuh_tempo"
        },
        {
          "data": "banyak_invoice"
        },
        {
          "data": "total_tagihan"
        }, {
          "data": "total_bayar"
        }, {
          "data": "sisa_tagihan"
        },
        {
          "data": "actions"
        },
      ],
      "columnDefs": [{
        targets: "_all",
        orderable: false,
        targets: [4, 5, 6],
        className: 'dt-body-right'
      }],
      "searching": true
    });

  });
</script>