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
    <div class="container mt-2">
        <div class="mt-2">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title mb-4 text-center"><?= 'Laporan Omset Fabrikasi' ?></h3>
                            <h4 class="card-title mb-4 text-center"><?= $dari_tanggal.' s/d '.$sampai_tanggal ?></h4>
                            <h4 class="card-title mb-4 text-center"><?= $departemen ?></h4>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px;font-size: 8pt;">#</th>
                                        <th style="font-size: 8pt;width: 150px;">No. PO</th>
                                        <th style="font-size: 8pt;width: 150px;">Tanggal</th>
                                        <th class="text-center">Departemen</th>
                                        <th style="font-size: 8pt; width: 150px;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($omset): ?>
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
                                            <td class="text-center" style="font-size: 8pt;"><?= $no++;?></td>
                                            <td style="font-size: 8pt;"><?= $i['no_po'];?></td>
                                            <td style="font-size: 8pt;"><?= date('d-m-Y', strtotime($i['tanggal']));?></td>
                                            <td style="font-size: 8pt;"><?= $i['departemen'];?></td>
                                            <td style="font-size: 8pt;">Rp. <?= number_format($i['total'], 0,',','.');?></td>
                                        </tr>
                                        <?php endforeach;?>
                                        <tr>
                                            <td colspan="4"  class="text-center" style="font-size: 8pt;">TOTAL</td>
                                            <td style="font-size: 8pt;">Rp. <?= number_format($total, 0,',','.');?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td class="bg-light" colspan="5">Tidak ada data.</td>
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
    <script>
        window.print();
    </script>
</body>
</html>
