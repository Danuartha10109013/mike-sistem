<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail BC General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Detail BC General Supply</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-detail-bc-gs/'.$bc['id_bc_gs']);?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
              
            </div>
            
            <div class="card-body"> 
              <div class="row">
                  <div class="col-2"><h6>Nama Vendor</h6></div>
                  <div class="col-10">: <?= $bc['nama_vendor']?></div>
                  <div class="col-2"><h6>Tanggal</h6></div>
                  <div class="col-10">: <?= $tanggal?></div>
                  <div class="col-2"><h6>Ekspor to Excel</h6></div>
                  <div class="col-10 mb-2">: 
                    <a href="<?= base_url('ekspor-detail-bc-gs/'.$bc['id_bc_gs']);?>" class="btn btn-info mr-2"><i class="fa fa-upload"></i> Eksport</a>
                    <a href="<?= base_url('ekspor-detail-bc-gs-new/'.$bc['id_bc_gs']);?>" class="btn btn-info mr-2"><i class="fa fa-upload"></i> Eksport (NEW)</a>
                  </div>
              </div>               
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>No. Invoice</th>
                      <th>Tanggal Invoice</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($bc_detail as $i):
                      $detail = $this->db->get_where('gs', ['id_gs' => $i['id_gs']])->row_array();
                    ?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $detail['no_invoice'];?></td>
                      <td><?= $detail['tanggal'];?></td>
                      <td class="text-center">
                        <a href="<?= base_url('edit-detail-bc-gs/'.$i['id_detail_bc_gs'].'/'.$i['id_bc_gs']);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-detail-bc-gs/'.$i['id_detail_bc_gs'].'/'.$i['id_bc_gs']); ?>';"><i class="fa fa-trash"></i> Delete</button>
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