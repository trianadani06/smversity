<?php

namespace App\Http\Controllers;

use App\Follow;
use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Notification;
use Response;

class FollowController extends Controller
{
    
    public function viewallfollow($username)
    {
        $thisuser = User::where('nickname',$username)->firstorfail();
        return view('viewfollow',compact('thisuser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id'=>'required|exists:users,id'
        ]);
        $user = User::findorfail($request->id);
        $follow = Follow::where('user_NIM',Auth::user()->id)->where('following',$user->id)->get();
        if($follow->count()>0)
        {
            $notification = Notification::where('user_id',$user->id)->where('sender_id',Auth::user()->id)->where('type','follow')->where('not_id',$follow->first()->id)->delete();
            $follow->first()->delete();
            return Response::json([
                'success' => true,
                'data' => "unfollow",
            ], 200);
        }
        else
        {
            $follow = new Follow();
            $follow->following = $request->id;
            $follow->user_NIM = Auth::user()->id;
            $follow->save();
            $notification = new Notification();
            $notification->text = "mulai mengikuti anda";
            $notification->user_id = $user->id;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "follow";
            $notification->not_id = $follow->id;
            $notification->save();
            return Response::json([
                'success' => true,
                'data' => "follow",
            ], 200);
        }
        
    }
}
