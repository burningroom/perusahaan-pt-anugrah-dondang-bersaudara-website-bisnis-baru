<div>
    <div class="container">
        <header class="header">
            <div class="d-flex">
                <div>
                    <img src="{{ asset('assets/image/logo-ADB.png') }}" alt="Logo Perusahaan"> <!-- Ganti "logo.png" dengan URL atau path logo -->
                </div>
                <div class="flex-fill">
                    <h1>PT. ANUGRAH DONDANG BERSAUDARA</h1>
                    <p>
                        Jl. P. Sudirman, RT.005 Kel. Dondang, Kec. Muara Jawa, Kab. Kutai Kartanegara, Kalimantan Timur 75265<br>
                        HP/WA: 081347142400, Fax: (0541) 7893020, Email: anugrahdondangbersaudara@yahoo.com
                    </p>
                </div>
            </div>
        </header>

        <h2 class="invoice-title">INVOICE</h2>
        <div class="mb-3 client-info">
            <p><strong>Kepada Yth,</strong><br><b>{{$invoice->user->name}}</b></p>
            <div class="row g-2 align-items-start">
                <div class="col-6">
                    <table style="font-size: 11px">
                        <tbody>
                        <tr>
                            <td>NPWP</td>
                            <td>:</td>
                            <td>{{$invoice->user->company->npwp}}</td>
                        </tr>
                        <tr>
                            <td>CP/HP</td>
                            <td>:</td>
                            <td>{{$invoice->user->phone ?? '-'}}</td>
                        </tr>
                        <tr>
                            <td>SKTD</td>
                            <td>:</td>
                            <td>{{$invoice->user->company->sktd}}</td>
                        </tr>
                        <tr>
                            <td>Alamat </td>
                            <td>:</td>
                            <td>{{$invoice->user->company->address ?? '-'}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <table style="font-size: 11px">
                        <tbody>
                        <tr>
                            <td>Nomer Invoice</td>
                            <td>:</td>
                            <td>{{$invoice->number_invoice}}</td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>{{date('d/m/Y',strtotime($invoice->created_at))}}</td>
                        </tr>
                        <tr>
                            <td>Periode</td>
                            <td>:</td>
                            <td>{{$invoice->periode ? $invoice->periode : '-'}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th rowspan="2" style="width: 1%" class="text-center">No</th>
                <th rowspan="2" class="text-center">Nomor Nota</th>
                <th rowspan="2" class="text-center">Nama Kapal</th>
                <th colspan="3" class="text-center">Tanggal Pengolongan</th>
                <th colspan="2" class="text-center">Tarif + PNPB</th>
            </tr>
            <tr>
                <th class="text-center">Masuk</th>
                <th class="text-center">Keluar</th>
                <th class="text-center">Pindah</th>
                <th class="text-center">Pemanduan</th>
                <th class="text-center">Penundaan</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($invoice->invoiceItems as $item)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td>{{$item->nomor_nota}}</td>
                    <td>{{Str::title($item->vesselMaster->name)}}</td>
                    <td>{{$item->in ? date('d/m/Y',strtotime($item->in)) : '-'}}</td>
                    <td>{{$item->out ? date('d/m/Y',strtotime($item->out)) : '-'}}</td>
                    <td>{{$item->move ? date('d/m/Y',strtotime($item->move)) : '-'}}</td>
                    <td>
                        <div class="d-flex">
                            <div>Rp.</div>
                            <div class="ms-auto">@number($item->scouting)</div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div>Rp.</div>
                            <div class="ms-auto">@number($item->procrastination)</div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="text-end">
            <tr>
                <th colspan="6">Subtotal : </th>
                <td>
                    <div class="d-flex">
                        <div>Rp.</div>
                        <div class="ms-auto">@number($invoice->scouting)</div>
                    </div>
                </td>
                <td>
                    <div class="d-flex">
                        <div>Rp.</div>
                        <div class="ms-auto">@number($invoice->procrastination)</div>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="7">Total : </th>
                <td>
                    <div class="d-flex">
                        <div>Rp.</div>
                        <div class="ms-auto">@number($invoice->sub_total_price)</div>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="7">DPP PPN : </th>
                <td>
                    <div class="d-flex">
                        <div>Rp.</div>
                        <div class="ms-auto">@number($invoice->dpp_ppn)</div>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="7">PPH 23 : </th>
                <td>
                    <div class="d-flex">
                        <div>Rp.</div>
                        <div class="ms-auto">@number($invoice->pph_23)</div>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="7">PPN {{$configuration->tax}} %: </th>
                <td>
                    <div class="d-flex">
                        <div>Rp.</div>
                        <div class="ms-auto">@number($invoice->tax_price)</div>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="7">Grand Total : </th>
                <td>
                    <div class="d-flex">
                        <div>Rp.</div>
                        <div class="ms-auto">@number($invoice->grand_total_price)</div>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>

        <div class="d-flex">
            <div>Terbilang : </div>
            <div class="flex-fill">
                <div class="terbilang-section">
                    <span>{{Str::title(Terbilang::make($invoice->grand_total_price))}}</span>
                </div>
            </div>
        </div>


        <div class="bank-info">
            <div style="color: red">* Bukti transfer harap di WA, Fax, atau Email</div>
            <p><strong>Transfer ke:</strong><br>PT. Anugrah Dondang Bersaudara<br>Bank Mandiri<br>No. Rek. 149 00 2108195 7</p>
        </div>

    </div>
</div>
