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

        .w-40 {
            width: 40%;
        }

        .w-60 {
            width: 60% !important;
        }

        .w-80 {
            width: 80% !important;
        }

        .card-title {
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

        .table {
            width: 100% !important;
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
                        <div class="" style="width: 100% !important;">
                            <div class="w-80" style="background-color: #d00c6c !important;">

                                <h3 class="card-title text-start fw-bold ">Laporan Stok Masuk</h3>
                            </div>
                            <p class="w-60" style="margin: 30px 0px 10px 0px;color: #000 !important;">Jln raya pantura, Dsn Kubangjaran RT.02/RW.01, Ds. Karanganyar, Kec. Pusakajaya, Kab. Subang-JABAR</p>
                        </div>

                        <div class="logoM">
                            <img src="<?= base_url() . '/assets/img/logotext.jpg' ?>" width="200" alt="">
                        </div>
                    </div>

                </th>
            </tr>
        </thead>

        <tbody class="body-surat">
            <tr>
                <td class="body-surat-cell">



                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 8pt;">No</th>
                                <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">Nama Barang</th>
                                <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">Kode Barang</th>
                                <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">Tanggal Masuk</th>
                                <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">Jumlah</th>
                                <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">Harga Beli</th>
                                <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">Jenis Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($stokMasuk): ?>
                                <?php

                                $no = 1;

                                foreach ($stokMasuk as $i):

                                ?>

                                    <tr>

                                        <td class="text-center" style="font-size: 8pt;"><?= $no++; ?></td>

                                        <td><?= $i['nama_barang']; ?></td>
                                        <td><?= $i['kode_barang']; ?></td>
                                        <td><?= date('d-m-Y', strtotime($i['tanggal_masuk'])); ?></td>
                                        <td><?= $i['jumlah']; ?></td>
                                        <td><?= 'Rp ' . number_format($i['harga_beli'], 0, ',', '.'); ?></td>
                                        <td><?= $i['jenis_barang']; ?></td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td class="bg-light" colspan="7">Tidak ada data.</td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>


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