<?php $this->load->view('template/header');?>
<?php $this->load->view('template/sidebar');?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Kelola Outstanding General Supply</a></div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Outstanding General Supply</h4>
              <div class="card-header-action">
                <a href="<?= base_url('tambah-outstanding-gs/'.$cabang);?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a>
              </div>
            </div>
            
            <div class="card-body">   
              <div class="row">
                  <div class="col-md-4"><h6>Total Outstanding General Supply</h6></div>
                  <div class="col-md-8"><h6>: Rp <?= number_format($total_outstanding, 2, ',', '.')?></h6></div>
                  <div class="col-md-4"><h6>Ekspor to Excel</h6></div>
                  <div class="col-md-8 mb-2">: 
                    <a href="<?= base_url('ekspor-outstanding-gs/'.$cabang);?>" class="btn btn-info mr-2"><i class="fa fa-upload"></i> Eksport</a>
                  </div>
              </div>               
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>No. PO</th>
                      <th>Sisa Tagihan</th>
                      <th>Tgl Tagihan</th>
                      <th>Departemen</th>
                      <th>Dibuat Oleh</th>
                      <th class="text-center" style="width: 260px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1; 
                    foreach($outstanding as $i):
                      $cek_data = $this->db->get_where('riwayat_po_gs', ['no_po' => $i['no_po']])->num_rows();
                      $cek_data_po = $this->db->get_where('detail_po_gs', ['id_po_gs' => $i['id_po_gs']])->num_rows();
                      $sisa_outstanding = $this->db->select_sum('total_harga_barang')->from('detail_po_gs')->where('id_po_gs', $i['id_po_gs'])->get()->row_array();
                      if($cek_data > 0 && $cek_data_po == 0){
                        continue;
                      }
                    ?>
                    <tr>
                      <td class="text-center">
                        <?= $no++;?>
                        <?php if (is_admin() && $i['status'] == '1') { ?>
                          <i class="fa fa-check text-success"></i>
                        <?php } ?>
                      </td>
                      <td><?= $i['no_po'];?></td>
                      <td><?= number_format($sisa_outstanding['total_harga_barang'], 2, ',', '.') ?></td>
                      <td><?= $i['tanggal'];?></td>
                      <td><?= $i['nama_user'];?></td>
                      <td><?= $i['nama'] != '' ? $i['nama'] : '-' ;?></td>
                      <td class="text-center">
                        <a href="<?= base_url('detail-outstanding-gs/'.$i['id_po_gs'].'/'.$cabang);?>" class="btn btn-light"><i class="fa fa-list"></i> Details</a>
                        <a href="<?= base_url('edit-outstanding-gs/'.$i['id_po_gs'].'/'.$cabang);?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
                        <button class="btn btn-danger" data-confirm="Anda yakin ingin menghapus data ini?|Data yang sudah dihapus tidak akan kembali." data-confirm-yes="document.location.href='<?= base_url('hapus-outstanding-gs/'.$i['id_po_gs'].'/'.$cabang); ?>';"><i class="fa fa-trash"></i> Delete</button>
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