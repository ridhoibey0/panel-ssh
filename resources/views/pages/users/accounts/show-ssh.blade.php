@extends('layouts.member')
@push('addon-style')
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
</style>
@endpush
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
            <form id="NewPassword" class="mb-3">
                <div class="balance-profile">
                    <h5 class="f-light d-block mb-4 text-center">Password</h5>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                            <label class="form-label">
                                <a href="javascript:change()" style="font-style:italic">
                                    <small>(Show Password)</small>
                                </a>
                            </label>
                            <div class="input-group w-100">
                                <input class="form-control" type="password" id="password1" name="password" value="{{ $accounts['Password'] ?? '' }}">
                            </div>
                        </div>
                        </div>
                    </div>
                   <div class="pricingtable-signup mb-3 text-center">
                    <button class="input-group-text btn btn-primary" type="button" id="copy" style="display:none">Copy</button>
                    <button class="input-group-text btn btn-primary" type="button" id="changepass">
                        <i id="spinnerIcon"></i> <span id="btnText">Change Password</span>
                    </button>
                   </div>
                        
                    </div>
                </form>
                   <p class="text-muted mt-3" style="font-size:x-small">
                        Jika mengalami kesulitan saat Generate Password, mohon <a href="#" class="fw-bold">hubungi Customer Service</a>.<br>
                        Perhatian: Simpan Password dengan aman. Jika Anda telah mencapai batas pergantian, harap tunggu hingga esok hari untuk Generate kembali.
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
                                    <input type="text" class="form-control" id="hostname" name="hostname" value="{{ $accounts['hostname'] }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $accounts['username'] }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control" id="password2" name="password" value="{{ $accounts['password'] }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="create_on">Created At</label>
                                    <input type="text" class="form-control" id="create_on" name="create_on" value="{{ \Carbon\Carbon::parse($account->created_at)->isoFormat('dddd, Y-M-D') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="expired_on">Expired At</label>
                                    <input type="text" class="form-control" id="expired_on" name="expired_on" value="{{ \Carbon\Carbon::parse($account->created_at)->isoFormat('dddd, Y-M-D') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <input type="text" class="form-control" id="status" name="status" value="{{ $account->status }}" readonly>
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
                                      <label for="bandwith">Bandwith</label>
                                      <input type="text" class="form-control" id="bandwith" name="bandwith" value="Unlimited" readonly>
                                  </div>
                                  <div class="mb-3">
                                      <label for="sni">Status Torrent</label>
                                      <input type="text" class="form-control" id="sni" name="sni" value="{{ $account->server->notes }}" readonly>
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
                                      <input type="text" class="form-control" id="servername" name="servername" value="{{ $accounts['hostname'] }}" readonly>
                                  </div>
                                  <div class="mb-3">
                                      <label for="sni">SNI Bug/Host</label>
                                      <input type="text" class="form-control" id="sni" name="sni" value="{{ $accounts['hostname'] }}" readonly>
                                  </div>
                                  <div class="mb-3">
                                      <label for="device_session">Max Device</label>
                                      <input type="text" class="form-control" id="device_session" name="device_session" value="2 Session" readonly>
                                  </div>
                                  <div class="mb-3">
                                      <label for="pathmulti">Dynamic Path WS</label>
                                      <input type="text" class="form-control" id="pathmulti" name="pathmulti" value="/anything" readonly>
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
                                      <input type="text" class="form-control" id="username" name="username" value="{{ $accounts['username'] }}" readonly>
                                  </div>
                                  <div class="mb-3">
                                      <label for="password">Password</label>
                                      <input type="text" class="form-control" id="password3" name="password" value="{{ Str::of($accounts['password'])->mask('*', 2) }}" readonly>
                                  </div>
                                  {{-- <div class="mb-3">
                                      <label for="nameserver">NameServer(NS)</label>
                                      <input type="text" class="form-control" id="nameserver" name="nameserver" value="{{ $accounts['ns'] }}" readonly>
                                  </div> --}}
                                  <div class="mb-3">
                                      <label for="nameserver">PubKey</label>
                                      <input type="text" class="form-control" id="nameserver" name="nameserver" value="{{ $accounts['pubkey'] }}" readonly>
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
                                  <textarea class="form-control f-14" id="ssl_tls" rows="3" spellcheck="false">{{ $accounts['port']['tls'] }}</textarea>
                                  <div class="mt-3 text-end">
                                    <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#ssl_tls"><i class="fa fa-copy"></i></button>
                                  </div>
                              </div>
                          </div>
                          {{-- <div class="col-md-6">
                              <div class="clipboaard-container">
                                  <p class="f-16">{{ Str::upper($categoryData->name) }} NTLS</p>
                                  <textarea class="form-control f-14" id="sshntls" rows="3" spellcheck="false">{{ $accounts['LinkNTLS'] }}</textarea>
                                  <div class="mt-3 text-end">
                                    <button class="btn btn-warning btn-clipboard" type="button" data-clipboard-action="copy" data-clipboard-target="#sshntls"><i class="fa fa-copy"></i></button>
                                  </div>
                              </div>
                          </div> --}}
                          </div>
                      </div>
                  </div>
              <div class="tab-pane fade" id="pills-port-detail" role="tabpanel" aria-labelledby="pills-port-detail-tab">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="table-responsive">
                                  <table class="table table-striped nowrap" style="width:100%" id="info-port">
                                      <thead>
                                          <tr class="border-bottom-primary">
                                              <th>Service Name</th>
                                              <th>Protocol</th>
                                              <th>Port</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr class="border-bottom-success">
                                              <td>{{ $categoryData->name }} Websocket TLS</td>
                                              <td>SSL/TLS</td>
                                              <td>{{ $accounts['port']['tls'] }}</td>
                                          </tr>
                                          {{-- <tr class="border-bottom-warning">
                                              <td>{{ $categoryData->name }} Websocket NTLS</td>
                                              <td>None TLS</td>
                                              <td>{{ $accounts['PortNTLS'] }}</td>
                                          </tr> --}}
                                          <tr class="border-bottom-warning">
                                              <td>{{ $categoryData->name }} OpenVPN</td>
                                              <td>TLS/SSL/TCP/UDP</td>
                                              <td>443, 1194, 2200</td>
                                          </tr>
                                          <tr class="border-bottom-warning">
                                              <td>{{ $categoryData->name }} UDP CUSTOM</td>
                                              <td>UDP CUSTOM</td>
                                              <td>1-65535</td>
                                          </tr>
                                          <tr class="border-bottom-warning">
                                              <td>BadVPN UDPGW</td>
                                              <td>UDP</td>
                                              <td>7100, 7200, 7300 - 7900</td>
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
          </div>

        </div>
      </div>
    </div>
              
        </div>
    </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
@endsection
@push('addon-script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard-script.js') }}"></script>
<script>
    function change() {
        const passwordField = document.getElementById('password1');
        const copyButton = document.getElementById('copy');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }

    $(document).ready(function() {
        const passwordField = $('#password1');
        const copyButton = $('#copy');
        const changePassButton = $('#changepass');

        if (passwordField.val()) {
            copyButton.show();
            changePassButton.hide();
        } else {
            copyButton.hide();
            changePassButton.show();
        }
        
        $('#copy').on('click', function() {
        $('#copy').html('Copied!')
        setInterval(function() {
            $('#copy').html('Copy');
        }, 3000);
        var copyText = document.getElementById("password1");
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        });

        passwordField.on('input', function() {
            const passwordValue = $(this).val();
            if (passwordValue === '{{ $accounts['Password'] ?? '' }}' || passwordValue === '') {
                copyButton.hide();
                changePassButton.show();
            } else {
                copyButton.hide();
                changePassButton.show();
            }
        });
    });
</script>
<script>
$(document).ready(function() {
    $('#NewPassword').on('submit', function(e) {
        e.preventDefault();
        updatePassword();
    });

    $('#changepass').on('click', function() {
        updatePassword();
    });

    function updatePassword() {
        let url = "{{ route('accounts.update', [$categoryData->slug, $account->username]) }}";
        let data = $('#NewPassword').serialize();

        $("#btnText").html('<div class="loader-box"><div class="loader-15"></div></div>');

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status == 'success') {
                    $('#password1').val(response.data.replace(/"/g, ''));
                    $('#password2').val(response.data.replace(/"/g, ''));
                    $('#password3').val(response.data.replace(/"/g, ''));
                    swal("Success", response.message, "success");
                } else {
                    swal("Error", response.message, "warning");
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat melakukan request.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                swal("Error", errorMessage, "error");
            },
            complete: function() {
                $("#spinnerIcon").removeClass("fa fa-spin fa-cog");
                $("#btnText").text("Change Password");
                $('#copy').show();
                $('#changepass').hide();
            }
        });
    }
});
</script>
<script>
  function updateUserBalance() {
      $.ajax({
          url: "{{ route('get.user.balance') }}",
          method: "GET",
          dataType: "json",
          success: function (data) {
              $("#user-balance").text(data.balance);
          },
          error: function (xhr, status, error) {
              console.log("Error: " + error);
          }
      });
  }
</script>
@endpush