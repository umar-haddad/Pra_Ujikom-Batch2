<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Levels;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = Levels::orderBy('id', 'desc')->get();
        $title = "Data Level";
        return view('level.index', compact('datas', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah Level";
        return view('level.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Levels::create($request->all());
        return redirect()->to('level')->with('success', 'Level Berhasil ditambah');
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
        $level = Levels::find($id); //BLANK
        // $level = Levels::findOrFail($id); // INI BIASANYA ERROR 404 KARENA OrFail
        // $level = Levels::where('id', $id)->First(); //INI BIASANYA DI GUNAKAN UNTUK FOREIGN KEY
        return view('level.edit', compact('level', 'title'));
        return redirect()->to('level')->with('success', 'Data Berhasil diubah');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $level = Levels::find($id);
        $level->name = $request->name;
        $level->save();
        return redirect()->to('level')->with('success', 'data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $level = Levels::findOrFail($id);
        $level->delete();


        return redirect()->to('level')->with('success', 'Hapus Level Berhasil');
    }
}
