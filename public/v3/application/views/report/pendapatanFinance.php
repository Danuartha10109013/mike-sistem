<?php $this->load->view('template/header2'); ?>
<?php $this->load->view('template/sidebar2'); ?>

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

            <?php if ($omset !== null) { ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data <?= $title2 ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="datatables-user">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Barang</th>
                                            <th class="text-center">Tanggal Masuk/Total Harga Beli</th>
                                            <th class="text-center">Tanggal Pesanan/Total Penjualan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $total = 0;
                                        foreach ($omset as $i):

                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td class="text-start"><?= $i['nama_barang'] ?></td>
                                                <td class="text-right">
                                                    <?php
                                                    $tbel = 0;
                                                    foreach ($i['masuk'] as $j): ?>

                                                        <?= $j['harga_beli_total'] . ' | ' . date('d-m-Y', strtotime($j['tanggal_masuk'])) ?><br>
                                                    <?php
                                                        $tbel +=  $j['harga_beli_total'];
                                                    endforeach; ?>



                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                    $tju = 0;
                                                    foreach ($i['keluar'] as $k):
                                                        $type = '';
                                                        if ($k['type_po']) {
                                                            $type =   ' (' . $k['type_po'] . ') ';
                                                        }
                                                    ?>

                                                        <?= $type . $k['total_harga_barang'] . ' | ' . date('d-m-Y', strtotime($k['tanggal_detail_gs']))  ?><br>
                                                    <?php
                                                        $tju += $k['total_harga_barang'];
                                                    endforeach; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" colspan="2">Total</td>
                                                <td class="text-center">
                                                    <b><?= $tbel ?></b>
                                                </td>
                                                <td class="text-center">
                                                    <b><?= $tju ?></b>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

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

<?php $this->load->view('template/footer2'); ?>