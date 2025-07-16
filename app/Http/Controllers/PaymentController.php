<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\TransOrders;
use  App\Models\TransOrderDetail;
use App\Models\TypeOfServices;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
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
        $title = 'Transaksi';
        return view('trans.index',  compact('datas', 'title'));
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

    public function payCash(Request $request, $id)
    {
        $order = TransOrders::findOrFail($id);

        // Simpan pembayaran cash ke database (misalnya update status)
        $order->status_text = 1;
        $order->save();

        return redirect()->to('trans.index')->with('success', 'Pembayaran cash berhasil!');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //TR-01072025-001
        $title = 'Tambah Transaksi';

        // $today = date('dmY');
        $today = Carbon::now()->format('dmY');
        $countDay = TransOrders::whereDate('created_at', now()->toDateString())->count() + 1;
        $runningNumber = str_pad($countDay, 3, '0', STR_PAD_LEFT);
        $orderCode = "TR-" . $today . "-" . $runningNumber;
        $customers = Customers::orderBy('id', 'desc')->get();
        $services = TypeOfServices::orderBy('id', 'desc')->get();
        return view('trans.create', compact('title', 'orderCode', 'customers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transOrder = TransOrders::create([
            'id_customer' => $request->id_customer,
            'order_code' => $request->order_code,
            'order_end_date' => $request->order_end_date,
            'total' => $request->total
        ]);

        foreach ($request->id_service as $key => $idService) {
            $id_trans = $transOrder->id;
            TransOrderDetail::create([
                'id_trans' => $id_trans,
                'id_service' => $idService,
                'qty' => $request->qty[$key],
                'subtotal' => $request->subtotal[$key]
            ]);
        }
        // TransOrders::create($request->all());
        return redirect()->to('trans')->with('success', 'Data Berhasil Ditambah');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request) {}

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
        //
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
        $details = TransOrders::with('customer', 'details.service')->where('id', $id)->first();
        // $services = TransOrderDetail::with('services')->where('id_service', $id)->get();
        // dd($details);
        // return ($details);
        return view('trans.print_struk', compact('details'));
    }
}
