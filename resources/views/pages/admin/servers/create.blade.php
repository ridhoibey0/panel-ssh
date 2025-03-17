@extends('layouts.member')
@section('addon-style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <form id="createServers" action="{{ route('admin.servers.store') }}" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card height-equal" style="min-height: auto;">
                          <div class="card-body">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Name Servers">
                            </div>
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" name="slug" id="slug" placeholder="Slug Servers">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-12">
                        <div class="card height-equal" style="min-height: auto;">
                          <div class="card-body">
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option selected disabled value>Select Category</option>
                                    @foreach ($categorys as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="country">Country</label>
                                <select name="country_id" id="country_id" class="form-select">
                                    <option selected disabled value>Select Category</option>
                                    @foreach ($countrys as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card height-equal" style="min-height: auto;">
                  <div class="card-header bg-info">
                    <h4 class="text-white">Info Color Header</h4>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                        <label for="host">Hostname</label>
                        <input type="text" name="host" id="host" class="form-control" placeholder="Hostname Servers">
                    </div>
                    <div class="mb-3">
                        <label for="isp">ISP</label>
                        <input type="text" name="isp" id="isp" class="form-control" placeholder="ISP Servers">
                    </div>
                    <div class="mb-3">
                        <label for="limit">Limit</label>
                        <input type="text" name="limit" id="limit" class="form-control" placeholder="Limit Servers">
                    </div>
                    <div class="mb-3">
                        <label for="notes">Notes</label>
                        <input type="text" name="notes" id="notes" class="form-control" placeholder="Notes Servers">
                    </div>
                    <div class="mb-3">
                        <label for="token">Token</label>
                        <input type="text" name="token" id="token" class="form-control" placeholder="Token Servers" required>
                    </div>
                    <div id="prices_repeater" class="">
                        <div data-repeater-list="prices_repeater">
                            <div data-repeater-item="">
                                <div class="form-group row mb-2">
                                    <div class="col-md-3">
                                        <label class="form-label required">Price for</label>
                                        <select name="role_id" class="form-select"
                                            data-kt-repeater="select2" data-control="select2"
                                            data-placeholder="Select an option">
                                            <option value="">Select an option</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">
                                                    {{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label required">Price Monthly</label>
                                        <input type="text" class="form-control mb-2 mb-md-0"
                                            name="price_monthly" placeholder="Price" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label required">Price Hourly</label>
                                        <input type="text" class="form-control mb-2 mb-md-0"
                                            name="price_hourly" placeholder="Price" />
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:;" data-repeater-delete=""
                                            class="btn btn-sm btn-danger mt-4 mt-md-9">
                                            <i class="fa fa-trash"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="javascript:;" data-repeater-create="" class="btn btn-primary">
                                <i class="fa fa-plus"></i>Add</a>
                        </div>
                    </div>
                    <div id="ports_repeater" class="mb-5 fv-row fv-plugins-icon-container">
                        <div data-repeater-list="ports_repeater">
                            <div data-repeater-item="">
                                <div class="form-group row mb-2">
                                    <div class="col-md-3">
                                        <label class="form-label required">Ports Name</label>
                                        <input type="text" class="form-control mb-2 mb-md-0"
                                            placeholder="Ex: SSH(SSL)" name="ports_name">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label required">Ports Number</label>
                                        <input type="text" class="form-control mb-2 mb-md-0"
                                            placeholder="Enter port number" name="ports_number">
                                    </div>
                                    <div class="col-md-4">
                                        <a href="javascript:;" data-repeater-delete=""
                                            class="btn btn-sm btn-danger mt-4 mt-md-9">
                                            <i class="fa fa-trash"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="javascript:;" data-repeater-create="" class="btn btn-primary">
                                <i class="fa fa-plus"></i>Add</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="limit">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                  </div>
                  <div class="card-footer">
                    <h6 class="mb-0 text-end">
                        <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip" data-bs-original-title="btn btn-primary" id="btnCreateServers"><i id="spinnerIcon"></i> <span id="btnText">Create Now</span></button>
                    </h6>
                  </div>
                </div>
              </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('addon-script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js" integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
  // Menangani event submit form
  $('#createServers').submit(function(event) {
    event.preventDefault(); // Mencegah pengiriman form secara normal

    // Mengambil data yang di-serialize dari form
    var formData = $(this).serialize();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
    $('#btnCreateServers').prop('disabled', true);
    // Mengirim data ke server menggunakan Ajax
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: formData,
      beforeSend: function() {
            $("#spinnerIcon").addClass("fa fa-spin fa-cog");
            $("#btnText").text("Loading...");
        },
      success: function(response) {
        // Respon sukses dari server
        if (response.status == 'success') {
            $('#btnCreateServers').prop('disabled', false);
            swal("Success", response.message, "success", {buttons: false,timer: 2000,})
        } else {
            $('#btnCreateServers').prop('disabled', false);
            swal("Error", response.message, "warning", {buttons: false,timer: 2000,})
        }
      },
      error: function() {
        // Respon error dari server
        console.error('Terjadi kesalahan saat mengirim data.');
      },
      complete: function() {
                  $("#spinnerIcon").removeClass("fa fa-spin fa-cog");
                  $("#btnText").text("Create Now");
              },
    });
  });
});

</script>
<script>
    $(document).ready(function() {
        $('#country_id').select2();
    });
</script>
<script>
    $(document).ready(function() {
        $('#prices_repeater').repeater({
            initEmpty: false,

            show: function() {
                $(this).slideDown();
                // Re-init select2
                $(this).find('[data-kt-repeater="select2"]').select2();
            },

            hide: function(deleteElement) {
                var element = $(this);
                swal({
                    text: "Are you sure you would like to delete this element?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                }).then((willDelete) => {
                    if (willDelete) {
                        element.slideUp(deleteElement);
                    }
                });
            },
            isFirstItemUndeletable: true
        });

        $('#ports_repeater').repeater({
            initEmpty: false,

            show: function() {
                $(this).slideDown();
            },

            hide: function(deleteElement) {
                var element = $(this);
                swal({
                    text: "Are you sure you would like to delete this element?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                }).then((willDelete) => {
                    if (willDelete) {
                        element.slideUp(deleteElement);
                    }
                });
            },
            isFirstItemUndeletable: true
        });
    });
    </script>
    
@endpush