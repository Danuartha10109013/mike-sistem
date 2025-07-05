<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail Invoice Fabrikasi</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Filter Tracking Barang</h4>
              <div class="card-header-action">
              </div>
              
            </div>
            
            <div class="card-body">
              <div class="row">
                  <div class="col-md-2"><h6>Nama Barang</h6></div>
                  <div class="col-md-10">: <?= $b['nama_barang'];?></div>
                  <div class="col-md-2"><h6>Stok Terbaru</h6></div>
                  <div class="col-md-10">: <?= $stok['stok'];?></div>
              </div>          
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-user">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Tanggal</th>
                      <th>Masuk</th>
                      <th>Keluar</th>
                      <th>Ref</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    $total = 0;
                    foreach($barang as $i):
                    ?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['tanggal'];?></td>
                      <td><?= $i['masuk'];?></td>
                      <td><?= $i['keluar'];?></td>
                      <td><?= $i['ref'];?></td>
                    </tr>
                    <?php endforeach;?>
                    
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