<?php

namespace App\Http\Controllers;

use App\Likeartikel;
use App\Artikel;
use Illuminate\Http\Request;
use App\Notification;
use Auth;
use Response;

class LikeartikelController extends Controller
{
    public function store(Request $request)
    {
        $article = Artikel::findorfail($request->id);
        $like = Likeartikel::where('artikel_id',$request->id)->where('user_NIM',Auth::user()->id)->get();
        if($like->count()>0)
        {
            $like->first()->delete();
            return Response::json([
                'success' => true,
                'data' => "unlike",
                'like' =>$article->likes->count(),
            ], 200);
        }
        else
        {
            $like = new Likeartikel();
            $like->artikel_id = $request->id;
            $like->user_NIM = Auth::user()->id;
            $like->save();
            if(Auth::user()->id!=$article->user_NIM)
            {
                $notification = new Notification();
                $notification->text = "menyukai artikel anda";
                $notification->user_id = $article->user_NIM;
                $notification->sender_id = Auth::user()->id;
                $notification->read = 0;
                $notification->stat = 0;
                $notification->type = "likeartikel";
                $notification->not_id = $article->id;
                $notification->save();
            }
            return Response::json([
                'success' => true,
                'data' => "like",
                'like' =>$article->likes->count(),
            ], 200);
        }
    }
}
