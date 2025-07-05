<?php $this->load->view('template/header2');?>
<?php $this->load->view('template/sidebar2');?>

<!-- Main Content -->
<div class="main-content">

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <form action="<?= base_url('laporan-pendapatan-finance'); ?>" method="post">
              <div class="card-header">
                <h4><?= $title2 ?></h4>
              </div>
              <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">

                            <label>Dari Tanggal</label>

                            <input type="date" name="dari_tanggal" class="form-control" value="<?= set_value('dari_tanggal'); ?>" required="">

                            <?= form_error('dari_tanggal', '<span class="text-danger small">', '</span>'); ?>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">

                            <label>Sampai Tanggal</label>

                            <input type="date" name="sampai_tanggal" class="form-control" value="<?= set_value('sampai_tanggal'); ?>" required="">

                            <?= form_error('sampai_tanggal', '<span class="text-danger small">', '</span>'); ?>

                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="filter" value="filter" class="btn btn-primary w-100"><i class="fa fa-eye"></i> Lihat</button>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="cetak" value="cetak" formtarget="_blank" class="btn btn-primary w-100"><i class="fa fa-print"></i> Cetak</button>
                        </div>
                    </div>
                </div>

              </div>
            </form>

          </div>

        </div>

        <?php if($omset !== null){ ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Data <?= $title2 ?></h4>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"><h6>Total Omset</h6></div>
                        <div class="col-md-10">: <?= 'Rp '.number_format($total, 0,',','.');?></div>
                    </div>             
                    <div class="table-responsive">
                        <table class="table table-striped" id="datatables-user">
                        <thead>
                            <tr>
                            <th class="text-center">No</th>
                            <th>Nomor PO</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1; 
                            $total = 0;
                            foreach($omset as $i):
                            $total += $i['total'];
                            if ($i['no_po'] === NULL && $i['tanggal'] === NULL) {
                                continue;
                            }
                            ?>
                            <tr>
                            <td class="text-center"><?= $no++;?></td>
                            <td><?= $i['no_po'];?></td>
                            <td><?= date('d-m-Y', strtotime($i['tanggal']));?></td>
                            <td><?= 'Rp '.number_format($i['total'], 0,',','.');?></td>
                            </tr>
                            <?php endforeach;?>
                            
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        <?php } ?>

      </div>

    </div>

  </section>

</div>

<?php $this->load->view('template/footer2');?>