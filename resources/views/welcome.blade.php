<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ env('APP_NAME', 'Test') }} || Jual Vpn Mudah dan Murah!</title>
    <meta name="title" content="VPN MURAH - TEMPAT BELI DAN SEWA VPN TERMURAH">
    <meta name="description" content="VPN MURAH - TEMPAT BELI DAN SEWA VPN TERMURAH">
    <meta property="og:type" content="website">
    <meta property="og:title" content="VPN MURAH - TEMPAT BELI DAN SEWA VPN TERMURAH">
    <meta property="og:description" content="VPN MURAH - TEMPAT BELI DAN SEWA VPN TERMURAH">
    <meta property="twitter:title" content="VPN MURAH - TEMPAT BELI DAN SEWA VPN TERMURAH">
    <meta property="twitter:description" content="VPN MURAH - TEMPAT BELI DAN SEWA VPN TERMURAH">
    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/ico" />
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <link rel="stylesheet" href="{{ asset('home/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('home/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('home/css/lineicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('home/css/ud-styles.css') }}" />
</head>

<body>
    <header class="ud-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="{{ asset('assets/logo.svg') }}" alt="Logo" />
                        </a>
                        <button class="navbar-toggler">
                            <span class="toggler-icon"> </span>
                            <span class="toggler-icon"> </span>
                            <span class="toggler-icon"> </span>
                        </button>

                        <div class="navbar-collapse">
                            <ul id="nav" class="navbar-nav mx-auto">
                                <li class="nav-item">
                                    <a class="ud-menu-scroll" href="#home">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="ud-menu-scroll" href="#services">Layanan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="ud-menu-scroll" href="#about">Tentang</a>
                                </li>
                                <li class="nav-item">
                                    <a class="ud-menu-scroll" href="#pricing">Harga</a>
                                </li>
                            </ul>
                        </div>

                        <div class="navbar-btn d-none d-sm-inline-block">
                            @guest
                                <a href="{{ url('/login') }}" class="ud-main-btn ud-login-btn">
                                    Masuk
                                </a>
                                <a href="{{ url('/register') }}" class="ud-main-btn ud-white-btn" href="javascript:void(0)">
                                    Registrasi
                                </a>
                            @else
                                <a href="{{ url('/dashboard') }}" class="ud-main-btn ud-login-btn">
                                    Dashboard
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="ud-main-btn ud-white-btn">
                                        Keluar
                                    </a>
                            @endguest
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <section class="ud-hero" id="home">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ud-hero-content wow fadeInUp" data-wow-delay=".2s">
                        <h1 class="ud-hero-title">
                            Akses SSH Premium Secara Mudah dan Murah!
                        </h1>
                        <p class="ud-hero-desc">
                            Dapatkan akses SSH premium ke berbagai situs secara mudah dan murah .
                        </p>
                        <ul class="ud-hero-buttons">
                            <li>
                                @guest
                                    <a href="{{ url('/masuk') }}" class="ud-main-btn ud-white-btn">
                                        Masuk
                                    </a>
                                @else
                                    <a href="{{ url(optional(Auth::user())->role === 'member' ? '/member' : '/admin') }}"
                                        class="ud-main-btn ud-white-btn">
                                        Dasboard
                                    </a>
                                @endguest
                            </li>
                        </ul>
                    </div>
                    <div class="ud-hero-image wow fadeInUp" data-wow-delay=".25s">
                        <img src="{{ asset('home/img/image.png') }}" alt="hero-image"
                            style="border-top-left-radius:15px;border-top-right-radius:15px;" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="services" class="ud-team">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ud-section-title mx-auto text-center">
                        <h2>Tentang Kami</h2>
                        <p>
                            Dapatkan akses ke ssh dan layanan lainnya secara mudah dan murah
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-3 col-sm-6">
                  <div class="ud-single-feature wow fadeInUp" data-wow-delay=".1s">
                    <div class="ud-feature-icon">
                      <i class="lni lni-gift"></i>
                    </div>
                    <div class="ud-feature-content">
                      <h3 class="ud-feature-title">Support All Devices</h3>
                      <p class="ud-feature-desc">
                        Aplikasi VPN mendukung semua Devices mulai dari Desktop sampai Mobile Apps..
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-sm-6">
                  <div class="ud-single-feature wow fadeInUp" data-wow-delay=".15s">
                    <div class="ud-feature-icon">
                      <i class="lni lni-move"></i>
                    </div>
                    <div class="ud-feature-content">
                      <h3 class="ud-feature-title">Hi Speed Networks</h3>
                      <p class="ud-feature-desc">
                        Seluruh server di pilihan VPN berkecepatan tinggi dengan minimal networks speed 1 Gbps.
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-sm-6">
                  <div class="ud-single-feature wow fadeInUp" data-wow-delay=".2s">
                    <div class="ud-feature-icon">
                      <i class="lni lni-locked-2"></i>
                    </div>
                    <div class="ud-feature-content">
                      <h3 class="ud-feature-title">Privasi Aman</h3>
                      <p class="ud-feature-desc">
                        Ketika Anda melakukan koneksi ke VPN, seluruh aktivitas Anda akan terenkripsi.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
          </div>
    </section>
    <section id="pricing" class="ud-pricing">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ud-section-title mx-auto text-center">
                        <h2>Harga Paket</h2>
                        <p>
                            Temukan paket layanan yang paling sesuai dengan Kebutuhan anda.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-0 align-items-center justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="ud-single-pricing  wow fadeInUp" data-wow-delay=".15s">
                        <div class="ud-pricing-header">
                            <h3>PAKET MINGGUAN</h3>
                            <h4>Rp 10.000</h4>
                        </div>
                        <div class="ud-pricing-body">
                            <ul>
                                <li>Military-grade encryption</li>
                                <li>No-logs policy</li>
                                <li>High speed connection</li>
                                <li>Windows, Android, Mac & iPhone Apps</li>
                            </ul>
                        </div>
                        <div class="ud-pricing-footer">
                            <a href="{{ url('/member/buy?plan=weekly') }}" class="ud-main-btn ud-border-btn">
                                Beli Paket
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="ud-single-pricing active wow fadeInUp" data-wow-delay=".1s">
                        <span class="ud-popular-tag">POPULER</span>
                        <div class="ud-pricing-header">
                            <h3>PAKET MINGGUAN</h3>
                            <h4>Rp 10.000</h4>
                        </div>
                        <div class="ud-pricing-body">
                            <ul>
                                <li>Military-grade encryption</li>
                                <li>No-logs policy</li>
                                <li>High speed connection</li>
                                <li>Windows, Android, Mac & iPhone Apps</li>
                            </ul>
                        </div>
                        <div class="ud-pricing-footer">
                            <a href="{{ url('/member/buy?plan=weekly') }}" class="ud-main-btn ud-border-btn">
                                Beli Paket
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="ud-single-pricing wow fadeInUp" data-wow-delay=".15s">
                        <div class="ud-pricing-header">
                            <h3>PAKET MINGGUAN</h3>
                            <h4>Rp 10.000</h4>
                        </div>
                        <div class="ud-pricing-body">
                            <ul>
                                <li>Military-grade encryption</li>
                                <li>No-logs policy</li>
                                <li>High speed connection</li>
                                <li>Windows, Android, Mac & iPhone Apps</li>
                            </ul>
                        </div>
                        <div class="ud-pricing-footer">
                            <a href="{{ url('/member/buy?plan=weekly') }}" class="ud-main-btn ud-border-btn">
                                Beli Paket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="faq" class="ud-faq">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ud-section-title text-center mx-auto">
                        <h2>Punya Pertanyaan?</h2>
                        <p>
                            Temukan jawaban anda dibawah ini atau kontak email kami.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="ud-single-faq wow fadeInUp" data-wow-delay=".1s">
                        <div class="accordion">
                            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne">
                           
                                <span>Bagaimana Cara Menggunakannya?</span>
                            </button>
                            <div id="collapseOne" class="accordion-collapse collapse">
                                <div class="ud-faq-body">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora reiciendis aspernatur ut illum obcaecati officia deserunt veniam ullam nulla molestiae exercitationem, consectetur expedita commodi voluptates provident aliquid, voluptatem voluptatibus eius.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ud-single-faq wow fadeInUp" data-wow-delay=".15s">
                        <div class="accordion">
                            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo">
                        
                                <span>Bagaimana Jika Layanan Bermasalah?</span>
                            </button>
                            <div id="collapseTwo" class="accordion-collapse collapse">
                                <div class="ud-faq-body">
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Possimus et, expedita accusantium ratione harum nisi aperiam? Facere, ut nulla! Rerum qui inventore repudiandae totam at asperiores consequatur nemo rem non.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ud-single-faq wow fadeInUp" data-wow-delay=".2s">
                        <div class="accordion">
                            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree">
                        
                                <span>Apakah VPN Boleh Dibagikan?</span>
                            </button>
                            <div id="collapseThree" class="accordion-collapse collapse">
                                <div class="ud-faq-body">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis eveniet nam inventore laboriosam iusto tempora. Iure, quasi, earum quibusdam illum nobis quaerat culpa qui ut delectus, iusto voluptates ex quisquam.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ud-single-faq wow fadeInUp" data-wow-delay=".1s">
                        <div class="accordion">
                            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour">
                           
                                <span>Apakah Ada Kebijakan Refund?</span>
                            </button>
                            <div id="collapseFour" class="accordion-collapse collapse">
                                <div class="ud-faq-body">
                                 Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod voluptatibus quas reiciendis quae modi officia, ab quis earum doloremque illum error culpa sapiente similique impedit mollitia aliquam? Ex, doloremque dolores.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ud-single-faq wow fadeInUp" data-wow-delay=".15s">
                        <div class="accordion">
                            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapseFive">
                           
                                <span>Apakah Bisa Kustom Paket?</span>
                            </button>
                            <div id="collapseFive" class="accordion-collapse collapse">
                                <div class="ud-faq-body">
                                    Bisa
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ud-single-faq wow fadeInUp" data-wow-delay=".2s">
                        <div class="accordion">
                            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapseSix">
                           
                                <span>Apakah Bisa Menawarkan Kerjasama?</span>
                            </button>
                            <div id="collapseSix" class="accordion-collapse collapse">
                                <div class="ud-faq-body">
                                   Lorem ipsum, dolor sit amet consectetur adipisicing elit. Officiis fuga assumenda quis autem voluptatum non quibusdam aspernatur aut eum nam impedit omnis tempore aliquid culpa necessitatibus provident beatae, blanditiis dolore?
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="ud-footer wow fadeInUp" data-wow-delay=".15s">
        <div class="ud-footer-widgets">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="ud-widget">
                            <a href="{{ url('/') }}" class="ud-footer-logo">
                                <img src="{{ asset('assets/logo.svg') }}" alt="logo" />
                            </a>
                            <p class="ud-widget-desc">
                                Akses SSH Premium Secara Mudah dan Murah!
                            </p>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Navigasi</h5>
                            <ul class="ud-widget-links">
                                <li>
                                    <a href="#home">Rumah</a>
                                </li>
                                <li>
                                    <a href="#services">Layanan</a>
                                </li>
                                <li>
                                    <a href="#about">Tentang</a>
                                </li>
                                <li>
                                    <a href="#pricing">Harga</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Halaman</h5>
                            <ul class="ud-widget-links">
                                <li>
                                    <a href="{{ url('/news') }}">Berita</a>
                                </li>
                                <li>
                                    <a href="{{ url('/tos') }}">Ketentuan Layanan</a>
                                </li>
                                <li>
                                    <a href="{{ url('/privacy') }}">Kebijakan Privasi</a>
                                </li>
                                <li>
                                    <a href="url('/tos#refund')">Kebijakan Refund</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Produk</h5>
                            <ul class="ud-widget-links">
                                <li>
                                    <a href="{{ url('/member/buy?plan=weekly') }}">Paket Mingguan</a>
                                </li>
                                <li>
                                    <a href="{{ url('/member/buy?plan=monthly') }}">Paket Bulanan</a>
                                </li>
                                <li>
                                    <a href="{{ url('/member/buy?plan=quarterly') }}">Paket 3 Bulan</a>
                                </li>
                                <li>
                                    <a href="mailto:premify.cs@gmail.com">Tawaran Kerjasama</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-8 col-sm-10">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Pembayaran</h5>
                            <ul class="ud-widget-brands">
                                <li>
                                        <img src="{{ asset('home/img/qris.png') }}"
                                            alt="qris" />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ud-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <ul class="ud-footer-bottom-left">
                            <li>
                                <a href="{{ url('/tos') }}">Ketentuan Layanan</a>
                            </li>
                            <li>
                                <a href="{{ url('/tos#privacy') }}">Kebijakan Privasi</a>
                            </li>
                            <li>
                                <a href="{{ url('/tos#refund') }}">Kebijakan Refund</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <p class="ud-footer-bottom-right">
                            Hak Cipta &copy; {{ gmdate('Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('home/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('home/js/wow.min.js') }}"></script>
    <script src="{{ asset('home/js/main.js') }}"></script>
</body>

</html>
