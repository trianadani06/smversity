<?php

namespace App\Http\Controllers;

use App\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    
    public function store(Request $request)
    {
        $jurusan = new Jurusan();
        $request->validate([
            'jurusan'                  => ['required', 'string', 'max:255','unique:jurusans,jurusan'],
        ]);
        $jurusan->jurusan = $request->jurusan;
        $jurusan->save();
        return redirect()->back()->with('status','Jurusan berhasil di tambahkan');
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'jurusan'                  => ['required', 'string', 'max:255','unique:jurusans,jurusan'],
        ]);
        $jurusan->jurusan = $request->jurusan;
        $jurusan->save();
        return redirect()->back()->with('status','Jurusan berhasil di update');
    }

}
