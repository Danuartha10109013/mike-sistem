<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= $title?></title>



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
      

    .kopatas{
        display: flex;
        align-items: center; /* Agar gambar dan teks rata vertikal */
       justify-content: end;
    }
    .kopbawah{
        position: absolute;
        bottom: 0px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: end;
    }

    .logoM{
     display: flex;
     flex-wrap: wrap;
     align-content: center;   
    }

    .textkop{
        font-weight: 700;
        width: 450px;
        text-align: center;
    }

    .linekop{
    margin-top: 0px;
    margin-bottom: 0px;
    border: 0.5px solid;
    }
    
    .linekop2{
        margin-top: 1px;
        border: 1px solid;
    }

    h1{
        font-weight: 900;
        margin-top: 0px;
    }
    .w-40{
        width: 40%;
    }

    .w-60{
        width: 60%;
    }
    .w-60 .card-title{
        padding: 5px  !important;
        padding-right: 0px !important;
        padding-bottom: 10px !important;
        margin: 0px;
    }
    thead, tr >th {
        
        color: #fff !important;
    }
   
    * {
        -webkit-print-color-adjust: exact !important;
        /* Chrome, Safari 6 – 15.3, Edge */
        color-adjust: exact !important;
        /* Firefox 48 – 96 */
        print-color-adjust: exact !important;
        /* Firefox 97+, Safari 15.4+ */
      }
 
</style>



</head>

<body>

    <div id="footer">

        <div class="page-number"></div>

    </div>

    <div class="container mt-2">
        <div class="kopatas">
            <div class="logoM">
                <img src="<?=  base_url() . '/assets/img/logotext.jpg'?>" width="200" alt="" >
            </div>
        </div>
        <div class="mt-0">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                        <div class="w-60"  style="background-color: darksalmon !important;">

                            <h1 class="card-title text-start fw-bold ">Tracking Barang</h1>
                        </div>

                            <p class="w-40" style="margin: 30px 0px 10px 0px;">Jln raya pantura, Dsn Kubangjaran RT.02/RW.01, Ds. Karanganyar, Kec. Pusakajaya, Kab. Subang-JABAR</p>

                            <h5 class="card-title mb-4 text-left">Nama Barang : <?= $b['nama_barang'] ?></h5>

                            <h5 class="card-title mb-4 text-left">Stok Terbaru : <?= $stok['stok'] ?></h5>

                            <table class="table table-striped table-bordered">

                                <thead>

                                    <tr>

                                        <th class="text-center" style="background-color: #000 !important;width: 20px;font-size: 11pt;">#</th>

                                        <th style="background-color: #000 !important;">TANGGAL</th>

                                        <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">MASUK</th>

                                        <th class="text-center" style="background-color: #000 !important;width: 50px;font-size: 11pt;">KELUAR</th>

                                        <th class="text-center" style="background-color: #000 !important;width: 150px;font-size: 11pt;">REF</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if($barang): ?>

                                        <?php

                                        $no = 1; 

                                        $total = 0;

                                        foreach($barang as $i):
                                        
                                        ?>
                                        <tr>
                                            <td class="text-center" style="font-size: 11pt;"><?= $no++;?></td>
                                            <td style="font-size: 11pt;"><?= $i['tanggal'];?></td>
                                            <td class="text-center" style="font-size: 11pt;"><?= $i['masuk'];?></td>
                                            <td class="text-center" style="font-size: 11pt;"><?= $i['keluar'] ?></td>
                                            <td class="text-center" style="font-size: 11pt;"><?= $i['ref'] ?></td>
                                        </tr>

                                        <?php endforeach;?>

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

        <div class="kopbawah">
            <div class="w-60"  style="background-color: darksalmon !important;">
        
                <h1 class="card-title text-start fw-bold ">&nbsp</h1>
            </div>
        </div>
    </div>

    <script>

        window.print();

    </script>

</body>

</html>

