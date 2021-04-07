<?php

namespace App\Http\Controllers;

use App\Komenartikel;
use App\Notification;
use App\Artikel;
use Illuminate\Http\Request;
use Auth;

class KomenartikelController extends Controller
{
    public function getkomenarticle($id,$idkomen) {
        $idkomen = $idkomen;
        $article= Artikel::findorfail($id);
        return view('getkomenarticle',compact('article','idkomen'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            "comment"=>'required|max:255',
            "article"=>"required|exists:artikels,id"
        ]);
        $article = Artikel::findorfail($request->article);
        $users = explode(" ",$request->comment);
        $komenarticle = new Komenartikel();
        $komenarticle->artikel_id = $request->article;
        $komenarticle->user_id = Auth::user()->id;
        $komenarticle->text = $request->comment;
        $komenarticle->stat = 1;
        $komenarticle->save();
        for($i = 0; $i<count($users);$i++)
        {
            if($users[$i][0]=="@")
            {
                $user = substr($users[$i],1,strlen($users[$i]));
                $user = \App\User::where('nickname',$user)->get();
                if($user->count()>0&&$user->first()->id!=$article->user_NIM)
                {
                    $notification = new Notification();
                    $notification->text = "menandai kamu dalam komentar artikel";
                    $notification->user_id = $user->first()->id;
                    $notification->sender_id = Auth::user()->id;
                    $notification->read = 0;
                    $notification->stat = 0;
                    $notification->type = "komenartikel";
                    $notification->not_id = $komenarticle->id;
                    $notification->save();
                }
            }
        }
        if($article->user_id!=Auth::user()->id)
        {
            $notification = new Notification();
            $notification->text = "memberikan komentar di artikel kamu";
            $notification->user_id = $article->user_NIM;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "komenartikel";
            $notification->not_id = $komenarticle->id;
            $notification->save();
        }
        return redirect()->back();
    }
}
