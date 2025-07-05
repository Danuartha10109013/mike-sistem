<html>

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= $title ?></title>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


    <!-- <link rel="stylesheet" href="<?= base_url(); ?>assets/modules/bootstrap/css/bootstrap.min.css"> -->

    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css">
    <!--   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

    <style>
        body {
            color: #000;
        }

        p {

            line-height: 18px !important;
        }

        .kopatas {
            position: absolute;
            display: block !important;
            right: 5px !important;
            /* align-items: center !important; */
            /* Agar gambar dan teks rata vertikal */
            /* justify-content: end !important; */
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

        .to,
        .to>div,
        .to>div>span,
        .to>div>p {
            height: 100% !important;
            width: 100% !important;
            bottom: 0px !important;

        }

        .to>div {

            display: flex !important;
            flex-wrap: wrap !important;
        }

        h1 {
            font-weight: 900;
            margin-top: 0px;
        }


        /* .w-25 {
            width: 25% !important;
        }

        .w-75 {
            width: 75% !important;
        }

        .w-50 {
            width: 50% !important;
        }
        .w-100 {
            width: 100% !important;
        } */


        .w-40 {
            width: 40%;
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

        thead,
        tr>th {

            color: #fff !important;

        }

        th,
        td {
            font-size: 8px !important;
        }

        .table {
            width: 100% !important;
        }

        h3 .name {
            margin-bottom: 1px !important;
        }

        .table-data td,
        .table-data th {
            height: fit-content !important;
            width: max-content !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            /* Chrome, Safari 6 – 15.3, Edge */
            color-adjust: exact !important;
            /* Firefox 48 – 96 */
            print-color-adjust: exact !important;
            /* Firefox 97+, Safari 15.4+ */
        }

        .to>div {
            display: flex !important;
            flex-wrap: wrap !important;
            overflow: hidden;
            /* Memastikan tidak melampaui area */
        }

        .contact {
            margin: 5px 0px 5px 0px !important;
            font-size: 10px;
            word-wrap: break-word;
            /* Memastikan kata pecah */
            white-space: normal;
            /* Kata dapat turun ke baris baru */
            width: 100%;
            /* Pastikan elemen menggunakan lebar penuh */
            max-width: 100%;
            /* Jangan melampaui batas */
            box-sizing: border-box;
            /* Ikut ukuran kontainer */
        }

        @media print {
            thead.header-surat {
                display: table-header-group;
            }

            .table-data thead.header-surat {
                display: table-row-group;
            }

            @page {
                size: 100mm 150mm;
                margin: 5px;
            }

            .to>div,
            .contact {
                max-width: 100mm;
                /* Batas lebar cetak */
            }
        }

        .address {
            padding: 0px !important;
            overflow-wrap: break-word !important;

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

            .address {
                padding: 0px !important;
                overflow-wrap: break-word !important;
                width: fit-content !important;

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

            .address {
                padding: 0px !important;
                overflow-wrap: break-word !important;
                width: fit-content !important;

            }


        }

        .table-data {
            /* Print-specific styles for the table */
            width: 100%;
            /* Ensure table fills the page width */
            table-layout: fixed;
            /* Prevent content from overflowing cells */
            font-size: 8px !important;
            /* Adjust font size as needed */
        }

        .table-data th,
        .table-data td {
            word-wrap: break-word;
            /* Allow long content to wrap within cells */
            overflow-wrap: break-word;
            /* Additional wrapping for better compatibility */
            white-space: normal;
            /* Allow content to flow naturally */
        }

        .to,
        .to>div,
        .contact {
            max-width: 100mm;
            /* Set maximum width for "To" section to prevent overflow */
        }
    </style>
</head>

<body class="" style="background-color: #fff !important;">
    <table class="container-surat">

        <thead class="header-surat">
            <tr>
                <th class="header-surat-cell">
                    <div class="kopatas">
                        <div class="logoM">
                            <img src="<?= base_url() . '/assets/img/logotext.jpg' ?>" width="75" alt="">
                        </div>
                    </div>

                </th>
            </tr>
        </thead>

        <?php
        $agen = '-';
        $to = '-';
        $contact = '-';
        $driver = '-';
        if (!empty($f_detail)) {
            $agen = $f_detail[0]['nama_departemen'] == null ? $f_detail[0]['departemen'] : $f_detail[0]['nama_departemen'];
            $contact = isset($f_detail[0]['kontak_customer']) && $f_detail[0]['kontak_customer'] == null ? '' : $f_detail[0]['kontak_customer'];
            $to = $f_detail[0]['nama_user'];
        }
        if (!empty($f)) {
            $driver = $f['driver'];
        }

        ?>
        <tbody class="body-surat">
            <tr>
                <td class="body-surat-cell">

                    <div class="w-60" style="background-color: #d00c6c !important;">

                        <h3 class="card-title text-start fw-bold"><?= $title ?></h3>
                    </div>
                    <div class=" " style="display:flex;flex:flex-wrap; margin: 0 !important;">
                        <div class="col-4">

                            <p class="w-100" style="margin: 10px 0px 10px 0px;font-size:10px">Jln raya pantura, Dsn Kubangjaran RT.02/RW.01, Ds. Karanganyar, Kec. Pusakajaya, Kab. Subang-JABAR<br>
                                <!-- <u style="color:blue !important">Sub.merlinstore@gmail.com</u> -->

                            </p>

                            <div class="col-md-2 p-0">
                                <h5 class=" mb-4 text-left" style="margin: 0px 0px 5px 0px !important; font-size:10px"> <b>Nomor Invoice:</b> <?= $f['no_invoice'] ?> </h5>
                            </div>
                            <div class="col-md-2 p-0">
                                <h5 class=" mb-4 text-left" style="margin: 0px 0px 5px 0px !important; font-size:10px"> <b>Tanggal Invoice:</b> <?= $tanggal ?> </h5>
                            </div>
                            <!-- <div class="col-md-2 p-0">
                                <h5 class=" mb-4 text-left" style="margin: 0px 0px 5px 0px !important; font-size:10px"> <b>Agen:</b> <?= $agen ?> </h5>
                            </div> -->
                            <div class="col-md-2 p-0">
                                <h5 class=" mb-4 text-left" style="margin: 0px 0px 5px 0px !important; font-size:10px"> <b>Driver:</b> <?= $driver ?> </h5>
                            </div>
                        </div>
                        <div class="col-6 to p-0">
                            <div class="col-md-2 p-0" style="margin-top:3% !important;">
                                <span class="name text-left" style="font-size:10px;">
                                    To:<br><b><?= $to ?></b>
                                </span>
                                <span class="contact text-left">
                                    <?= $contact ?>

                                </span>
                                <br>
                                <span class="col-10 address text-left" style="line-height: 1.2;width:fit-content!important"> <?= $f_detail[0]['address_customer'] ?><?= $f_detail[0]['address_customer'] ?> </span>

                            </div>
                        </div>
                    </div>

                    <div class="table-data">
                        <table class="table table-striped table-bordered">

                            <thead>
                                <tr>
                                    <th class="text-center" style="background-color: #000 !important;font-size: 11pt;">No</th>
                                    <th class="text-center" style="background-color: #000 !important;font-size: 11pt;">No Order</th>
                                    <th class="text-center" style="background-color: #000 !important;font-size: 11pt;">Deskripsi</th>
                                    <th class="text-center" style="background-color: #000 !important;font-size: 11pt;">QTY</th>
                                </tr>
                            </thead>
                            <tbody>

                            <tbody>
                                <?php
                                $no = 1;
                                $total = 0;
                                $diskon = 0;
                                foreach ($f_detail as $i): ?>
                                    <?php if ($i['tipe_item'] === 'Diskon') {
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td colspan="3" class="text-center"><?= $i['nama_barang']; ?></td>


                                        </tr>
                                    <?php $diskon =  str_replace('-', '', $i['total_harga_barang']);
                                    } else {

                                        $satuan = $i['type_po'] == 'Paket' ? ' ' . $i['type_po'] : '/' . $i['satuan_barang'];

                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= $i['no_po']; ?></td>
                                            <td><?= $i['nama_barang'] . '<br/>' . $i['kode_barang']; ?></td>
                                            <td><?= $i['jumlah_barang'] . '/' . $satuan; ?></td>
                                        </tr>
                                    <?php
                                    } ?>
                                <?php endforeach;

                                ?>
                            </tbody>
                        </table>
                    </div>
        </tbody>

    </table>

    <br>

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