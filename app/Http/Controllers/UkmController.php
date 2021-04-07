<?php

namespace App\Http\Controllers;

use App\Ukm;
use App\Ukmanggota;
use Illuminate\Http\Request;
use App\Notification;
use App\User;
use File;
use Auth;

class UkmController extends Controller
{
    public function view($id) {
        $ukm = Ukm::where('slug',$id)->firstorfail();
        foreach($ukm->anggotas->where('user_id',Auth::user()->id) as $ukmanggota)
        {
            Notification::where('user_id',Auth::user()->id)->where(function($query){ $query->where('type','joinukm')->orwhere('type','quitukm');})->where('user_id',Auth::user()->id)->update(['read'=>'1']);
        }
        Notification::where('user_id',Auth::user()->id)->where(function($query){ $query->where('type','ukm');})->where('user_id',Auth::user()->id)->update(['read'=>'1']);
        return view('ukmprofile',compact('ukm'));
    }

    public function changeimage(Request $request,Ukm $ukm)
    {
        $request->validate([
            'description'=>'required|max:100'
        ]);
        $ukm->text = $request->description;
        if(isset($request->image)&&!empty($request->image))
        {
            if($ukm->foto)
            {
                File::delete(base_path('/upload/ukm/'.$ukm->id.'/').$ukm->foto);
            }
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/ukm/" .$ukm->id.'/';
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $ukm->foto = $image_name;
        }
        $ukm->save();
        return redirect()->back();
    }

    public function changecover(Request $request,Ukm $ukm)
    {
        $request->validate([
            'image'=>'required'
        ]);
        if(isset($request->image)&&!empty($request->image))
        {
            if($ukm->cover)
            {
                File::delete(base_path('/upload/ukm/'.$ukm->id.'/cover/').$ukm->cover);
            }
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/ukm/" .$ukm->id.'/cover';
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $ukm->cover = $image_name;
        }
        $ukm->save();
        return redirect()->back();
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
            'title'=>'required|max:50|unique:ukms,name',
            'description'=>'required|max:100',
            'leader'=>'required|exists:users,username',
            'image'=>'required',
            'cover'=>'required'
        ]);
        $leader = User::where('username',$request->leader)->first();
        $ukm= new UKM();
        $ukm->name = $request->title;
        $ukm->text = $request->description;
        $ukm->user_NIM = $leader->id;
        $ukm->stat = 1;
        $ukm->verifiedBy = Auth::user()->id;
        $ukm->save();
        if(isset($request->image)&&!empty($request->image))
        {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/ukm/" .$ukm->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $ukm->foto = $image_name;
        }
        if(isset($request->cover)&&!empty($request->cover))
        {
            $data = $request->cover;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/ukm/" .$ukm->id.'/cover';
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $ukm->cover = $image_name;
        }
        $ukm->save();
        $notification = new Notification();
        $notification->text = "Memilih anda menjadi ketua UKM ".$ukm->name;
        $notification->user_id = $leader->id;
        $notification->sender_id = Auth::user()->id;
        $notification->read = 0;
        $notification->stat = 0;
        $notification->type = "ukm";
        $notification->not_id = $ukm->id;
        $notification->save();
        $ukmanggota = new Ukmanggota();
        $ukmanggota->user_id = $leader->id;
        $ukmanggota->ukm_id = $ukm->id;
        $ukmanggota->stat = 1;
        $ukmanggota->jabatan = "leader";
        $ukmanggota->save();
        return redirect('/addukm')->with('status','UKM berhasil ditambahkan');
    }

    public function getukm($id) {
        $ukm_id = $id;
        return view('getukm',compact('ukm_id'));
    }

    public function getukmstatus($id,$status_id) {
        $ukm_id = $id;
        $stat_id = $status_id;
        return view('getukmstatus',compact('ukm_id','stat_id'));
    }
    
    public function changeleader(Request $request,Ukm $ukm)
    {
        $request->validate([
            'leader'=>'required|exists:users,id'
        ]);
        if($ukm->anggotas->where('user_id',$request->leader)->count()>0)
        {
            $ukm->user_NIM = $request->leader;
            $ukm->save();
            $ukm->anggotas->where('user_id',$request->leader)->first()->jabatan = "leader";
            $ukm->anggotas->where('user_id',$request->leader)->first()->save();
            $ukm->anggotas->where('user_id',Auth::user()->id)->first()->jabatan = "Anggot";
            $ukm->anggotas->where('user_id',Auth::user()->id)->first()->save();
            $notification = new Notification();
            $notification->text = "Memilih anda menjadi ketua UKM ".$ukm->name;
            $notification->user_id = $ukm->user_NIM;
            $notification->sender_id = Auth::user()->id;
            $notification->read = 0;
            $notification->stat = 0;
            $notification->type = "ukm";
            $notification->not_id = $ukm->id;
            $notification->save();
            return redirect()->back()->with('status','Leader berhasil diganti');
        }
        else
        {
            return redirect()->back()->with('warning','Leader tidak terdaftar di ukm ini');
        }
    }
}
