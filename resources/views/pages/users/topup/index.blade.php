@extends('layouts.member')
@push('addon-style')
    <style>
        .input-payment-method {
            display: none;
        }

        .card-payment-method {
            width: 100%;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            background-color: white;
            color: black;
            cursor: pointer;
            border-radius: 10px;
            padding: 10px 15px;
        }

        .input-payment-method:checked+.card-payment-method {
            background-color: #85b5fc;
            color: white;
            webkit-transition: background-color 100ms linear;
            -ms-transition: background-color 100ms linear;
            transition: background-color 100mslinear;
        }
    </style>
@endpush
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h4 class="card-title m-0">
                                <i class="bi bi-wallet-fill"></i>
                                Deposit
                            </h4>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger fade show" role="alert">
                                    <strong>Oops! Ada kesalahan:</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>

                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Oops!</strong> {{ session('error') }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md">
                                    <div class="mb-3">
                                        <label for="">Jumlah Deposit</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="amount" id="input-amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="fs-15 text-dark fw-500 mb-3">Pilih Metode Pembayaran</p>
                            <div class="row">
                                @php
                                    $groupedMethods = collect($paymentMethod['data'])->groupBy('group');
                                @endphp

                                @foreach ($groupedMethods as $group => $methods)
                                    <p class="fs-15 text-dark fw-500 mb-3">{{ $group }}</p>

                                    @foreach ($methods as $method)
                                        <label class="w-100">
                                            <input type="radio" name="Method" id="input-method"
                                                class="input-payment-method" value="{{ $method['code'] }}"
                                                data-fee-percent="{{ $method['total_fee']['percent'] }}"
                                                data-fee-flat="{{ $method['total_fee']['flat'] }}">
                                            <div class="card-payment-method d-flex justify-content-between mb-3">
                                                <span>{{ $method['name'] }}</span>
                                                <img src="{{ $method['icon_url'] }}" height="20"
                                                    alt="{{ $method['name'] }}">
                                            </div>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                            <div class="pb-8 row">
                                <a data-bs-toggle="modal" id="btn-detail-deposit" data-bs-target="#detail-deposit"
                                    class="btn btn-primary br-10 my-4 fs-14 fw-600">Lanjutkan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="detail-deposit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-800px">
            <div class="modal-content">
                <div class="modal-content">
                    <div class="modal-header pb-0 border-0 justify-content-end">
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p class="fz-15 fw-600">Detail Topup</p>
                        <form action="{{ route('invoce.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-2 d-flex justify-content-between">
                                <div>
                                    <label for="">Metode</label>
                                    <input type="hidden" name="payment_method" id="payment_method">
                                </div>
                                <div class="fw-500 fs-12 mb-2" id="displayPaymentMethod"></div>
                            </div>
                            <div class="form-group mb-2 d-flex justify-content-between">
                                <div>
                                    <label for="">Nominal Deposit</label>
                                    <input type="hidden" name="amount" id="amount">
                                </div>
                                <div class="fw-500 fs-12 mb-2" id="displayAmount"></div>
                            </div>
                            <div class="form-group mb-2 d-flex justify-content-between">
                                <div>
                                    <label for="">Biaya Admin</label>
                                    <input type="hidden" name="fee" id="fee">
                                </div>
                                <div class="fw-500 fs-12 mb-2" id="displayFee"></div>
                            </div>
                            <div class="form-group mb-2 d-flex justify-content-between">
                                <div>
                                    <label for="">Total Diterima</label>
                                    <input type="hidden" name="amount_received" id="amount_received">
                                </div>
                                <div class="fw-500 fs-12 mb-2" id="displayAmountReceived"></div>
                            </div>
                            <hr>
                            <div class="form-group mb-2 d-flex justify-content-between">
                                <div>
                                    <label for="">Total Bayar</label>
                                    <input type="hidden" name="total_payment" id="total_payment">
                                </div>
                                <div class="fw-500 fs-12 mb-2" id="displayTotal"></div>
                            </div>
                            <button type="submit" id="proses-topup" class="btn btn-primary btn-block">Topup</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('addon-script')
        <script>
            const btnModalDetail = document.getElementById("btn-detail-deposit");

            btnModalDetail.addEventListener("click", () => {
                const inputAmount = document.getElementById("input-amount");
                const inputMethod = document.querySelector('input[name="Method"]:checked');
                const displayPaymentMethod = document.getElementById("displayPaymentMethod");
                const displayAmount = document.getElementById("displayAmount");
                const displayAmountReceived = document.getElementById("displayAmountReceived");
                const displayFee = document.getElementById("displayFee");
                const displayTotal = document.getElementById("displayTotal");
                const deposit = parseFloat(inputAmount.value) || 0;
                const feeFlat = parseFloat(inputMethod.getAttribute("data-fee-flat")) || 0;
                const feePercent = parseFloat(inputMethod.getAttribute("data-fee-percent")) || 0;
                let feeFromPercent = (deposit * feePercent) / 100;
                const fee = feeFlat + feeFromPercent;
                let total = deposit + fee;
                displayPaymentMethod.textContent = inputMethod.value
                displayAmount.textContent = formatRupiah(deposit);
                displayFee.textContent = formatRupiah(fee);
                displayAmountReceived.textContent = formatRupiah(deposit);
                displayTotal.textContent = formatRupiah(total);

                document.getElementById("payment_method").value = inputMethod.value;
                document.getElementById("amount").value = deposit;
                document.getElementById("fee").value = fee;
                document.getElementById("amount_received").value = amountReceived;
                document.getElementById("total_payment").value = totalPayment;
            })

            function formatRupiah(amount) {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0
                }).format(amount);
            }
        </script>
    @endpush
