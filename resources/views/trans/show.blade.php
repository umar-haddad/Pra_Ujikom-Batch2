@extends('app')
@section('content')

<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> Data Pelanggan </h3>
                <table class="table table-stripped">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td> {{$details->customer->name}} </td>
                    </tr>
                    <tr>
                        <td>Telp</td>
                        <td>:</td>
                        <td> {{$details->customer->phone}} </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td> {{$details->customer->address}} </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> Transaksi Pemesanan </h3>
                <table class="table table-stripped">
                    <tr>
                        <td>No.Transaksi</td>
                        <td>:</td>
                        <td> {{$details->order_code}} </td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengambilan</td>
                        <td>:</td>
                        <td> {{date('d F Y', strtotime($details->order_end_date))}} </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td> {{$details->status_text}} </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    @if ($details->order_status == 0)

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> Detail Pemesanan </h3>
                <form action="{{route('trans.update', $details->id)}}" method="post" id="paymentForm" data-order-id=" {{$details->id}} ">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Paket</th>
                                <th>Qty</th>
                                <th>Harga/Kg</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details->details as $key => $detail)
                            <tr>
                                <td> {{$key += 1}} </td>
                                <td> {{$detail->service->service_name}} </td>
                                <td align="right">{{$detail->qty}} Kg</td>
                                <td align="right">Rp. {{number_format($detail->service->price)}}</td>
                                <td align="right"> Rp. {{number_format($detail->subtotal)}}</td>
                            </tr>
                            @endforeach
                            <tfoot>
                                <tr>
                                    <td colspan="4"><strong>Total</strong></td>
                                    <td align="right" colspan="1"><strong>Rp. {{number_format($details->total)}} </strong></td>
                                    <input type="hidden" id="totalInput" value=" {{$details->total}} ">
                                </tr>
                                <tr>
                                    <td colspan="4">Bayar</td>
                                    <td colspan="1" class="text-right" align="right">
                                        <input type="number" class="form-control" id="order_pay" name="order_pay" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">Kembali</td>
                                    <td colspan="1" class="text-right" align="right">
                                        <input type="text" class="form-control" id="order_change_display" readonly>
                                        <input type="hidden" class="form-control" id="order_change" name="order_change" required>
                                    </td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                    <div class="mt-3">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-primary" name="payment_method" value="cash">Bayar Cash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
     const orderChange = document.getElementById('order_change');
    const orderChangeDisplay = document.getElementById('order_change_display');
    const orderPay = document.getElementById('order_pay');
    const totalInput = document.getElementById('totalInput');

    //kembalian = bayar - total harga
   function pay() {
    const pay = parseFloat(orderPay.value) || 0;
    const total = parseFloat(totalInput.value) || 0;
    const change = pay - total;

    // Access both display and hidden input fields
    const changeGroup = orderChangeDisplay.closest('td'); // assuming inputs are inside the <td>

    if (pay >= total) {
        // Show and populate change fields
        orderChangeDisplay.value = change.toLocaleString('id-ID');
        orderChange.value = change;
        changeGroup.style.display = ''; // show the cell
    } else {
        // Hide and clear change fields
        orderChangeDisplay.value = '';
        orderChange.value = '';
        changeGroup.style.display = 'none'; // hide the cell
    }
}


    orderPay.addEventListener('input', pay);
  document.getElementById('paymentForm').addEventListener('submit', function(e){
    e.preventDefault();

    const form = e.target;
    const method = form.querySelector('[name="payment_method"]:checked, [name="payment_method"]:focus') ?.value;

    const data ={
      order_pay: document.getElementById('order_pay').value,
      order_change: document.getElementById('order_change').value,
      payment_method: method,
      _token: ' {{csrf_token()}} '
    }
    const orderId = form.dataset.orderId;

    if (method === 'cash') {
      form.submit();
    }else{
      fetch(`/trans/${orderId}/snap`, {
        method: "POST",
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': data._token
        },
        body:JSON.stringify(data)
      })
      .then(res=>res.json())
      .then(res=> {
        if(res.token){
          snap.pay(res.token, {
            onSuccess: function(result){
              window.location.href = 'trans';
            },
            onPending: function(result){
              alert('Silahkan selesaikan pembayaran anda.');
            },
            onError: function(result){
              alert('Gagal');
            }
          });
        }else{
          alert("Gagal mengambil token pembayaran");
        }
      });
    }
  });

  paymentInput.addEventListener('input', function() {
        const paymentAmount = parseFloat(paymentInput.value) || 0;
        const totalAmount = parseFloat(grandTotalInput.value) || 0;
        const change = paymentAmount - totalAmount;

        changeDisplay.textContent = change >= 0 ? change.toLocaleString('id-ID') : '0';
    });
</script>

@endsection
