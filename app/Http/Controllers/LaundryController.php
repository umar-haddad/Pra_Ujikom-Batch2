<?php

namespace App\Http\Controllers;

use App\Models\TransOrderDetail;
use App\Models\TransOrders;
use Illuminate\Http\Request;

class LaundryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $datascus = TransOrders::with('customer')->orderBy('id', 'desc')->get(); // with->ngambil dari data customer
        // $dataservice = TransOrderDetail::with('service')->orderBy('id', 'desc')->get();
        // $title = "Transaksi Order";


        // return view('laundry', compact('title', 'datascus', 'dataservice'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
