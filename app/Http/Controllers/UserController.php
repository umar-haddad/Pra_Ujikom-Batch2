<?php

namespace App\Http\Controllers;

use App\Models\Levels;
use Illuminate\Http\Request;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = User::with('level')->orderBy('id', 'desc')->get();
        $title = "Data User";
        return view('user.index', compact('datas', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah User";
        $levels = Levels::orderBy('id', 'asc')->get();
        return view('user.create', compact('title', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'id_level' => 'required',
        ]);
        User::create($validated);

        alert()->success('Tambah Berhasil', 'Data Berhasil Ditambah');
        return redirect()->to('user')->with('success', 'Data Berhasil Ditambah');
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
        $title = "edit User";
        $levels = Levels::all();
        $user = User::find($id); //BLANK
        // $level = User::findOrFail($id); // INI BIASANYA ERROR 404 KARENA OrFail
        // $level = User::where('id', $id)->First(); //INI BIASANYA DI GUNAKAN UNTUK FOREIGN KEY
        return view('user.edit', compact('user', 'title', 'levels'));
        return redirect()->to('user')->with('success', 'Data Berhasil diubah');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $user->id_level = $request->id_level;
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = $request->password;
        }
        $user->save();
        alert()->success('Ubah Berhasil', 'Data Berhasil Diubah');
        return redirect()->to('user')->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();


        return redirect()->to('user')->with('success', 'Hapus Level Berhasil');
    }
}
