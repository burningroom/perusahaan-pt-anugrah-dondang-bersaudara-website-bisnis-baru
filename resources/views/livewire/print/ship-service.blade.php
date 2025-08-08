@php
    $pkk = $spk_pandu->pkk;
    $request_arrival = $pkk->requestable;
@endphp
<div>
    <div class="w-[210mm] h-[297mm] p-4 mx-auto bg-white border shadow-md">
        <div class="flex flex-row">
            <div class="flex items-center m-1 basis-20">
                <img src="{{ asset('/logo_adb.png') }}" alt="Logo" class="w-16 h-16 mr-4">
            </div>
            <div class="m-1 basis-[440px]">
                <h1 class="text-lg font-bold uppercase">PT. Anugrah Dondang Bersaudara</h1>
                <p class="text-xs">
                    Jl. P. Sudirman RT. 005 Kel. Dondang, Kec. Muara Jawa<br>
                    Kab. Kutai Kartanegara, Kalimantan Timur, Indonesia<br>
                    Telp: 0813-4714-2400, Fax: 0541 - 7898020<br>
                    Email: anugrahdondangbersaudara@yahoo.com
                </p>
            </div>
            <div class="flex flex-col h-full gap-2 m-1 basis-1/2">
                <div class="flex w-full gap-1">
                    <div class="flex w-1/2 p-1 border border-slate-950">
                        <div class="flex-col w-1/2 mr-2">
                            <div class="text-center text-md-center">IN</div>
                            <hr class="border-t-2 border-gray-500 ">
                            <div class="text-center text-md-center">Masuk</div>
                        </div>
                        <div class="flex items-center w-1/2 mx-auto border border-slate-950">
                            {{--                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"--}}
                            {{--                                 class="w-12 h-12 size-6 text-slate-950">--}}
                            {{--                                <path fill-rule="evenodd"--}}
                            {{--                                      d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"--}}
                            {{--                                      clip-rule="evenodd"/>--}}
                            {{--                            </svg>--}}
                        </div>
                    </div>
                    <div class="flex w-1/2 p-1 border border-slate-950">
                        <div class="flex-col w-1/2 mr-2">
                            <div class="text-center text-md-center">OUT</div>
                            <hr class="border-t-2 border-gray-500">
                            <div class="text-center text-md-center">Keluar</div>
                        </div>
                        <div class="flex items-center w-1/2 mx-auto border border-slate-950">
                            {{--                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"--}}
                            {{--                                 class="w-12 h-12 size-6 text-slate-950">--}}
                            {{--                                <path fill-rule="evenodd"--}}
                            {{--                                      d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"--}}
                            {{--                                      clip-rule="evenodd"/>--}}
                            {{--                            </svg>--}}
                        </div>
                    </div>
                </div>
                <div class="flex h-full text-xl font-bold">
                    NO : <span class="ml-2 text-red-500">{{$spk_pandu->nomor_nota}}</span>
                </div>
            </div>
        </div>
        <hr class="border-t-2 border-gray-500">
        <div class="m-1 text-center">
            <h1 class="font-semibold uppercase">BUKTI PEMAKAIAN PELAYANAN KAPAL</h1>
            <p class="text-sm uppercase">SHIPS SERVICES CERTIFICATE</p>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-r-0 border-slate-950">
                    <div class="flex-col w-1/2 p-1">
                        <div class="text-center text-md-center">Nama Kapal</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Vessel's Name</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full mx-auto ml-2 text-xl font-bold">
                        {{$pkk->ship->name}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border basis-1/2 border-slate-950 ">
                <div class="flex w-full border border-slate-950">
                    <div class="flex-col w-1/2 p-1">
                        <div class="text-center text-md-center">Nama Barge</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Barge Name</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full mx-auto ml-2 text-xl font-bold">
                        {{$request_arrival->vessel_bg?->name}}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col w-1/2 border border-t-0 border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-r-0 border-slate-950">
                    <div class="flex-col p-1 w-[300px]">
                        <div class="text-center text-md-center">Gross Tonnage Kapal</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Vessel Gross Tonnage</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full mx-auto ml-2 text-xl font-bold">
                        {{$pkk->ship->grt}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-1/2 border border-t-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-slate-950">
                    <div class="flex-col w-[350px] p-1">
                        <div class="text-center text-md-center">Tongkang Gross Tonnage</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Barge Gross Tonnage</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full mx-auto ml-2 text-xl font-bold">
                        {{$request_arrival->vessel_bg?->grt}}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-t-0 border-b-0 border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-r-0 border-slate-950">
                    <div class="flex-col p-1 w-[300px]">
                        <div class="text-center text-md-center">Kode Panggilan Kapal</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Call Sign</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full mx-auto ml-2 text-xl font-bold">
                        {{$pkk->ship?->call_sign}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border border-t-0 border-b-2 border-l-2 border-r-2 basis-1/2 border-slate-950">
                <div class="flex w-full">
                    <div class="flex-col w-[350px] p-1">
                        {{--                        <div class="text-center text-md-center"></div>--}}
                        {{--                        <hr class="">--}}
                        {{--                        <div class="text-center text-md-center"></div>--}}
                    </div>
                    <div class="flex items-center w-1 mx-auto"></div>
                    <div class="flex items-center w-full mx-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-b-0 border-r-0 border-slate-950">
                    <div class="flex-col p-1 w-[160px]">
                        <div class="text-center text-md-center">Nama Nahkoda</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Master's Name</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full ml-2 text-xl">
                        {{$pkk->ship?->captain_name}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border border-t-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-b-0 border-slate-950">
                    <div class="flex-col w-[150px] p-1">
                        <div class="text-center text-md-center">Pemilik Kapal</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Ships Owner</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full ml-2 text-xl">
                        -
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-b-0 border-r-0 border-slate-950">
                    <div class="flex-col p-1 w-[160px]">
                        <div class="text-center text-md-center">Nama Agent</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Agent's Name</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full ml-2 text-xl">
                        {{$pkk->user->name}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border border-t-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-b-0 border-slate-950">
                    <div class="flex-col w-[270px] p-1">
                        <div class="text-center text-md-center">Telpon Agent</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Contact Person Agent</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full ml-2 text-xl">
                        {{$pkk->user->phone}}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-r-0 border-slate-950">
                    <div class="flex-col p-1 w-[160px]">
                        <div class="text-center text-md-center">Datang Dari</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Arrival From</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full ml-2 text-xl">
                        {{$pkk->port?->port_code . ' - ' . $pkk->port?->port_name}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border border-t-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-slate-950">
                    <div class="flex-col w-[220px] p-1">
                        <div class="text-center text-md-center">Tujuan dari sini</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Next Port Of Call</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full ml-2 text-xl">
                        {{$pkk->port?->destination_port_code . ' - ' . $pkk->port?->destination_port_name}}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex ">
            <div class="flex items-center mr-2">I .</div>
            <div class="flex-col w-[160px] p-1 ">
                <div class="text-start text-md-center">Menerangkan Bahwa</div>
                <hr class="border-t-2 border-slate-950">
                <div class="text-start text-md-center">Next Port Of Call</div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-r-0 border-slate-950">
                    <div class="flex-col w-full p-1">
                        <div class="text-center text-md-center">Pergerakan kapal dari/ke</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Movements from - to</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full mx-auto ml-2 font-bold">
                        {{$spk_pandu?->lokasi_awal . ' - ' . $spk_pandu?->lokasi_akhir}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border border-t-2 border-b-2 border-l-2 border-r-2 basis-1/2 border-slate-950">
                <div class="flex w-full">
                    <div class="flex-col w-[350px] p-1">
{{--                        <div class="text-center text-md-center"></div>--}}
{{--                        <hr class="">--}}
{{--                        <div class="text-center text-md-center"></div>--}}
                    </div>
                    <div class="flex items-center w-1 mx-auto"></div>
                    <div class="flex items-center w-full mx-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-t-0 border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-r-0 border-slate-950">
                    <div class="flex-col p-1 w-[190px]">
                        <div class="text-center text-md-center">Jam Pandu Naik Dikapal</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">The Time Pilot on Board</div>
                    </div>
                    <div class="flex items-center">:</div>
                    <div class="flex items-center mx-auto ml-2 text-xl font-bold">
                        {{$spk_pandu->pilot_on_board ? \Carbon\Carbon::parse($spk_pandu->pilot_on_board)->toTimeString() : ''}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border border-t-0 border-b-2 border-l-2 border-r-2 basis-1/2 border-slate-950">
                <div class="flex w-full">
                    {{-- <div class="flex-col w-full p-1">
                        <div class="text-center text-md-center"></div>
                        <hr class="">
                        <div class="text-center text-md-center"></div>
                    </div>
                    <div class="flex items-center w-1 mx-auto"></div>
                    <div class="flex items-center w-full mx-auto">
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-t-0 border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-t-0 border-r-0 border-slate-950">
                    <div class="flex-col p-1 w-[180px]">
                        <div class="text-center text-md-center">Selesai Pandu</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Pilotage finished</div>
                    </div>
                    <div class="flex items-center">:</div>
                    <div class="flex items-center mx-auto ml-2 text-xl font-bold">
                        {{$spk_pandu->pilotage_finished ? \Carbon\Carbon::parse($spk_pandu->pilotage_finished)->toTimeString() : ''}}
                    </div>
                </div>
            </div>
            <div class="flex flex-col border-2 border-t-0 border-l-2 basis-1/2 border-slate-950">
                <div class="flex w-full">
                    <div class="flex-col w-[350px] p-1">
{{--                        <div class="text-center text-md-center"></div>--}}
{{--                        <hr class="">--}}
{{--                        <div class="text-center text-md-center"></div>--}}
                    </div>
                    <div class="flex items-center w-1 mx-auto"></div>
                    <div class="flex items-center w-full mx-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="flex">
            <div class="flex items-center mr-2">II .</div>
            <div class="flex-col w-[250px] p-1 ">
                <div class="text-start text-md-center">Ia telah menggunakan kapal Tunda</div>
                <hr class="border-t-2 border-slate-950">
                <div class="text-start text-md-center">She used the Tug Boat</div>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex flex-col border border-r-0 basis-1/2 border-slate-950">
                <div class="flex w-full border border-r-0 border-slate-950">
                    <div class="flex-col w-32 p-1">
                        <div class="text-center text-md-center">Nama</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Name</div>
                    </div>
                    <div class="flex items-center w-1 mx-auto">:</div>
                    <div class="flex items-center w-full mx-auto ml-2 text-xl font-bold">
                        {{$request_arrival->vessel_tb?->name}}
                    </div>
                </div>
            </div>
            <div class="flex flex-row border basis-1/2 border-slate-950 ">
                <div class="flex w-1/2 border border-slate-950">
                    <div class="flex-col w-16 p-1">
                        <div class="text-center text-md-center">Jam</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Time</div>
                    </div>
                    <div class="flex items-center">:</div>
                    <div class="flex items-center ml-2 text-xl font-bold">
                        {{$spk_pandu->waktu_pandu ? \Carbon\Carbon::parse($spk_pandu->waktu_pandu)->toTimeString() : ''}}
                    </div>
                </div>
                <div class="flex w-1/2 border border-slate-950">
                    <div class="flex-col w-16 p-1">
                        <div class="text-center text-md-center">Sampai</div>
                        <hr class="border-t-2 border-slate-950">
                        <div class="text-center text-md-center">Up to</div>
                    </div>
                    <div class="flex items-center">:</div>
                    <div class="flex items-center ml-2 text-xl font-bold">

                    </div>
                </div>
            </div>
        </div>
        <div>

        </div>
    </div>
</div>
@section('scripts')
    <script src="https://cdn.tailwindcss.com"></script>
@endsection
