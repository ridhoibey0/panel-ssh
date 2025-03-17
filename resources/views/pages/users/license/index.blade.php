@extends('layouts.master')
@section('scss')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
.xray-badges {
    display: flex;
    flex-wrap: wrap;
}

.xray-badge {
    margin: 3px;
}

@media (max-width: 576px) {
    .xray-badge:nth-child(-n+3) {
        flex: 1 0 calc(33.3333% - 4px)
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
                    <h4>Register License</h4>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">                                       
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item"> Dashboard</li>
                    <li class="breadcrumb-item active">Register License</li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <div class="card h-100 height-equal">
                      <div class="card-header border-l-secondary border-2">
                        <h4>Register License</h4>
                      </div>
                      <div class="card-body scroll-demos">
                        <form id="createRegister" action="{{ route('license.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12"> 
                                    <label class="form-label" for="username">Username</label>
                                    <input class="form-control" id="username" name="username" type="text" placeholder="Username" aria-label="Username" required="">
                                </div>
                                <div class="col-12">
                                    <label for="address">IP Address</label>
                                    <input class="form-control" id="address" name="ip" type="text" placeholder="IP Address" aria-label="IP Address" required="">
                                </div>
                                <div class="col-12">
                                  @php
                                      $jsonData = api_price();
                                  @endphp

                                  @if ($jsonData)
                                      @foreach ($jsonData as $name => $price)
                                          <div class="form-check">
                                              <input class="form-check-input" type="radio" id="{{ $name }}" name="expired_on" value="{{ $price }}" @if ($loop->first) checked @endif>
                                              <label class="form-check-label" for="{{ $name }}">{{ $name }} | IDR. {{ number_format($price * 500, 2, ',', '.') }}</label>
                                          </div>
                                      @endforeach
                                  @else
                                      <p>No data available.</p>
                                  @endif
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="tooltip" data-bs-original-title="btn btn-primary" id="btnRegister"><i id="spinnerIcon"></i> <span id="btnText">Register Now</span></button>
                                </div>
                            </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body method">
                            <div id="messages" class="row">
                                <div class="col-sm-12">
                                    <h5 class="f-light mb-3 text-center">License Information</h5>
                                </div>
                                
                                <!-- Server Details as Table -->
                                <div class="col-sm-12">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th class="text-right">SSH</th>
                                                <td>
                                                    <div class="xray-badges">
                                                    <span class="badge badge-light-success xray-badge">Websocket</span>
                                                    <span class="badge badge-light-primary xray-badge">UDP</span>
                                                    <span class="badge badge-light-warning xray-badge">SlowDNS</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Protocol</th>
                                                <td>
                                                    <div class="xray-badges">
                                                        <span class="badge badge-light-warning xray-badge">VMess</span>
                                                        <span class="badge badge-light-warning xray-badge">Trojan</span>
                                                        <span class="badge badge-light-secondary xray-badge">ShadowSocks</span>
                                                        <span class="badge badge-light-primary xray-badge">ShadowSocks2022</span>
                                                        <span class="badge badge-light-warning xray-badge">Socks5</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Virtualisasi</th>
                                                <td>KVM, Xen, VMware, VirtualBox</td>
                                            </tr>
                                            <tr>
                                                <th class="text-right">OS</th>
                                                <td>
                                                    <div class="xray-badges">
                                                    <span class="badge badge-light-success xray-badge">Debian 10</span>
                                                    <span class="badge badge-light-primary xray-badge">Ubuntu 20</span>
                                                    <span class="badge badge-light-secondary xray-badge">Recommended Debian 10 </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Multi Port AIO</th>
                                                <td>443</td>
                                            </tr>
                                            <tr>
                                                <th class="text-right">SlowDNS/DNSTT</th>
                                                <td><span class="badge badge-light-success">Supported</span></td>
                                            </tr>
                                            <tr>
                                                <th class="text-right">Remote WebAPI Services</th>
                                                <td><span class="badge badge-light-success">Supported</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="text-muted mt-3" style="font-size: x-small;">
                                        Jika mengalami error saat registers, mohon <a href="#" class="fw-bold">hubungi Customer Service</a>.<br>
                                        <strong>Perhatian:</strong> Untuk Details Lebih Lanjut Silahkan Klik Tombol Details Di List License.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                  
                  <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header pb-0 card-no-border">
                        <h4>Riwayat Register</h4>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped nowrap" style="width:100%" id="license-table">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>IP Address</th>
                                <th>API KEY</th>
                                <th>Auto Renew</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Expired At</th>
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
    $(document).ready(function() {
        new DataTable('#license-table', {
            processing: true,
            responsive: true,
            ajax: "{{ route('license.index') }}",
            columns: [
                { data: 'rand', name: 'rand' },
                { data: 'username', name: 'username' },
                { data: 'ip', name: 'ip' },
                { data: 'x_api_key', name: 'x_api_key' },
                { data: 'auto_renew', name: 'auto_renew' },
                { data: 'price', name: 'price' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'expired_on', name: 'expired_on' },
                { data: 'action', name: 'action' },
            ]
        });
    });
</script>
<script>
  $(document).ready(function() {
      // Menangani klik tombol "Create Gift Code"
      $('#btnRegister').click(function() {
          // Ambil URL dari atribut action pada form
          let url = $('#createRegister').attr('action');

          // Ambil nilai form dan serialize menjadi data yang akan dikirim sebagai body request
          let data = $('#createRegister').serialize();

          $('#btnRegister').prop('disabled', true);

          // Kirim permintaan AJAX ke URL dari atribut action pada form
          $.ajax({
              url: url,
              type: 'POST',
              data: data,
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              beforeSend: function() {
                  $("#spinnerIcon").addClass("fa fa-spin fa-cog");
                  $("#btnText").text("Loading...");
              },
              success: function(response) {
                  if (response.status == 'success') {
                      updateUserBalance();
                      $('#license-table').DataTable().ajax.reload();
                      swal("Success", response.message, "success", {buttons: false,timer: 2000,})
                  }else{
                    $('#btnRegister').prop('disabled', false);
                      swal("Error", response.message, "warning", {buttons: false,timer: 2000,})
                  }
              },
              error: function(xhr) {
                  // Gagal, tampilkan pesan error dari server
                  if (xhr.responseJSON && xhr.responseJSON.message) {
                    swal("Error", xhr.responseJSON.message, "warning", {buttons: false,timer: 2000,});
                  } else {
                      alert('Terjadi kesalahan saat melakukan request.');
                  }
              },
              complete: function() {
                  $("#spinnerIcon").removeClass("fa fa-spin fa-cog");
                  $("#btnText").text("Register Now");
              },
          });
      });
  });

  // Event listener for auto_renew checkbox
  $('#license-table').on('change', 'input[type="checkbox"]', function () {
            var licenseId = $(this).data('license-id');
            var autoRenew = $(this).prop('checked') ? 1 : 0;

            // Send AJAX request to update auto_renew value
            $.ajax({
                type: "PUT",
                url: "/register/license/" + licenseId, // Replace with your route URL for updating auto_renew
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    auto_renew: autoRenew
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == 'success') {
                      $('#license-table').DataTable().ajax.reload();
                      swal("Success", response.message, "success", {buttons: false,timer: 2000,})
                    }else{
                      $('#license-table').DataTable().ajax.reload();
                      swal("Error", response.message, "warning", {buttons: false,timer: 2000,})
                    }
                  },
                  error: function (error) {
                    console.log(error.responseJSON);
                },
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
  
  function deleteLicense(licenseId) {
    // Tampilkan dialog konfirmasi SweetAlert
    swal({
        title: 'Are you sure?',
        text: 'You will not be able to recover this license!',
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            // Jika pengguna mengkonfirmasi, kirim permintaan Ajax untuk menghapus lisensi
            $.ajax({
                url: '/register/license/' + licenseId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#license-table').DataTable().ajax.reload();
                    // Tampilkan SweetAlert berhasil
                    swal({
                        title: 'Deleted!',
                        text: 'The license has been deleted.',
                        icon: 'success',
                        buttons: false,
                        timer: 3000
                    }).then(() => {
                        // Tindakan yang diambil setelah lisensi dihapus, seperti memperbarui tabel
                        console.log(response);
                    });
                },
                error: function(xhr, status, error) {
                    // Tampilkan SweetAlert kesalahan
                    swal({
                        title: 'Error!',
                        text: 'An error occurred while deleting the license.',
                        icon: 'error'
                    }).then(() => {
                        // Tindakan yang diambil jika terjadi kesalahan saat menghapus lisensi
                        console.error(error);
                    });
                }
            });
        }
    });
}
</script>
@endpush