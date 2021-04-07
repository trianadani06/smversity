<?php

namespace App\Http\Controllers;

use App\Artikel;
use App\Komenartikel;
use App\Notification;
use Illuminate\Http\Request;
use Auth;
use File;

class ArtikelController extends Controller
{
    public function getarticle($id) {
        $article_id = $id;
        return view('getarticle',compact('article_id'));
    }

    public function getuserarticle($id,$artikel_id) {
        $user_id = $id;
        $artikel_id = $artikel_id;
        return view('getuserarticle',compact('user_id','artikel_id'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|max:50',
            'description'=>'required',
            'category'=>'required|exists:artikelcategories,id',
            'image'=>'required'
        ]);
        $article = new Artikel();
        $article->name = $request->title;
        $article->text = $request->description;
        $article->user_NIM = Auth::user()->id;
        $article->stat = 1;
        $article->verifiedBy = 1;
        $article->foto = $request->foto;
        $article->categoryartikel_id = $request->category;
        $article->view = 0;
        $article->save();
        if(isset($request->image)&&!empty($request->image))
        {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/artikel/" .$article->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $article->foto = $image_name;
        }
        $article->save();
        return redirect('/articles')->with('status','Artikel berhasil di tambahkan');
    }

    public function edit($artikel)
    {
        $article = Artikel::where('slug',$artikel)->get();
        if($article->count()>0&&$article->first()->user_NIM == Auth::user()->id)
        {
            $article = $article->first();
            return view('editarticle',compact('article'));
        }   
        else
        {
            return redirect()->back()->with('kamu tidak memiliki hak untuk mengakses halaman ini');
        }
    }

    public function view ($id) {
        $article = Artikel::where('slug',$id)->firstorfail();
        Notification::where('user_id',Auth::user()->id)->where('type','likeartikel')->where('not_id',$article->id)->where('read',0)->update(["read"=>1]);
        return view('viewarticle',compact('article'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $article = $artikel;
        $request->validate([
            'title'=>'required|max:255',
            'description'=>'required',
            'category'=>'required|exists:artikelcategories,id',
            'image'=>'required'
        ]);
        $article->name = $request->title;
        $article->text = $request->description;
        $article->user_NIM = Auth::user()->id;
        $article->stat = 1;
        $article->slug = null;
        $article->verifiedBy = 1;
        $article->foto = $request->foto;
        $article->categoryartikel_id = $request->category;
        $article->view = 0;
        $article->save();
        if(isset($request->image)&&!empty($request->image))
        {
            if($article->foto)
            {
                File::delete(base_path('/upload/artikel/'.$article->id.'/').$article->foto);
            }
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/artikel/" .$article->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $article->foto = $image_name;
        }
        $article->save();
        return redirect('/articles');
    }

    public function destroy(Artikel $artikel)
    {
        if(Auth::user()->id==$artikel->user_NIM||Auth::user()->role=="admin")
        {
            foreach($artikel->komenartikels as $komenartikel)
            {
                $notification = Notification::where('not_id',$komenartikel->id)->where('type','komenartikel')->get()->each->delete();
                $komenartikel->delete();
            }
            $notification = Notification::where('not_id',$artikel->id)->where('type','likeartikel')->get()->each->delete();
            $artikel->likes()->delete();
            if($artikel->foto)
            {
                File::delete(base_path('/upload/artikel/'.$artikel->id.'/').$artikel->foto);
            }
            $artikel->delete();
            return redirect()->back();
        }
        else
        {
            return redirect('articles')->with('kamu tidak memiliki hak untuk mengakses halaman ini');
        }
    }
}
