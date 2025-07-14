@extends('app')
@section('content')

<div class="container"></div>
 @include('sweetalert::alert')
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-header">{{ $title }}</div>
                <div class="card-body">
                    <form action="{{ route('trans.store') }}" method="Post">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="">No Pesanan</label>
                                <input type="text" class="form-control" name="order_code" readonly value="{{ $orderCode ?? '' }}">
                                <div class="mt-3">
                                    <label for="">Pelanggan</label>
                                    <select name="id_customer" id="" class="form-control">
                                        <option value="">--- Pilih pelanggan ---</option>
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="">Pengambilan :</label>
                                    <input type="date" name="order_end_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Catatan</label>
                                <textarea name="notes" id="" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                                <div class="mb-3" align="right">
                                    <button align="right" type="button" class="btn btn-success mt-3" id="inputOrder">Add Row</button>
                                </div>
                            <table class="table table-bordered" id="orderDetail">
                                <thead>
                                <tr>
                                    <th>no</th>
                                    <th>service :</th>
                                    <th>qty :</th>
                                    <th>price :</th>
                                    <th>subtotal</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <div class="col-md-6">
                                <label for="" class="btn btn-outline-primary" id="grandtotal">Grand Total : 0</label>
                                <input type="hidden" name="total">
                            </div>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//ambil data service di php
let services = <?php echo $services?>;

//ambil input si addrow

function numberRow() {
    const rows = document.querySelectorAll('#orderDetail tbody tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

document.getElementById('inputOrder').addEventListener('click', function() {
    const tbody = document.querySelector('#orderDetail tbody');
    const row = document.createElement('tr');

    let serviceOptions = '<option value="">-- Pilih Service --</option>';
    services.forEach(service => {
        serviceOptions += `<option value="${service.id}" data-price="${service.price}">
                ${service.service_name}
            </option>`;
    });

    row.innerHTML = `
        <td class="row-number"></td>
        <td>
            <select name="id_service[]" class="form-control service-select" required>
                ${serviceOptions}
            </select>
        </td>
        <td><input type="number" name="qty[]" step="any" class="form-control qty" value="1"></td>
        <td><input type="number" name="harga[]" class="form-control harga" readonly></td>
        <td><input type="number" name="subtotal[]" class="form-control subtotal" value="0" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm deleteRow">X</button></td>
    `;

    tbody.appendChild(row);
    newRow(row); // <-- Kirim baris yang baru ditambahkan
    numberRow(row); //nambah si No
});

function hitungTotal() {
    const totalFields = document.querySelectorAll('.subtotal');
    let grand = 0;
    totalFields.forEach(field => {
        grand += parseFloat(field.value || 0);
    });
    document.getElementById('grandtotal').innerText = `Total : ${grand.toLocaleString()}`

    const inputTotal = document.querySelector('input[name="total"]');
    if (inputTotal) inputTotal.value = grand;
}

function newRow(row) {
    const select = row.querySelector('.service-select');
    const qty = row.querySelector('.qty');
    const harga = row.querySelector('.harga');
    const subtotal = row.querySelector('.subtotal');
    const deleteBtn = row.querySelector('.deleteRow');


    select.addEventListener('change', function() {
        const price = this.options[this.selectedIndex].getAttribute('data-price');
        harga.value = price || 0;
        subtotal.value = (qty.value || 0) * (price || 0)
        hitungTotal();
    });

    qty.addEventListener('input', function() {
        const price = parseFloat(harga.value) || 0;
        const quantity = parseFloat(qty.value) || 0;
        subtotal.value = quantity * price;
        hitungTotal();
    });

    deleteBtn.addEventListener('click', function() {
        row.remove();
        hitungTotal();
        numberRow(); // hapus si no yang ditambah lewat numberRow()
    });;
}
</script>
@endsection
