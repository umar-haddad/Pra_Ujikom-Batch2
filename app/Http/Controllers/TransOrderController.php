<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Customers;
use App\Models\TransOrders;
use Illuminate\Http\Request;
use App\Models\TypeOfServices;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransOrderDetail;

class TransOrderController extends Controller
{

    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = TransOrders::with('customer')->orderBy('id', 'desc')->get();
        // $datas = TransOrders::all();
        $title = 'Transaction';
        return view('trans.index',  compact('datas', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //TR-01072025-001
        $title = 'Add Transaction';

        // $today = date('dmY');
        $today = Carbon::now()->format('dmY');
        $countDay = TransOrders::whereDate('created_at', now()->toDateString())->count() + 1;
        $runningNumber = str_pad($countDay, 3, '0', STR_PAD_LEFT);
        $orderCode = "TR-" . $today . "-" . $runningNumber;

        $customers = Customers::orderBy('id', 'desc')->get();
        $services = TypeOfServices::orderBy('id', 'desc')->get();
        return view('trans.laundry', compact('title', 'orderCode', 'customers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transOrder = TransOrders::create([
            'id_customer' => $request->id_customer,
            'order_code' => $request->order_code ?? 'TRX-' . time(),
            'order_end_date' => $request->order_end_date ?? now()->addDays(2),
            'total' => $request->total,
            'notes' => $request->notes ?? ''
        ]);


        foreach ($request->items as $item) {
            TransOrderDetail::create([
                'id_trans' => $transOrder->id,
                'id_service' => $item['id_service'],
                'qty' => $item['qty'],
                'subtotal' => $item['subtotal']
            ]);
        }
    }
    // TransOrders::create($request->all());

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $title = "Transaction Detail";
        $details = TransOrders::with('customer', 'details.service')->where('id', $id)->first();

        return view('trans.show', compact('title', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = TransOrders::findOrFail($id);
        $order->order_pay = $request->order_pay;
        $order->order_change = $request->order_change;
        $order->order_status = 1;
        $order->save();
        return redirect()->to('trans')->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trans = TransOrders::findOrFail($id);
        $trans->delete();

        return redirect()->to('trans')->with('success', 'Hapus Berhasil');
    }

    public function printStruk(string $id)
    {
        $details = TransOrders::with(['customer', 'details.service'])->where('id', $id)->first();
        // return $details; // ->  buat debug laravel dan ada lagi yaitu => dd($details);
        // $details = TransOrders::with('customer', 'details')->where('id', $id)->first();
        // return view('trans.print_struk', compact('details'));

        $pdf = Pdf::loadView('trans.print_struk', compact('details'));
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

    public function transStore(Request $request)
    {
        return $request;
    }
}
