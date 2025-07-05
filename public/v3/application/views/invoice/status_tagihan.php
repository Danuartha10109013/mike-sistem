<?php $this->load->view('template/header2');?>
<?php $this->load->view('template/sidebar2');?>
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
                      <th>Nama Barang</th>
                      <th>Jumlah Barang</th>
                      <th>Total Harga</th>
                      <th>Status Invoice</th>
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

<?php $this->load->view('template/footer2');?>

<script>
  $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'throw';
    $('#datatable-gs').DataTable({
      scrollX: true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('StatusTagihan/server_side') ?>",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {
        }
      },
      "columns": [
        { "data": "no" },
        { "data": "no_invoice" },
        { "data": "tanggal" },
        { "data": "nama_barang" },
        { "data": "jumlah_barang" },
        { "data": "total_harga_barang" },
        { "data": "status_gs" },
      ],
      "columnDefs": [{
        targets: "_all",
        orderable: false
      }],
      "searching": true
    });
  });
</script>
