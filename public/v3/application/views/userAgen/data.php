<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>
<?php
$id_user = $this->session->userdata('id_user');
$get_user = $this->db->get_where('user', ['id_user' => $id_user])->row_array();
?>

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
              </div>
            </div>
            <div class="card-body">
              <?= $this->session->flashdata('pesan'); ?>
              <div class="table-responsive">
                <table class="table table-striped" id="datatables-user">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Nama Distributor</th>
                      <th>Poin</th>
                      <th>Telepon</th>
                      <th style="width: 300px !important;">Alamat</th>
                      <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($departemen as $u): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $u['nama_departemen']; ?></td>
                        <td><?= $u['point']; ?> Poin</td>
                        <td><?= $u['phone_departemen']; ?> </td>
                        <td><?= $u['address_departemen']; ?> </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-success btn-edit" data-id="<?= $u['id_departemen']; ?>" data-tippy-content="Edit Data"><i class="fa fa-edit"></i></button>
                          <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-user-agen/' . $u['id_departemen']); ?>';" data-tippy-content="Hapus Data"><i class="fa fa-trash"></i></button>
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
          <input type="hidden" name="id_departemen" id="departemenId">

          <div class="form-group">
            <label for="nama_departemen">Nama Distributor</label>
            <input type="text" name="nama_departemen" id="nama_departemen" class="form-control" placeholder="Masukkan Nama Distributor" required>
          </div>
          <div class="form-group">
            <label for="phone_departemen">No. Telepon</label>
            <input type="text" name="phone_departemen" id="phone_departemen" class="form-control" placeholder="Masukkan Telepon  Distributor" required>
          </div>
          <div class="form-group">
            <label for="address_departemen">Alamat</label>
            <textarea type="text" name="address_departemen" id="address_departemen" class="form-control" style="min-height:80px !important" placeholder="Masukkan Nama Distributor" required></textarea>
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
      $('#modalUserLabel').text('Form Tambah Distributor');
      $('#formUser').attr('action', '<?= base_url("UserAgen/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
    });

    $(document).on('click', '.btn-edit', function() {
      var id_departemen = $(this).data('id');
      $('#modalUserLabel').text('Form Edit Distributor');
      $('#formUser').attr('action', '<?= base_url("UserAgen/edit"); ?>');
      $('#tambah').modal('show');

      $.ajax({
        url: '<?= base_url("UserAgen/get_by_id"); ?>',
        data: {
          id_departemen: id_departemen
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#departemenId').val(data.id_departemen);
          $('#nama_departemen').val(data.nama_departemen);
          $('#phone_departemen').val(data.phone_departemen);
          $('#address_departemen').val(data.address_departemen);
        }
      });
    });
  });
</script>