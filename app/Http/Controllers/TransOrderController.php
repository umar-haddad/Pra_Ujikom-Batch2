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
use RealRashid\SweetAlert\Facades\Alert;

class TransOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $datas = TransOrders::with('customer')->orderBy('id', 'desc')->get(); // with->ngambil dari data customer
        $title = "Transaksi Order";


        return view('trans.index', compact('title', 'datas'));
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
        $datas = TransOrders::orderBy('id', 'desc')->get();
        return view('trans.laundry', compact('title', 'orderCode', 'customers', 'services', 'datas'));
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

        return redirect()->to('trans')->with('success', 'berhasil di order');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = "Detail Transaksi";
        $details = TransOrders::with(['customer', 'transOrderDetail.service'])->where('id', $id)->first();
        return view('trans.show', compact('title', 'details'));
        dd($details);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transOrder = TransOrders::findOrFail($id);

        $title = 'berhasil terhapus';
        $text = 'yakin mau hapus';
        confirmDelete($title, $text);

        $transOrder->delete();

        return redirect()->to('trans')->with('success', 'Hapus service Berhasil');
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
}