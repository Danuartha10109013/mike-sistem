<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Invoice General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Invoice General Supply</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-invoice-gs');?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
              
            </div>
            
            <div class="card-body">                
              <div class="table-responsive">
                <table class="table table-striped" width="100%" id="datatable-gs">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>No. Invoice</th>
                      <th>Tanggal</th>
                      <th>Total Invoice</th>
                      <th>PPn</th>
                      <th class="text-center" style="width: 260px;">Aksi</th>
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
<?php $this->load->view('template/footer');?>

<script>
  $(document).ready(function() {
    $.fn.dataTable.ext.errMode = 'throw';
    
    $('#datatable-gs').DataTable({
      scrollX: true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?= base_url('Gs/server_side') ?>",
        "dataType": "json",
        "type": "POST",
        "data": function(d) {
        }
      },
      "columns": [
        { "data": "#" },
        { "data": "no_invoice" },
        { "data": "tanggal" },
        { "data": "total_harga_barang_sum" },
        { "data": "ppn_status" },
        { "data": "actions" },
      ],
      "columnDefs": [{
        targets: "_all",
        orderable: false
      }],
      "searching": true
    });
  });
</script>
