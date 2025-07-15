<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\TransOrders;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransOrderPaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function printStruk(string $id)
    {
        $details = TransOrders::with(['customer', 'transOrderDetail.service'])->where('id', $id)->first();
        // return $details; // ->  buat debug laravel dan ada lagi yaitu => dd($details);

        $pdf = Pdf::loadView('trans.print', compact('details'));
        return $pdf->download('struk-transaksi.pdf');
    }


    public function snap(Request $request, $id)
    {
        $order = TransOrders::with('customer')->findOrFail($id);

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->customer->first_name,
                'email' => $order->customer->email,
                'phone' => $order->customer->phone
            ],
            'enable_payment' => [
                'qris'
            ]
        ];
        // $snapToken = Snap::getSnapToken($params);
        $snap = Snap::createTransaction($params);
        return response()->json(['token' => $snap->token]);
    }

    public function apiTransactions()
    {
        $transactions = TransOrders::with([
            'customer',
            'transOrderDetail.service'
        ])->orderBy('id', 'desc')->get();

        return response()->json($transactions);
    }
}
