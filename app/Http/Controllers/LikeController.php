<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Like;
use App\Status;
use Illuminate\Http\Request;
use Auth;
use Response;

class LikeController extends Controller
{
    public function store(Request $request)
    {
        $status = Status::findorfail($request->id);
        $like = Like::where('status_id',$request->id)->where('user_id',Auth::user()->id)->get();
        if($like->count()>0)
        {
            $like->first()->delete();
            return Response::json([
                'success' => true,
                'data' => "unlike",
                'like' =>$status->likes->count(),
            ], 200);
        }
        else
        {
            $like = new Like();
            $like->status_id = $request->id;
            $like->user_id = Auth::user()->id;
            $like->save();
            if(Auth::user()->id!=$status->user_id)
            {
                $notification = new Notification();
                $notification->text = "menyukai status anda";
                $notification->user_id = $status->user_id;
                $notification->sender_id = Auth::user()->id;
                $notification->read = 0;
                $notification->stat = 0;
                $notification->type = "likestatus";
                $notification->not_id = $status->id;
                $notification->save();
            }
            return Response::json([
                'success' => true,
                'data' => "like",
                'like' =>$status->likes->count(),
            ], 200);
        }
    }
}
