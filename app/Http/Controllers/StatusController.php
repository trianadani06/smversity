<?php

namespace App\Http\Controllers;

use App\Status;
use App\Komenstatus;
use App\Notification;
use Illuminate\Http\Request;
use File;
use Auth;
use App\Ukm;

class StatusController extends Controller
{

    public function getnewallstatus($id) {
        $stat_id = $id;
        return view('getnewallstatus',compact('stat_id'));
    }

    public function getcountallstatus($id) {
        $status = Status::where('id','>',$stat_id)->orderBy('created_at','desc')->count();
        return $status;
    }
    
    public function getcountstatus($id) {
        $status = Status::select('id')->where('id','>',$id)->where(function($query){
            $query->whereHas('user',function($query){
                                                $query->whereHas('followers',function($query){
                                                    $query->where('user_NIM',Auth::user()->id);
                                                });
                                            })->orWhere('user_id',Auth::user()->id);
        })->get();
        return $status;
    }

    public function getnewstatus($id) {
        $stat_id = $id;
        return view('getnewstatus',compact('stat_id'));
    }

    public function getstatus($id) {
        $stat_id = $id;
        return view('getstatus',compact('stat_id'));
    }

    public function getuserstatus($id,$status_id) {
        $user_id = $id;
        $stat_id = $status_id;
        return view('getuserstatus',compact('user_id','stat_id'));
    }

    public function getallstatus($id) {
        $stat_id = $id;
        return view('getallstatus',compact('stat_id'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'description'=>'required|max:255',
            'image'=>'nullable|max:500000'
        ]);
        $stat = new Status();
        $stat->text = $request->description;
        $stat->stat = 1;
        $stat->user_id = Auth::user()->id;
        $stat->save();
        if(isset($request->image)&&!empty($request->image))
        {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/status/" .$stat->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $stat->foto = $image_name;
        }
        $stat->save();
        return redirect()->back();
    }

    public function storeukm(Request $request,Ukm $ukm)
    {
        $request->validate([
            'description'=>'required|max:255',
            'image'=>'nullable|max:500000'
        ]);
        $stat = new Status();
        $stat->ukm_id = $ukm->id;
        $stat->text = $request->description;
        $stat->stat = 1;
        $stat->user_id = Auth::user()->id;
        $stat->save();
        if(isset($request->image)&&!empty($request->image))
        {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/status/" .$stat->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $stat->foto = $image_name;
        }
        $stat->save();
        return redirect()->back()->with('status','status berhasil di post');
    }

    public function view($id) {
        $status = Status::findorfail($id);
        Notification::where('user_id',Auth::user()->id)->where('type','likestatus')->where('not_id',$status->id)->where('read',0)->update(["read"=>1]);
        return view('viewstatus',compact('status'));
    }

    public function destroy(Status $status)
    {
        if(Auth::user()->id==$status->user_id||Auth::user()->role=="admin")
        {
            foreach($status->komenstatuses as $komenstatus)
            {
                $notification = Notification::where('not_id',$komenstatus->id)->where('type','komenstatus')->get()->each->delete();
                $komenstatus->delete();
            }
            $notification = Notification::where('not_id',$status->id)->where('type','likestatus')->get()->each->delete();
            $status->likes()->delete();
            $status->delete();
            if($status->foto)
            {
                File::delete(base_path('/upload/status/'.$status->id.'/').$status->foto);
            }
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->with('kamu tidak memiliki hak untuk mengakses halaman ini');
        }
    }
}
