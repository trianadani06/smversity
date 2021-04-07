<?php

namespace App\Http\Controllers;

use App\Ukmanggota;
use Auth;
use App\Ukm;
use Illuminate\Http\Request;
use App\Notification;
use Response;

class UkmanggotaController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'id'=>'required|exists:ukms,id'
        ]);
        $ukm = Ukm::findorfail($request->id);
        $ukmanggota = $ukm->anggotas->where('user_id',Auth::user()->id);
        if($ukmanggota->count()>0)
        {
            $notification = Notification::where('user_id',$ukm->user_NIM)->where('sender_id',Auth::user()->id)->where('type','joinukm')->where('not_id',$ukmanggota->first()->id)->delete();
            $notification = new Notification();
            $notification->text = "keluar dari UKM anda";
            $notification->user_id = $ukm->user_NIM ;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "quitukm";
            $notification->not_id = $ukm->id;
            $notification->save();
            $ukmanggota->first()->delete();
            return Response::json([
                'success' => true,
                'data' => "unfollow",
            ], 200);
        }
        else
        {
            $ukmanggota = new Ukmanggota();
            $ukmanggota->ukm_id = $ukm->id;
            $ukmanggota->user_id = Auth::user()->id;
            $ukmanggota->stat = 0;
            $ukmanggota->jabatan = "anggota";
            $ukmanggota->save();
            $notification = new Notification();
            $notification->text = "ingin bergabung dengan UKM anda";
            $notification->user_id = $ukm->user_NIM ;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "joinukm";
            $notification->not_id = $ukmanggota->id;
            $notification->save();
            return Response::json([
                'success' => true,
                'data' => "follow",
            ], 200);
        }
    }

    public function viewallmember($slug)
    {
        $thisukm = Ukm::where('slug',$slug)->firstorfail();
        return view('viewmember',compact('thisukm'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Ukmanggota  $ukmanggota
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request,Ukmanggota $ukmanggota)
    {
        if($ukmanggota->stat==0)
        {
            $ukmanggota->stat=1;
            $ukmanggota->save();
            $notification = new Notification();
            $notification->text = "telah mengizinkan anda masuk ke dalam UKM";
            $notification->user_id = $ukmanggota->user_id;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "ukm";
            $notification->not_id = $ukmanggota->ukm_id;
            $notification->save();
            return redirect()->back()->with('status','User berhasil masuk UKM');
        }
        else
        {
            return redirect()->back()->with('warning','Terjadi kesalahan');
        }
    }

    public function decline(Request $request,Ukmanggota $ukmanggota)
    {
        if($ukmanggota->stat==0)
        {
            $notification = new Notification();
            $notification->text = "telah menolak permintaan anda untuk masuk UKM";
            $notification->user_id = $ukmanggota->user_id;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "ukm";
            $notification->not_id = $ukmanggota->ukm_id;
            $notification->save();
            Notification::where('not_id',$ukmanggota->id)->where('type','joinukm')->delete();
            $ukmanggota->delete();
            return redirect()->back()->with('status','User berhasil ditolak untuk masuk UKM');
        }
        else
        {
            return redirect()->back()->with('warning','Terjadi kesalahan');
        }
    }

}
