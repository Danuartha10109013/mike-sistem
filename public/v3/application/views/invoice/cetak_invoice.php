<html>

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= $title ?></title>



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        .kopatas {
            display: flex !important;
            align-items: center !important;
            /* Agar gambar dan teks rata vertikal */
            justify-content: end !important;
        }

        .kopbawah {
            display: flex !important;
            align-items: center !important;
            /* Agar gambar dan teks rata vertikal */
            justify-content: end !important;
        }


        .logoM {
            display: flex;
            flex-wrap: wrap;
            align-content: center;
        }

        .textkop {
            font-weight: 700;
            width: 450px;
            text-align: center;
        }

        .linekop {
            margin-top: 0px;
            margin-bottom: 0px;
            border: 0.5px solid;
        }

        .linekop2 {
            margin-top: 1px;
            border: 1px solid;
        }

        h1 {
            font-weight: 900;
            margin-top: 0px;
        }

        .w-25 {
            width: 25%;
        }

        .w-40 {
            width: 40%;
        }

        .w-25 {
            width: 25% !important;
        }

        .w-75 {
            width: 75% !important;
        }

        .w-50 {
            width: 50% !important;
        }

        .w-60 {
            width: 60% !important;
        }

        .w-60 .card-title {
            padding: 5px !important;
            padding-right: 0px !important;
            padding-bottom: 10px !important;
            margin: 0px;
            color: #fff !important;
        }

        .p-0 {
            padding: 0px !important;
        }

        thead,
        tr>th {

            color: #fff !important;
        }

        .table {
            width: 100% !important;
        }

        h3 .name {
            margin-bottom: 1px !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            /* Chrome, Safari 6 – 15.3, Edge */
            color-adjust: exact !important;
            /* Firefox 48 – 96 */
            print-color-adjust: exact !important;
            /* Firefox 97+, Safari 15.4+ */
        }

        @media print {
            thead.header-surat {
                display: table-header-group;
            }

            @page {
                size: 21.0cm 29.7cm;
                margin: 1cm 1cm 2cm 2cm;
            }
        }

        /*
        UNTUK GOOGLE CHROME
        */
        @media print and (-webkit-min-device-pixel-ratio:0) {
            #blank {
                height: 85px;
                position: relative;
            }

            #print-footer {
                display: table-footer-group;
                bottom: 1px;
                left: 2px;
                position: fixed;
            }
        }


        @media print and (min--moz-device-pixel-ratio:0) {

            #blank {
                height: 85px;
                position: relative;
            }

            #print-footer {
                display: table-footer-group;
                bottom: 1px;
                left: 2px;
                position: fixed;
            }

        }
    </style>
</head>

<body>
    <table class="container-surat">

        <thead class="header-surat">
            <tr>
                <th class="header-surat-cell">
                    <div class="kopatas">
                        <div class="logoM">
                            <img src="<?= base_url() . '/assets/img/logotext.jpg' ?>" width="150" alt="">
                        </div>
                    </div>

                </th>
            </tr>
        </thead>

        <?php
        $agen = '-';
        $to = '-';
        $contact = '-';
        if (!empty($f_detail)) {
            $agen = $f_detail[0]['nama_departemen'] == null ? $f_detail[0]['departemen'] : $f_detail[0]['nama_departemen'];
            $contact = isset($f_detail[0]['kontak_customer']) && $f_detail[0]['kontak_customer'] == null ? '' : $f_detail[0]['kontak_customer'];
            $to = $f_detail[0]['nama_user'];
        }
        ?>
        <tbody class="body-surat">
            <tr>
                <td class="body-surat-cell">

                    <div class="w-60" style="background-color: #d00c6c !important;position:fixed !important;top:15px !important">

                        <h1 class="card-title text-start fw-bold">Invoice</h1>
                    </div>
                    <div class=" " style="display:flex;flex:flex-wrap; margin: 0 !important;">
                        <div class="w-75">

                            <p class="w-75" style="margin: 0px 0px 10px 0px;">Jln raya pantura, Dsn Kubangjaran RT.02/RW.01, Ds. Karanganyar, Kec. Pusakajaya, Kab. Subang-JABAR<br>
                                <u style="color:blue !important">Sub.merlinstore@gmail.com</u>

                            </p>

                            <div class="col-md-2 p-0">
                                <h5 class=" mb-4 text-left"><b>Nomor Invoice:</b> <?= $f['no_invoice'] ?> </h5>
                            </div>
                            <div class="col-md-2 p-0">
                                <h5 class=" mb-4 text-left"><b>Tanggal Invoice:</b> <?= $tanggal ?> </h5>
                            </div>

                        </div>
                        <div class="w-25 ">
                            <div class="col-md-2 p-0" style="margin-top:25% ;">
                                <h3 class=" name text-left">To:<br><b> <?= $agen ?> </b> </h3>
                                <h5 class=" contact text-left"><?= $f_detail[0]['phone_departemen'] ?></h5>
                                <p class=" contact text-left"><?= $f_detail[0]['address_departemen'] ?></p>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered">

                        <thead>
                            <tr>
                                <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">No</th>
                                <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Deskripsi</th>
                                <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Nomor Order</th>
                                <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">QTY</th>
                                <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Harga</th>
                                <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        <tbody>
                            <?php
                            $no = 1;
                            $total = 0;
                            $diskon = 0;
                            if ($f_detail):
                                foreach ($f_detail as $i): ?>
                                    <?php if ($i['tipe_item'] === 'Diskon') {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td colspan="4" class="text-center"><?= $i['nama_barang'];; ?></td>
                                            <td><?= str_replace('-', '', number_format($i['total_harga_barang'], 0, ',', '.')); ?></td>


                                        </tr>
                                    <?php $diskon =  str_replace('-', '', $i['total_harga_barang']);
                                    } else {

                                        $satuan = $i['type_po'] == 'Paket' ? ' ' . $i['type_po'] : '/' . $i['satuan_barang'];

                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= $i['nama_barang'] . '<br/>' . $i['kode_barang']; ?></td>
                                            <td><?= $i['no_po']; ?></td>
                                            <td><?= $i['jumlah_barang'] . $satuan; ?></td>
                                            <td><?= number_format($i['harga_barang'], 0, ',', '.'); ?></td>
                                            <td><?= number_format($i['total_harga_barang'], 0, ',', '.'); ?></td>

                                        </tr>
                                    <?php $total += $i['total_harga_barang'];
                                    } ?>
                            <?php endforeach;
                            endif;
                            ?>
                            <tr>
                                <td class="text-center"></td>
                                <td colspan="4" class="text-center">Total</td>
                                <td class="text-start"><?= number_format($total - $diskon, 0, ',', '.') ?></td>
                            </tr>
                        </tbody>

        </tbody>

    </table>

    <h4>SYARAT DAN KETENTUAN PEMBAYARAN</h4>
    <p class="" style="margin: 30px 0px 10px 0px;">
        1. Silakan kirim pembayaran dalam waktu 30 hari setelah menerima faktur ini.<br>
        2. Tidak dapat melakukan pembatalan setelah pembayaran dilakukan.
        <br>
        <br>
        Saya telah setuju dengan syarat dan ketentuan yang berlaku.<br>
        Terimakasih telah berbelanja dan bekerjasama dengan kami.

    </p>

    <div class="kopbawah" style="width: 100vw !important;">

        <div class="w-25">

            <h5 class=" text-start fw-bold  w-40" style=" width: 100% !important;">Mengetahui,</h5>
            <br>
            <br>

            <h5 class=" text-start fw-bold  w-40" style="width: 100% !important;">Merlina Wijaya</h5>
        </div>
    </div>
    </td>
    </tr>

    </tbody>

    <tfoot class="footer-surat">
        <tr>
            <td class="footer-surat-cell">
                <div id="blank"></div>
                <span id="print-footer">
                    <div class="kopbawah" style="width: 100vw !important;">

                        <div class="w-60" style="background-color: #d00c6c !important; ">

                            <h1 class=" text-start fw-bold  w-60" style="width: 100% !important;">&nbsp</h1>
                        </div>
                    </div>
                </span>
            </td>
        </tr>
    </tfoot>
    </table>

    <script>
        window.print();
    </script>
</body>

</html>