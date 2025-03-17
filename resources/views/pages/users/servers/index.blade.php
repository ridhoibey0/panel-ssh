@extends('layouts.member')
@push('addon-style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/extra-component-sweetalert.css') }}">
@endpush
@section('content')
    <div class="page-content mb-4">
        <div class="container">
            {{-- <div class="row">
                @foreach ($servers as $item)
                    <div class="col-lg-4">
                        <div class="card h-100">
                            <div class="card-header flex-nowrap border-9 pt-9">
                                <div class="card-title m-0">
                                    <p class="fs-2 fw-bold text-center">{{ $item->nama }}</p>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column px-9 pt-6 pb-8">
                                <!--begin::Heading-->
                                <h1 class="fs-2tx fw-bold mb-3">
                                    {{ Auth::user()->reseller == 'ya' ? number_format($item->harga_reseller, 0, '.', '.') : number_format($item->harga_member, 0, '.', '.') }}/bulan
                                </h1>
                                @if (Auth::user()->reseller !== 'ya')
                                    <div class="fw-bold text-danger me-2">Harga Reseller
                                        Rp {{ number_format($item->harga_reseller, 0, '.', '.') }}
                                    </div>
                                @endif
                                <div class="fw-bold text-primary me-2">Tersedia
                                    {{ $item->slot_server - $item->slot_terpakai }} Slot</div>
                                @if ($item->stb === 'ya')
                                    <div class="fw-bold text-success me-2">Untuk Pengguna
                                        STB : Di Ijinkan</div>
                                @else
                                    <div class="fw-bold text-danger me-2">Untuk Pengguna STB : Banned</div>
                                @endif
                                <div class="d-flex justify-content-between mt-auto" style="gap: 20px;">
                                    <a data-bs-toggle="modal" id="btn-detail-deposit" data-bs-target="#buyModal"
                                        class="btn btn-outline btn-outline-success btn-active-light-primary w-100 ">
                                        Beli
                                    </a>


                                    <a href="#"
                                        class="btn btn-outline btn-outline-warning btn-active-light-primary w-100"
                                        onclick="handleTrialClick(3, 12)">Trial</a>
                                </div>
                                <div class="d-flex align-items-center fw-semibold">
                                    <br>
                                </div>
                                <div class="d-flex justify-content-between mt-auto" style="gap: 20px;">
                                    <a href="#"
                                        class="btn btn-outline btn-outline-primary btn-active-light-primary w-100"
                                        onclick="checkServer()">Cek Server</a>
                                    <a href="#"
                                        class="btn btn-outline btn-outline-danger btn-active-light-primary w-100"
                                        onclick="restartServer()">Restart
                                        Server</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div> --}}
            <div class="row">
                @forelse ($servers as $server)
                    <div class="col-12 col-md-4 mb-3">
                        <div class="card" style="padding: 10px;">
                            <div class="card-body p-2">
                                <div class="text-center mb-2 mt-2">
                                    <h4 class="f-light d-block mb-1">{{ Str::upper($server->name) }}</h4>
                                    <span class="badge bg-primary fw-bold">{{ $server->country->name }}</span>
                                    {!! $server->status == 'online'
                                        ? '<label class="badge bg-success">Online</label>'
                                        : '<label class="badge bg-danger">Offline</label>' !!}
                                    {{-- <hr> --}}
                                </div>
                                <ul class="text-start list-unstyled">
                                    <li><i class="icofont icofont-hand-drawn-right"></i> Host : <b>{{ $server->host }}</b>
                                    </li>
                                    <li><i class="icofont icofont-hand-drawn-right"></i> ISP : <b> {{ $server->isp }}</b>
                                    </li>
                                    <li><i class="icofont icofont-hand-drawn-right"></i> Price Monthly : <b>IDR.
                                            {{ $server->prices->where('role_id', Auth::user()->roles->first()->id)->first() ? number_format($server->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_monthly, 0) : 'N/A' }}</b>
                                    </li>
                                    <li><i class="icofont icofont-hand-drawn-right"></i> Price Hourly : <b>IDR.
                                            {{ $server->prices->where('role_id', Auth::user()->roles->first()->id)->first() ? number_format($server->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_hourly, 0) : 'N/A' }}</b>
                                    </li>

                                    @foreach ($server->ports as $key => $value)
                                        <li><i class="icofont icofont-hand-drawn-right"></i> {{ $key }} :
                                            <b>{{ $value }}</b></li>
                                    @endforeach

                                    <li><i class="icofont icofont-hand-drawn-right"></i> SlowDNS : <b> <span
                                                class="badge bg-success">Support</span></b></li>
                                    <li><i class="icofont icofont-hand-drawn-right"></i> UDP : <b> <span
                                                class="badge bg-success">Support</span></b></li>
                                    <li><i class="icofont icofont-hand-drawn-right"></i> Max Device : <b> <span
                                                class="badge bg-warning">2 Session</span></b></li>
                                    <li><i class="icofont icofont-hand-drawn-right"></i> Torrent : <b> <span
                                                class="badge bg-danger">Not Allow</span></b></li>
                                    <li class="pricing-list-item">
                                        <div class="text-center mt-3">
                                            <label class="badge bg-primary custom-label">
                                                <svg viewBox="0 0 24 24" width="12" height="10" stroke="currentColor"
                                                    stroke-width="2" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                                {{ round(($server->current / $server->limit) * 100, 2) }}%
                                                {{ round(($server->current / $server->limit) * 100, 2) < 100 ? 'Account Created' : 'Fully Created!' }}
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                                <div class="d-grid gap-2 mt-4">
                                    <a href="{{ route('servers.create', [$server->category->slug, $server->slug]) }}"
                                        class="btn btn-primary">Select</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center">
                        Server {{ $categoryData->name }} Not Found
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
@push('addon-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const serviceSelect = document.getElementById("service-select");
        const hargaReseller = document.getElementById("harga-reseller");
        const slotTersedia = document.getElementById("slot-tersedia");
        const stb = document.getElementById('stb-usage');
        const detailService = document.getElementById("detail-service");
        const hargaResellerValue = document.getElementById("harga-reseller-value");
        const durationSelect = document.getElementById("durasi");
        const totalPriceEl = document.getElementById("infoCharge")

        function updateServiceDescription() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const hargaResellerText = selectedOption.getAttribute("data-harga-reseller");
            const slotTersediaText = selectedOption.getAttribute("data-slot");
            const stbAllowed = selectedOption.getAttribute("data-stb");
            slotTersedia.textContent = slotTersediaText;
            durationSelect.disabled = false;
            if (stbAllowed == 'ya') {
                stb.classList.add('text-success', 'fw-bold')
                stb.textContent = 'Bisa Digunakan Untuk STB'
            }
            detailService.classList.remove('d-none')
            @if (Auth::user()->reseller !== 'ya')
                hargaReseller.style.display = "block";
                hargaResellerValue.textContent = hargaResellerText;
            @else
                hargaReseller.style.display = "none";
            @endif
        }

        function updatePrice() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const hargaResellerText = selectedOption.getAttribute("data-harga-reseller");
            const hargaMemberText = selectedOption.getAttribute("data-harga-member");
            const duration = durationSelect ? parseInt(durationSelect.value) : 1;

            let price = 0;
            @if (Auth::user()->reseller == 'ya')
                price = parseInt(hargaResellerText);
            @else
                price = parseInt(hargaMemberText);
            @endif
            console.log(price)
            const totalPrice = price * duration;

            if (totalPriceEl) {
                totalPriceEl.value = totalPrice;
            }
        }
        serviceSelect.addEventListener("change", updateServiceDescription);
        durationSelect.addEventListener("change", updatePrice)

        function handleBuyClick() {
            const modal = new boostrap.Modal(document.querySelector('#buyModal'))
            modal.show();
        }

        function checkServer() {

            Swal.fire({

                icon: 'success',
                title: 'Server Online Tuan',
                showConfirmButton: true,
                confirmButtonText: 'OK Mengerti!',
                heightAuto: false, // Menambahkan opsi heightAuto: false
                customClass: {
                    confirmButton: 'btn btn-success' // Menambahkan kelas custom untuk tombol konfirmasi
                },
                buttonsStyling: false
            });
        }

        function restartServer() {
            Swal.fire({

                icon: 'success',
                title: 'Layanan server berhasil di restart',
                showConfirmButton: true,

                confirmButtonText: 'OK Mengerti!',

                heightAuto: false,
                customClass: {

                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();

                }
            });

        }
    </script>
@endpush
