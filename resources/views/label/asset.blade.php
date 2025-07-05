<!DOCTYPE html>
<html>
<head>
    <title>Label Aset</title>
    <style>
    @media print {
        body {
            margin: 0;
        }

        img {
            display: block !important;
            max-width: 100%;
            height: auto;
        }

        .label {
            break-inside: avoid;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
    </style>

</head>
<body onload="window.print()">
    <div class="label">
        <h3>{{ $asset->name }}</h3>
        <p>{{ $asset->number }}</p>
        {!! DNS2D::getBarcodeHTML(url('/asset/public/' . $asset->id), 'QRCODE') !!}
    </div>
</body>
</html>
