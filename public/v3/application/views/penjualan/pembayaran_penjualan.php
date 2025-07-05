<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-body">
      <a href="<?= base_url('detail-penjualan/' . $f['departemen']) ?>" class="btn btn-secondary mb-2 text-dark"><i class="bi bi-arrow-left"></i> Kembali</a>
      <div class="row">
        <div class="col-5">

          <div class="card">
            <div class="card-header">
              <h4>Detail Invoice</h4>
              <div class="card-header-action">
                <a href="<?= base_url('cetak-detail-invoice/' . $f['id_gs']); ?>" target="_blank" class="btn btn-primary">Cetak Invoice</a>
                <a href="<?= base_url('detail-invoice/') . $f['id_gs'] ?>" class="btn btn-primary">Detail Invoice <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h6>Nomor Invoice:</h6>
                </div>
                <div class="col-md-12"><?= $f['no_invoice'] ?></div>
                <div class="col-md-12">
                  <h6>Tanggal Invoice:</h6>
                </div>
                <div class="col-md-12"><?= $tanggal ?></div>
                <div class="col-md-12">
                  <h6>Driver:</h6>
                </div>
                <div class="col-md-12"> <?= $f['driver'] ? $f['driver'] : '' ?></div>
              </div>
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan'); ?>
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nama Barang</th>
                      <th>Jumlah Barang</th>
                      <th>Harga Barang</th>
                      <th>Total</th>
                      <th>Status Indent</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    $ttotal = 0;
                    foreach ($f_detail as $i): ?>
                      <?php if ($i['tipe_item'] === 'Diskon') { ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td colspan="2" class="text-center"><?= $i['nama_barang']; ?></td>
                          <td>Rp.<?= str_replace('-', '', number_format($i['total_harga_barang'], 2, ',', '.')); ?></td>
                          <td></td>
                          <td> </td>
                        </tr>
                      <?php } else { ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td><?= $i['nama_barang'] . '<br/>' . $i['kode_barang']; ?></td>
                          <td><?= $i['jumlah_barang']; ?>/<?= $i['satuan_barang'] ? $i['satuan_barang'] : 'Paket'; ?></td>
                          <td>Rp.<?= number_format($i['harga_barang'], 2, ',', '.'); ?></td>
                          <td class="text-right">Rp.<?= number_format($i['total_harga_barang'], 2, ',', '.'); ?></td>
                          <td><?= $i['status_indent']; ?></td>

                        </tr>
                      <?php } ?>
                    <?php
                      $ttotal += $i['total_harga_barang'];
                    endforeach; ?>
                    <tr class="bg-secondary">
                      <td class="text-center font-weight-bold text-dark" colspan="4">Total</td>
                      <td class="text-right font-weight-bold text-dark">Rp.<?= number_format($ttotal, 2, ',', '.'); ?></td>
                      <td class="text-center"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-7">
          <div class="card">
            <div class="card-header">
              <h4>Bayar Penjualan</h4>
              <div class="card-header-action">
                <button type="button" class="btn btn-primary btn-tambah-bayar" data-target="#tambah-pembayaran" data-toggle="modal">Tambah</button>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="col-md-12">Total Tagihan:</h6>

                  <div class="col-md-12">Rp.<?= number_format($ttotal, 0, ',', '.'); ?></div>
                  <div class="col-md-12">
                    <h6>Sisa Tagihan:</h6>
                  </div>
                  <div class="col-md-12">Rp.<?= number_format($ttotal - $bayar_penjualan_sum, 0, ',', '.'); ?></div>
                  <div class="col-md-12">
                    <h6>Jatuh Tempo:</h6>
                  </div>
                  <div class="col-md-12"><?= $jatuh_tempo_tagihan ?> </div>
                </div>
                <div class="col-md-6">
                  <h6 class="col-md-12">Distributor:</h6>
                  <div class="col-md-12"><?= $f_detail[0]['nama_departemen'] ?> </div>
                </div>
              </div>
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan_two'); ?>
                <table class="table table-striped" width="100%" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Tanggal</th>
                      <th>Tagihan</th>
                      <th>Bayar</th>
                      <th>Sisa</th>
                      <th>No Ref</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ttotal = 0;
                    $ttotal2 = 0;
                    foreach ($f_detail as $i):
                      $ttotal += $i['total_harga_barang'];
                      $ttotal2 += $i['total_harga_barang'];
                    endforeach;
                    ?>
                    <?php
                    $no = 1;
                    $totalBayar = 0;
                    $totalSisa = 0;
                    foreach ($detail_penjualan_bayar as $i): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= date('d F Y', strtotime($i['tanggal_bayar_penjualan'])); ?></td>
                        <td class="text-right  text-dark">Rp.<?= number_format($ttotal, 0, ',', '.'); ?></td>
                        <td class="text-right  text-dark">Rp.<?= number_format($i['bayar'], 0, ',', '.'); ?></td>
                        <td class="text-right  text-dark">Rp.<?= number_format($ttotal - $i['bayar'], 0, ',', '.'); ?></td>
                        <td><?= $i['penjualan_bayar_ref'] ?><br><a href="<?= base_url('assets/img/bukti_penjualan/' . $i['penjualan_bayar_bukti']); ?>" target="_blank">Bukti</a></td>
                        <td class="text-center">
                          <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-penjualan-bayar/' . $i['id_penjualan_bayar'] . '/' . $id_gs); ?>';"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    <?php
                      $ttotal -= $i['bayar'];
                      $totalBayar += $i['bayar'];
                      $totalSisa += ($ttotal - $i['bayar']);
                    endforeach;
                    ?>
                    <tr class="bg-secondary">
                      <td class="text-center font-weight-bold text-dark" colspan="2">Total</td>
                      <td class="text-right  text-dark">Rp.<?= number_format($ttotal2, 0, ',', '.'); ?></td>
                      <td class="text-right  text-dark">Rp.<?= number_format($totalBayar, 0, ',', '.'); ?></td>
                      <td class="text-right  text-dark">Rp.<?= number_format($ttotal2 - $totalBayar, 0, ',', '.'); ?></td>
                      <td class="text-center"></td>
                      <td class="text-center"></td>
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
          <input type="hidden" name="id_detail_gs" id="gsDetailId">
          <input type="hidden" name="id_gs" id="id_gs" value="<?= $id_gs; ?>">

          <div class="form-group">
            <label>Barang</label>
            <select class="form-control search-option" name="id_detail_po_gs" id="detail_po_gs" data-live-search="true" required>
              <option disabled selected>-- Pilih Barang --</option>
              <?php foreach ($barang as $b):
                $get_id_po = $this->db->get_where('detail_po_gs', ['id_detail_po_gs' => $b['id_detail_po_gs']])->row_array();
                $get_po = $this->db->get_where('po_gs', ['id_po_gs' => $get_id_po['id_po_gs']])->row_array();
              ?>
                <option value="<?= $b['id_detail_po_gs'] ?>" <?= set_value('id_detail_po_gs') == $b['id_detail_po_gs'] ? 'selected' : ''; ?>><?= $b['nama_barang'] . ' | No. PO ' . $get_po['no_po'] . ' | ' . $b['status_indent']; ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_detail_po_gs', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Nomor Order</label>
            <div id="no_po">

            </div>
            <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Sisa Barang Dalam Order</label>
            <div id="jumlah_po">

            </div>
            <?= form_error('jumlah_po', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Jumlah Barang</label>
            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" value="<?= set_value('jumlah_barang'); ?>" required="" placeholder="Masukkan Jumlah Barang">
            <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Harga Barang</label>
            <div id="harga">

            </div>
            <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
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

<div class="modal fade" id="tambah-pembayaran" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
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
          <input type="hidden" name="id_penjualan_bayar" id="id_penjualan_bayar">
          <input type="hidden" name="id_gs" id="id_gs" value="<?= $id_gs; ?>">

          <div class="form-group">
            <label>No Ref</label>
            <input type="text" name="penjualan_bayar_ref" id="penjualan_bayar_ref" class="form-control" value="<?= set_value('penjualan_bayar_ref'); ?>" required="" placeholder="Masukkan No Ref">
            <?= form_error('penjualan_bayar_ref', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Bukti Bayar</label>
            <input type="file" name="penjualan_bayar_bukti" id="penjualan_bayar_bukti" class="form-control" value="<?= set_value('penjualan_bayar_bukti'); ?>" required="" placeholder="Masukkan Bukti Bayar">
            <?= form_error('penjualan_bayar_bukti', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Nominal Bayar</label>
            <input type="number" name="bayar" id="bayar" class="form-control" value="<?= set_value('bayar'); ?>" required="" placeholder="Masukkan Nominal Bayar">
            <?= form_error('bayar', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar_penjualan" id="tanggal_bayar_penjualan" class="form-control" value="<?= set_value('tanggal_bayar_penjualan'); ?>" required="" placeholder="Masukkan Tanggal Bayar">
            <?= form_error('tanggal_bayar_penjualan', '<span class="text-danger small">', '</span>'); ?>
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

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel">Form Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUser" action="" method="POST">
          <input type="hidden" name="id_detail_gs" id="gsDetailId">
          <input type="hidden" name="id_gs" id="id_gs" value="<?= $id_gs; ?>">

          <div class="form-group">
            <label>Barang</label>
            <input type="hidden" name="id_detail_po_gs" id="id_detail_po_gs" class="form-control" readonly="">
            <input type="text" name="" class="form-control" readonly="">
            <?= form_error('id_detail_po_gs', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Sisa Barang Dalam Order</label>
            <input type="number" name="jumlah_po" id="jumlah_po" class="form-control" readonly="">
            <?= form_error('jumlah_po', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Jumlah Barang</label>
            <input type="hidden" name="jumlah_barang_lama" id="jumlah_barang_lama" class="form-control" required="">
            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" required="" placeholder="Masukkan Jumlah Barang">
            <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Harga Barang</label>
            <input type="number" name="harga_barang" id="harga_barang" class="form-control" required="" placeholder="Masukkan Harga Barang">
            <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <!-- <div class="form-group">
              <label>Nomor Order</label>
              <input type="number" name="no_po" id="no_po" class="form-control" required="">
              <?= form_error('no_po', '<span class="text-danger small">', '</span>'); ?>
            </div> -->

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
      $('#formUser').attr('action', '<?= base_url("Invoice/tambah_detail"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    $('.btn-tambah-bayar').on('click', function() {
      $('#modalUserLabelBayar').text('Form Tambah');
      $('#formUserBayar').attr('action', '<?= base_url("Penjualan/tambah_bayar"); ?>');
      $('#formUserBayar')[0].reset();
      $('#tambah-pembayaran').modal('show');
    });

    $('#datatables-jabatan').on('click', '.btn-edit', function() {
      var id_detail_gs = $(this).data('id');
      console.log(id_detail_gs);

      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("Invoice/edit_detail"); ?>');
      $('#edit').modal('show');

      $.ajax({
        url: '<?= base_url("Invoice/get_by_id_detail"); ?>',
        data: {
          id_detail_gs: id_detail_gs
        },
        method: 'POST',
        dataType: 'json',
        success: function(data) {
          $('#gsDetailId').val(data.id_detail_gs);
          $('#no_invoice').val(data.no_invoice);
          $('#tanggal').val(data.tanggal);
          $('#jumlah_barang').val(data.jumlah_barang);
          $('#harga_barang').val(data.harga_barang);
          $('#no_po').val(data.no_po);
          $('#jumlah_po').val(data.jumlah_po);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log('Error: ' + textStatus + ' ' + errorThrown);
        }
      });
    });
  });
</script>