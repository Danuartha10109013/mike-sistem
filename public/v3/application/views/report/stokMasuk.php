<?php $this->load->view('template/header2');?>
<?php $this->load->view('template/sidebar2');?>

<!-- Main Content -->
<div class="main-content">
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('laporan-pemasukan-stok'); ?>" method="post">
              <div class="card-header">
                <h4><?= $title2 ?></h4>
              </div>
              <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" name="dari_tanggal" class="form-control" value="<?= set_value('dari_tanggal'); ?>" required="">
                            <?= form_error('dari_tanggal', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="sampai_tanggal" class="form-control" value="<?= set_value('sampai_tanggal'); ?>" required="">
                            <?= form_error('sampai_tanggal', '<span class="text-danger small">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="filter" value="filter" class="btn btn-primary w-100"><i class="fa fa-eye"></i> Lihat</button>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="cetak" value="cetak" formtarget="_blank" class="btn btn-primary w-100"><i class="fa fa-print"></i> Cetak</button>
                        </div>
                    </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <?php if($stokMasuk !== null){ ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Data <?= $title2 ?></h4>
                    </div>
                    <div class="card-body">  
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatables-user">
                          <thead>
                            <tr>
                              <th class="text-center">No</th>
                              <th>Nama Barang</th>
                              <th>Kode Barang</th>
                              <th>Tanggal Masuk</th>
                              <th>Jumlah</th>
                              <th>Harga Beli</th>
                              <th>Jenis Barang</th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php
                              $no = 1; 
                              foreach($stokMasuk as $i):
                              ?>
                              <tr>
                                <td class="text-center"><?= $no++;?></td>
                                <td><?= $i['nama_barang'];?></td>
                                <td><?= $i['kode_barang'];?></td>
                                <td><?= date('d F Y', strtotime($i['tanggal_masuk']));?></td>
                                <td><?= $i['jumlah'];?></td>
                                <td><?= 'Rp '.number_format($i['harga_beli'], 0,',','.');?></td>
                                <td><?= $i['jenis_barang'];?></td>
                              </tr>
                              <?php endforeach;?>
                          </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        <?php } ?>
      </div>
    </div>
  </section>
</div>

<?php $this->load->view('template/footer2');?>