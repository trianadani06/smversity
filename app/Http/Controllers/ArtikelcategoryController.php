<?php

namespace App\Http\Controllers;

use App\Artikelcategory;
use Illuminate\Http\Request;

class ArtikelcategoryController extends Controller
{

    public function store(Request $request)
    {
        $categoryarticle = new Artikelcategory();
        $request->validate([
            'name'                  => ['required', 'string', 'max:255','unique:artikelcategories,name'],
        ]);
        $categoryarticle->name = $request->name;
        $categoryarticle->save();
        return redirect()->back()->with('status','Kategori Artikel berhasil di tambahkan');
    }

    public function update(Request $request, Artikelcategory $artikelcategory)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255','unique:artikelcategories,name,'.$artikelcategory->id],
        ]);
        $artikelcategory->name = $request->name;
        $artikelcategory->save();
        return redirect()->back()->with('status','Kategori Artikel berhasil di update');
    }
}
