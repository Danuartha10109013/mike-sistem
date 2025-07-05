<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style type="text/css">
        /*#footer {
          position: fixed;
          left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.9em;
        }

        #footer {
          bottom: 0;
          border-top: 0.1pt solid #aaa;
        }

        .page-number {
          text-align: center;
        }

        .page-number:before {
          content: counter(page);
        }*/
/*        .page-number:before {
          content: "Page " counter(page);
        }*/
    </style>
</head>
<body>
    <div id="footer">
        <div class="page-number"></div>
    </div>
    <div class="row mt-2">
        <div class="mt-2">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title mb-4 text-center"><?= 'Laporan Invoice Cleaning Supply' ?></h3>
                            <h4 class="card-title mb-4 text-center"><?= $dari_tanggal.' s/d '.$sampai_tanggal ?></h4>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px;font-size: 8pt;">#</th>
                                        <th style="font-size: 8pt;">No. Invoice</th>
                                        <th style="font-size: 8pt;">Tanggal</th>
                                        <th style="font-size: 8pt;">Nama Barang</th>
                                        <th class="text-center" style="width: 30px;font-size: 8pt;">QTY</th>
                                        <th class="text-center" style="width: 30px;font-size: 8pt;">UOM</th>
                                        <th class="text-center" style="width: 80px;font-size: 8pt;">Harga</th>
                                        <th class="text-center" style="width: 80px;font-size: 8pt;">Total</th>
                                        <th class="text-center" style="width: 60px;font-size: 8pt;">PO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($invoice): ?>
                                        <?php
                                        $no = 1; 
                                        $total = 0;
                                        foreach($invoice as $i):
                                        $total += $i['total_harga_barang'];
                                        ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 8pt;"><?= $no++;?></td>
                                            <td style="font-size: 8pt;"><?= $i['no_invoice'];?></td>
                                            <td style="font-size: 8pt;"><?= date('d-m-Y', strtotime($i['tanggal']));?></td>
                                            <td style="font-size: 8pt;"><?= $i['nama_barang'].'<br/>'.$i['kode_barang'];?></td>
                                            <td style="font-size: 8pt;"><?= $i['jumlah_barang'];?></td>
                                            <td style="font-size: 8pt;"><?= $i['satuan_barang'];?></td>
                                            <td style="font-size: 8pt;"><?= number_format($i['harga_barang'], 0,',','.');?></td>
                                            <td style="font-size: 8pt;"><?= number_format($i['total_harga_barang'], 0,',','.');?></td>
                                            <td style="font-size: 8pt;"><?= $i['no_po'];?></td>
                                        </tr>
                                        <?php endforeach;?>
                                        <tr>
                                            <td colspan="7"  class="text-center" style="font-size: 8pt;">TOTAL</td>
                                            <td style="font-size: 8pt;"><?= number_format($total, 0,',','.');?></td>
                                            <td></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td class="bg-light" colspan="9">Tidak ada data.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
