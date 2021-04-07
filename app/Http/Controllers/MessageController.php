<?php

namespace App\Http\Controllers;

use App\Message;
use Auth;
use Response;
use App\User;
use Illuminate\Http\Request;
use DB;

class MessageController extends Controller
{
    public function loadmoremessage($nickname,$id) {
        $user = User::where('nickname',$nickname)->firstorfail();
        $message_id = $id;
        return view('getmoremessage',compact('message_id','message_id','user'));
    }

    public function getmessage($id) {
        $messages = Message::with('sender')->with('receiver')->where('id','>',$id)->whereIn('id',Message::select(DB::RAW('max(id) as id'))->where('sender_id',Auth::user()->id)->orWhere('receiver_id',Auth::user()->id)->groupBy(DB::RAW('IF ('.Auth::user()->id.' = sender_id,receiver_id,sender_id)'))->get()->toArray())->orderBy('id','asc')->paginate(4)->toArray()["data"];
        return $messages;
    }
   
    public function read(Request $request)
    {
        $message = Message::where('sender_id',$request->id)->where('receiver_id',Auth::user()->id)->where('read',0)->update(['read'=>1]);
        return Response::json([
            'success' => true,
            'data' => "read",
        ], 200);
    }

    public function view($id) {
        $user = User::where('nickname',$id)->firstorfail();
        if($user->id==Auth::user()->id)
        {
            return redirect('/allmessage');
        }
        $messages = Message::where('sender_id',$user->id)->where('receiver_id',Auth::user()->id)->orwhere(function($request) use($user){
            $request->where('sender_id',Auth::user()->id)->where('receiver_id',$user->id);
        })->orderBy('id','desc')->paginate(8);
        Message::where('receiver_id',Auth::user()->id)->where('sender_id',$user->id)->where('read',0)->update(["read"=>1]);
        if($user->id==Auth::user()->id)
        {
            return redirect('/profile');
        }
        $viewmessage =  $user;
        return view('viewmessage',compact('messages','user','viewmessage'));
    }

    public function allmessage(){
        $allmessage = true;
        return view('allmessage',compact('allmessage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "message"=>'required',
        ]);
        $user = User::findorfail($request->id);
        $message = new Message();
        $message->sender_id = Auth::user()->id;
        $message->receiver_id = $user->id;
        $message->text = $request->message;
        $message->read = 0;
        $message->stat = 1;
        $message->save();
        return Response::json([
            'success' => true,
            'data' => "unlike",
        ], 200);
    }
}
