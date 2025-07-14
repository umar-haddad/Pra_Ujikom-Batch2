<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wakey Laundry | Kalibata City</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: auto;
            padding: 10px;
        }

        .struk {
            text-align: center;
        }

        .line {
            margin: 5px 0;
            border-top: 1px dashed black;
        }

        .info {
            text-align: center;
        }

        .service,
        .summary {
            text-align: left;
        }

        .service .item {
            margin-bottom: 5px;
        }

        .item-qty {
            display: flex;
            justify-content: space-between;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        footer {
            text-align: center;
            font-size: 13px;
            margin-top: 10px;
        }

        @media print {
            body {
                width: 80mm;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="struk">
        <div class="info">
            <h3>Wakey Laundry || KALIBATA CITY</h3>
            <p>sayyidumar11@gmail.com</p>
            <p>Jl.Cipinang Jaya aa ujung, Kamp.Besar, Jatinegara</p>
            <p>085772169466</p>
        </div>
        <div class="line"></div>
        <div class="info">
            <div class="row">
                <span>{{ $details->code_order ?? '-' }}</span>
                <span>{{ $details->created_at ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Cashier</span>
                <span>{{ auth()->user()->name }}</span>
            </div>
            <div class="row">
                <span>Order Id:</span>
                <span>{{$details->order_code ?? '-'}}</span>
            </div>
            <div class="row">
                <span>Customer:</span>
                <span>{{ $customer->name ?? '-' }}</span>
            </div>
        </div>
        <div class="line"></div>

        @foreach ($details->transOrderDetail as $detail)

            <div class="service">
                <div class="item">
                    <strong>{{ $detail['service_name'] }}</strong>
                    <div class="item-qty">
                        <span>{{ number_format($detail->qty, 2, ',', '.') }} kg x Rp{{ number_format($detail->service->price, 0, ',', '.') }}</span>
                        <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="line"></div>
        <div class="summary">
            <div class="row">
                <span>Total</span>
                <span>Rp {{ number_format($details->total    ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="line"></div>
        <footer>Terima kasih telah menggunakan layanan kami!</footer>
    </div>

    @if(request('print') === 'true')
        <script>
            window.onload = function() {
                window.print();
                setTimeout(() => window.close(), 1000);
            };
        </script>
    @endif
</body>
</html>
