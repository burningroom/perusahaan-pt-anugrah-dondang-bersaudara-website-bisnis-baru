<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        @media print {
            body { background: white; }
            .print-button { display: none; }
            @page { size: A4; margin: 0mm; }
        }

        .container {
            width: 210mm;
            background: white;
            padding: 20px;
            margin: auto;
            border: 1px solid #ccc;
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }

        .header img {
            height: 80px;
            width: 100px;
        }

        .header div {
            margin-left: 10px;
        }

        .invoice-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
        }

        .client-info {
            margin-top: 20px;
        }

        .client-info .row {
            display: flex;
            justify-content: space-between;
        }

        .client-info .col-6 {
            width: 48%;
        }

        .client-info table {
            font-size: 11px;
            width: 100%;
        }

        .client-info td {
            padding: 2px 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table, .table th, .table td {
            border: 1px solid black;
        }

        .table th, .table td {
            padding: 5px;
            text-align: center;
            font-size: 12px;
        }

        .total-section {
            text-align: right;
            font-weight: bold;
        }

        .bank-info {
            width: 50%;
            margin-top: 10px;
            border: 2px dotted black;
            padding: 5px;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            text-align: center;
            font-size: 12px;
        }

        .print-button {
            text-align: center;
            margin: 20px 0;
        }

        h1.blue {
            color: blue;
        }
    </style>
</head>
<body>
<div class="print-button">
    <button onclick="window.print()">Print Invoice</button>
</div>

<div class="container">
    <header class="header">
        <img src="{{ asset('assets/image/logo-ADB.png') }}" alt="Logo Perusahaan">
        <div>
            <h1 class="blue">PT. ANUGRAH DONDANG BERSAUDARA</h1>
            <p>Jl. P. Sudirman, RT.005 Kel. Dondang, Kec. Muara Jawa, Kalimantan Timur 75265<br>
                HP/WA: 081347142400, Fax: (0541) 7893020, Email: example@example.com</p>
        </div>
    </header>

    <h2 class="invoice-title">INVOICE</h2>

    <div class="client-info">
        {{-- <p><strong>Kepada Yth,</strong><br><b>{{$invoice->user->name}}</b></p> --}}
        <p><strong>Kepada Yth,</strong><br><b>Test</b></p>
        <div class="row g-2 align-items-start">
            <div class="col-6">
                <table>
                    <tbody>
                    <tr>
                        <td>NPWP</td>
                        <td>:</td>
                        {{-- <td>{{$invoice->user->company->npwp}}</td> --}}
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>CP/HP</td>
                        <td>:</td>
                        <td>{{'-'}}</td>
                        {{-- <td>{{$invoice->user->phone ?? '-'}}</td> --}}
                    </tr>
                    <tr>
                        <td>SKTD</td>
                        <td>:</td>
                        {{-- <td>{{$invoice->user->company->sktd ?? '-'}}</td> --}}
                        <td>{{'-'}}</td>
                    </tr>
                    <tr>
                        <td>Alamat </td>
                        <td>:</td>
                        {{-- <td>{{$invoice->user->company->address ?? '-'}}</td> --}}
                        <td>{{ '-'}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table>
                    <tbody>
                    <tr>
                        <td>Nomer Invoice</td>
                        <td>:</td>
                        {{-- <td>{{$invoice->number_invoice ?? '-'}}</td> --}}
                        <td>{{'-'}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        {{-- <td>{{date('d/m/Y',strtotime($invoice->created_at))}}</td> --}}
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>:</td>
                        {{-- <td>{{$invoice->periode ? $invoice->periode : '-'}}</td> --}}
                        <td>-</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>No</th>
            <th>Nomor Nota</th>
            <th>Nama Kapal</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>Pindah</th>
            <th>Pemanduan</th>
            <th>Penundaan</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Nota-001</td>
            <td>Kapal ABC</td>
            <td>01/01/2025</td>
            <td>03/01/2025</td>
            <td>-</td>
            <td>Rp. 1.000.000</td>
            <td>Rp. 500.000</td>
        </tr>
        </tbody>
    </table>

    <div class="bank-info">
        <p><strong>Transfer ke:</strong><br>PT. Anugrah Dondang Bersaudara<br>Bank Mandiri<br>No. Rek. 149 00 2108195 7</p>
    </div>
</div>
</body>
</html>
