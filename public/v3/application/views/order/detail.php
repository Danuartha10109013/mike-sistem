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
                <?php if ($o['status_order_invoice'] === 'Belum Invoice') { ?>
                  <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
                <?php } ?>
              </div>
            </div>

            <div class="card-body">
              <div class="row bg-secondary text-dark rounded p-3 m-2">
                <div class="col-12 col-lg-6 row">
                  <div class="col-md-4 ">
                    <h6>Nomor Order</h6>
                  </div>
                  <div class="col-md-6">: <?= $o['no_po'] ?></div>
                  <div class="col-md-4 ">
                    <h6>Tanggal Order</h6>
                  </div>
                  <div class="col-md-6">: <?= $tanggal ?></div>
                  <div class="col-md-4 ">
                    <h6>Total</h6>
                  </div>
                  <div class="col-md-6">
                    <h6>: Rp<?= number_format($total_outstanding, 2, ',', '.') ?></h6>
                  </div>
                </div>
                <div class="col-12 col-lg-6 row">
                  <div class="col-md-4">
                    <h6>Konsumen</h6>
                  </div>
                  <div class="col-md-6">: <?= $o['nama_user'] . ' / ' . $o['kontak_customer'] ?><br><?= $o['address_customer'] ?></div>
                  <div class="col-md-4">
                    <h6>Distributor</h6>
                  </div>
                  <div class="col-md-6">: <?= $o['nama_departemen'] ?></div>
                </div>
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
                      <th>Status Indent</th>
                      <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($o_detail as $i): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $i['nama_barang'] . '<br/>' . $i['kode_barang']; ?></td>
                        <?php if ($i['type_po'] == 'Paket') { ?>
                          <td>Stok Normal: <?= $i['stok_normal']; ?> <br /> Stok Indent: <?= $i['stok_indent']; ?> </td>
                        <?php } else { ?>
                          <td><?= $i['jumlah_barang']; ?></td>
                        <?php } ?>
                        <td><?= $i['satuan_barang'] ? $i['satuan_barang'] : $i['type_po']; ?></td>
                        <td><?= 'Rp ' . number_format($i['harga_barang'], 0, ',', '.'); ?></td>
                        <td><?= 'Rp ' . number_format($i['total_harga_barang'], 0, ',', '.'); ?></td>
                        <td><?= $i['status_indent']; ?></td>
                        <td class="text-center">
                          <?php
                          $get_detail_invoice = $this->db->get_where('detail_gs', ['id_detail_po_gs' => $i['id_detail_po_gs']])->row_array();
                          ?>
                          <!-- <?php if ($o['status_order_invoice'] === 'Belum Invoice') { ?> -->
                          <!-- <button type="button" class="btn btn-success btn-edit" data-id="<?= $i['id_detail_po_gs']; ?>"><i class="fa fa-edit"></i></button> -->
                          <?php if ($i['type_po'] == 'Paket') { ?>
                            <button class="btn btn-info btn-detail" onclick="details(<?= $i['id_detail_po_gs']; ?>, <?= $i['id_package']; ?>)" data-id="<?= $i['id_detail_po_gs']; ?>"><i class="fa fa-eye"></i></button>
                          <?php } ?>
                          <?php if (!$get_detail_invoice) { ?>
                            <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-detail-order/' . $i['id_detail_po_gs'] . '/' . $i['id_po_gs']); ?>';"><i class="fa fa-trash"></i></button>
                          <?php } else { ?>
                            Sudah Masuk Invoice
                          <?php } ?>
                          <!-- <?php } else { ?>
                            Sudah Di Invoice
                          <?php } ?> -->
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col-md-2"><a href="<?= base_url('data-order'); ?>" class="btn btn-light">Kembali</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel">Form Tambah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUser" action="" method="POST">
          <input type="hidden" name="id_detail_po_gs" id="detailPoGsId">
          <input type="hidden" name="id_po_gs" id="id_po_gs" value="<?= $id_po_gs; ?>">
          <input type="hidden" id="formMode" value="tambah">
          <input type="hidden" id="barangAsli" value="">
          <input type="hidden" name="id_barang_asli" id="id_barang_asli">
          <input type="hidden" name="departemen" id="departemen" class="form-control" value="<?= $o['id_departemen']; ?>">
          <div class="form-group">
            <label>Pilih Pesanan</label>
            <select class="form-control " name="type_po" id="type_po" required>
              <option disabled selected>-- Pilih Pesanan --</option>
              <option value="Satuan">Satuan</option>
              <option value="Paket">Paket</option>

            </select>
            <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>

          <div class="form-group" id="sbarang" style="display: none;">
            <label>Pilih Barang</label>
            <input type="hidden" name="status_indent" id="status_indent_input" value="">
            <select class="form-control barang search-option" name="id_barang" id="id_barang" data-live-search="true" required>
              <option disabled selected>-- Pilih Barang --</option>
              <?php foreach ($barang as $b): ?>
                <option value="<?= $b['id_barang'] ?>" data-status-indent="<?= $b['status_indent']; ?>" <?= set_value('id_barang') == $b['id_barang'] ? 'selected' : ''; ?>><?= $b['kode_barang'] . ' | ' . $b['nama_barang'] . ' | Stok: ' . $b['stok'] . ' | ' . $b['status_indent']; ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group" id="spaket" style="display: none;">
            <label>Pilih Paket</label>
            <select class="form-control barang search-option" name="id_package" id="id_package" data-live-search="true" required>
              <option disabled selected>-- Pilih Barang --</option>
              <?php foreach ($paket as $p): ?>
                <option value="<?= $p['id_package'] ?>" <?= set_value('id_package') == $p['id_package'] ? 'selected' : ''; ?>><?= $p['package_name'] ?></option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group" id="sjumlah" style="display: none;">
            <label>Jumlah</label>
            <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" value="<?= set_value('jumlah_barang'); ?>" required="" placeholder="Masukkan Jumlah...">
            <?= form_error('jumlah_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group" id="sharga" style="display: none;">
            <label>Harga Satuan</label>
            <div id="harga">

            </div>
            <?= form_error('harga_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary" id="sub" style="display: none;">Simpan</button>
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
  var sbarang = document.getElementById('sbarang');
  var spaket = document.getElementById('spaket');
  var sjumlah = document.getElementById('sjumlah');
  var sharga = document.getElementById('sharga');
  var sub = document.getElementById('sub');

  var datapackage = <?php echo json_encode($paket) ?>;

  function ambilHarga(id_barang, url) {
    $("#harga").hide();
    $.ajax({
      type: "POST",
      url: url,
      data: {
        id_barang: id_barang
      },
      async: true,
      dataType: "JSON",
      success: function(response) {
        $("#harga").html(response.harga).show();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
    });
  }

  $("#id_barang").change(function() {
    var mode = $("#formMode").val();
    var barangAsli = $("#barangAsli").val();
    var id_barang = $(this).val();

    if (id_barang != '' && id_barang != null) {
      if (mode === "tambah") {
        ambilHarga(id_barang, "<?= base_url('Order/get_harga'); ?>");
      } else if (mode === "edit") {
        ambilHarga(id_barang, "<?= base_url('Order/get_harga'); ?>");
      }
    }

    var selectedOption = $(this).find(':selected');
    var statusIndent = selectedOption.data('status-indent');
    $('#status_indent_input').val(statusIndent);
  });

  $('#id_package').on('change', function() {

    var mode = $("#formMode").val();
    var barangAsli = $("#barangAsli").val();
    var id_package = $(this).val();

    if (id_package != '' && id_package != null) {
      var fpac = datapackage.find(item => item.id_package === id_package);
      $('#harga').html('<input type="text" name="harga_barang" class="form-control" value="' + fpac.harga_package + '" required>');
    }
  });

  $(document).ready(function() {
    $('.btn-tambah').on('click', function() {
      $('#modalUserLabel').text('Form Tambah');
      $('#formMode').val('tambah');
      $('#formUser').attr('action', '<?= base_url("Order/tambah_detail"); ?>');
      $('#formUser')[0].reset();
      $('#harga').html('');
      $('#barangAsli').val('');
      $('#id_barang').removeAttr('disabled');
      $('#tambah').modal('show');
      $('#id_barang').val('').trigger('change');
      $('#id_package').val('').trigger('change');


      sbarang.style.display = "none";
      sjumlah.style.display = "none";
      sharga.style.display = "none";
      sub.style.display = "none";
    });

    $('#type_po').on('change', function() {

      $('#harga').html('');
      $('#barangAsli').val('');
      $('#jumlah_barang').val('');
      $('#id_barang').val('').trigger('change');
      $('#id_package').val('').trigger('change');

      if ($(this).val() == 'Paket') {
        sbarang.style.display = "none";
        spaket.style.display = "block";

        $('#id_barang').removeAttr('required');
        $('#id_package').attr('required', 'required');
      } else {

        sbarang.style.display = "block";
        spaket.style.display = "none";

        $('#id_barang').attr('required', 'required');
        $('#id_package').removeAttr('required');

      }
      sjumlah.style.display = "block";
      sharga.style.display = "block";
      sub.style.display = "block";

    });

    $('.btn-edit').on('click', function() {
      var id_detail_po_gs = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formMode').val('edit');
      $('#formUser').attr('action', '<?= base_url("Order/edit_detail"); ?>');
      $('#tambah').modal('show');

      $.ajax({
        url: '<?= base_url("Order/get_by_id_detail"); ?>',
        data: {
          id_detail_po_gs: id_detail_po_gs
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          if (data.type_po === 'Paket') {
            sbarang.style.display = "none";
            spaket.style.display = "block";

            $('#id_barang').removeAttr('required');
            $('#id_package').attr('required', 'required');
          } else {

            sbarang.style.display = "block";
            spaket.style.display = "none";

            $('#id_barang').attr('required', 'required');
            $('#id_package').removeAttr('required');

          }

          sjumlah.style.display = "block";
          sharga.style.display = "block";
          sub.style.display = "block";

          $('#type_po').val(data.type_po);
          $('#id_package').val(data.id_package).trigger('change');
          $('#detailPoGsId').val(data.id_detail_po_gs);
          $('#id_barang').val(data.id_barang).trigger('change');
          $('#jumlah_barang').val(data.jumlah_barang);
          $('#departemen').val(data.departemen).trigger('change');
          $('#barangAsli').val(data.id_barang);
          $('#id_barang_asli').val(data.id_barang);

          // $('#id_barang').prop('disabled', true).selectpicker('refresh');
          console.log(data.harga_barang);
          // $('#harga').html('<input type="text" name="harga_barang" class="form-control" value="' + data.harga_barang + '" required>');
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
        '<td>' + (parseInt(value.stok_indent) + parseInt(value.stok_normal)) + '</td>' +
        '</tr>';
    });
    return rows;
  }

  function details(id_detail_po_gs, id_package) {
    if (id_detail_po_gs) {

      $.ajax({
        url: "<?= base_url('Order/get_detail_stok'); ?>",
        type: "POST",
        data: {
          id_detail_po_gs: id_detail_po_gs,
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