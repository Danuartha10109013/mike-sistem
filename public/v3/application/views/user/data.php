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
                      <th>Nama Lengkap</th>
                      <th>Email</th>
                      <th>Username</th>
                      <th>Nomor Telepon</th>
                      <th>Role</th>
                      <th class="text-center" style="width: 200px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($user as $u): ?>
                      <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $u['nama']; ?></td>
                        <td><?= $u['email']; ?></td>
                        <td><?= $u['username']; ?></td>
                        <td><?= $u['no_hp']; ?></td>
                        <td><?= $u['role']; ?></td>
                        <td class="text-center">
                          <button type="button" class="btn btn-success btn-edit" data-id="<?= $u['id_user']; ?>" data-tippy-content="Edit Data"><i class="fa fa-edit"></i></button>
                          <?php if ($get_user['id_user'] !== $u['id_user']) { ?>
                            <button class="btn btn-danger" data-confirm="Apakah Anda yakin akan hapus data ini?" data-confirm-yes="document.location.href='<?= base_url('hapus-user/' . $u['id_user']); ?>';" data-tippy-content="Hapus Data"><i class="fa fa-trash"></i></button>
                          <? } else { ?>
                            <br> Akun Anda
                          <?php } ?>
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
          <input type="hidden" name="id_user" id="userId">

          <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
          </div>
          <div class="form-group">
            <label for="nomorTelepon">Nomor Telepon</label>
            <input type="number" name="no_hp" id="nomorTelepon" class="form-control" placeholder="Masukkan Nomor Telepon" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email" required>
          </div>
          <div class="form-group">
            <label for="role">Role</label>
            <select name="role[]" class="form-control select-user" id="role" data-live-search="true" multiple>
              <option value="Super_Admin">Super Admin</option>
              <option value="Admin">Admin</option>
              <option value="Finance">Finance</option>
            </select>
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan Username" required>
          </div>
          <div class="form-group password-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password">
          </div>
          <div class="form-group password-group">
            <label for="password2">Konfirmasi Password</label>
            <input type="password" name="password2" id="password2" class="form-control" placeholder="Konfirmasi Password">
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
      $('#formUser').attr('action', '<?= base_url("user/tambah"); ?>');
      $('#formUser')[0].reset();
      $('#tambah').modal('show');
      $('.password-group').show();

      $('#password').attr('required', true);
      $('#password2').attr('required', true);
    });

    $(document).on('click', '.btn-edit', function() {
      var id_user = $(this).data('id');
      $('#modalUserLabel').text('Form Edit');
      $('#formUser').attr('action', '<?= base_url("user/edit"); ?>');
      $('#tambah').modal('show');
      // $('.password-group').hide();
      $('.password-group').show();

      $('#password').removeAttr('required');
      $('#password2').removeAttr('required');

      $.ajax({
        url: '<?= base_url("user/get_user"); ?>',
        data: {
          id_user: id_user
        },
        method: 'post',
        dataType: 'json',
        success: function(data) {
          $('#userId').val(data.id_user);
          $('#nama').val(data.nama);
          $('#nomorTelepon').val(data.no_hp);
          $('#email').val(data.email);
          $('#username').val(data.username);
          // console.log(data.role)
          // $('#role').val(data.role).trigger('change');
          // $.each(data.role.split(", "), function(i,e){
          //   $("#role option[value='" + e + "']").prop("selected", true);
          // });


          var rolesFromData = data.role.split(',').map(role => role.trim());

          $('.selectpicker').selectpicker();
          $('#role').selectpicker('val', rolesFromData);

          $('#role').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = $(this).val(); // Get selected options
            $('#output').text(`Selected roles: ${selectedRoles.join(',')}`);
          });
        }
      });
    });
  });
</script>