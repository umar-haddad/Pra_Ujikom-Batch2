<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = Customers::orderBy('id', 'desc')->get();
        $title = "Data Customer";
        return view('customer.index', compact('datas', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Customer";
        return view('customer.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Customers::create($request->all());
        return redirect()->to('customer')->with('success', 'customer Berhasil ditambah');
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
        $title = "edit";
        $customer = Customers::find($id); //BLANK
        // $customer = Customers::findOrFail($id); // INI BIASANYA ERROR 404 KARENA OrFail
        // $customer = Customers::where('id', $id)->First(); //INI BIASANYA DI GUNAKAN UNTUK FOREIGN KEY
        return view('customer.edit', compact('customer', 'title'));
        return redirect()->to('customer')->with('success', 'Data Berhasil diubah');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customers::find($id);
        $customer->name = $request->name;
        $customer->save();
        return redirect()->to('customer')->with('success', 'data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customers::findOrFail($id);
        $customer->delete();


        return redirect()->to('customer')->with('success', 'Hapus service Berhasil' );
    }
}
