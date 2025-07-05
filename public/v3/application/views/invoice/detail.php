<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4><?= $title2; ?></h4>
              <div class="card-header-action">
                <?php if ($f['status_gs'] === 'Belum Selesai') { ?>
                  <button type="button" class="btn btn-primary" data-confirm="Apakah Anda yakin akan selesaikan invoice ini?" data-confirm-yes="document.location.href='<?= base_url('update-status-invoice/' . $f['id_gs'] . '/selesai'); ?>';">Selesaikan Invoice</button>
                  <button type="button" class="btn btn-primary" data-target="#diskon" data-toggle="modal">Diskon</button>
                  <!-- <button type="button" class="btn btn-primary" data-target="#driver" data-toggle="modal">Driver</button> -->
                <?php } else { ?>
                  <button type="button" class="btn btn-primary" data-confirm="Apakah Anda yakin akan mengembalikan invoice ini?" data-confirm-yes="document.location.href='<?= base_url('update-status-invoice/' . $f['id_gs'] . '/kembali'); ?>';">Kembalikan Invoice</button>
                <?php } ?>

                <a href="<?= base_url('cetak-detail-surat-jalan-stiker/' . $f['id_gs']); ?>" target="_blank" class="btn btn-primary">Cetak Surat Jalan Stiker </a>
                <a href="<?= base_url('cetak-detail-invoice/' . $f['id_gs']); ?>" target="_blank" class="btn btn-primary">Cetak Invoice</a>
                <a href="<?= base_url('cetak-detail-surat-jalan/' . $f['id_gs']); ?>" target="_blank" class="btn btn-primary">Cetak Surat Jalan</a>
                <?php if ($f['status_gs'] === 'Belum Selesai') { ?>
                  <a href="<?= base_url('tambah-detail-invoice/'.$f['id_gs'])?>" class="btn btn-primary">Tambah</a>
                <?php } ?>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-2">
                  <h6>Nomor Invoice</h6>
                </div>
                <div class="col-md-10">: <?= $f['no_invoice'] ?></div>
                <div class="col-md-2">
                  <h6>Tanggal Invoice</h6>
                </div>
                <div class="col-md-10">: <?= $tanggal ?></div>
                <div class="col-md-2">
                  <h6>Driver</h6>
                </div>
                <div class="col-md-10">: <?= $f['driver'] ? $f['driver'] : '' ?></div>
              </div>
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan'); ?>
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nama Barang</th>
                      <th>Jumlah Barang</th>
                      <th>Satuan Barang</th>
                      <th>Harga Barang</th>
                      <th>Total</th>
                      <th>Nomor Order</th>
                      <th>Agen</th>
                      <th>Status Indent</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($f_detail as $i): ?>
                      <?php if ($i['tipe_item'] === 'Diskon') { ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td colspan="4" class="text-center"><?= $i['nama_barang'];; ?></td>
                          <td><?= str_replace('-', '', number_format($i['total_harga_barang'], 0, ',', '.')); ?></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-center">
                            <?php if ($f['status_gs'] === 'Sudah Selesai') { ?>
                              Invoice Selesai
                            <?php } else { ?>
                              <?php if ($i['tipe_item'] !== 'Diskon') { ?>
                                <a href="<?= base_url('edit-detail-invoice/' . $i['id_detail_gs'] . '/' . $f['id_gs']); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                              <?php } ?>
                              <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-detail-invoice/' . $i['id_detail_gs'] . '/' . $f['id_gs'] . '/diskon'); ?>';"><i class="fa fa-trash"></i></button>
                            <?php } ?>
                          </td>
                        </tr>
                      <?php } else { ?>
                        <tr>
                          <td class="text-center"><?= $no++; ?></td>
                          <td><?= $i['nama_barang'] . '<br/>' . $i['kode_barang']; ?></td>
                          <?php if ($i['type_po'] == 'Paket') {?>
                            <td>Stok Normal: <?= $i['stok_normal']; ?> <br/> Stok Indent: <?= $i['stok_indent']; ?> </td>
                          <?php } else { ?>
                            <td><?= $i['jumlah_barang']; ?></td>
                          <?php } ?>
                          <td><?= $i['satuan_barang'] ? $i['satuan_barang'] : 'Paket'; ?></td>
                          <td><?= number_format($i['harga_barang'], 0, ',', '.'); ?></td>
                          <td><?= number_format($i['total_harga_barang'], 0, ',', '.'); ?></td>
                          <td><?= $i['no_po']; ?></td>
                          <td><?= $i['nama_departemen'] == null ? $i['departemen'] : $i['nama_departemen']; ?></td>
                          <td><?= $i['status_indent']; ?></td>
                          <td class="text-center">
                            <!-- <?php if ($i['nama_barang'] == 'PPn 10%' || $i['nama_barang'] == 'PPn 11%'): ?>
                            <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('Invoice/delete_ppn/' . $i['id_gs']); ?>';"><i class="fa fa-trash"></i></button>
                          <?php else: ?>
                            <button type="button" class="btn btn-success btn-edit" data-id="<?= $i['id_detail_gs']; ?>"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-detail-invoice/' . $i['id_detail_gs'] . '/' . $f['id_gs']); ?>';"><i class="fa fa-trash"></i></button>
                          <?php endif; ?> -->
                            <?php if ($f['status_gs'] === 'Sudah Selesai') { ?>
                              Invoice Selesai
                            <?php } else { ?>
                              <?php if ($i['type_po'] == 'Paket') {?>
                                <button class="btn btn-info btn-detail" onclick="details(<?= $i['id_detail_gs']; ?>, <?= $i['id_package']; ?>)" data-id="<?= $i['id_detail_gs']; ?>"><i class="fa fa-eye"></i></button>
                              <?php } ?>
                              <!-- <?php if ($i['tipe_item'] !== 'Diskon') { ?>
                                <a href="<?= base_url('edit-detail-invoice/' . $i['id_detail_gs'] . '/' . $f['id_gs']); ?>" class="btn btn-success"><i class="fa fa-edit"></i></a>
                              <?php } ?> -->
                              <?php if ($i['tipe_item'] !== 'Diskon') { ?>
                                <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-detail-invoice/' . $i['id_detail_gs'] . '/' . $f['id_gs'] . '/bukan'); ?>';"><i class="fa fa-trash"></i></button>
                              <?php } else { ?>
                                <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-detail-invoice/' . $i['id_detail_gs'] . '/' . $f['id_gs'] . '/diskon'); ?>';"><i class="fa fa-trash"></i></button>
                              <?php } ?>
                            <?php } ?>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-md-2"><a href="<?= base_url('invoice'); ?>" class="btn btn-light">Kembali</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="diskon" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel">Form Diskon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="diskonModal" action="<?= base_url('tambah-diskon-invoice') ?>" method="POST">
          <input type="hidden" name="id_gs" id="id_gs" value="<?= $id_gs; ?>">
          <div class="form-group">
            <label>Nominal Diskon (Contoh: 1000)</label>
            <input type="number" name="harga_barang" id="harga_barang" class="form-control" required="" placeholder="Masukkan Diskon...">
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

<div class="modal fade" id="driver" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel">Form Driver</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="driverModal" action="<?= base_url('tambah-driver-invoice') ?>" method="POST">
          <input type="hidden" name="id_gs" id="id_gs" value="<?= $id_gs; ?>">
          <div class="form-group">
            <label>Nama Driver</label>
            <input type="text" name="driver" id="driver" value="<?= $f['driver'] ? $f['driver'] : '' ?>" class="form-control" required="" placeholder="Masukkan Nama Driver...">
            <?= form_error('driver', '<span class="text-danger small">', '</span>'); ?>
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
                <option value="<?= $b['id_detail_po_gs'] ?>" <?= set_value('id_detail_po_gs') == $b['id_detail_po_gs'] ? 'selected' : ''; ?>><?= $b['nama_barang'] . ' | No. PO ' . $get_po['no_po'] . ' | '.$b['status_indent']; ?></option>
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

<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel">Detail Stok</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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

  function generateTableRows(data) {
    var rows = '';
    var no = 1;
    $.each(data, function(index, value) {

      rows += '<tr>' +
          '<td class="text-center">' + no++ + '</td>' +
          '<td>' + value.nama_barang + '</td>' +
          '<td>' + value.stok_indent + '</td>' +
          '<td>' + value.stok_normal + '</td>' +
          '<td>' + (parseInt(value.stok_indent)+parseInt(value.stok_normal)) + '</td>' +
        '</tr>';
    });
    return rows;
  }

  function details(id_detail_gs, id_package) {
    if (id_detail_gs) {
      
      $.ajax({
        url: "<?= base_url('Invoice/get_detail_stok'); ?>",
        type: "POST",
        data: {
          id_detail_gs: id_detail_gs,
          id_package: id_package
        },
        dataType: "json",
        success: function(data) {
          function numberFormat(number, decimals = 0, decPoint = ',', thousandsSep = '.') {
            const parts = number.toFixed(decimals).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);
            return parts.join(decPoint);
          }
          if (data) {
            let total_stok = 0
            for (const item of data.detail_stok) {
              total_stok += parseInt(item.total_stok)
            }
            $('#detail .modal-body').html(
              '<div class="row">' +
              '<div class="col-md-4"><h6>Nama Paket</h6></div>' +
              '<div class="col-md-6">: ' + data.detail_stok[0].package_name + '</div>' +
              '<div class="col-md-4"><h6>Harga Paket</h6></div>' +
              '<div class="col-md-6">: ' + new Intl.NumberFormat('id-ID', {
                  style: 'currency',
                  currency: 'IDR'
                }).format(data.detail_stok[0].total_harga_barang) + '</div>' +
              '<div class="col-md-4"><h6>Total Order</h6></div>' +
              '<div class="col-md-6">: ' + total_stok + '</div>' +
              '</div>' +
              '<div class="table-responsive">' +
              '<table class="table table-striped" id="datatables-pegawai">' +
              '<thead>' +
                '<tr>' +
                  '<th class="text-center">No</th>' +
                  '<th>Nama Barang</th>' +
                  '<th>Indent</th>' +
                  '<th>Normal</th>' +
                  '<th>Total</th>' +
                '</tr>' +
              '</thead>' +
              '<tbody>' + generateTableRows(data.detail_stok) + '</tbody>' +
              '</table>' +
              '</div>'
            );
            $('#datatables-pegawai').DataTable();
            $('#detail').modal('show');
          } else {
            iziToast.error({
              title: 'Gagal!',
              message: 'Data tidak ditemukan!',
              position: 'topCenter',
            });
          }
        },
        error: function() {
          iziToast.error({
            title: 'Gagal!',
            message: 'Terjadi kesalahan saat mengambil data.',
            position: 'topCenter',
          });
        }
      });
    } else {

      iziToast.error({
        title: 'Gagal!',
        message: 'ID Barang tidak valid.',
        position: 'topCenter',
      });
    }
  }
</script>