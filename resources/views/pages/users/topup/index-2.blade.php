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
            <h3>Top Up Balance</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">                                       
                  <svg class="stroke-icon">
                    <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                  </svg></a></li>
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item active"> Top Up Balance</li>
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
                    <div class="balance-profile">
                        <h5 class="f-light d-block mb-4">Jumlah Deposit </h5>
                        <div class="mb-4 m-form__group">
                            <div class="input-group"><span class="input-group-text">Rp</span>
                                <input class="form-control" type="number" id="nominal" placeholder="15000">
                            </div>
                        </div>
                        <div class="pricingtable-signup">
                            <button class="btn btn-primary btn-lg" type="button" id="depositBtn">Deposit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <div class="col-sm-8 mb-4">
            <div class="card h-100">
                <div class="card-body method">
                    <div id="message"></div>
                    <div id="PaymentMethod" class="row">
                        <div class="col-sm-12">
                            <h5 class="f-light mb-3">Panduan deposit</h5>
                        </div>
                        <div class="col-sm-12">
                            <div class="list-method">
                                <p class="mb-2">Silahkan melakukan deposit terlebih dahulu untuk menggunakan layanan PremiumSSH.net</p> <ol class="mb-2"> <li>Silahkan isi jumlah yang ingin anda depositkan (min. Rp15.0000) dan klik Deposit</li> <li>Pilihan metode payment akan muncul, anda dapat memilih salah satunya.</li> <li>Anda akan dipindahkan ke halaman pembayaran</li> </ol> <p class="text-muted" style="font-size:x-small"> *Anda dapat menghubungi <a href="#" class="fw-bold">Customer Service</a> jika deposit tidak masuk lebih dari 30 menit sejak anda menyelesaikan pembayaran <br> **Total biaya yang muncul belum termasuk dengan biaya yang diterapkan oleh pihak Payment Gateway </p>
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
                  <table class="table table-striped nowrap" style="width:100%" id="list-invoice">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Invoice ID</th>
                        <th>Ref ID</th>
                        <th>Invoice URL</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Paid At</th>
                        <th>Expired Date</th>
                        <th>Action</th>
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
        new DataTable('#list-invoice', {
            processing: true,
            responsive: true,
            order: [[1, 'asc']],
            ajax: "{{ route('invoce.index') }}",
            columns: [
                { data: 'rand', name: 'rand' },
                { data: 'user_name', name: 'user_name' },
                { data: 'invoice_id', name: 'invoice_id' },
                { data: 'ref_id', name: 'ref_id' },
                { data: 'invoice_url', name: 'invoice_url' },
                { data: 'status', name: 'status' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'amount', name: 'amount' },
                { data: 'paid_at', name: 'paid_at' },
                { data: 'expiry_date', name: 'expiry_date' },
                { data: 'action', name: 'action' },
            ]
        });
    });
</script>
<script>
    // Add an event listener to the "Deposit" button
    document.getElementById('depositBtn').addEventListener('click', function() {
        // Get the value from the "nominal" input field
        let nominalValue = document.getElementById('nominal').value;

        // Send an AJAX request to the Laravel route
        fetch('{{ route('invoice.payment') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                nominal: nominalValue,
            }),
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.status == 'success') {
              document.getElementById('depositBtn').disabled = true;
              document.getElementById('PaymentMethod').innerHTML = data.message;
            }else{
              swal(data.status, data.message, "warning", {buttons: false,timer: 2000,});
              // document.getElementById('message').innerHTML = data.message;
            }
          })
          .catch(error => {
            console.error('Error:', error);
          });
        });
      </script>
<script>
    $(document).ready(function() {
    $('.payment-option').on('click', function() {
        var option = $(this).data('option');
        if (option === 'crypto') {
            // Tampilkan atau filter daftar metode pembayaran Crypto
        } else if (option === 'paypal') {
            // Tampilkan atau filter daftar metode pembayaran Paypal
        }
        $('.list-method').show();
    });
});

</script>
<script>
  $(document).on('click', '.bank-btn', function() {
    // Get the selected bank from the data attribute
    var selectedBank = $(this).data('bank');
    // Get the form data
    var formData = $('#createInvoice').serialize();
    
        formData += '&bank=' + selectedBank;
        $('.bank-btn').prop('disabled', true);
        $(this).html('<span class="font-danger">Loading...</span>');
        
        // Send the AJAX request
        $.ajax({
          url: $('#createInvoice').attr('action'),
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: formData,
          beforeSend: function(){
                $('#depositBtn').html('<span class="txt-light"><div class="loader-box"><div class="loader-15"></div></div></span>')
          },
          success: function(data) {
            if(data.status == 'success'){
                    console.log(data);
                    $('#depositBtn').prop('disabled', false);
                    // $('#message').html(data.message);
                    // swal(data.status, data.message, "success", {buttons: false,timer: 2000,});
                    window.location.href = data.redirect;
            }else{
                    console.log(data);
                    swal(data.status, data.message, "warning", {buttons: false,timer: 2000,});
                    $('#message').html(data.message);
                }
            },
            error: function(error) {
                console.error('Error:', error);
                $('#message').html('Error occurred while processing the request.');
            },
            complete: function(){
                $('#depositBtn').html('Deposit')
                $('.bank-btn').html('Bayar');
                $('.bank-btn').attr('disabled', false);
            }
        });
    });
    function batalDepo() {
        $('#depositBtn').attr('disabled', false);
        $('#message').html('');
        $('.method h5').html('Panduan deposit');
        $('.list-method').html(`<p class="mb-2">Silahkan melakukan deposit terlebih dahulu untuk menggunakan layanan PremiumSSH.net</p> <ol class="mb-2"> <li>Silahkan isi jumlah yang ingin anda depositkan (min. Rp1.000) dan klik Deposit</li> <li>Pilihan metode payment akan muncul, anda dapat memilih salah satunya.</li> <li>Anda akan dipindahkan ke halaman pembayaran</li> </ol> <p class="text-muted" style="font-size:x-small"> *Anda dapat menghubungi <a href="#" class="fw-bold">Customer Service</a> jika deposit tidak masuk lebih dari 30 menit sejak anda menyelesaikan pembayaran <br> **Total biaya yang muncul belum termasuk dengan biaya yang diterapkan oleh pihak Payment Gateway </p>`);
    }
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Check if the URL contains a "status" parameter
      const statusParam = new URLSearchParams(window.location.search).get('status');
      if (statusParam) {
          if (statusParam === 'success') {
              swal("success", "Payment was successful!", "success", {buttons: false,timer: 2000,});
          } else if (statusParam === 'failure') {
              swal("error", "Payment was unsuccessful.", "warning", {buttons: false,timer: 2000,});
          }
      }
  });
</script>
<script>
$(document).on('click', '.cancel-invoice', function(e) {
    e.preventDefault();
    var url = $(this).data('url');

    swal({
        title: "Are you sure?",
        text: "Once cancelled, you will not be able to revert this!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        swal("Error!", response.message, "error");
                    }
                },
                error: function(error) {
                    swal("Error!", "An error occurred", "error");
                }
            });
        } else {
            swal("Your action was cancelled!");
        }
    });
});
</script>
@endpush