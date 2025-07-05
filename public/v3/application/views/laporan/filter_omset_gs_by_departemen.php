<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Laporan Omset General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Omset General Supply By Departemen</h4>
              <div class="card-header-action">
              </div>
              
            </div>
            
            <div class="card-body">
              <div class="row">
                  <div class="col-md-2"><h6>Total Omset</h6></div>
                  <div class="col-md-10">: <?= 'Rp '.number_format($total, 0,',','.');?></div>
              </div>             
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-user">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>No. PO</th>
                      <th>Tanggal</th>
                      <th>Total</th>
                      <th>Departemen</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    $total = 0;
                    foreach($omset as $i):
                      $total += $i['total'];
                      if ($i['no_po'] === NULL && $i['tanggal'] === NULL) {
                        continue;
                      }
                    ?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['no_po'];?></td>
                      <td><?= date('d-m-Y', strtotime($i['tanggal']));?></td>
                      <td><?= 'Rp '.number_format($i['total'], 0,',','.');?></td>
                      <td><?= $i['departemen'];?></td>
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