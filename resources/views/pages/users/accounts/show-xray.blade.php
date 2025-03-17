@extends('layouts.master')
@section('scss')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
.loader-box .loader-15 {
  background: #ffffff;
  position: relative;
  animation: loader-15 1s ease-in-out infinite;
  animation-delay: 0.4s;
  width: 0.25em;
  height: 0.5em;
  margin: 0 0.5em;
}
.loader-box .loader-15:after, .loader-box .loader-15:before {
  content: "";
  position: absolute;
  width: inherit;
  height: inherit;
  background: inherit;
  animation: inherit;
}
.loader-box .loader-15:before {
  right: 0.5em;
  animation-delay: 0.2s;
}
.loader-box .loader-15:after {
  left: 0.5em;
  animation-delay: 0.6s;
}
@keyframes loader-15 {
  0%, 100% {
    box-shadow: 0 0 0 #ffffff, 0 0 0 #ffffff;
  }
  50% {
    box-shadow: 0 -0.25em 0 #ffffff, 0 0.25em 0 #ffffff;
  }
}
.uuid-box {
    background-color: white;
}
.flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.nav-pills1 {
      --tblr-nav-pills-border-radius: 3px;
      --tblr-nav-pills-link-active-color: #007bff;  /* Warna primer */
      --tblr-nav-pills-link-active-bg: var(--tblr-active-bg);
    }
    .nav-pills1 .nav-link {
      background: 0 0;
      border: 0;
      border-radius: 5px;
      transition: background-color 0.2s; /* animasi transisi untuk perubahan warna latar belakang */
    }

    /* Menambahkan efek ketika link aktif */
    .nav-pills1 .nav-link.active {
      background-color: var(--tblr-nav-pills-link-active-bg);
      color: var(--tblr-nav-pills-link-active-color);
    }

    /* Opsional: Tambahkan efek hover */
    .nav-pills1 .nav-link:hover {
      background-color: rgba(0, 0, 0, 0.1); /* efek hover dengan latar belakang sedikit lebih gelap */
    }
    .label-wrapper {
        display: block;
    }
</style>
@endsection
@section('content')
<div class="page-body">
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-6">
            <h3>Details Accounts {{ $categoryData->name }}</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                  <svg class="stroke-icon">
                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                  </svg></a></li>
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item active"> Details Accounts {{ $categoryData->name }}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
     <div class="row">
    <div class="col-sm-4 mb-4">
    <div class="card uuid-box h-100">
        <div class="card-body flex-center">
            <form id="NewUUID" method="POST" action="{{ route('accounts.update', [$categoryData->slug, $account->username]) }}" class="mb-3">
                @csrf
                <div class="balance-profile">
                    <h5 class="f-light d-block mb-4 text-center">Password/UUID</h5>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">
                                    <a href="javascript:tampilkanTombolGenerate()" style="font-style:italic">
                                        <small>(Generate new UUID)</small>
                                    </a>
                                </label>
                                <div class="input-group w-100">
                                    <span class="input-group-text">
                                        <i class="fa fa-key"></i>
                                    </span>
                                    <input type="hidden" name="uuidlama" value="{{ $accounts['UUID'] ?? '' }}">
                                    <input class="form-control" type="text" id="uuid" name="uuidlama" value="{{ $accounts['UUID'] ?? '' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pricingtable-signup mb-3 text-center">
                        <button class="input-group-text btn btn-primary" type="button" id="copy" style="display:none">Copy</button>
                        <button class="input-group-text btn btn-primary" type="button" id="newuuid"><i id="spinnerIcon"></i> <span id="btnText">Generate</span></button>
                    </div>
                    </div>
                </form>
                   <p class="text-muted mt-3" style="font-size:x-small">
                        Jika mengalami kesulitan saat Generate UUID, mohon <a href="#" class="fw-bold">hubungi Customer Service</a>.<br>
                        Perhatian: Simpan UUID dengan aman. Jika Anda telah mencapai batas pergantian, harap tunggu hingga esok hari untuk Generate kembali.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-8 mb-4">
        <div class="card h-100">
          <div class="card-body method">
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="f-light mb-3">Details Account {{ $categoryData->name }}</h5>
                </div>
                <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="hostname">Hostname</label>
                                    <input type="text" class="form-control" id="hostname" name="hostname" value="{{ $accounts['Host'] }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $accounts['User'] }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="sni">SNI/Bug Host</label>
                                    <input type="text" class="form-control" id="sni" name="sni" value="{{ $accounts['Sni'] }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="create_on">Created At</label>
                                    <input type="text" class="form-control" id="create_on" name="create_on" value="{{ \Carbon\Carbon::parse($account->created_at)->isoFormat('dddd, Y-M-D') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="expired_on">Expired At</label>
                                    <input type="text" class="form-control" id="expired_on" name="expired_on" value="{{ \Carbon\Carbon::parse($account->created_at)->isoFormat('dddd, Y-MM-D') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <div class="label-wrapper">
                                        <label for="status">Status</label>
                                    </div>
                                    <div class="rounded {{ $account->status === 'Active' ? 'bg-success' : ($account->status === 'Inactive' ? 'bg-danger' : 'bg-warning') }} p-10">
                                        {{ $account->status }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="card shadow-sm-6 p-2">
                <ul class="nav nav-pills1 mt-2" id="pills-tab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center active" id="pills-overview-tab" data-bs-toggle="pill" data-bs-target="#pills-overview" type="button" role="tab" aria-controls="pills-overview" aria-selected="true">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z"></path>
                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M15 8l2 0"></path>
                        <path d="M15 12l2 0"></path>
                        <path d="M7 16l10 0"></path>
                      </svg> Overview </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center" id="pills-config-tab" data-bs-toggle="pill" data-bs-target="#pills-config" type="button" role="tab" aria-controls="pills-config" aria-selected="false" tabindex="-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                        <path d="M9 9l1 0"></path>
                        <path d="M9 13l6 0"></path>
                        <path d="M9 17l6 0"></path>
                      </svg> Config </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center" id="pills-port-detail-tab" data-bs-toggle="pill" data-bs-target="#pills-port-detail" type="button" role="tab" aria-controls="pills-port-detail" aria-selected="false" tabindex="-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wall" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                        <path d="M4 8h16"></path>
                        <path d="M20 12h-16"></path>
                        <path d="M4 16h16"></path>
                        <path d="M9 4v4"></path>
                        <path d="M14 8v4"></path>
                        <path d="M8 12v4"></path>
                        <path d="M16 12v4"></path>
                        <path d="M11 16v4"></path>
                      </svg> Port Info </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center" id="pills-download-tab" data-bs-toggle="pill" data-bs-target="#pills-download" type="button" role="tab" aria-controls="pills-download" aria-selected="false" tabindex="-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cloud-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 18.004h-5.343c-2.572 -.004 -4.657 -2.011 -4.657 -4.487c0 -2.475 2.085 -4.482 4.657 -4.482c.393 -1.762 1.794 -3.2 3.675 -3.773c1.88 -.572 3.956 -.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.38 0 2.573 .813 3.13 1.99"></path>
                        <path d="M19 16v6"></path>
                        <path d="M22 19l-3 3l-3 -3"></path>
                      </svg> Downloads </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center" id="pills-class-tab" data-bs-toggle="pill" data-bs-target="#pills-class" type="button" role="tab" aria-controls="pills-class" aria-selected="false" tabindex="-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings-cog" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12.003 21c-.732 .001 -1.465 -.438 -1.678 -1.317a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c.886 .215 1.325 .957 1.318 1.694"></path>
                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M19.001 15.5v1.5"></path>
                        <path d="M19.001 21v1.5"></path>
                        <path d="M22.032 17.25l-1.299 .75"></path>
                        <path d="M17.27 20l-1.3 .75"></path>
                        <path d="M15.97 17.25l1.3 .75"></path>
                        <path d="M20.733 20l1.3 .75"></path>
                      </svg> OpenClash/Clash Generator </button>
                  </li>
                </ul>
                <hr class="mt-0 mb-3">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-overview" role="tabpanel" aria-labelledby="pills-overview-tab">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- Kotak Pertama -->
                                <div class="col-sm-4">
                                    <!--<div class="card h-100">-->
                                        <div class="p-4 bg-light-primary rounded text-dark mb-2">
                                            <div class="d-flex justify-content-between">
                                                <h6>Created</h6>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-hour-9" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                               <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                               <path d="M12 12h-3.5"></path>
                                               <path d="M12 7v5"></path>
                                            </svg>
                                            </div>
                                            <h4 class="fs-6">{{ \Carbon\Carbon::parse($account->created_at)->isoFormat('Y-MM-D - HH:mmA') }}</h4>
                                        </div>
                                        <div class="">
                                        <div class="mb-3">
                                        <label for="servername">Server Name</label>
                                            <input type="text" class="form-control" id="servername" name="servername" value="{{ $account->server->name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="location">Location</label>
                                            <input type="text" class="form-control" id="location" name="location" value="Singapore" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <div class="label-wrapper">
                                                <label for="status">Bandwith</label>
                                            </div>
                                            <div class="rounded bg-primary p-10">
                                                Unlimited
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="label-wrapper">
                                                <label for="status">Status Torrent</label>
                                            </div>
                                            <div class="rounded bg-danger p-10">
                                                {{ $account->server->notes }}
                                            </div>
                                        </div>
                                        </div>
                                    <!--</div>-->
                                </div>
                                <!-- Kotak Kedua -->
                                <div class="col-sm-4">
                                    <!--<div class="card h-100">-->
                                        <div class="p-4 bg-light-primary rounded text-dark mb-2">
                                            <div class="d-flex justify-content-between">
                                                <h6>Expired</h6>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-bolt" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                               <path d="M20.984 12.53a9 9 0 1 0 -7.552 8.355"></path>
                                               <path d="M12 7v5l3 3"></path>
                                               <path d="M19 16l-2 3h4l-2 3"></path>
                                            </svg>
                                            </div>
                                            <h4 class="fs-6">{{ \Carbon\Carbon::parse($account->expired_at)->isoFormat('Y-MM-D - HH:mmA') }}</h4>
                                        </div>
                                        <div class="cy">
                                            <div class="mb-3">
                                        <label for="servername">Server Host</label>
                                            <input type="text" class="form-control" id="servername" name="servername" value="{{ $accounts['Host'] }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pathmulti">Dynamic Path WS</label>
                                            <input type="text" class="form-control" id="pathmulti" name="pathmulti" value="/anything" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pathws">Path VMess WS</label>
                                            <input type="text" class="form-control" id="pathws" name="pathws" value="{{ $accounts['Path'] }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pathgrpc">Path VMess gRPC</label>
                                            <input type="text" class="form-control" id="pathgrpc" name="pathgrpc" value="{{ $accounts['ServiceName'] }}" readonly>
                                        </div>
                                        </div>
                                    <!--</div>-->
                                </div>
                                <!-- Kotak Ketiga -->
                                <div class="col-sm-4">
                                    <!--<div class="card h-100">-->
                                        <div class="p-4 bg-light-primary rounded text-dark mb-2">
                                            <div class="d-flex justify-content-between">
                                                <h6>Price</h6>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                               <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
                                               <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                                            </svg>
                                            </div>
                                            <h4 class="fs-6">IDR. {{ number_format($account->charge,2, ',', '.') }}</h4>
                                        </div>
                                        <div class="cy">
                                            <div class="mb-3">
                                        <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="{{ $accounts['User'] }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="uuid2">Password/UUID</label>
                                            <input type="text" class="form-control" id="uuid2" name="uuid2" value="{{ $accounts['UUID'] ?? '' }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nameserver">NameServer(NS)</label>
                                            <input type="text" class="form-control" id="nameserver" name="nameserver" value="{{ $accounts['NS'] }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nameserver">PubKey</label>
                                            <input type="text" class="form-control" id="nameserver" name="nameserver" value="{{ $accounts['PubKey'] }}" readonly>
                                        </div>
                                        </div>
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-config" role="tabpanel" aria-labelledby="pills-config-tab">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="clipboaard-container">
                                        <p class="f-16">{{ Str::upper($categoryData->name) }} SSL/TLS</p>
                                        <textarea class="form-control f-14" id="ssl_tls" rows="3" spellcheck="false">{{ $accounts['LinkTLS'] }}</textarea>
                                        <div class="mt-3 text-end">
                                          <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#ssl_tls"><i class="fa fa-copy"></i></button>
                                          <button class="btn btn-success qr-button" type="button" data-target="#qrModal" data-value="{{ $accounts['LinkTLS'] }}"><i class="fa fa-qrcode"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="clipboaard-container">
                                        <p class="f-16">{{ Str::upper($categoryData->name) }} SSL/TLS CDN</p>
                                        <textarea class="form-control f-14" id="ssl_cdn" rows="3" spellcheck="false">{{ $accounts['LinkTLS_CDN'] }}</textarea>
                                        <div class="mt-3 text-end">
                                          <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#ssl_cdn"><i class="fa fa-copy"></i></button>
                                          <button class="btn btn-success qr-button" type="button" data-target="#qrModal" data-value="{{ $accounts['LinkTLS_CDN'] }}"><i class="fa fa-qrcode"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="clipboaard-container">
                                        <p class="f-16">{{ Str::upper($categoryData->name) }} NTLS</p>
                                        <textarea class="form-control f-14" id="ntls" rows="3" spellcheck="false">{{ $accounts['LinkNTLS'] }}</textarea>
                                        <div class="mt-3 text-end">
                                          <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#ntls"><i class="fa fa-copy"></i></button>
                                           <button class="btn btn-success qr-button" type="button" data-target="#qrModal" data-value="{{ $accounts['LinkNTLS'] }}"><i class="fa fa-qrcode"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="clipboaard-container">
                                        <p class="f-16">{{ Str::upper($categoryData->name) }} NTLS CDN</p>
                                        <textarea class="form-control f-14" id="ntls_cdn" rows="3" spellcheck="false">{{ $accounts['LinkNTLS_CDN'] }}</textarea>
                                        <div class="mt-3 text-end">
                                          <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#ntls_cdn"><i class="fa fa-copy"></i></button>
                                          <button class="btn btn-success qr-button" type="button" data-target="#qrModal" data-value="{{ $accounts['LinkNTLS_CDN'] }}"><i class="fa fa-qrcode"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="clipboaard-container">
                                        <p class="f-16">{{ Str::upper($categoryData->name) }} GRPC TLS</p>
                                        <textarea class="form-control f-14" id="grpc_tls" rows="3" spellcheck="false">{{ $accounts['LinkgRPC_TLS'] }}</textarea>
                                        <div class="mt-3 text-end">
                                          <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#grpc_tls"><i class="fa fa-copy"></i></button>
                                          <button class="btn btn-success qr-button" type="button" data-target="#qrModal" data-value="{{ $accounts['LinkgRPC_TLS'] }}"><i class="fa fa-qrcode"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="clipboard-container">
                                    <p class="f-16">{{ Str::upper($categoryData->name) }} GRPC TLS CDN</p>
                                    <textarea class="form-control f-14" id="grpc_cdn" rows="3" spellcheck="false">{{ $accounts['LinkgRPC_CDN'] }}</textarea>
                                    <div class="mt-3 text-end">
                                        <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#grpc_cdn">
                                            <i class="fa fa-copy"></i>
                                        </button>
                                        <button class="btn btn-success qr-button" type="button" data-target="#qrModal" data-value="{{ $accounts['LinkgRPC_CDN'] }}">
                                            <i class="fa fa-qrcode"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-port-detail" role="tabpanel" aria-labelledby="pills-port-detail-tab">
                            <div class="row">
                                <div class="col-md-15">
                                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-nowrap">
                                      <thead>
                                        <tr>
                                          <th scope="col">Service Name</th>
                                          <th scope="col">Protocol</th>
                                          <th scope="col">Port</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       <tr class="border-bottom-success">
                                            <td>{{ $categoryData->name }} Websocket TLS</td>
                                            <td>SSL/TLS</td>
                                            <td>{{ $accounts['PortTLS'] }}</td>
                                        </tr>
                                        <tr class="border-bottom-danger">
                                            <td>{{ $categoryData->name }} Websocket TLS CDN</td>
                                            <td>SSL/TLS CDN</td>
                                            <td>{{ $accounts['PortTLS'] }}</td>
                                        </tr>
                                        <tr class="border-bottom-warning">
                                            <td>{{ $categoryData->name }} Websocket NTLS</td>
                                            <td>None TLS</td>
                                            <td>{{ $accounts['PortNTLS'] }}</td>
                                        </tr>
                                        <tr class="border-bottom-secondary">
                                            <td>{{ $categoryData->name }} Websocket NTLS CDN</td>
                                            <td>None TLS CDN</td>
                                            <td>{{ $accounts['PortNTLS'] }}</td>
                                        </tr>
                                        <tr class="border-bottom-dark">
                                            <td>{{ $categoryData->name }} Websocket gRPC TLS</td>
                                            <td>gRPC TLS</td>
                                            <td>{{ $accounts['PortgRPC'] }}</td>
                                        </tr>
                                        <tr class="border-bottom-success">
                                            <td>{{ $categoryData->name }} Websocket gRPC TLS CDN</td>
                                            <td>gRPC TLS CDN</td>
                                            <td>{{ $accounts['PortgRPC'] }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                            </div>
                    </div>
                    <div class="tab-pane fade" id="pills-download" role="tabpanel" aria-labelledby="pills-download-tab">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped nowrap" style="width:100%" id="info-port">
                                            <thead>
                                                <tr class="border-bottom-primary">
                                                    <th>File Name</th>
                                                    <th>Download Link</th>
                                                    <th>Upload At</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-class" role="tabpanel" aria-labelledby="pills-class-tab">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="generateConfigForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="linkInput">Paste Link Here:</label>
                                            <textarea class="form-control" id="linkInput" placeholder="VMess://
Vless://
Trojan://" style="height: 200px;" required="" name="link" rows="10"></textarea>
                                        </div>
                                        <div class="form-group" style="margin-top: 10px;">
                                            <button type="button" class="btn btn-primary" id="generateConfigButton">Generate Config</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <textarea id="generatedConfig" rows="10" class="form-control" style="display: none; resize: none;"></textarea>
                        <div class="mt-3 text-start" id="configButtons" style="display: none;">
                           <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#generatedConfig">
                            <span>Copy Config</span>
                          </button>
                          <a id="downloadLink" class="btn btn-secondary" href="#" download="{{ $categoryData->name}}-config.yml" onclick="downloadConfig();">Download</a>
                         </div>
                        </div>
                       </div>

                      </div>
                    </div>

                 </div>

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- Container-fluid Ends-->
    <div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="{{ $categoryData->name }}">QRcode {{ $categoryData->name }}</h4>
            <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
           <div class="modal-body">
            <div class="modal-toggle-wrapper d-flex justify-content-center">
                <div id="qrCodeCanvas">
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard-script.js') }}"></script>
<script>
document.querySelectorAll('.qr-button').forEach(button => {
    button.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        fetch(`https://script.youdomain.com/api/v1/qrcode?value=${value}`)
        .then(response => response.blob())
        .then(blob => {
            const qrCodeCanvas = document.getElementById('qrCodeCanvas');
            const imageUrl = URL.createObjectURL(blob);
            qrCodeCanvas.innerHTML = `<img src="${imageUrl}" alt="QR Code">`;

            $('#qrModal').modal('show');
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
<script>
    function change() {
        var x = document.getElementById('password').type;

        if (x == 'password') {
            document.getElementById('password').type = 'text';

            document.getElementById('mybutton').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
   <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
   <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"></path>
   <path d="M3 3l18 18"></path>
</svg>`;
        }
        else {
            document.getElementById('password').type = 'password';

            document.getElementById('mybutton').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
   <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
   <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
</svg>`;
        }
    }
</script>
<script>
    $(document).ready(function() {
        new DataTable('#info-port', {
            processing: true,
            responsive: true,
            searching: false,
            lengthChange: false,
            paging: false,
            dom: 'rtip',
            language: {
      info: ''
    }
        })
        aturTombol();

        $('#newuuid').on('click', function() {
            let url = "{{ route('accounts.update', [$categoryData->slug, $account->username]) }}";
            let data = $('#NewUUID').serialize();
            $("#btnText").html('<div class="loader-box"><div class="loader-15"></div></div>');
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                  $("#btnText").html('<div class="loader-box"><div class="loader-15"></div></div>');
                },
                success: function(response) {
                    if (response.status == 'success') {
                        aturTombol();
                        $('#uuid').val(response.data.replace(/"/g, ''));
                        $('#uuid2').val(response.data.replace(/"/g, ''));
                        swal("Success", response.message, "success");
                    } else {
                        aturTombol();
                        swal("Error", response.message, "warning");
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        aturTombol(); // Update the button state
                        alert(xhr.responseJSON.message);
                    } else {
                        aturTombol(); // Update the button state
                        alert('Terjadi kesalahan saat melakukan request.');
                    }
                },
                complete: function() {
                    $("#spinnerIcon").removeClass("fa fa-spin fa-cog");
                    $("#btnText").text("Generate");
                }
            });
        });
    });

    function tampilkanTombolGenerate() {
    $('#copy').hide();
    $('#newuuid').show();
    }

    function aturTombol() {
        if ($('#uuid').val() === null || $('#uuid').val().trim() === '') {
            $('#copy').hide();
            $('#newuuid').show();
        } else {
            $('#newuuid').hide();
            $('#copy').show();
        }
    }
</script>
<script>
$(document).ready(function () {
    $('#generateConfigButton').click(function () {
        var link = $('#linkInput').val();
        var token = $('input[name="_token"]').val();

        var data = {
            link: link,
            _token: token
        };

        $.ajax({
            url: 'https://script.youdomain.com/api/v1/subconverter',
            type: 'POST',
            dataType: 'text',
            data: data,
            success: function (response) {
                $('#generatedConfig').val(response);
                $('#generatedConfig').show();
                $('#configButtons').show();
                swal({
                    title: "Succeed!",
                    text: "Successfully Convert.",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function (error) {
                console.error('Error:', error);

                var errorResponse = JSON.parse(error.responseText);
                var errorMessage = errorResponse.error;
                swal({
                    title: "Error!",
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    });
});
    function downloadConfig() {
            var content = document.getElementById('generatedConfig').value;
            var blob = new Blob([content], { type: 'text/yaml' });
            var url = window.URL.createObjectURL(blob);
            var downloadLink = document.getElementById('downloadLink');
            downloadLink.href = url;
        }
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const generalClipboard = new ClipboardJS('.btn-clipboard');
    generalClipboard.on('success', function(e) {
        swal({
            title: "Succeed!",
            text: "Text successfully copied to clipboard.",
            type: "success",
            timer: 1500,
            showConfirmButton: false
        });
        e.clearSelection();
    });
    generalClipboard.on('error', function() {
        swal({
            title: "Error!",
            text: "Failed to copy text. Try again.",
            type: "error"
        });
    });
    const uuidClipboard = new ClipboardJS('#copy', {
        target: function() {
            return document.getElementById('uuid');
        }
    });
    uuidClipboard.on('success', function(e) {
        swal({
            title: "Succeed!",
            text: "UUID successfully copied to clipboard.",
            type: "success",
            timer: 1500,
            showConfirmButton: false
        });
        e.clearSelection();
    });
    uuidClipboard.on('error', function() {
        swal({
            title: "Error!",
            text: "Failed to copy UUID. Try again.",
            type: "error"
        });
    });
});
</script>
@endpush
