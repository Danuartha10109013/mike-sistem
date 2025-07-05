<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Riwayat Outstanding General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Riwayat Outstanding General Supply</h4>
              <div class="card-header-action">
              </div>
              
            </div>
            
            <div class="card-body">               
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>No PO</th>
                      <th>Tanggal</th>
                      <th>Nama Barang</th>
                      <th>QTY</th>
                      <th>UOM</th>
                      <th>Harga</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($outstanding as $i):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['no_po'];?></td>
                      <td><?= $i['tanggal_po'];?></td>
                      <td><?= $i['nama_barang'].'<br/>'.$i['kode_barang'];?></td>
                      <td><?= $i['jumlah_barang'];?></td>
                      <td><?= $i['satuan_barang'];?></td>
                      <td><?= 'Rp '.number_format($i['harga_barang'], 2,',','.');?></td>
                      <td><?= 'Rp '.number_format($i['total_harga_barang'], 2,',','.');?></td>
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