@extends('layouts.member')
@section('content')
    <div class="page-content">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <form action="{{ route('invoice.history') }}" method="get">
                                <div class="d-flex align-items-center position-relative my-1">

                                    <input type="text" data-kt-ecommerce-order-filter="search"
                                        class="form-control form-control-solid w-250px ps-14" name="invoice" placeholder="No Invoice" />
                                </div>
                                <div class="w-100 mw-150px mt-3">
                                    <!--begin::Select2-->
                                    <select class="form-select form-select-solid" name="status" data-control="select2"
                                        data-hide-search="true" data-placeholder="Status"
                                        data-kt-ecommerce-order-filter="status">
                                        <option selected disabled>--- STATUS ---</option>
                                        <option value="ALL">ALL STATUS</option>
                                        <option value="PAID">PAID</option>
                                        <option value="UNPAID">UNPAID</option>
                                        <option value="EXPIRED">EXPIRED</option>

                                    </select>
                                    <!--end::Select2-->
                                </div>
                                <button class="btn btn-primary mt-4 btn-block" type="submit">Cari</button>
                            </form>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        {{-- <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <!--begin::Flatpickr-->
                            <div class="input-group w-250px">
                                <input class="form-control form-control-solid rounded rounded-end-0"
                                    placeholder="Pick date range" id="kt_ecommerce_sales_flatpickr" />
                                <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr088.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2"
                                                rx="1" transform="rotate(-45 7.05025 15.5356)"
                                                fill="currentColor" />
                                            <rect x="8.46447" y="7.05029" width="12" height="2" rx="1"
                                                transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </button>
                            </div>
                            <!--end::Flatpickr-->
                            <div class="w-100 mw-150px">
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                                    data-placeholder="Status" data-kt-ecommerce-order-filter="status">
                                    <option></option>
                                    <option value="all">All</option>
                    
                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--begin::Add product-->
                            <a href="../../demo23/dist/apps/ecommerce/catalog/add-product.html" class="btn btn-primary">Add
                                Order</a>
                            <!--end::Add product-->
                        </div> --}}
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th>Invoice</th>
                                    <th>Channel</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Pemeesanan</th>
                                    <th>Pembayaran</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($transactions as $topup)
                                    <!--begin::Table row-->
                                    <tr>
                                        <!--begin::Order ID=-->
                                        <td data-kt-ecommerce-order-filter="order_id">
                                            <a href="/topup/detail/{{ $topup->ref_id }}"
                                                class="text-gray-800 text-hover-primary fw-bold">{{ $topup->ref_id }}</a>
                                        </td>
                                        <!--end::Order ID=-->
                                        <!--begin::Customer=-->
                                        <td>
                                            <div class="">
                                                <div>
                                                    <a href="/topup/detail/{{ $topup->ref_id }}"
                                                        class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $topup->payment_method }}</a>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="" data-order="Status">
                                            @if (!empty($topup->status))
                                                <div
                                                    class="s 
                                                    @if ($topup->status === 'SUCCESS') text-success 
                                                    @elseif($topup->status === 'UNPAID') text-warning 
                                                    @else text-danger @endif">
                                                    {{ strtoupper($topup->status) }}
                                                </div>
                                            @else
                                                <span class="text-muted">Status Not Available</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ number_format($topup->amount, 0, '.', '.') }}</span>
                                        </td>

                                        <td data-order="{{ $topup->created_at }}">
                                            <span class="fw-bold">{{ $topup->created_at }}</span>
                                        </td>

                                        <td data-order="{{ $topup->updated_at }}">
                                            <span class="fw-bold">{{ $topup->updated_at }}</span>
                                        </td>
                                    </tr>
                                    <!--end::Table row-->
                                @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                        {{ $transactions->links() }}
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Products-->
            </div>
            <!--end::Content container-->
        </div>
    </div>
@endsection
