<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola BC General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data BC General Supply</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-bc-gs');?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
              
            </div>
            
            <div class="card-body">                
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nama Vendor</th>
                      <th>Tanggal</th>
                      <th>NPWP</th>
                      <th class="text-center" style="width: 260px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($bc as $i):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['nama_vendor'];?></td>
                      <td><?= $i['tanggal'];?></td>
                      <td><?= $i['npwp'];?></td>
                      <td class="text-center">
                        <a href="<?= base_url('detail-bc-gs/'.$i['id_bc_gs']);?>" class="btn btn-light"><i class="fa fa-list"></i> Details</a>
                        <a href="<?= base_url('edit-bc-gs/'.$i['id_bc_gs']);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-bc-gs/'.$i['id_bc_gs']); ?>';"><i class="fa fa-trash"></i> Delete</button>
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