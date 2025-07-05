<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css">
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            /* Chrome, Safari, Edge */
            color-adjust: exact !important;
            /* Firefox */
            print-color-adjust: exact !important;
            /* Firefox 97+ */
            line-height: 22px !important;
        }

        body {
            font-size: 18px !important;
            background-color: #fff !important;
            color: #000 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        p {
            margin-bottom: 0.5rem !important;
        }

        .container-surat {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .kopatas {
            text-align: right !important;
        }

        .to,
        .contact {
            width: 100% !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }

        .address {
            word-wrap: break-word !important;
            white-space: normal !important;
            overflow-wrap: break-word !important;
        }

        .table-data table {
            width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
            font-size: 18px !important;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #000 !important;
            padding: 5px !important;
            text-align: center !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .table-data thead {
            background-color: #000 !important;
            color: #fff !important;
        }

        @media print {
            body {
                margin: 0 !important;
                padding: 5mm !important;
            }

            @page {
                size: A4 !important;
                margin: 0mm !important;
                padding: 10mm !important;
            }

            .container-surat {
                width: 100% !important;
            }

            .table-data table {
                width: 100% !important;
                table-layout: fixed !important;
            }

            .table-data th,
            .table-data td {
                padding: 5px !important;
            }

            .to,
            .contact,
            .address {
                max-width: 100% !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }
        }
    </style>
</head>

<body>
    <table class="container-surat">
        <thead class="header-surat">

        </thead>

        <tbody class="body-surat">

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
            <tr>
                <td>
                    <div class="w-100" style="color: #000;padding: 10px;top: 10px;">
                        <h2 class="card-title text-center pt-2"><u><?= $title ?></u></h2>
                        <h4 class=" text-right pt-2"><?= date('d F Y') ?></h4>
                    </div>
                    <div class="w-60" style="margin-top:10px">
                        <div style="flex: 1;">
                            <p style="font-size: 18px;"><b>Penerima: </b> <?= $to ?></p>
                            <p class="contact"><b>Telp/HP: </b> <?= $contact ?></p>
                            <p class="address"><b>Alamat: </b> <?= $f_detail[0]['address_customer'] ?></p>
                        </div>
                        <br>
                        <div style="flex: 1;">

                            <!-- <p style="font-size: 18px;"><b>Nomor Invoice:</b> <?= $f['no_invoice'] ?></p>
                            <p style="font-size: 18px;"><b>Tanggal Invoice:</b> <?= $tanggal ?></p>
                            <p style="font-size: 18px;"><b>Driver:</b> <?= $driver ?></p> -->
                            <div class="col-md-2 p-0">
                                <p class="text-left"><b>Pengirim: </b> <?= $agen ?> </h5>
                                <p class="contact"><b>Telp/HP: </b> <?= $f_detail[0]['phone_departemen'] ?> </h5>
                                <p class="address"><b>Alamat: </b> <?= $f_detail[0]['address_departemen'] ?></p>
                            </div>
                        </div>

                    </div>
                    <div class="table-data">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>QTY</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                $total = 0; ?>
                                <?php foreach ($f_detail as $i):  ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $i['nama_barang']  ?></td>
                                        <td><?= (int)$i['jumlah_barang'] ?></td>
                                        <td><?= $i['type_po'] == 'Paket' ? $i['type_po'] : $i['satuan_barang'] ?></td>
                                    </tr>
                                <?php $total += $i['jumlah_barang'];
                                endforeach; ?>
                                <tr class="font-weight-bold">
                                    <td colspan="2">Total</td>
                                    <td><?= $total ?></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>

    </table>

    <script>
        window.print();
    </script>
</body>

</html>