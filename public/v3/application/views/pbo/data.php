<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Project Non PO</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Project Non PO</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-pbo');?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
              
            </div>
            
            <div class="card-body"> 
              <div class="row mb-4">
                  <div class="col-md-3"><h6>Total Amount</h6></div>
                  <div class="col-md-8"><h6>: Rp <?= number_format($total_project_non_po, 2, ',', '.')?></h6></div>
              </div>               
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Deskripsi</th>
                      <th>UOM</th>
                      <th>Qty</th>
                      <th>Amount</th>
                      <th>Total</th>
                      <th>Department</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($pbo as $u):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $u['deskripsi'];?></td>
                      <td><?= $u['uom'];?></td>
                      <td><?= $u['qty'];?></td>
                      <td><?= 'Rp'. number_format($u['amount'], 2,',','.');?></td>
                      <td><?= 'Rp'. number_format($u['total'], 2,',','.');?></td>
                      <td><?= $u['department'];?></td>
                      <td class="text-center">
                        <a href="<?= base_url('generate-po/'.$u['id_pbo']);?>" class="btn btn-success"><i class="fa fa-paper-plane"></i> Generate PO</a>
                        <a href="<?= base_url('edit-pbo/'.$u['id_pbo']);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-pbo/'.$u['id_pbo']); ?>';"><i class="fa fa-trash"></i> Delete</button>
                        <a href="<?= base_url('download-spk-fabrikasi/'.$u['id_pbo']);?>" class="btn btn-light ml-2"><i class="fa fa-upload"></i> Download SPK</a>
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