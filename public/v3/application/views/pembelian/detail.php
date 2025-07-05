<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-body">
      <a href="<?= base_url('pembelian') ?>" class="btn btn-secondary mb-2 text-dark"><i class="bi bi-arrow-left"></i> Kembali</a>
      <div class="row">

        <div class="col-6">
          <div class="card">
            <div class="card-header">
              <h4><?= $title2; ?></h4>
              <div class="card-header-action">
                <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h6>No Pembelian</h6>
                </div>
                <div class="col-md-6">: <?= $pembelian['no_pembelian']; ?></div>
                <div class="col-md-6">
                  <h6>Produsen/Pabrik</h6>
                </div>
                <div class="col-md-6">: <?= $pembelian['nama_pabrik']; ?></div>
                <div class="col-md-6">
                  <h6>Total Barang</h6>
                </div>
                <div class="col-md-6">: <?= $pembelian['jumlah_beli_barang_sum']; ?></div>
                <div class="col-md-6">
                  <h6>Total Hutang</h6>
                </div>
                <div class="col-md-6">: Rp.<?= number_format($pembelian['total_harga_beli_sum'], 0, ',', '.'); ?></div>
                <div class="col-md-6">
                  <h6>Total Bayar</h6>
                </div>
                <div class="col-md-6">: Rp.<?= number_format($pembelian['nominal_bayar_sum'], 0, ',', '.'); ?></div>
                <div class="col-md-6">
                  <h6>Sisa Hutang</h6>
                </div>
                <div class="col-md-6">: Rp.<?= number_format($pembelian['total_harga_beli_sum'] - $pembelian['nominal_bayar_sum'], 0, ',', '.'); ?></div>
              </div>
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan'); ?>
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Tanggal Beli</th>
                      <th>Barang</th>
                      <th>Jumlah Beli</th>
                      <th>Harga Beli</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th class="text-center" style="width: fit-content;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $total = 0;
                    foreach ($detail_pembelian as $i): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= date('d F Y', strtotime($i['tanggal_beli_barang'])); ?></td>
                        <td><?= $i['nama_barang']; ?></td>
                        <td><?= $i['jumlah_beli_barang']; ?></td>
                        <td class="text-right">Rp.<?= number_format($i['harga_beli_barang'], 2, ',', '.'); ?></td>
                        <td class="text-right">Rp.<?= number_format($i['jumlah_beli_barang'] * $i['harga_beli_barang'], 2, ',', '.'); ?></td>
                        <td><?= $i['status_beli_barang']; ?></td>
                        <td class="text-center">
                          <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-detail-pembelian/' . $i['id_detail_pembelian'] . '/' . $pembelian['id_pembelian']); ?>';"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    <?php
                      $total += $i['jumlah_beli_barang'] * $i['harga_beli_barang'];
                    endforeach; ?>
                    <tr class="bg-secondary font-weight-bold text-dark">
                      <td class="text-center" colspan="5">Total</td>
                      <td class="text-right">Rp.<?= number_format($total, 2, ',', '.'); ?></td>
                      <td colspan="2"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="row mt-2">
                <div class="col-md-2"><a href="<?= base_url('pembelian'); ?>" class="btn btn-light">Kembali</a></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card">
            <div class="card-header">
              <h4><?= $title2; ?></h4>
              <div class="card-header-action">
                <button type="button" class="btn btn-primary btn-tambah-bayar">Tambah</button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h6>Total Bayar</h6>
                </div>
                <div class="col-md-6">: Rp.<?= number_format($pembelian['nominal_bayar_sum'], 0, ',', '.'); ?></div>
                <div class="col-md-6">
                  <h6>Bukti</h6>
                </div>
                <div class="col-md-6">: <a href="<?= base_url('assets/img/bukti_pembelian/' . $pembelian['upload_bukti']); ?>" target="_blank">Bukti</a></div>
              </div>
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan_two'); ?>
                <table class="table table-striped" id="datatables-pegawai">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>No Ref</th>
                      <th>Hutang</th>
                      <th>Nominal Bayar</th>
                      <th>Sisa</th>
                      <th>Tanggal Bayar</th>
                      <th class="text-center" style="width: fit-content;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $totalbayar = 0;
                    $totalsisa = 0;
                    $total2 = $total;
                    $total1 = $total;
                    foreach ($detail_pembelian_bayar as $i): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $i['no_ref_bayar_pembelian']; ?><br><a href="<?= base_url('assets/img/bukti_pembelian/' . $i['bukti_bayar']); ?>" target="_blank">Bukti</a></td>
                        <td class="text-right text-dark">Rp.<?= number_format($total1, 2, ',', '.'); ?></td>
                        <td class="text-right text-dark">Rp.<?= number_format($i['nominal_bayar'], 2, ',', '.'); ?></td>
                        <td class="text-right text-dark">Rp.<?= number_format($total1 - $i['nominal_bayar'], 2, ',', '.'); ?></td>
                        <td><?= date('d F Y', strtotime($i['tanggal_bayar'])); ?></td>
                        <td class="text-center">
                          <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-detail-pembelian-bayar/' . $i['id_detail_pembelian_bayar'] . '/' . $pembelian['id_pembelian']); ?>';"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    <?php
                      $total1 -= $i['nominal_bayar'];
                      $totalbayar += $i['nominal_bayar'];

                    endforeach; ?>
                    <tr class="bg-secondary font-weight-bold text-dark">
                      <td class="text-center" colspan="2">Total</td>
                      <td class="text-right font-weight-bold text-dark">Rp.<?= number_format($total2, 2, ',', '.'); ?></td>
                      <td class="text-right font-weight-bold text-dark">Rp.<?= number_format($totalbayar, 2, ',', '.'); ?></td>
                      <td class="text-right font-weight-bold text-dark">Rp.<?= number_format($total2 - $totalbayar, 2, ',', '.'); ?></td>
                      <td colspan="2"></td>
                    </tr>
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

<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel">Form Tambah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUser" action="" method="POST">
          <input type="hidden" name="id_detail_pembelian" id="pembelianDetailId">
          <input type="hidden" name="id_pembelian" id="id_pembelian" value="<?= $pembelian['id_pembelian']; ?>">

          <div class="form-group">
            <label>Pilih Barang</label>
            <select class="form-control barang" name="id_barang" id="id_barang" data-live-search="true" required>
              <option disabled selected>-- Pilih Barang --</option>
              <?php foreach ($barang as $b): ?>
                <option value="<?= $b['id_barang'] ?>" <?= set_value('id_barang') == $b['id_barang'] ? 'selected' : ''; ?>><?= $b['kode_barang'] . ' - ' . $b['nama_barang']; ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal Beli</label>
            <input type="date" name="tanggal_beli_barang" id="tanggal_beli_barang" class="form-control" value="<?= set_value('tanggal_beli_barang'); ?>" required="" placeholder="Masukkan Tanggal Beli">
            <?= form_error('tanggal_beli_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Jumlah Beli</label>
            <input type="number" name="jumlah_beli_barang" id="jumlah_beli_barang" class="form-control" value="<?= set_value('jumlah_beli_barang'); ?>" required="" placeholder="Masukkan Jumlah Beli">
            <?= form_error('jumlah_beli_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Harga Beli</label>
            <input type="number" name="harga_beli_barang" id="harga_beli_barang" class="form-control" value="<?= set_value('harga_beli_barang'); ?>" required="" placeholder="Masukkan Harga Beli">
            <?= form_error('harga_beli_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Status</label>
            <input type="text" name="status_beli_barang" id="status_beli_barang" class="form-control" value="<?= set_value('status_beli_barang'); ?>" required="" placeholder="Masukkan Status">
            <?= form_error('status_beli_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="tambah-bayar" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabelBayar">Form Tambah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUserBayar" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_detail_pembelian_bayar" id="pembelianDetailBayarId">
          <input type="hidden" name="id_pembelian" id="id_pembelian" value="<?= $pembelian['id_pembelian']; ?>">

          <div class="form-group">
            <label>No Ref</label>
            <input type="text" name="no_ref_bayar_pembelian" id="no_ref_bayar_pembelian" class="form-control" value="<?= set_value('no_ref_bayar_pembelian'); ?>" required="" placeholder="Masukkan No Ref">
            <?= form_error('no_ref_bayar_pembelian', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Bukti Bayar</label>
            <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control" value="<?= set_value('bukti_bayar'); ?>" required="" placeholder="Masukkan Bukti Bayar">
            <?= form_error('bukti_bayar', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Nominal Bayar</label>
            <input type="number" name="nominal_bayar" id="nominal_bayar" class="form-control" value="<?= set_value('nominal_bayar'); ?>" required="" placeholder="Masukkan Nominal Bayar">
            <?= form_error('nominal_bayar', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control" value="<?= set_value('tanggal_bayar'); ?>" required="" placeholder="Masukkan Tanggal Bayar">
            <?= form_error('tanggal_bayar', '<span class="text-danger small">', '</span>'); ?>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php $this->load->view('template/footer2'); ?>

<script>
  $(document).ready(function() {
    $('.btn-tambah').on('click', function() {
      $('#modalUserLabel').text('Form Tambah');
      $('#formUser').attr('action', '<?= base_url("pembelian/tambah_detail"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });
    $('.btn-tambah-bayar').on('click', function() {
      $('#modalUserLabelBayar').text('Form Tambah');
      $('#formUserBayar').attr('action', '<?= base_url("pembelian/tambah_detail_bayar"); ?>');
      $('#formUserBayar')[0].reset();
      $('#tambah-bayar').modal('show');
    });
  });
</script>