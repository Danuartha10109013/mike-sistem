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
                <button class="btn btn-primary btn-tambah">Tambah</button>
              </div>
            </div>

            <div class="card-body">
              <form action="<?= base_url('barang-masuk'); ?>" method="post">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <label>Jenis Barang</label>
                    <select name="jenis_barang" class="form-control" required>
                      <option selected disabled>-- Pilih Jenis Barang --</option>
                      <option value="All" <?= $jenis_barang == 'All' ? 'selected' : '' ?>>All</option>
                      <?php foreach ($jenis as $key => $value) { ?>
                        <option value="<?= $value ?>" <?= $jenis_barang == $value ? 'selected' : '' ?>><?= $value ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-md-3 form-group">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                  </div>
                  <div class="col-md-3 ">
                    <label>&nbsp;</label><br>
                    <a href="<?= base_url('barang-masuk'); ?>" class="btn btn-warning"><i class="fa fa-undo"></i> Reset</a>
                  </div>
                </div>
              </form>
              <div class="table-responsive">
                <?= $this->session->flashdata('pesan'); ?>
                <table class="table table-striped" id="datatables-jabatan">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Nama Barang</th>
                      <th>Satuan</th>
                      <th>Jumlah Barang</th>
                      <?php if (is_super_admin()): ?>
                        <th>Harga Beli</th>
                      <?php endif; ?>
                      <th>Jenis Barang</th>
                      <th>Tanggal Masuk</th>
                      <th>Status Indent</th>
                      <?php if (is_super_admin()): ?>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                      <?php endif; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($barangMasuk as $u): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $u['nama_barang']; ?></td>
                        <td><?= $u['satuan_barang']; ?></td>
                        <td><?= $u['jumlah']; ?></td>
                        <?php if (is_super_admin()): ?>
                          <td><?= 'Rp' . number_format($u['harga_beli'], 2, ',', '.'); ?></td>
                        <?php endif; ?>
                        <td><?= $u['jenis_barang']; ?></td>
                        <td><?= date('d F Y', strtotime($u['tanggal_masuk'])); ?></td>
                        <td><?= $u['status_indent'] ?></td>
                        <?php if (is_super_admin()): ?>
                          <td class="text-center">
                            <button type="button" class="btn btn-success btn-edit" data-id="<?= $u['id_barang_masuk']; ?>" data-tippy-content="Edit Data"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-barang-masuk/' . $u['id_barang_masuk']); ?>';" data-tippy-content="Hapus Data"><i class="fa fa-trash"></i></button>
                          </td>
                        <?php endif; ?>
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
          <input type="hidden" name="id_barang_masuk" id="barangMasukId">

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
            <label>Jumlah Barang</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= set_value('jumlah'); ?>" required="" placeholder="Masukkan Jumlah Barang">
            <?= form_error('jumlah', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <?php if (is_super_admin()): ?>
            <div class="form-group">
              <label>Harga Beli</label>
              <input type="number" name="harga_beli" id="harga_beli" class="form-control" min="0" value="<?= set_value('harga_beli') ? set_value('harga_beli') : 0; ?>" required="" placeholder="Masukkan Harga Beli">
              <?= form_error('harga_beli', '<span class="text-danger small">', '</span>'); ?>
            </div>
          <?php endif; ?>
          <div class="form-group">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="<?= set_value('tanggal_masuk'); ?>" required="" placeholder="Masukkan Tanggal Masuk">
            <?= form_error('tanggal_masuk', '<span class="text-danger small">', '</span>'); ?>
          </div>
          <div class="form-group">
            <label>Pilih Status Indent</label>
            <select class="form-control barang" name="status_indent" id="status_indent" data-live-search="true" required>
              <option disabled selected>-- Pilih --</option>
              <option value="Normal">Normal</option>
              <option value="Indent Masuk">Indent Masuk</option>
            </select>
            <?= form_error('status_indent', '<span class="text-danger small">', '</span>'); ?>
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
      $('#formUser').attr('action', '<?= base_url("BarangMasuk/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    $(document).on('click', '.btn-edit', function() {
      console.log('MASUKKK')
      var id_barang_masuk = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("BarangMasuk/edit"); ?>');
      $('#tambah').modal('show');

      $.ajax({
        url: '<?= base_url("BarangMasuk/get_by_id"); ?>',
        data: {
          id_barang_masuk: id_barang_masuk
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#barangMasukId').val(data.id_barang_masuk);
          $('#id_barang').val(data.id_barang).trigger('change');
          $('#jumlah').val(data.jumlah);
          $('#harga_beli').val(data.harga_beli);
          $('#tanggal_masuk').val(data.tanggal_masuk);
          $('#status_indent').val(data.status_indent);
        }
      });
    });
  });
</script>