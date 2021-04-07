<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{

    public function morenotif($notifsetting_id) {
        $notifsetting_id = $notifsetting_id;
        return view('getmorenotif',compact('notifsetting_id'));
    }

    public function getnotif($id) {
        $notifications = Notification::with('sender')->where('id','>',$id)->where('user_id',Auth::user()->id)->orderBy('created_at','asc')->get();
        return view('getnotif',compact('notifications'));
    }
}
