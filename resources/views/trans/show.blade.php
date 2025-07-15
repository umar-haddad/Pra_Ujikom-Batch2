@extends('app')
@section('content')
<div class="row ">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Data Pelanggan :</h3>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <td>:</td>
                                <td>{{ $details->customer->name }}</td>
                            </tr>
                            <tr>
                                <th>No. Telp</th>
                                <td>:</td>
                                <td>{{ $details->customer->phone }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>:</td>
                                <td>{{ $details->customer->address}}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>


    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Data Transaksi :</h3>
                <table class="table ">
                        <thead>
                            <tr>
                                <th>No. Transaksi</th>
                                <td>:</td>
                                <td>{{ $details->order_code }}</td>
                            </tr>
                            <tr>
                                <th>estimasi pengambilan</th>
                                <td>:</td>
                                <td>{{ date('d F y', strtotime($details->order_end_date)) }}</td>
                            </tr>
                            <tr>
                                <th>status</th>
                                <td>:</td>
                                <td>{{ $details->status_text }}</td>
                            </tr>
                        </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center">PEMESANAN</h2>
                <form action="{{ route('trans.cash', $details->id) }}" method="post" id="paymentForm" data-order-id="{{ $details->id }}">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>paket</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details->details as $index => $detail)
                        <tr>
                            <td>{{ $index += 1 }}</td>
                            <td>{{ $detail->service->service_name }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ number_format($detail->service->price) }}</td>
                            <td>{{ $detail->subtotal }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Grand Total</th>
                            <td colspan="2" class="text-right" align="right">Rp {{ number_format($details->total) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3">Bayar :</th>
                            <td colspan="2" class="text-right" align="right">
                                <input type="number" class="form-control" id="order_pay" name="order_pay" required>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3">Kembali :</th>
                            <td colspan="2" class="text-right" align="right">
                                <input type="text" class="form-control" id="order_change_display" name="order_change_display" readonly>
                                <input type="hidden" class="form-control" id="order_change" name="order_change" required>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                 <div class="mt-3">
                        <button type="submit" class="btn btn-primary" name="payment_method" value="cash">Bayar Cash</button>
                        <button class="btn btn-success" name="payment_method" value="midTrans">Cashless</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const formPayment = document.getElementById('paymentForm')
    const orderPay = document.getElementById('order_pay');
    const orderChange = document.getElementById('order_change');
    const orderChangeDisplay = document.getElementById('order_change_display');
    const grandTotal = document.getElementById('grand_total');

    formPayment.addEventListener('submit', function() {
        const total = {{ $details->total }};
        const pay = parseInt(orderPay.value) || 0;
        const change = pay - total;

        orderChangeDisplay.value = change.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
        });
        orderChange.value = change;
    });

</script>
<script
type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key={{ env('MIDTRANS_CLIENT_KEY') }}>
</script>
<script>
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const form = e.target;
    const method = form.querySelector(`[name="payment_method"]:checked, [name="payment_method"]:focus`)?.value;

    if (method === 'cash') {
        // biarkan submit normal
        return;
    }

    e.preventDefault();

    // AJAX untuk Midtrans
    const data = {
        order_pay: document.getElementById('order_pay').value,
        order_change: document.getElementById('order_change').value,
        payment_method: method,
        _token: '{{ csrf_token() }}',
    };

    const orderId = form.dataset.orderId?.trim();

    fetch("{{ url('trans') }}/" + orderId + "/snap", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token,
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.token) {
            snap.pay(res.token, {
                onSuccess: function(result) {
                    window.location.href = "/midtrans/finish?order_id=" + orderId;
                },
                onPending: function(result) {
                    alert("Silakan selesaikan pembayaran.");
                },
                onError: function(result) {
                    alert("Pembayaran gagal.");
                }
            });
        } else {
            alert('token tidak ditemukan!');
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>
@endsection
