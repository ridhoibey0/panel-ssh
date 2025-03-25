@extends('layouts.member')
@push('addon-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        .qty .count {
            color: #000;
            display: inline-block;
            vertical-align: top;
            font-size: 25px;
            font-weight: 700;
            line-height: 30px;
            padding: 0 2px;
            min-width: 35px;
            text-align: center;
        }

        .qty .plus {
            cursor: pointer;
            display: inline-block;
            vertical-align: top;
            color: white;
            width: 30px;
            height: 30px;
            font: 30px/1 Arial, sans-serif;
            text-align: center;
            border-radius: 50%;
        }

        .qty .minus {
            cursor: pointer;
            display: inline-block;
            vertical-align: top;
            color: white;
            width: 30px;
            height: 30px;
            font: 30px/1 Arial, sans-serif;
            text-align: center;
            border-radius: 50%;
            background-clip: padding-box;
        }

        div {
            text-align: center;
        }

        .minus:hover {
            background-color: #717fe0 !important;
        }

        .plus:hover {
            background-color: #717fe0 !important;
        }

        span {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        input {
            border: 0;
            width: 5%;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input:disabled {
            background-color: white;
        }
    </style>
@endpush
@section('content')
    <div class="page-content">
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-start">
                            <form id="createAccounts" method="POST"
                                action="{{ route('servers.store', [$serverData->category->slug, $serverData->slug]) }}">
                                @csrf
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="username">Username</label>
                                    <input class="form-control" id="username" name="username" value="{{ old('username') }}"
                                        type="text" placeholder="Username" aria-label="Username" required="">
                                </div>
                                @if ($categoryData->slug == 'ssh' || $categoryData->slug == 'socks-5')
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-control" id="password" name="password" type="text"
                                            placeholder="Password" aria-label="Password" required="">
                                    </div>
                                @endif
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="sni">SNI/Bug Host</label>
                                    <input class="form-control" id="sni" name="sni"
                                        value="{{ $serverData->host }}" type="text" placeholder="SNI/BUG"
                                        aria-label="SNI/BUG" required="">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="permission">Pilih Tipe Langganan</label>
                                    <select class="form-select" id="type" name="type"
                                        data-price-monthly="{{ $serverData->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_monthly }}"
                                        data-price-daily="{{ $serverData->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_daily }}">
                                        <option value="1" selected>Bulanan</option>
                                        <option value="2">Harian</option>
                                        <option value="3">Trial</option>
                                    </select>
                                    <div class="qty mt-2">
                                        <span class="minus bg-success">-</span>
                                        <input type="number" min="1" max="30" class="count" name="duration"
                                            value="1">
                                        <span class="plus bg-danger">+</span>
                                    </div>
                                    <p class="mt-3 badge bg-primary"><b>Harga: IDR. <span id="total-price">Gratis</span></b>
                                    </p>


                                    {{-- <div class="form-check">
                                        <input class="form-check-input" id="trial" type="radio" name="metode"
                                            value="trial">
                                        <label class="form-check-label" for="trial" id="trialLabel">Trial |
                                            <b>Loading...</b></label>
                                    </div> --}}
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
                                                {!! $serverData->status == 'online'
                                                    ? '<td><span class="badge bg-success status-green">ONLINE <span class="status-dot status-dot-animated"></span></span></td>'
                                                    : '<td><span class="badge bg-danger status-red">OFFLINE <span class="status-dot status-dot-animated"></span></span></td>' !!}
                                            </tr>
                                            <tr>
                                                <th>Torrent</th>
                                                <td><span class="badge bg-danger">Not Supported</span></td>
                                            </tr>
                                            <tr>
                                                <th>Monthly Price</th>
                                                <td><span class="badge bg-primary">IDR.
                                                        {{ number_format($serverData->prices->where('role_id', Auth::user()->roles->first()->id)->first()->price_monthly, 2) }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="text-muted mt-3" style="font-size:x-small">
                                        Jika Mengalami Error Saat Membuat Accounts, Mohon <a href="#"
                                            class="fw-bold">Hubungi Customer Service</a>.<br>
                                        <b>Perhatian:</b> Max Multi Login 2 Device, Jika Melebihi Batas Di Tentukan Accounts
                                        Akan Suspends Dan Akan Dihapus Dari Server.
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
        $(document).ready(function() {
            var columns = [{
                    data: 'rand',
                    name: 'rand'
                },
                {
                    data: 'server.host',
                    name: 'server.host'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'tipe',
                    name: 'tipe',
                    render: function(data, type, row) {
                        if (data === 'Trial') {
                            return '<span class="badge bg-warning">' + data + '</span>';
                        } else if (data === 'Monthly') {
                            return '<span class="badge bg-success">' + data + '</span>';
                        } else if (data === 'Hourly') {
                            return '<span class="badge bg-info">' + data + '</span>';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: 'status_badge',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'charge',
                    name: 'charge'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'expired_at',
                    name: 'expired_at'
                },
            ];

            $('#riwayat-table').DataTable({
                processing: true,
                responsive: true,
                ajax: "{{ route('servers.create', [$serverData->category->slug, $serverData->slug]) }}",
                columns: columns,
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function updateTrialLimit() {
                $.get("/get-tunnel-settings", function(data) {
                    $("#trialLabel b").text(data.timelimit + " Minutes " + data.trial_limit + "/" + data
                        .limit);
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
                            swal("Success", response.message, "success", {
                                buttons: false,
                                timer: 2000,
                            })
                            $('#btnCreate').prop('disabled', false);
                        } else {
                            $('#btnCreate').prop('disabled', false);
                            swal("Error", response.message, "warning", {
                                buttons: false,
                                timer: 2000,
                            })
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            swal("Error", xhr.responseJSON.message, "warning", {
                                buttons: false,
                                timer: 2000,
                            });
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
            const formattedTime = date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
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
                success: function(data) {
                    // Update count
                    $("#notif-count").text(data.count);

                    const notificationsHTML = renderNotifications(data.logs);
                    $('#notification-list').html(notificationsHTML +
                        '<li><a class="f-w-700" href="#">Check all</a></li>');
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
        }


        function updateUserBalance() {
            $.ajax({
                url: "{{ route('get.user.balance') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    $("#user-balance").text(data.balance);
                },
                error: function(xhr, status, error) {
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
    <script>
        $(document).ready(function() {
            let totalPriceElement = $("#total-price");
            let countInput = $(".count");
            let typeSelect = $("#type");
            let pricePerMonth = parseFloat(typeSelect.data("price-monthly")) || 0;
            let pricePerDay = parseFloat(typeSelect.data("price-daily")) || 0;

            function updateTotalPrice() {
                let selectedType = typeSelect.val();
                let selectedValue = parseInt(countInput.val()) || 1;
                let totalPrice = 0;
                if (typeSelect.val() == 3) {
                    totalPriceElement.text("Gratis");
                    countInput.closest(".qty").hide(); // Sembunyikan counter
                } else {
                    countInput.closest(".qty").show(); // Tampilkan counter
                    if (selectedType ==1) {
                        totalPrice = pricePerMonth * selectedValue;
                        console.log(pricePerMonth, selectedValue);
                    } else if (selectedType == 2) {
                        totalPrice = pricePerDay * selectedValue;
                    }
                    totalPriceElement.text(totalPrice.toLocaleString("id-ID", {
                        minimumFractionDigits: 2
                    }));
                }
            }


            typeSelect.change(updateTotalPrice);

            $(document).on("click", ".plus", function() {
                let currentValue = parseInt(countInput.val()) || 1;
                if (currentValue < 30) {
                    countInput.val(currentValue + 1);
                    updateTotalPrice();
                }
            });

            $(document).on("click", ".minus", function() {
                let currentValue = parseInt(countInput.val()) || 1;
                if (currentValue > 1) {
                    countInput.val(currentValue - 1);
                    updateTotalPrice();
                }
            });

            updateTotalPrice();
        });
    </script>
@endpush
