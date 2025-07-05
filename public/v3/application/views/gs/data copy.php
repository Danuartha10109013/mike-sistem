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
                <table class="table table-striped" id="datatables-jabatan">
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
                    <?php
                    $no = 1; 
                    foreach($invoice as $i):
                      $inv = $this->db->select_sum('total_harga_barang')->from('detail_gs')->where('id_gs', $i['id_gs'])->get()->row_array();
                      $inv1 = $this->db->select('*')->from('detail_gs')->where('id_gs', $i['id_gs'])->get()->result_array();
                      $ppn='-';
                      foreach ($inv1 as $key) {
                        if($key['nama_barang'] == 'PPn 10%' || $key['nama_barang'] == 'PPn 11%'){
                          $ppn='Ya';
                        }else{
                          $ppn='Tidak';
                        }
                      }
                      ?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['no_invoice'];?></td>
                      <td><?= $i['tanggal'];?></td>
                      <td><?= number_format($inv['total_harga_barang'], 2, ',', '.');?></td>
                      <td><?= $ppn ?></td>
                      <td class="text-center">
                        <a href="<?= base_url('detail-invoice-gs/'.$i['id_gs']);?>" class="btn btn-light"><i class="fa fa-list"></i> Details</a>
                        <a href="<?= base_url('edit-invoice-gs/'.$i['id_gs']);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-invoice-gs/'.$i['id_gs']); ?>';"><i class="fa fa-trash"></i> Delete</button>
                      </td>
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