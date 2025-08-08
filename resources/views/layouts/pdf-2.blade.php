<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Layanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            /* margin: 0; */
            /* padding: 0; */
            background: #f4f4f4;
        }

        @media print {

            body{
                background: white;
            }

            .print-button {
                display: none;
            }

            /* Set automatic margins using the @page rule */
            @page {
                size: A4;
                margin: 0mm;
            }
        }
    </style>
    @livewireStyles
</head>
<body>
<div class="m-6 text-center print-button">
    <button class="px-3 py-2 font-bold rounded-lg bg-cyan-400" onclick="window.print()">Cetak Layanan</button>
</div>

@yield('content')
@livewireScripts
</body>
</html>
