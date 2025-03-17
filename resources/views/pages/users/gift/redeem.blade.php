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
</style>
@endsection
@section('content')
<div class="page-body">
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-6">
            <h3>Redeem Gift Code</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">                                       
                  <svg class="stroke-icon">
                    <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                  </svg></a></li>
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item active"> Redeem Gift Code</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-4 mb-4">
            <div class="card balance-box h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <form id="giftCodeForm" method="POST" action="{{ route('redeem.store') }}">
                        @csrf
                        <div class="balance-profile">
                            <h5 class="f-light d-block mb-4">Gift Code </h5>
                            <div class="mb-4">
                                <input class="form-control" type="text" id="code" name="code" placeholder="PREMIUMSSH-4581-6063-6869-9619">
                            </div>
                            <div class="pricingtable-signup">
                                <button class="btn btn-primary btn-lg" type="button" id="depositBtn">Redeem Gift Code</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
          <div class="col-sm-8 mb-4">
            <div class="card h-100">
                <div class="card-body method">
                    <div id="message" class="row">
                        <div id="message" class="row">
                            <div class="col-sm-12">
                                <h5 class="f-light mb-3">Panduan deposit</h5>
                            </div>
                            <div class="col-sm-12">
                                <div class="list-method"><p class="mb-2">Silahkan melakukan deposit terlebih dahulu untuk menggunakan layanan OTPweb</p> <ol class="mb-2"> <li>Silahkan isi jumlah yang ingin anda depositkan (min. Rp15.0000) dan klik Deposit</li> <li>Pilihan metode payment akan muncul, anda dapat memilih salah satunya.</li> <li>Anda akan dipindahkan ke halaman pembayaran</li> </ol> <p class="text-muted" style="font-size:x-small"> *Anda dapat menghubungi <a href="#" class="fw-bold">Customer Service</a> jika deposit tidak masuk lebih dari 30 menit sejak anda menyelesaikan pembayaran <br> **Total biaya yang muncul belum termasuk dengan biaya yang diterapkan oleh pihak Payment Gateway </p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Zero Configuration  Starts-->
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header pb-0 card-no-border">
                <h4>Riwayat Deposite</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped nowrap" style="width:100%" id="list-gift">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Paid At</th>
                        <th>Gift Code</th>
                        <th>Balance</th>
                        <th>Is Redeem</th>
                        <th>User Redeem</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- Zero Configuration  Ends-->
      </div>
    </div>
    <!-- Container-fluid Ends-->
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
    $(document).ready(function() {
        new DataTable('#list-gift', {
            processing: true,
            responsive: true,
            ajax: "{{ route('redeem.index') }}",
            columns: [
                { data: 'rand', name: 'rand' },
                { data: 'update_at', name: 'update_at' },
                { data: 'code', name: 'code' },
                { data: 'value', name: 'value' },
                { data: 'redeem', name: 'is_redeemed' },
                { data: 'user', name: 'user' },
            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Menangani klik tombol "Create Gift Code"
        $('#depositBtn').click(function() {
            // Ambil URL dari atribut action pada form
            let url = $('#giftCodeForm').attr('action');

            // Ambil nilai form dan serialize menjadi data yang akan dikirim sebagai body request
            let data = $('#giftCodeForm').serialize();

            // Kirim permintaan AJAX ke URL dari atribut action pada form
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function(){
                    $('#depositBtn').html('<span class="txt-light"><div class="loader-box"><div class="loader-15"></div></div></span>');
                    $('#depositBtn').prop('disabled', true);
                },
                success: function(response) {
                    if (response.status == 'success') {
                        updateUserBalance()
                        $('#message').html(response.code)
                        swal("Success", response.message, "success", {buttons: false,timer: 2000,})
                    }else{
                        swal("Error", response.message, "warning", {buttons: false,timer: 2000,})
                    }
                },
                error: function(xhr) {
                    // Gagal, tampilkan pesan error dari server
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Terjadi kesalahan saat melakukan request.');
                    }
                },
                complete: function() {
                    $('#depositBtn').html('Redeem Gift Code');
                    $('#depositBtn').prop('disabled', false);
                    $('#list-gift').DataTable().ajax.reload();
                }
            });
        });
    });
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