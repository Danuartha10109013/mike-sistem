<?php $this->load->view('template/header2'); ?>

<?php $this->load->view('template/sidebar2'); ?>
<style>
    .card {
        border-top: 2px solid #dd5093;
    }
</style>
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-body pt-2 mt-2 pt-lg-0 mt-lg-0 pt-xl-0 mt-xl-0">
            <div class="d-flex flex-warp">
                <div class="col-lg-12 pb-3 d-flex flex-warp justify-content-center">
                    <h4>Selamat Datang di Merlin Store</h4>
                </div>

            </div>


            <div class="row">
                <!-- Balance -->
                <?php if (in_array("Super_Admin", $this->session->userdata('role')) ): ?>
                <div class="col-md-6 mb-4">
                    <div class="card ">
                        <div class="card-header">
                            <h4 class="">Balance</h4>
                        </div>
                        <div class="card-body">
                            <h5>Omzet <br><small>(Pesanan Lolos Invoice):</small> <span class="float-right"> Rp.<?= number_format($total_tagihan, 2, ",", "."); ?></span></h5>

                            <h5>Tagihan</h5>
                            <ul>
                                <li>Nominal lunas: <span class="float-right">Rp.<?= number_format($total_bayar, 2, ",", "."); ?></span></li>
                                <li>Nominal belum lunas: <span class="float-right">Rp.<?= number_format($total_sisa, 2, ",", "."); ?> </span></li>
                            </ul>
                            <h5>Profit: <span class="float-right">Rp.<?= number_format($allpembelian[0]['total_hutang'] - $total_tagihan, 2, ",", "."); ?></span></h5>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <h4 class="col-12">Chart Selling</h4>
                                <div class="col-12">

                                    <form action="" method="get">
                                        <div class="row mb-4 col-12">
                                            <div class="col col-lg-5 form-group">
                                                <label for="monthFilter">Filter Bulan:</label>
                                                <select class="form-control" id="monthFilter" name="monthFilter" style="height: fit-content;">
                                                    <option value="1" <?= $current_month == '1' ? 'selected' : '' ?>>Januari</option>
                                                    <option value="2" <?= $current_month == '2' ? 'selected' : '' ?>>Februari</option>
                                                    <option value="3" <?= $current_month == '3' ? 'selected' : '' ?>>Maret</option>
                                                    <option value="4" <?= $current_month == '4' ? 'selected' : '' ?>>April</option>
                                                    <option value="5" <?= $current_month == '5' ? 'selected' : '' ?>>Mei</option>
                                                    <option value="6" <?= $current_month == '6' ? 'selected' : '' ?>>Juni</option>
                                                    <option value="7" <?= $current_month == '7' ? 'selected' : '' ?>>Juli</option>
                                                    <option value="8" <?= $current_month == '8' ? 'selected' : '' ?>>Agustus</option>
                                                    <option value="9" <?= $current_month == '9' ? 'selected' : '' ?>>September</option>
                                                    <option value="10" <?= $current_month == '10' ? 'selected' : '' ?>>Oktober</option>
                                                    <option value="11" <?= $current_month == '11' ? 'selected' : '' ?>>November</option>
                                                    <option value="12" <?= $current_month == '12' ? 'selected' : '' ?>>Desember</option>
                                                </select>
                                            </div>
                                            <div class="col col-lg-5 form-group">
                                                <label for="yearFilter">Filter Tahun:</label>
                                                <input class="form-control" type="number" id="yearFilter" name="yearFilter" value="<?= $current_year ?>">
                                            </div>
                                            <div class="col col-lg-2 form-group">
                                                <label for="btnFilter">&nbsp;</label>

                                                <button type="submit" class="btn btn-primary w-100" id="btnFilter"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <?php if (in_array("Super_Admin", $this->session->userdata('role')) ): ?>

                <div class="col-md-6 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="row w-100 justify-content-between">
                                <div class="col col-6">
                                    <h4> Piutang Distributor</h4>
                                </div>
                                <div class="col col-6 row justify-content-end">
                                    <a href="<?= base_url('penjualan') ?>" class="btn btn-info" style="height: fit-content;">Lihat Selengkapnya <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-2"> Nama Distributor </div>
                                <div class="col-6 mb-2 text-right"> Sisa Tagihan</div>
                                <?php foreach ($distri as $key => $d) { ?>

                                    <div class="col-6 mb-2"><?= $d['actions'] . $d['nama_departemen'] . '(' . $d['banyak_invoice'] . ' Invoice)' ?></div>
                                    <div class="col-6 font-weight-bold text-right"><?= $d['sisa_tagihan'] ?></div>
                                <?php } ?>
                            </div>


                        </div>
                    </div>
                    <!-- Top 5 Product -->

                </div>
                <div class="col-md-6 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="row w-100 justify-content-between">
                                <div class="col col-6">
                                    <h4> Hutang Belanja</h4>
                                </div>
                                <div class="col col-6 row justify-content-end">
                                    <a href="<?= base_url('pembelian') ?>" class="btn btn-info" style="height: fit-content;">Lihat Selengkapnya <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-2"> Nama Parbrik/Produsen </div>
                                <div class="col-6 mb-2 text-right"> Sisa Hutang</div>
                                <?php foreach ($pembelian as $key => $d) { ?>

                                    <div class="col-6 mb-2"><?= $d['actions'] . $d['nama_pabrik'] ?> </div>
                                    <div class="col-6 font-weight-bold text-right"><?= $d['sisa_hutang'] ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4> List Jatuh Tempo Piutang</h4>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($piutang_tempo as $key => $pt) {


                                    $this->db->select_sum('bayar');
                                    $this->db->where('id_gs',  $pt['id_gs']);
                                    $query = $this->db->get('penjualan_bayar');
                                    $bayar_penjualan_sum = $query->row()->bayar;
                                    $sisa = $pt['total_harga_barang_sum'] - $bayar_penjualan_sum;
                                    if ($sisa == 0) {
                                        continue;
                                    }

                                    $tanggal_sekarang = new DateTime();
                                    $tanggal_jatuh_tempo = new DateTime($pt['jatuh_tempo_tagihan']);
                                    $selisih_hari = $tanggal_jatuh_tempo->diff($tanggal_sekarang)->days;

                                    // Tentukan apakah jatuh tempo di masa depan atau sudah lewat
                                    $jatuh_tempo_text =  $pt['jatuh_tempo_tagihan'];

                                    if ($sisa > 0 && $selisih_hari < 7 && $pt['jatuh_tempo_tagihan'] != NULL) {
                                        if ($selisih_hari == 0) {
                                            $jatuh_tempo_text .= '<br><b class="text-danger"> Hari Ini Jatuh Tempo</b>';
                                        } elseif ($selisih_hari > 0) {
                                            $jatuh_tempo_text .= '<br><b class="text-danger">' . $selisih_hari . ' Hari Lebih dari Jatuh Tempo</b>';
                                        } else {
                                            $jatuh_tempo_text .= '<br><b class="text-danger">' . $selisih_hari . ' Hari Menuju Jatuh Tempo</b>';
                                        }
                                    } elseif ($pt['jatuh_tempo_tagihan'] == NULL) {
                                        $jatuh_tempo_text =  '<span class="text-warning">Belum ada</span>';
                                    }
                                    if ($sisa == 0) {
                                        $jatuh_tempo_text .=  '<br><span class="text-success">Lunas</span>';
                                    }

                                    $action = "
                                    <a href=" . base_url('detail-pembayaran-distributor/' . $pt['id_gs']) . " class='btn btn-light btn-sm'><i class='fa fa-list'></i></a>";


                                ?>

                                    <div class="col-6 mb-2"><?= $action . '  ' . $pt['nama_departemen'] . '-' . $pt['no_invoice'] ?> </div>
                                    <div class="col-6 font-weight-bold text-right"><?= $jatuh_tempo_text ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4> List Jatuh Tempo Utang</h4>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($pembelian as $key => $d) { ?>

                                    <div class="col-6 mb-2"><?= $d['actions'] . $d['nama_pabrik'] ?> </div>
                                    <div class="col-6 font-weight-bold text-right"><?= $d['jatuh_tempo'] ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
<?php endif ?>
                <div class="col-12">
                    <div class="card  pb-4 ">
                        <div class="card-header">
                            <div class="row w-100 justify-content-between">
                                <div class="col col-6">
                                    <h4 class="">
                                        Status Pesanan</h4>
                                    Total Pesanan:<?= $inv ?>
                                </div>

                                <div class="col col-6 row justify-content-end">
                                    <a href="<?= base_url('invoice') ?>" class="btn btn-info" style="height: fit-content;">Lihat Selengkapnya <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-stats">
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="row justify-content-center">
                                            <div class="col col-lg-3 mb-2">

                                                <div class="btn btn-warning btn-lg"><i class="fas fa-boxes" style="font-size:24px"></i></div>
                                            </div>
                                            <div class="col col-lg-5 mb-2">

                                                <div class="card-stats-item-count"><?= $invBlmKirim  ?></div>
                                                <div class="card-stats-item-label">Dikemas/Belum Kirim</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="row justify-content-center">
                                            <div class="col col-lg-3 mb-2">

                                                <div class="btn btn-info btn-lg"><i class="fas fa-truck" style="font-size:24px"></i></div>
                                            </div>
                                            <div class="col col-lg-5 mb-2">

                                                <div class="card-stats-item-count"><?= $invKirim ?></div>
                                                <div class="card-stats-item-label">Dikirim</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="row justify-content-center">
                                            <div class="col col-lg-3 mb-2">

                                                <div class="btn btn-success btn-lg"><i class="fas fa-people-carry" style="font-size:24px"></i></div>
                                            </div>
                                            <div class="col col-lg-5 mb-2">
                                                <div class="card-stats-item-count"><?= $invSelesai ?></div>
                                                <div class="card-stats-item-label">Selesai</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="card ">
                        <div class="card-header">
                            <h4> Top 5 Product</h4>
                        </div>
                        <div class="card-body">
                            <?php foreach ($top5 as $s => $st): if ($s > 4) break; ?>
                                <li class="media pb-0 mb-0">
                                    <span class="btn btn-primary shadow-primary  m-2">
                                        <?= $s + 1 ?>
                                    </span>
                                    <div class="media-body">
                                        <div class="float-right mt-3">
                                            <div class="font-weight-600 text-muted text-small"> <?= $st['keluar'] ?> Terjual</div>
                                        </div>
                                        <div class="media-title mt-3"><?= $st['nama_barang'] ?> </div>
                                        <!-- <div class="mt-1">
                                                <div class="budget-price">

                                                    <div class="budget-price-label">Rp.1,268,714</div>
                                                </div>
                                            </div> -->
                                    </div>
                                </li>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4> Recently Out of Stock</h4>
                        </div>
                        <div class="card-body">
                            <ul>

                                <?php if (!empty($recently)) {
                                    foreach ($recently as $key => $rc) { ?>

                                        <li><?= $rc['nama_barang'] ?></li>
                                <?php  }
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="row w-100 justify-content-between">
                                <h4 class="col col-8">Assets/Inventory</h4>
                                <div class="col col-4 row justify-content-end">
                                    <a href="<?= base_url('stok-barang') ?>" class="btn btn-info"><i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Barang</th>
                                            <th>Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stok as $s => $st): if ($s > 4) break; ?>
                                            <tr>
                                                <td>
                                                    <?= $s + 1 ?> <br>
                                                </td>
                                                <td>
                                                    <?= $st['nama_barang'] ?>
                                                </td>
                                                <td>
                                                    <?= $st['stok'] ?>
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



<?php $this->load->view('template/footer2'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        function loadChart(month, year) {
            $.getJSON("<?= site_url('dashboard/get_sales_data') ?>?month=" + month + "&year=" + year, function(data) {
                const labels = data.map(item => item.date);
                const sales = data.map(item => item.total_sales);

                const ctx = document.getElementById('salesChart').getContext('2d');
                const salesChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Sales',
                            data: sales,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        }

        const currentYear = <?= $current_year ?>;
        loadChart(<?= $current_month ?>, currentYear);

        $('#filterButton').click(function() {
            const selectedMonth = $('#monthFilter').val();
            loadChart(selectedMonth, currentYear);
        });

        $('#yearFilter').on('input', function() {
            var value = $(this).val();

            value = value.replace(/[^0-9]/g, '');

            if (value.length > 4) {
                value = value.substring(0, 4);
            }

            $(this).val(value);
        });
    });
</script>