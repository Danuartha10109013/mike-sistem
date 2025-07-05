      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2020 <div class="bullet"></div> Dibuat dan Dikembangkan oleh <a href="#">Muhamad Ramadhan</a>
        </div>
        <div class="footer-right">
          Version 1.0
        </div>
      </footer>
      </div>
      </div>

      <!-- General JS Scripts -->
      <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
      <script src="<?= base_url(); ?>assets/js/stisla.js"></script>

      <!-- JS Libraies -->
      <script src="<?= base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
      <script src="<?= base_url(); ?>assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
      <script src="<?= base_url(); ?>assets/vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
      <script type="text/javascript" src="<?php echo base_url() . 'assets/vendor/izitoast/js/iziToast.min.js' ?>"></script>

      <!-- Template JS File -->
      <script src="<?= base_url(); ?>assets/js/scripts.js"></script>
      <script src="<?= base_url(); ?>assets/js/custom.js"></script>

      <!-- Page Specific JS File -->
      <script src="<?= base_url(); ?>assets/js/page/index-0.js"></script>
      <script type="text/javascript">
        $(document).ready(function() { // Ketika halaman sudah siap (sudah selesai di load)
          $('#barang').selectpicker({
            search: true,
          });

          $("#barang").change(function() { // Ketika user mengganti atau memilih data provinsi
            $("#harga").hide(); // Sembunyikan dulu combobox kota nya

            $.ajax({
              type: "POST", // Method pengiriman data bisa dengan GET atau POST
              url: "<?= base_url("Fabrikasi/get_harga"); ?>", // Isi dengan url/path file php yang dituju
              data: {
                id_barang: $("#barang").val()
              },
              async: true,
              dataType: "JSON",
              beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                $("#harga").html(response.harga).show();
              },
              error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
            });
          });
        });

        $(document).ready(function() { // Ketika halaman sudah siap (sudah selesai di load)
          $('#barang-pbo').selectpicker({
            search: true,
          });

          $("#barang-pbo").change(function() { // Ketika user mengganti atau memilih data provinsi
            $("#amount").hide(); // Sembunyikan dulu combobox kota nya

            $.ajax({
              type: "POST", // Method pengiriman data bisa dengan GET atau POST
              url: "<?= base_url("Fabrikasi/get_harga_for_pbo"); ?>", // Isi dengan url/path file php yang dituju
              data: {
                id_barang: $("#barang-pbo").val()
              },
              async: true,
              dataType: "JSON",
              beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                console.log(response)
                $("#deskripsi").val(response.deskripsi);
                $("#uom").val(response.uom);
                $("#value-amount").val(response.value_amount);
                $("#amount").html(response.amount).show();
              },
              error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
            });
          });
        });

        $(document).ready(function() { // Ketika halaman sudah siap (sudah selesai di load)
          $('#detail_po_fabrikasi').selectpicker({
            search: true,
          });

          $("#detail_po_fabrikasi").change(function() { // Ketika user mengganti atau memilih data provinsi
            $("#jumlah_po").hide(); // Sembunyikan dulu combobox kota nya
            $("#harga").hide(); // Sembunyikan dulu combobox kota nya
            $("#no_po").hide(); // Sembunyikan dulu combobox kota nya

            $.ajax({
              type: "POST", // Method pengiriman data bisa dengan GET atau POST
              url: "<?= base_url("Fabrikasi/get_detail_po_fabrikasi"); ?>", // Isi dengan url/path file php yang dituju
              data: {
                id_detail_po_fabrikasi: $("#detail_po_fabrikasi").val()
              },
              async: true,
              dataType: "JSON",
              beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                $("#jumlah_po").html(response.jumlah_po).show();
                $("#harga").html(response.harga).show();
                $("#no_po").html(response.no_po).show();
              },
              error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
            });
          });
        });
        $(document).ready(function() { // Ketika halaman sudah siap (sudah selesai di load)
          $('#detail_po_cs').selectpicker({
            search: true,
          });

          $('#detail_po_gs').selectpicker({
            search: true,
          });

          $("#detail_po_cs").change(function() { // Ketika user mengganti atau memilih data provinsi
            $("#jumlah_po").hide(); // Sembunyikan dulu combobox kota nya
            $("#harga").hide(); // Sembunyikan dulu combobox kota nya
            $("#no_po").hide(); // Sembunyikan dulu combobox kota nya

            $.ajax({
              type: "POST", // Method pengiriman data bisa dengan GET atau POST
              url: "<?= base_url("Cs/get_detail_po_cs"); ?>", // Isi dengan url/path file php yang dituju
              data: {
                id_detail_po_cs: $("#detail_po_cs").val()
              },
              async: true,
              dataType: "JSON",
              beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                $("#jumlah_po").html(response.jumlah_po).show();
                $("#harga").html(response.harga).show();
                $("#no_po").html(response.no_po).show();
              },
              error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
            });
          });

          $("#detail_po_gs").change(function() { // Ketika user mengganti atau memilih data provinsi
            $("#jumlah_po").hide(); // Sembunyikan dulu combobox kota nya
            $("#harga").hide(); // Sembunyikan dulu combobox kota nya
            $("#no_po").hide(); // Sembunyikan dulu combobox kota nya

            $.ajax({
              type: "POST", // Method pengiriman data bisa dengan GET atau POST
              url: "<?= base_url("Gs/get_detail_po_gs"); ?>", // Isi dengan url/path file php yang dituju
              data: {
                id_detail_po_gs: $("#detail_po_gs").val()
              },
              async: true,
              dataType: "JSON",
              beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response) { // Ketika proses pengiriman berhasil
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                $("#jumlah_po").html(response.jumlah_po).show();
                $("#harga").html(response.harga).show();
                $("#no_po").html(response.no_po).show();
              },
              error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
            });
          });
        });
      </script>
      <script type="text/javascript">
        $(document).ready(function() {
          $('#datatables-user').DataTable({
            "ordering": false,
          });
          $('#datatables-pegawai').DataTable({
            "ordering": false,
          });
          $('#datatables-jabatan').DataTable({
            "ordering": false,
          });
          $('#datatables-bidang').DataTable({
            "ordering": false,
          });
          $('#datatables-golongan').DataTable({
            "ordering": false,
          });
          $('#datatables-cuti').DataTable({
            "ordering": false,
          });
          $('#datatables-izin').DataTable({
            "ordering": false,
          });

          $('#select-golruang').selectpicker({
            search: true,
          });
          $('#select-user').selectpicker({
            search: true,
          });
          $('#select-bidang').selectpicker({
            search: true,
          });
          $('#select-jabatan').selectpicker({
            search: true,
          });
          $('#select-atasan').selectpicker({
            search: true,
          });
          $('#select-pejabat').selectpicker({
            search: true,
          });
          $('#select-pegawai').selectpicker({
            search: true,
          });
        });
      </script>

<script>
    function del(params) {
      var me = params,
      me_data = me.data('confirm');

      me_data = me_data.split("|");
      me.fireModal({
        title: me_data[0],
        body: me_data[1],
        buttons: [
          {
            text: me.data('confirm-text-yes') || 'Yes',
            class: 'btn btn-danger btn-shadow',
            handler: function() {
              eval(me.data('confirm-yes'));
            }
          },
          {
            text: me.data('confirm-text-cancel') || 'Cancel',
            class: 'btn btn-secondary',
            handler: function(modal) {
              $.destroyModal(modal);
              eval(me.data('confirm-no'));
            }
          }
        ]
      })
    }

    function training(params) {
      var me = params,
      me_data = me.data('confirm');

      me_data = me_data.split("|");
      me.fireModal({
        title: me_data[0],
        body: me_data[1],
        buttons: [
          {
            text: me.data('confirm-text-yes') || 'Yes',
            class: 'btn btn-success btn-shadow',
            handler: function() {
              eval(me.data('confirm-yes'));
            }
          },
          {
            text: me.data('confirm-text-cancel') || 'Cancel',
            class: 'btn btn-secondary',
            handler: function(modal) {
              $.destroyModal(modal);
              eval(me.data('confirm-no'));
            }
          }
        ]
      })
    }
  </script>

      <?php if ($this->session->flashdata('msg') == 'success') : ?>
        <script type="text/javascript">
          iziToast.success({
            title: 'Sukses!',
            message: 'Data berhasil disimpan!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'error') : ?>
        <script type="text/javascript">
          iziToast.error({
            title: 'Gagal!',
            message: 'Data gagal disimpan!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'duplicate-po') : ?>
        <script type="text/javascript">
          iziToast.error({
            title: 'Gagal!',
            message: 'NO PO Sudah ada! Silahkan cek kembali.',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'error_jml_barang') : ?>
        <script type="text/javascript">
          iziToast.error({
            title: 'Gagal!',
            message: 'Jumlah Barang Melebihi Outstanding atau kurang dari nol!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'existing-item') : ?>
        <script type="text/javascript">
          iziToast.error({
            title: 'Gagal!',
            message: 'Kode barang sudah terdaftar!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'error-stok') : ?>
        <script type="text/javascript">
          iziToast.error({
            title: 'Gagal!',
            message: 'Jumlah Barang Melebihi Stok! Silahkan cek Stok Barang Terlebih dahulu!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'edit') : ?>
        <script type="text/javascript">
          iziToast.info({
            title: 'Sukses!',
            message: 'Data berhasil diedit!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'hapus') : ?>
        <script type="text/javascript">
          iziToast.success({
            title: 'Sukses!',
            message: 'Data berhasil dihapus!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'password-salah') : ?>
        <script type="text/javascript">
          iziToast.error({
            title: 'Gagal!',
            message: 'Password Lama Salah!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php elseif ($this->session->flashdata('msg') == 'verifikasi') : ?>
        <script type="text/javascript">
          iziToast.success({
            title: 'Sukses!',
            message: 'Data berhasil diverifikasi!',
            position: 'topCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          });
        </script>
      <?php endif; ?>
      </body>

      </html>