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
                <!-- <a href="<?= base_url('tambah-barang'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Data</a> -->
                <!-- <a href="" class="btn btn-primary mr-2" data-tippy-content="Export Data"><i class="fa fa-upload"></i> Export</a> -->
                <a href="<?= base_url('ekspor-stok-barang'); ?>" class="btn btn-primary mr-2">Export Excel</a>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <h6>Total Harga Semua Barang</h6>
                </div>
                <div class="col-md-9">
                  <h6>: <?= 'Rp' . number_format($nab['nilai_asset'], 2, ',', '.') ?></h6>
                </div><br>
                <div class="col-md-12">
                  <form action="<?= base_url('stok-barang'); ?>" method="post">
                    <div class="row">
                      <div class="col-md-6 form-group">
                        <label>Jenis</label>
                        <select name="jenis_barang" class="form-control" required>
                          <option selected disabled>-- Pilih Jenis Barang --</option>
                          <option value="All" <?= $jenis_barang == 'All' ? 'selected' : '' ?>>All</option>
                          <?php foreach ($jenis as $key => $value) { ?>
                            <option value="<?= $value ?>" <?= $jenis_barang == $value ? 'selected' : '' ?>><?= $value ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-md-6 form-group">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Kode Barang</th>
                      <th>Nama Barang</th>
                      <th>Stok Normal</th>
                      <th>Stok Indent</th>
                      <th>Harga</th>
                      <!-- <th>Status Indent</th> -->
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($barang as $u):
                    ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $u['kode_barang']; ?></td>
                        <td><?= $u['nama_barang']; ?></td>
                        <td><?= $u['stok_normal']; ?></td>
                        <td><?= $u['stok_indent']; ?></td>
                        <td><?= 'Rp' . number_format($u['harga_beli'], 2, ',', '.'); ?></td>
                        <!-- <td><?= $u['status_indent']; ?></td> -->
                        <td class="text-center">
                          <button type="button" class="btn btn-primary btn-detail" onclick="details(<?= $u['id_barang']; ?>,'<?= $u['status_indent']; ?>')" data-id="<?= $u['id_barang']; ?>" data-status="<?= $u['status_indent']; ?>">Detail dan Edit Stok</button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
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


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Konten modal diisi melalui JavaScript -->
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('template/footer2'); ?>

<script>
  var superadmin = '<?= is_super_admin(); ?>';


  function generateTableRows(stokBarang) {
    var rows = '';
    var no = 1;
    $.each(stokBarang, function(index, value) {

      var th_hargabeli = '';
      if (superadmin == 1) {
        th_hargabeli = '<td>' + new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR'
        }).format(value.harga_beli) + '</td>';
      }

      rows += '<tr>' +
        '<td class="text-center">' + no++ + '</td>' +
        '<td>' + value.stok + '</td>' +
        th_hargabeli +
        '<td>' + value.status_indent + '</td>' +
        '<td><button type="button" class="btn btn-success btn-detail" onclick="editstock(' + value.id_stok_barang + ')" data-id="' + value.id + '"  data-tippy-content="Edit Data"><i class="fa fa-edit"></i></button></td>' +
        '</tr>';
    });
    return rows;
  }

  function details(id_barang, status_indent) {
    if (id_barang) {
      $.ajax({
        url: "<?= base_url('StokBarang/getDetailBarang'); ?>",
        type: "POST",
        data: {
          id_barang: id_barang,
          status_indent: status_indent
        },
        dataType: "json",
        success: function(data) {
          if (data) {

            var th_hargabeli = '';
            if (superadmin == 1) {
              th_hargabeli = '<th>Harga Beli</th>'
            }

            $('#detail .modal-body').html(
              '<div class="row">' +
              '<div class="col-md-4"><h6>Nama Barang</h6></div>' +
              '<div class="col-md-6">: ' + data.nama_barang + '</div>' +
              '<div class="col-md-4"><h6>Semua Stok Normal</h6></div>' +
              '<div class="col-md-6">: ' + data.stok_normal + '</div>' +
              '<div class="col-md-4"><h6>Semua Stok Indent</h6></div>' +
              '<div class="col-md-6">: ' + data.stok_indent + '</div>' +
              '</div>' +
              '<div class="table-responsive">' +
              '<table class="table table-striped" id="datatables-pegawai">' +
              '<thead>' +
              '<tr>' +
              '<th class="text-center">No</th>' +
              '<th>Stok Barang</th>' +

              th_hargabeli +

              '<th>Status Indent</th>' +
              '<th>Action</th>' +
              '</tr>' +
              '</thead>' +
              '<tbody>' + generateTableRows(data.stok_barang) + '</tbody>' +
              '</table>' +
              '</div>'
            );
            $('#datatables-pegawai').DataTable();
            $('#detail').modal('show');
          } else {
            iziToast.error({
              title: 'Gagal!',
              message: 'Data tidak ditemukan!',
              position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
            });
          }
        },
        error: function() {
          iziToast.error({
            title: 'Gagal!',
            message: 'Terjadi kesalahan saat mengambil data.',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        }
      });
    } else {

      iziToast.error({
        title: 'Gagal!',
        message: 'ID Barang tidak valid.',
        position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
      });
    }
  }

  function editstock(id) {

    if (id) {
      $.ajax({
        url: "<?= base_url('StokBarang/getBarangById'); ?>", // Ganti dengan endpoint untuk mendapatkan data barang berdasarkan ID
        type: "POST",
        data: {
          id_stok_barang: id
        },
        dataType: "json",
        success: function(data) {
          if (data) {

            $('#editModal .modal-body').html(
              '<form id="editForm">' +
              '<input type="hidden" class="form-control" id="id_stok_barang" name="id_stok_barang" value="' + data.id_stok_barang + '">' +
              '<div class="form-group">' +
              '<label for="stok">Stok</label>' +
              '<input type="number" class="form-control" id="stok" name="stok" value="' + data.stok + '">' +
              '</div>' +
              '</div>' +
              '<button type="button" class="btn btn-primary" id="saveChanges">Simpan Perubahan</button>' +
              '</form>'
            );

            $('#saveChanges').click(function() {
              var formData = $('#editForm').serialize();
              $.ajax({
                url: "<?= base_url('StokBarang/updateStokBarang'); ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {


                  if (response.status == 'success') {

                    $('#editModal').modal('hide');

                    iziToast.success({
                      title: 'Sukses!',
                      message: 'Data berhasil diupdate!',
                      position: 'topCenter',
                    });

                    setTimeout(function() {
                      location.reload();
                    }, 2000);
                  } else {

                    iziToast.error({
                      title: 'Gagal!',
                      message: response.message,
                      position: 'topCenter',
                    });
                  }

                },
                error: function() {

                  iziToast.error({
                    title: 'Gagal!',
                    message: 'Terjadi kesalahan saat mengupdate data.',
                    position: 'topCenter',
                  });
                }
              });
            });

            $('#editModal').modal('show');
          } else {

            iziToast.error({
              title: 'Gagal!',
              message: 'Data tidak ditemukan.',
              position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
            });
          }
        },
        error: function() {
          iziToast.error({
            title: 'Gagal!',
            message: 'Terjadi kesalahan saat mengambil data.',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });

        }
      });
    } else {

      iziToast.error({
        title: 'Gagal!',
        message: 'ID Barang tidak valid.',
        position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
      });
    }
  }
</script>