@extends('layouts.member')
@push('addon-style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header pb-0 card-no-border">
                        <h4>List Accounts {{ $categoryData->name }}</h4>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-striped nowrap" style="width:100%" id="accounts-table">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Host</th>
                                <th>Username</th>
                                @if($categoryData->slug !== 'ssh')
                                <th>UUID</th>
                                @endif
                                <th>Type</th>
                                <th>Status</th>
                                <th>Charge</th>
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
        var columns = [
            { data: 'rand', name: 'rand' },
            { data: 'server.host', name: 'server.host' },
            { data: 'username', name: 'username' },
            @if($categoryData->slug !== 'ssh')
            { data: 'detail', name: 'detail' },
            @endif
            { data: 'tipe', name: 'tipe' },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'formatted_balance', name: 'formatted_balance' },
            { data: 'created_at', name: 'created_at' },
            { data: 'expired_at', name: 'expired_at' },
            { data: 'action', name: 'action' }
        ];

        new DataTable('#accounts-table', {
            processing: true,
            responsive: true,
            ajax: "{{ route('accounts.index', ['category' => $categoryData->slug]) }}",
            columns: columns,
            order: [[1, 'asc']]
        });
    });
</script>
<script>
$(document).on('click', '.link-delete', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
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
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
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
                    console.log(error)
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