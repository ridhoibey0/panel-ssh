@extends('layouts.master')
@section('scss')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
.status-green {
    --tblr-status-color: #2fb344;
}
.status-red {
    --tblr-status-color: #f44336;
}
.badge {
    color: var(--tblr-status-color);
}
.status-dot {
    display: inline-block;
    width: 8px;
    height: 7px;
    border-radius: 50%;
    margin-left: 5px;
    background-color: var(--tblr-status-color);
}
.status-dot-animated {
    animation: status-pulsate-tertiary 1s linear 2s infinite backwards;
}
@keyframes status-pulsate-tertiary {
    0%, 100% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.05);
        opacity: 1;
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
            <h3>Create Accounts {{ $categoryData->name }}</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">                                       
                  <svg class="stroke-icon">
                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                  </svg></a></li>
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item active"> Create Accounts {{ $categoryData->name }}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-start">
                    <form id="createAccounts" method="POST" action="{{ route('servers.store', [$serverData->category->slug, $serverData->slug]) }}">
                        @csrf
                        <div class="col-12 mb-3"> 
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control" id="username" name="username" value="{{ old('username') }}" type="text" placeholder="Username" aria-label="Username" required="">
                        </div>
                        @if ($categoryData->slug == 'ssh' || $categoryData->slug == 'socks-5')
                            <div class="col-12 mb-3"> 
                                <label class="form-label" for="password">Password</label>
                                <input class="form-control" id="password" name="password" type="text" placeholder="Password" aria-label="Password" required="">
                            </div>
                        @endif
                        <div class="col-12 mb-3"> 
                            <label class="form-label" for="sni">SNI/Bug Host</label>
                            <input class="form-control" id="sni" name="sni" value="{{ $serverData->host }}" type="text" placeholder="SNI/BUG" aria-label="SNI/BUG" required="">
                        </div>
                        <div class="col-12 mb-3"> 
                          <label class="form-label" for="permission">Pilih Metode Pembayaran</label>
                          <div class="form-check">
                            <input class="form-check-input" id="monthly" type="radio" name="metode" value="1" checked>
                            <label class="form-check-label" for="monthly">Monthly | <b>IDR. {{ number_format($serverData->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_monthly, 2) }}</b></label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" id="hourly" type="radio" name="metode" value="2">
                            <label class="form-check-label" for="hourly">Hourly | <b>IDR. {{ number_format($serverData->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_hourly, 2) }}</b></label>
                          </div>
                          <div class="form-check">
                            <input class="form-check-input" id="trial" type="radio" name="metode" value="3">
                            <label class="form-check-label" for="trial" id="trialLabel">Trial | <b>Loading...</b></label>
                          </div>
                        </div>
                        <div class="col-12 mb-3 d-grid gap-2">
                          <button type="button" class="btn btn-primary btn-sm" id="btnCreate">
                            <i id="spinnerIcon"></i> <span id="btnText">Create Now</span>
                          </button>
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
                            <h5 class="f-light mb-3 text-center">Server {{ $categoryData->name }} Information</h5>
                        </div>
                        
                        <!-- Server Details as Table -->
                        <div class="col-sm-12">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Host</th>
                                        <td>{{ $serverData->host }}</td>
                                    </tr>
                                    <tr>
                                        <th>ISP</th>
                                        <td>{{ $serverData->isp }}</td>
                                    </tr>
                                            @foreach ($serverData->ports as $key => $value)
                                    <tr>
                                            <th>{{ $key }}</th>
                                               <td>{{ $value }}</td>
                                    </tr>
                                            @endforeach
                                    <tr>
                                        <th>Max Devices</th>
                                        <td>2 Session</td>
                                    </tr>
                                    <tr>
                                        <th>Status Server</th>
                                        {!! $serverData->status == 'online' ? '<td><span class="badge badge-light-success status-green">ONLINE <span class="status-dot status-dot-animated"></span></span></td>' : '<td><span class="badge badge-light-danger status-red">OFFLINE <span class="status-dot status-dot-animated"></span></span></td>' !!}
                                    </tr>
                                    <tr>
                                        <th>Torrent</th>
                                        <td><span class="badge badge-light-danger">Not Supported</span></td>
                                    </tr>
                                    <tr>
                                        <th>Monthly Price</th>
                                        <td><span class="badge badge-primary">IDR. {{ number_format($serverData->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_monthly, 2) }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="text-muted mt-3" style="font-size:x-small">
                            Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#" class="fw-bold">Hubungi Customer Service</a>.<br>
                            <b>Perhatian:</b> Max Multi Login 2 Device, Jika Melebihi Batas Di Tentukan Accounts Akan Suspends Dan Akan Dihapus Dari Server.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Zero Configuration  Starts-->
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header pb-0 card-no-border">
                <h4>Riwayat Transaksi</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped nowrap" style="width:100%" id="riwayat-table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Host</th>
                        <th>Username</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Charge</th>
                        <th>Paid At</th>
                        <th>Expired Date</th>
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
        var columns = [
            { data: 'rand', name: 'rand' },
            { data: 'server.host', name: 'server.host' },
            { data: 'username', name: 'username' },
            {
                data: 'tipe',
                name: 'tipe',
                render: function(data, type, row) {
                    if (data === 'Trial') {
                        return '<span class="badge badge-light-warning">' + data + '</span>';
                    } else if (data === 'Monthly') {
                        return '<span class="badge badge-light-success">' + data + '</span>';
                    } else if (data === 'Hourly') {
                        return '<span class="badge badge-light-info">' + data + '</span>';
                    } else {
                        return data;
                    }
                }
            },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'charge', name: 'charge' },
            { data: 'created_at', name: 'created_at' },
            { data: 'expired_at', name: 'expired_at' },
        ];

        $('#riwayat-table').DataTable({
            processing: true,
            responsive: true,
            ajax: "{{ route('servers.create', [$serverData->category->slug, $serverData->slug]) }}",
            columns: columns,
            order: [[1, 'asc']]
        });
    });
</script>
<script>
  $(document).ready(function() {
      function updateTrialLimit() {
        $.get("/get-tunnel-settings", function(data) {
          $("#trialLabel b").text(data.timelimit + " Minutes " + data.trial_limit + "/" + data.limit);
        });
      }
      updateTrialLimit();
      $('#btnCreate').click(function() {
          let url = $('#createAccounts').attr('action');

          let data = $('#createAccounts').serialize();

          $('#btnCreate').prop('disabled', true);

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
                      fetchNotifications();
                      $('#riwayat-table').DataTable().ajax.reload();
                      $('#messages').html(response.output)
                      swal("Success", response.message, "success", {buttons: false,timer: 2000,})
                  }else{
                    $('#btnCreate').prop('disabled', false);
                      swal("Error", response.message, "warning", {buttons: false,timer: 2000,})
                  }
              },
              error: function(xhr) {
                  if (xhr.responseJSON && xhr.responseJSON.message) {
                    swal("Error", xhr.responseJSON.message, "warning", {buttons: false,timer: 2000,});
                  } else {
                      alert('Terjadi kesalahan saat melakukan request.');
                  }
              },
              complete: function() {
                  $("#spinnerIcon").removeClass("fa fa-spin fa-cog");
                  $("#btnText").text("Create Now");
                  updateTrialLimit();
              },
          });
      });
  });
  
  function formatDateAndTime(logDate) {
    const date = new Date(logDate);
    const formattedTime = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    const relativeTime = moment(logDate).fromNow();

    return `${formattedTime} - ${relativeTime}`;
    }

      function renderNotifications(logs) {
        let listHTML = '';
        logs.forEach(log => {
            listHTML += `
            <li class="b-l-${log.html_warna} border-4">
               <p>${log.message} Rp.${log.charge} <span class="font-danger">${formatDateAndTime(log.created_at)}</span></p>
            </li>
            `;
        });
        return listHTML;
    }
    
     function fetchNotifications() {
        $.ajax({
            url: "{{ route('get.user.notif') }}",
            method: "GET",
            dataType: "json",
            success: function (data) {
                // Update count
                $("#notif-count").text(data.count);

                const notificationsHTML = renderNotifications(data.logs);
                $('#notification-list').html(notificationsHTML + '<li><a class="f-w-700" href="#">Check all</a></li>');
            },
            error: function (xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }


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
     var payloadhttp = new ClipboardJS('.payloadhttp');
        payloadhttp.on('success', function(e) {
            alert('Copy to cliboard Successfully')
        });

        var payloadws = new ClipboardJS('.payloadws');
        payloadws.on('success', function(e) {
            alert('Copy to cliboard Successfully')
        });
</script>
@endpush