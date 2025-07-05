<?php $this->load->view('template/header2'); ?>

<?php $this->load->view('template/sidebar2'); ?>

<!-- Main Content -->

<div class="main-content">



  <div class="section-body">

    <div class="row">

      <div class="col-12">

        <div class="card">

          <form action="<?= base_url('tracking-barang'); ?>" method="post">

            <div class="card-header">

              <h4><?= $title ?></h4>

            </div>

            <div class="card-body">
              <div class="row">

                <div class="form-group col-6 mb-0">

                  <label>Pilih Barang</label>

                  <select class="form-control barang" name="id_barang" id="barang" data-live-search="true">

                    <option disabled selected>-- Pilih Barang --</option>

                    <?php foreach ($barang as $bar): ?>

                      <option value="<?= $bar['id_barang'] ?>" <?= set_value('id_barang') == $bar['id_barang'] ? 'selected' : ''; ?>><?= $bar['kode_barang'] . ' - ' . $bar['nama_barang']; ?></option>

                    <?php endforeach; ?>

                  </select>

                  <?= form_error('id_barang', '<span class="text-danger small">', '</span>'); ?>

                </div>
                <div class="col-3 row align-content-end pb-1">
                  <button type="submit" name="filter" value="filter" class="btn btn-primary w-100"><i class="fa fa-eye"></i> Detail</button>
                </div>
                <div class="col-3 row align-content-end pb-1">
                  <a href="" class="btn btn-warning w-100"><i class="fa fa-repeat"></i> Reset</a>
                </div>
              </div>

            </div>

            <div class="card-footer ">

              <div class=" text-start mb-2">

                <?php if (!empty($b)) { ?>
                  <button type="submit" name="cetak" value="cetak" formtarget="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Cetak</button>
                <?php } ?>

              </div>
              <div class="row">

                <?php if (!empty($b)) { ?>

                  <div class="row m-2 p-2 col-12">

                    <div class="col-md-4">
                      <h6>Nama Barang</h6>
                    </div>

                    <div class="col-md-8">: <?= $b['nama_barang']; ?></div>

                    <div class="col-md-4">
                      <h6>Stok Terbaru</h6>
                    </div>

                    <div class="col-md-8">: <?= $stok['stok'] == null ? 0 : $stok['stok']; ?></div>

                  </div>
                  <?php if (!empty($stok) && !empty($baranglists)) { ?>
                    <div class="row m-2 p-2 col-12">

                      <div class="table-responsive">

                        <table class="table table-striped" id="datatables-user">

                          <thead>

                            <tr>

                              <th class="text-center">#</th>

                              <th>Tanggal</th>

                              <th>Masuk</th>

                              <th>Keluar</th>

                              <th>Nomor Order</th>
                              <th>Ref</th>
                              <th>Customer</th>
                              <th>Driver</th>

                            </tr>

                          </thead>

                          <tbody>

                            <?php

                            $no = 1;

                            $total = 0;

                            foreach ($baranglists as $i):
                            ?>

                              <tr>

                                <td class="text-center"><?= $no++; ?></td>

                                <td><?= $i['tanggal']; ?></td>

                                <td><?= $i['masuk']; ?></td>

                                <td><?= $i['keluar'] ?></td>

                                <td><?= $i['no_po'] ?></td>
                                <td><?= $i['ref'] ?></td>
                                <td><?= $i['nama_user'] ?></td>
                                <td><?= $i['driver'] ?></td>

                              </tr>

                            <?php endforeach; ?>

                          </tbody>

                        </table>

                      </div>
                    </div>
                  <?php } ?>


                <?php } ?>
              </div>

            </div>
          </form>

        </div>

      </div>

    </div>

  </div>

  </section>

</div>

<?php $this->load->view('template/footer2'); ?>