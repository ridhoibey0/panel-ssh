@extends('layouts.member')
@section('content')
    <div class="page-heading">
        <h3>Selamat datang, {{ Auth::user()->name }}!</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldWallet"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Saldo</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format(Auth::user()->balance) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">SSH Active</h6>
                                        <h6 class="font-extrabold mb-0">{{ $totalServiceActive }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon yellow mb-2">
                                            <i class="iconly-boldGraph"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Belanja</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalSpend) }}</h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldGraph"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Deposit</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalDeposit) }}</h6>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Informasi</h4>
                            </div>
                            <div class="card-content pb-4">
                                <div class="recent-message d-flex px-4 py-3">
                                    <i class="bi bi-info-circle"></i>
                                    <div class="name ms-3">
                                        <h5 class="mb-1">Gabung Group Whatsapp</h5>
                                        <h6 class="text-muted mb-0">KLIK <a href="DISINI">DISINI</a> UNTUK GABUNG GROUP
                                            WHATSAPP</h6>
                                    </div>
                                </div>
                                <div class="recent-message d-flex px-4 py-3">
                                    <i class="bi bi-info-circle"></i>
                                    <div class="name ms-3">
                                        <h5 class="mb-1">FREE TRIAL</h5>
                                        <h6 class="text-muted mb-0">KUSUS UNTUK RESELLER JIKA INGIN MENAMBAH BATAS TRIAL
                                            KLIK DISINI </h6>
                                    </div>
                                </div>
                                <div class="recent-message d-flex px-4 py-3">
                                    <i class="bi bi-info-circle"></i>
                                    <div class="name ms-3">
                                        <h5 class="mb-1">Group Telegram</h5>
                                        <h6 class="text-muted mb-0">Klik Disini Untuk Gabung di Channel Telegram
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                @if (Auth::user()->reseller !== 'ya')
                    <div class="card bg-danger bg-gradient">
                        {{-- <div class="card-body py-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="./assets/compiled/jpg/1.jpg" alt="Face 1">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{Auth::user()->name}}</h5>
                            </div>
                        </div>
                    </div> --}}
                        <div class="card-body">
                            <div class="flex-grow-1 text-white">
                                <h5 class="card-title">Upgrade Reseller</h2>
                                    <p class="fs-6 mb-6 ">Maksimalkan potensi pendapatan Anda! Upgrade ke akun reseller
                                        sekarang
                                        dan nikmati keuntungan eksklusif.</p>
                                    <button class="btn btn-primary " data-bs-toggle="modal"
                                        data-bs-target="#UpgradeReseller">
                                        <span class="indicator-label">
                                            <i class="bi bi-rocket-takeoff-fill me-2"></i>Upgrade Sekarang
                                        </span>
                                    </button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card bg-primary bg-gradient">
                    <div class="card-body">
                        <div class="flex-grow-1 text-white">
                            <h5 class="card-title">Informasi Penting</h5>
                            <p class="fs-6 mb-3">
                                Untuk mendapatkan informasi server terbaru dan promo menarik, bergabunglah dengan grup
                                Telegram kami
                            </p>
                            <a href="https://t.me/sdad" class="btn btn-warning ">
                                <span class="indicator-label">
                                    <i class="bi bi-telegram me-2"></i>Join Sekarang
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <div class="modal fade" id="UpgradeReseller" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable modal-lg"
            role="document">
            <div class="modal-content">
                <div class="p-3">
                    <h1 class="modal-title text-center" id="exampleModalCenterTitle">Upgrade Reseller
                        <p class="text-center fs-5 text-muted fw-semibold">Jika kamu membutuhkan informasi lebih lanjut
                            Silahkan Hubungi Admin.</p>
                </div>


                <div class="modal-body">
                    <div class="card border">
                        <div class="card-body">
                            <h2 class="fw-bold text-dark">Apa yang akan reseller dapatkan?</h2>
                            <p class="text-muted fw-semibold">
                                Semua fitur spesial disini
                            </p>
                            <div class="pt-1">
                                <div class="d-flex align-items-center mb-7">
                                    <p class="fw-semibold fs-5 text-gray flex-grow-1">1 Halaman Web Pribadi Soon</p>
                                    <span style="color: #28a745">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                rx="10" fill="currentColor"></rect>
                                            <path
                                                d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-7">
                                    <p class="fw-semibold fs-5 text-gray flex-grow-1">Restart Layanan Mandiri [Soon]</p>
                                    <span style="color: #28a745">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                rx="10" fill="currentColor"></rect>
                                            <path
                                                d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-7">
                                    <p class="fw-semibold fs-5 text-gray flex-grow-1">Kirim Akun ke WA Buyer [Soon]</p>
                                    <span style="color: #28a745">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                rx="10" fill="currentColor"></rect>
                                            <path
                                                d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-7">
                                    <p class="fw-semibold fs-5 text-gray flex-grow-1">Dukungan Prioritas</p>
                                    <span style="color: #28a745">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                rx="10" fill="currentColor"></rect>
                                            <path
                                                d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-7">
                                    <p class="fw-semibold fs-5 text-gray flex-grow-1">Unlimited Trial</p>
                                    <span style="color: #28a745">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                rx="10" fill="currentColor"></rect>
                                            <path
                                                d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-7">
                                    <p class="fw-semibold fs-5 text-gray flex-grow-1">Kesempatan Ganti Server 1x [soo]</p>
                                    <span style="color: #28a745">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                                rx="10" fill="currentColor"></rect>
                                            <path
                                                d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="btn btn-primary d-flex flex-stack text-start p-6 mb-6 mt-3">
                                <div class="d-flex align-items-center me-2 mt-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center fs-2 fw-bold flex-wrap">Reseller</div>
                                        <div class="fw-semibold opacity-75">Cocok untuk dijual kembali</div>
                                    </div>
                                </div>
                                <div class="ms-auto mt-3">
                                    <h1 class="fw-bold text-white" data-kt-plan-price-month="25.000"
                                        data-kt-plan-price-annual="3399">Rp 25.000</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <form action="" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary ms-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Lanjut Upgrade</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
