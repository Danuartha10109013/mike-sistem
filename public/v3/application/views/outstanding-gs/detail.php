<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Detail Outstanding General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Barang Outstanding General Supply</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-detail-outstanding-gs/'.$o['id_po_gs'].'/'.$cabang);?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
              
            </div>
            
            <div class="card-body"> 
              <div class="row">
                  <div class="col-md-2"><h6>No. PO</h6></div>
                  <div class="col-md-10">: <?= $o['no_po']?></div>
                  <div class="col-md-2"><h6>Tanggal</h6></div>
                  <div class="col-md-10">: <?= $tanggal?></div>
                  <div class="col-md-2"><h6>Total</h6></div>
                  <div class="col-md-10"><h6>: Rp<?= number_format($total_outstanding, 2, ',', '.')?></h6></div>
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
                      <th>Departemen</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($o_detail as $i):?>
                    <tr>
                      <td class="text-center"><?= $no++;?></td>
                      <td><?= $i['nama_barang'].'<br/>'.$i['kode_barang'];?></td>
                      <td><?= $i['jumlah_barang'];?></td>
                      <td><?= $i['satuan_barang'];?></td>
                      <td><?= 'Rp '.number_format($i['harga_barang'], 0,',','.');?></td>
                      <td><?= 'Rp '.number_format($i['total_harga_barang'], 0,',','.');?></td>
                      <td><?= $i['nama_departemen'] == null ? $i['departemen'] : $i['nama_departemen'];?></td>
                      <td class="text-center">
                        <a href="<?= base_url('edit-detail-outstanding-gs/'.$i['id_detail_po_gs'].'/'.$i['id_po_gs'].'/'.$cabang);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-detail-outstanding-gs/'.$i['id_detail_po_gs'].'/'.$i['id_po_gs'].'/'.$cabang); ?>';"><i class="fa fa-trash"></i> Delete</button>
                      </td>
                    </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-md-2"><a href="<?= base_url('outstanding-gs/'.$cabang);?>" class="btn btn-light"><i class="fa fa-arrow-left"></i> Kembali</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('template/footer');?>