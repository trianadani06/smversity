<?php

namespace App\Http\Controllers;

use App\Status;
use App\Notification;
use App\Komenstatus;
use Illuminate\Http\Request;
use Auth;

class KomenstatusController extends Controller
{
    public function getkomenstatus($id,$idkomen) {
        $idkomen = $idkomen;
        $status = Status::findorfail($id);
        return view('getkomenstatus',compact('status','idkomen'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            "comment"=>'required|max:255',
            "status"=>"required|exists:statuses,id"
        ]);
        $status = Status::findorfail($request->status);
        $users = explode(" ",$request->comment);
        $komenstatus = new Komenstatus();
        $komenstatus->status_id = $request->status;
        $komenstatus->user_id = Auth::user()->id;
        $komenstatus->text = $request->comment;
        $komenstatus->stat = 1;
        $komenstatus->save();
        for($i = 0; $i<count($users);$i++)
        {
            if($users[$i][0]=="@")
            {
                $user = substr($users[$i],1,strlen($users[$i]));
                $user = \App\User::where('nickname',$user)->get();
                if($user->count()>0&&$user->first()->id!=$status->user_id)
                {
                    $notification = new Notification();
                    $notification->text = "menandai kamu dalam komentar status";
                    $notification->user_id = $user->first()->id;
                    $notification->sender_id = Auth::user()->id;
                    $notification->read = 0;
                    $notification->stat = 0;
                    $notification->type = "komenstatus";
                    $notification->not_id = $komenstatus->id;
                    $notification->save();
                }
            }
        }
        if($status->user_id!=Auth::user()->id)
        {
            $notification = new Notification();
            $notification->text = "memberikan komentar di status kamu";
            $notification->user_id = $status->user_id;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "komenstatus";
            $notification->not_id = $komenstatus->id;
            $notification->save();
        }
        return redirect()->back()->with('status','berhasil menambahkan komentar');
    }
}
