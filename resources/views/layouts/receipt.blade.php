<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 40px;
            max-width: 1000px;
            border: 1px solid #000;
            border-radius: 10px;
            background-color: #fff;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-logo img {
            width: 120px;
        }

        .header-text {
            text-align: right;
            font-size: 14px;
        }

        .content {
            margin-top: 20px;
        }

        .content p {
            margin: 15px 0;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content p strong {
            flex: 0 0 30%; /* Label berada di 30% dari lebar */
            text-align: left;
        }

        .content p span {
            flex: 1; /* Isi teks memenuhi ruang */
            border-bottom: 1px solid #000;
            text-align: left;
            padding-left: 10px;
        }

        .amount-box {
            margin-top: 30px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 0;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-date {
            font-size: 14px;
        }

        .footer-signature {
            text-align: center;
        }

        .footer-signature img {
            width: 150px;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .container {
                border: none;
                padding: 5mm; /* Tambahkan margin internal */
            }

            @page {
                size: A4 landscape;
                margin: 5mm; /* Tambahkan margin eksternal */
            }

            .print-button {
                display: none;
            }

        }
    </style>
</head>

<body class="bg-light">

<div class="print-button">
    <button class="btn btn-primary" onclick="printReceipt()">Print Kuitansi</button>
</div>

@yield('content')

<script>
    function printReceipt() {
        window.print();
    }
</script>

</body>

</html>
