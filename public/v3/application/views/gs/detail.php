<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= 'General Supply'?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail Invoice General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Barang Invoice</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-detail-invoice-gs/'.$f['id_gs']);?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
              
            </div>
            
            <div class="card-body"> 
              <div class="row">
                  <div class="col-md-2"><h6>No. Invoice</h6></div>
                  <div class="col-md-10">: <?= $f['no_invoice']?></div>
                  <div class="col-md-2"><h6>Tanggal</h6></div>
                  <div class="col-md-10">: <?= $tanggal?></div>
                  <div class="col-md-2"><h6>Ekspor to PDF</h6></div>
                  <div class="col-md-10 mb-2">: 
                    <a href="<?= base_url('cetak-detail-invoice-gs/'.$f['id_gs']);?>" class="btn btn-info mr-2"><i class="fa fa-upload"></i> Invoice</a>
                    <a href="<?= base_url('cetak-detail-surat-jalan-gs/'.$f['id_gs']);?>" class="btn btn-info"><i class="fa fa-upload"></i> Surat Jalan</a>
                  </div>
                  <div class="col-md-2"><h6>Ekspor to Excel</h6></div>
                  <div class="col-md-10 mb-2">: 
                    <a href="<?= base_url('ekspor-detail-invoice-gs/'.$f['id_gs']);?>" class="btn btn-info mr-2" ><i class="fa fa-upload"></i> Eksport</a>
                  </div>
                  <div class="col-md-2"><h6>Include PPN</h6></div>
                  <div class="col-md-10 mb-2">: 
                    <a href="<?= base_url('include-ppn-gs/'.$f['id_gs']);?>" class="btn btn-success mr-2"><i class="fa fa-check"></i> Enable</a>
                  </div>
              </div>               
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nama Barang</th>
                      <th>QTY</th>
                      <th>UOM</th>
                      <th>Harga</th>
                      <th>Total</th>
                      <th>PO</th>
                      <th>Departemen</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($f_detail as $i):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['nama_barang'].'<br/>'.$i['kode_barang'];?></td>
                      <td><?= $i['jumlah_barang'];?></td>
                      <td><?= $i['satuan_barang'];?></td>
                      <td><?= number_format($i['harga_barang'], 0,',','.');?></td>
                      <td><?= number_format($i['total_harga_barang'], 0,',','.');?></td>
                      <td><?= $i['no_po'];?></td>
                      <td><?= $i['nama_departemen'] == null ? $i['departemen'] : $i['nama_departemen'];?></td>
                      <td class="text-center">
                        <?php if($i['nama_barang'] == 'PPn 10%' || $i['nama_barang'] == 'PPn 11%'): ?>
                          <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus PPN data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('Gs/delete_ppn/'.$i['id_gs']); ?>';"><i class="fa fa-trash"></i> Delete</button>
                        <?php else: ?>
                          <a href="<?= base_url('edit-detail-invoice-gs/'.$i['id_detail_gs'].'/'.$f['id_gs']);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                          <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-detail-invoice-gs/'.$i['id_detail_gs'].'/'.$f['id_gs']); ?>';"><i class="fa fa-trash"></i> Delete</button>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-md-2"><a href="<?= base_url('invoice-gs');?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer');?>