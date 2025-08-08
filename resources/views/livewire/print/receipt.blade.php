{{-- <div>
    <div class="container bg-white">
        <div class="header">
            <div class="header-logo">
                <img src="{{ asset('assets/image/logo-ADB-text.png') }}" alt="Logo"> <!-- Ganti dengan logo Anda -->
            </div>
            <div class="header-text">
                <strong>PT. ANUGRAH DONDANG BERSAUDARA</strong><br>
                Jl. P. Sudirman, RT.005 Kel. Dondang, Kec. Muara Jawa,<br>
                Kab. Kutai Kartanegara, Kalimantan Timur 7526<br>
                HP/WA: 081347142400, Fax: (0541) 7893020<br>
                Email: anugrahdondangbersaudara@yahoo.com
            </div>
        </div>

        <hr>

        <div class="content">
            <p><strong>Sudah Terima Dari:</strong> <span>{{$receipt->user->name}}</span></p>
            <p><strong>Banyaknya Uang:</strong> <span>{{Str::title(Terbilang::make($receipt->grand_total_price, ' rupiah', 'senilai '))}}</span></p>
            <p><strong>Untuk Pembayaran:</strong> <span>{{$receipt->description ?? '-'}}</span></p>
        </div>

        <div class="amount-box">
            Jumlah Rp. {{number_format($receipt->grand_total_price,0,',','.')}}
        </div>

    </div>
</div> --}}
