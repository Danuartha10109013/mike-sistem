<?php $this->load->view('template/header2');?>
<?php $this->load->view('template/sidebar2');?>
<!-- Main Content -->
<div class="main-content">
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
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nomor Order</th>
                      <th>Tanggal Order</th>
                      <th>Nama Barang</th>
                      <th>Jumlah Barang</th>
                      <th>Harga Barang</th>
                      <th>Total Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($outstanding as $i):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['no_po'];?></td>
                      <td><?= date('d F Y', strtotime($i['tanggal_po']));?></td>
                      <td><?= $i['nama_barang'];?></td>
                      <td><?= $i['jumlah_barang'];?></td>
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
<?php $this->load->view('template/footer2');?>