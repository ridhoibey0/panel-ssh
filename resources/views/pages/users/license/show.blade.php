@extends('layouts.master')
@section('scss')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    .pre-container {
        overflow-x: auto;
    }

    .pre-container pre {
        white-space: pre-wrap;
        word-break: break-word;
    }
</style>
@endsection

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                <div class="col-6">
                    <h4>Details License</h4>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item"> Dashboard</li>
                    <li class="breadcrumb-item active">Details License</li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">

              <div class="col-sm-12">
                    <div class="card height-equal">
                        <div class="card-header border-l-secondary border-2">
                            <h4>System Requirements</h4>
                        </div>
                        <div class="card-body scroll-demos">
                            <h5 class="mb-3">System Information:</h5>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Sistem</th>
                                        <th style="width: 70%;">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Virtualisasi</td>
                                        <td>KVM / Xen / VMware / VirtualBox</td>
                                    </tr>
                                    <tr>
                                        <td>CPU Arch</td>
                                        <td>amd64</td>
                                    </tr>
                                    <tr>
                                        <td>OS</td>
                                        <td>Debian 10 / Ubuntu 20</td>
                                    </tr>
                                    <tr>
                                        <td>OS Arch</td>
                                        <td>64 Bit</td>
                                    </tr>
                                    <tr>
                                        <td>CPU</td>
                                        <td>1 Core , 2 Core Or more</td>
                                    </tr>
                                    <tr>
                                        <td>RAM</td>
                                        <td>512 MB , 1GB, 2GB Or more</td>
                                    </tr>
                                    <tr>
                                        <td>Storage</td>
                                        <td>15 GB , 20GB, 25GB Or more</td>
                                    </tr>
                                    <tr>
                                        <td>Network</td>
                                        <td>1xIPv4 Disable IPv6 Open Port</td>
                                    </tr>
                                </tbody>
                            </table>


                            <h5 class="mt-5 mb-3">Port Details:</h5>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Tunnel Type</th>
                                        <th style="width: 70%;">Port List</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>OpenSSH</td>
                                        <td>22, 2222, 2223</td>
                                    </tr>
                                    <tr>
                                        <td>Dropbear</td>
                                        <td>143, 550, 444, 990, 109</td>
                                    </tr>
                                    <tr>
                                        <td>Stunnel</td>
                                        <td>443, 550, 109</td>
                                    </tr>
                                    <tr>
                                        <td>SSH WebSocket</td>
                                        <td>80, 8880, 2052, 2082, 2086, 2095</td>
                                    </tr>
                                    <tr>
                                        <td>SSH WebSocket TLS</td>
                                        <td>443, 2053, 2083, 2087, 2096, 8443</td>
                                    </tr>
                                    <tr>
                                        <td>SlowDNS</td>
                                        <td>53, 2222, 443</td>
                                    </tr>
                                    <tr>
                                        <td>OpenVPN</td>
                                        <td>443, 1194, 2200</td>
                                    </tr>
                                    <tr>
                                        <td>Proxy Squid</td>
                                        <td>8080, 3128</td>
                                    </tr>
                                    <tr>
                                        <td>Web Api Service</td>
                                        <td>14022</td>
                                    </tr>
                                    <tr>
                                        <td>All Xray</td>
                                        <td>Websocket SSL  : 443, 2053, 2083, 2087, 2096, 8443 / Websocket NTLS : 80, 8880, 2052, 2082, 2086, 2095</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>




                <div class="col-sm-12">
                <div class="card height-equal">
                    <div class="card-header border-l-secondary border-2">
                        <h4>Details License Installer</h4>
                    </div>
                    <div class="card-body scroll-demos">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">License Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                     <td>IP Address</td>
                                     <td>{{ $license->ip }}</td>
                                    </tr>
                                    <tr>
                                        <td>Username</td>
                                        <td>{{ $license->username }}</td>
                                    </tr>
                                    <tr>
                                        <td>Name File</td>
                                        <td>{{ $license->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Roles</td>
                                        <td>{{ ucfirst($license->roles) }}</td>
                                    </tr>
                                    <tr>
                                        <td>X-API-KEY</td>
                                        <td>{{ $license->x_api_key }}</td>
                                    </tr>
                                    <tr>
                                    <td>Status</td>
                                    <td>
                                        <span class="
                                            @if($license->status == 'active')
                                                badge badge-success
                                            @elseif($license->status == 'suspend')
                                                badge badge-warning
                                            @elseif($license->status == 'expired')
                                                badge badge-danger
                                            @endif
                                        ">
                                        {{ ucfirst($license->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Step 1</td>
                                    <td>
                                        <div class="pre-container py-2">
                                         <pre id="teksPre">addgroup dip &>/dev/null
apt-get update -y --allow-releaseinfo-change && \
apt-get install --reinstall -y grub && \
apt-get upgrade -y --fix-missing && \
update-grub && \
sleep 2 && \
reboot</pre>

                                        </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary btn-block" onclick="copyText()">
                                                <i class="fa fa-copy"></i> Copy Teks
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Step 2</td>
                                    <td>
                                        <div class="pre-container py-2">
                                            <pre id="teksPre2">apt-get update && \
apt-get --reinstall --fix-missing install -y whois bzip2 gzip coreutils wget screen nscd && \
wget --inet4-only --no-check-certificate -O setup.sh 'https://script.yourdomain.com/installer/{{ $license->name }}' && \
chmod +x setup.sh && \
screen -S setup ./setup.sh</pre>
                                         </div>
                                        <div class="mb-3">
                                            <button class="btn btn-primary btn-block" onclick="copyText2()">
                                                <i class="fa fa-copy"></i> Copy Teks
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card height-equal">
                    <div class="card-header border-l-secondary border-2">
                        <h4>Dokumentasi API</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">Detail Dokumentasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Judul Dokumentasi</td>
                                    <td>Isi dari judul dokumentasi</td>
                                </tr>
                                <tr>
                                    <td>Deskripsi</td>
                                    <td>Deskripsi singkat atau detail dari dokumentasi tersebut.</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Publikasi</td>
                                    <td>12 Agustus 2023</td>
                                </tr>
                                <tr>
                                    <td>Link Sumber</td>
                                    <td><a href="https://link-dokumentasi.com">https://link-dokumentasi.com</a></td>
                                </tr>
                                <!-- Anda bisa menambahkan baris lainnya sesuai kebutuhan Anda -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard-script.js') }}"></script>
<script>
    function copyText() {
        var teksPre = document.getElementById('teksPre');
        var range = document.createRange();
        range.selectNode(teksPre);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();

        swal({
            title: "Success!",
            text: "The First Step Successfully Copy!",
            icon: "success",
            button: "Tutup",
        });
    }

    function copyText2() {
        var teksPre2 = document.getElementById('teksPre2');
        var range = document.createRange();
        range.selectNode(teksPre2);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand("copy");
        window.getSelection().removeAllRanges();

        swal({
            title: "Success!",
            text: "Step Two Successfully Copy!",
            icon: "success",
            button: "Tutup",
        });
    }
</script>
@endpush
