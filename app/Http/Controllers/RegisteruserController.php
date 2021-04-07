<?php

namespace App\Http\Controllers;

use App\Registeruser;
use Illuminate\Http\Request;
use Mail;
use Auth;
use File;

class RegisteruserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notme($token)
    {
        $register = Registeruser::where('token',$token)->firstorfail();
        if($register->foto)
        {
            File::delete(base_path('/upload/register/'.$register->id.'/').$register->foto);
        }
        if($register->ktp)
        {
            File::delete(base_path('/upload/register/'.$register->id.'/ktp/').$register->ktp);
        }
        $register->delete();
        return redirect('login')->with('status','Pendaftaran anda berhasil dibatalkan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate($token)
    {
        $register = Registeruser::where('token',$token)->firstorfail();
        $register->stat = 1;
        $register->token = null;
        $register->save();
        return redirect('login')->with('status','Pendaftaran anda berhasil diteruskan ke admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                "nickname"=>"required|unique:users,nickname|unique:registerusers,nickname|min:6|max:14",
                "nim"=>"required|unique:users,username|unique:registerusers,nim",
                "email"=>"email|required|unique:users,email|unique:registerusers,email",
                "image"=>"required",
                "image1"=>"required",
            ]
        );
        $register = new Registeruser();
        $register->nickname = $request->nickname;
        $register->nim = $request->nim;
        $register->email = $request->email;
        $register->stat=0;
        $register->token = str_random(40);
        $register->save();
        if(isset($request->image)&&!empty($request->image))
        {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/register/" .$register->id;
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $register->foto = $image_name;
        }
        if(isset($request->image1)&&!empty($request->image1))
        {
            $data = $request->image1;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/register/" .$register->id.'/ktp';
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $register->ktp = $image_name;
        }
        $register->save();
        Mail::send('emailregisverifakun',  ['register' => $register], function($message) use($register){
            $message->to($register->email, $register->name)->subject
               ('SM Versity Register Account');
            $message->from('trianadani01@gmail.com','Triana Dani');
         });
        return redirect('login')->with('status','Pendaftaran berhasil. Silahkan konfirmasi email anda.');
    }

    public function decline(Request $requst,Registeruser $registeruser)
    {
        Mail::send('emailverifiedfailed',  ['register' => $registeruser], function($message) use($registeruser){
            $message->to($registeruser->email, $registeruser->nickname)->subject
               ('SM Versity Account Declined');
            $message->from('trianadani01@gmail.com','Triana Dani');
         });
        $registeruser->delete();
        return redirect()->back()->with('status','Pendaftaran user berhasil di tolak.');

    }
}
