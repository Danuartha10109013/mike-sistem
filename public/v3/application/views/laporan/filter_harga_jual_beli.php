<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Laporan Harga</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Harga Jual-Beli</h4>
              <div class="card-header-action">
              </div>
              
            </div>
            <?php
            foreach($jual_beli as $i):
            ?>
            <div class="card-body">
              <div class="row">
                  <div class="col-md-2"><h6>No. PO</h6></div>
                  <div class="col-md-10"><h6>: <?= $i['no_po'];?></h6></div>
                  <div class="col-md-2"><h6>Tanggal</h6></div>
                  <div class="col-md-10"><h6>: <?= date('d F Y', strtotime($i['tanggal']));?></h6></div>
                  <div class="col-md-2"><h6>Depertement</h6></div>
                  <div class="col-md-10"><h6>: <?= $i['nama_user'];?></h6></div>
              </div>             
              <div class="table-responsive">
                <table class="table table-striped table-bordered" id="">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nama Barang</th>
                      <th>QTY</th>
                      <th>Harga Beli (PCS)</th>
                      <th>Harga Jual (PCS)</th>
                      <th>Laba Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    $this->db->select('*');
                    $this->db->from('detail_cs');
                    $this->db->where('detail_cs.no_po', $i['no_po']);
                    $this->db->order_by('detail_cs.nama_barang', 'ASC');
                    $detail_jual_beli = $this->db->get()->result_array();
                    $total = 0;
                    foreach($detail_jual_beli as $u): 
                      $margin_total = $u['harga_barang']*$u['jumlah_barang']-$u['harga_beli']*$u['jumlah_barang'];
                      $total += $margin_total;
                      ?>
                      <tr>
                        <td class="text-center" style="width: 50px;"><?= $no++;?></td>
                        <td><?= $u['nama_barang'];?></td>
                        <td class="text-center" style="width: 60px;"><?= $u['jumlah_barang'];?></td>
                        <td class="text-right" style="width: 180px;"><?= number_format($u['harga_beli'], 0, '.', '.');?></td>
                        <td class="text-right" style="width: 180px;"><?= number_format($u['harga_barang'], 0, '.', '.');?></td>
                        <td class="text-right" style="width: 180px;"><span class="<?= $margin_total < 0 ? 'text-danger' : '' ?>"><?= number_format($margin_total, 0, '.', '.');?></span></td>
                      </tr>
                    <?php endforeach;?>
                    <tr>
                        <th class="text-center" colspan="5">TOTAL</th>
                        
                        <th class="text-right" style="width: 180px;"><?= number_format($total, 0, '.', '.');?></th>
                      </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer');?>