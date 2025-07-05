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
                <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
                <a href="<?= base_url('eksport-stok-opname'); ?>" class="btn btn-primary mr-2">Export Excel</a>

              </div>
            </div>
            <div class="card-body">
              <form action="<?= base_url('stok-opname'); ?>" method="post">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <label>Jenis Barang</label>
                    <select name="jenis_barang" class="form-control search-option" required>
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
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan'); ?>
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Kode Barang</th>
                      <th style="width: 100px;">Nama Barang</th>
                      <th>Jenis Barang</th>
                      <th>Tanggal Opname</th>
                      <th style="width: 100px;">Keterangan</th>
                      <th>Stok</th>
                      <th>Jumlah</th>
                      <th>Selisih</th>
                      <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;

                    foreach ($barang as $u):
                    ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $u['kode_barang'] . '<br>Status:  <b>' . $u['status_indent'] . '</b>' ?></td>
                        <td><?= $u['nama_barang']; ?></td>
                        <td><?= $u['jenis_barang']; ?></td>
                        <td><?= date('d F Y', strtotime($u['tanggal'])); ?></td>
                        <td><?= $u['keterangan']; ?></td>
                        <td><?= $u['stok']; ?></td>
                        <td><?= $u['jumlah']; ?></td>
                        <td><?= $u['stok'] - $u['jumlah']; ?></td>
                        <td class="text-center">
                          <button type="button" class="btn btn-success btn-edit" data-id="<?= $u['id_stok_opname'] ?>"><i class="fa fa-edit"></i></button>
                          <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-stok-opname/' . $u['id_stok_opname']); ?>';"><i class="fa fa-trash"></i></button>
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
          <input type="hidden" name="id_stok_opname" id="stokOpnameId">

          <div class="form-group">
            <label>Pilih Barang</label>
            <input type="hidden" name="id_stok_barang_old" id="id_stok_barang_old">
            <input type="hidden" name="jumlah_old" id="jumlah_old">

            <select class="form-control barang search-option" name="id_stok_barang" id="id_stok_barang" data-live-search="true" required>
              <option disabled selected>-- Pilih Barang --</option>
              <?php foreach ($stokBarang as $b): ?>
                <option value="<?= $b['id_stok_barang'] ?>" <?= set_value('id_stok_barang') == $b['id_stok_barang'] ? 'selected' : ''; ?>>
                  Barang: <?= $b['nama_barang'] . ' | Stok: ' . $b['stok'] .  ' | Status: ' . $b['status_indent']; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_stok_barang', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Jumlah Barang</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= set_value('jumlah'); ?>" required="" placeholder="Masukkan Jumlah Barang">
            <?= form_error('jumlah', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Tanggal Opname</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= set_value('tanggal'); ?>" required="" placeholder="Masukkan Tanggal">
            <?= form_error('tanggal', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" value="<?= set_value('keterangan'); ?>" required="" placeholder="Masukkan Deskripsi">
            <?= form_error('keterangan', '<span class="text-danger small">', '</span>'); ?>
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
      $('#formUser').attr('action', '<?= base_url("StokOpname/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    $('.btn-edit').on('click', function() {
      var id_stok_opname = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("StokOpname/edit"); ?>');
      $('#tambah').modal('show');

      $.ajax({
        url: '<?= base_url("StokOpname/get_by_id"); ?>',
        data: {
          id_stok_opname: id_stok_opname
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          console.log(data)
          $('#stokOpnameId').val(data.id_stok_opname);
          $('#id_stok_barang').val(data.id_stok_barang).trigger('change');
          $('#jumlah').val(data.jumlah);
          $('#keterangan').val(data.keterangan);
          $('#tanggal').val(data.tanggal);

          $('#id_stok_barang_old').val(data.id_stok_barang);
          $('#jumlah_old').val(data.jumlah);
        }
      });
    });
  });
</script>