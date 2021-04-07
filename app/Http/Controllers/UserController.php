<?php

namespace App\Http\Controllers;

use App\User;
use App\Registeruser;
use App\Notification;
use Illuminate\Http\Request;
use Auth;
use File;
use Hash;
use App\Verifyuser;
use Mail;

class UserController extends Controller
{

    public function changepassword(Request $request)
    {
        $request->validate(
            [
                'oldpassword' => 'required|max:12|min:6',
                'password' => 'required|max:12|min:6|confirmed',
                'password_confirmation' => 'required|max:12|min:6', 
            ]
        );
        if(Hash::check($request->oldpassword,Auth::user()->password))
        {           
            Auth::user()->password = Hash::make($request->password);;
            Auth::user()->save(); 
            return redirect()->back()->with('status','Password berhasil di ganti');
        }
        else
        {           
            return redirect()->back()->with('danger','Password gagal diganti');
        }
    }

    public function changeimage(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'description'=>'required|max:50'
        ]);
        $user->description = $request->description;
        if(isset($request->image)&&!empty($request->image))
        {
            if(Auth::user()->foto)
            {
                File::delete(base_path('/upload/user/'.Auth::user()->id.'/foto/').Auth::user()->foto);
            }
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/user/" .$user->id.'/foto';
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $user->foto = $image_name;
        }
        $user->save();
        return redirect()->back()->with('status','berhasil mengupdate detail');
    }

    public function changecover(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'image'=>'required'
        ]);
        if(isset($request->image)&&!empty($request->image))
        {
            if(Auth::user()->coverfoto)
            {
                File::delete(base_path('/upload/user/'.Auth::user()->id.'/cover/').Auth::user()->coverfoto);
            }
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.jpg';
            $path = base_path() . "/upload/user/" .$user->id.'/cover';
            if (!file_exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $path .= '/'.$image_name;
            file_put_contents($path, $data);
            $user->coverfoto = $image_name;
        }
        $user->save();
        return redirect()->back()->with('status','berhasil mengupdate cover');
    }

    public function hide(Request $request, User $user)
    {
        $user->stat = 0;
        $user->save();
        return redirect()->back()->with('status','User berhasil di nonaktifkan');
    }

    public function unhide(Request $request, User $user)
    {
        $user->stat = 1;
        $user->save();
        return redirect()->back()->with('status','User berhasil di aktifkan');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'role'                  => 'required',
            'username'              => 'required|max:14|min:6|unique:users,username,'.$user->id,
            'email'                 => 'required|unique:users,email,'.$user->id,
            'jurusan'               => 'required|exists:jurusans,id',
            'angkatan'              => 'required',
            'tanggallahir'          => 'required|date',
            'nickname'              => 'required|unique:users,nickname,'.$user->id.'|max:14|min:6',
        ]);
        $user->username = $request->username;
        $user->tanggallahir = $request->tanggallahir;
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->role = $request->role;
        $user->jurusan_id = $request->jurusan;
        $user->angkatan = $request->angkatan;
        $user->save();
        return redirect()->back()->with('status','User berhasil di update');
    }

    public function search(){
        if(ISSET($_GET['search']))
        {
            $users = User::withCount('followers')->where('id','!=',Auth::user()->id)->where('nickname', 'LIKE', "%".$_GET['search']."%")->orderBy('followers_count', 'desc')->paginate(4);
        }
        else
        {
            $users = User::withCount('followers')->where('id','!=',Auth::user()->id)->orderBy('followers_count', 'desc')->paginate(4);
        }
        return view('search',compact('users'));
    }

    public function view($id) {
        $user = User::where('nickname',$id)->firstorfail();
        $myid = Auth::user()->id;
        $user_id = $user->id;
        $follows = Notification::where('user_id',Auth::user()->id)->where('sender_id',$user->id)->where('type','follow')->whereHas('follow',function ($query)use($myid,$user_id) {
            $query->where('following',$myid)->where('user_NIM',$user_id);
        })->update(['read'=>1]);
        if($user->id==Auth::user()->id)
        {
            return redirect('/profile');
        }
        return view('userprofile',compact('user'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'password'              => 'required|min:6|confirmed|max:12',
            'password_confirmation' => 'required|min:6|max:12',
            'name'                  => ['required', 'string', 'max:255'],
            'role'                  => 'required',
            'username'              => 'required|max:14|min:6|unique:users,username',
            'email'                 => 'required|unique:users,email',
            'jurusan'               => 'required|exists:jurusans,id',
            'angkatan'              => 'required',
            'tanggallahir'          => 'required|date',
            'nickname'              => 'required|unique:users,nickname|max:14|min:6',
        ]);
        $user = new User();
        $user->username = $request->username;
        $user->tanggallahir = $request->tanggallahir;
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->role = $request->role;
        $user->jurusan_id = $request->jurusan;
        $user->angkatan = $request->angkatan;
        $user->password = Hash::make($request->password);;
        $user->stat = 1;
        $user->verifiedBy = 0;
        $user->description = "New User";
        $user->foto = "foto.jpg";
        $user->coverfoto = "cover.jpg";
        $user->save();
        if (!file_exists(base_path('upload/user/'.$user->id.'/foto'))) {
            File::makeDirectory(base_path('upload/user/'.$user->id.'/foto'), $mode = 0777, true, true);
        }
        File::copy(base_path('upload/user.png'),base_path('upload/user/'.$user->id.'/foto/foto.jpg'));
        if (!file_exists(base_path('upload/user/'.$user->id.'/cover'))) {
            File::makeDirectory(base_path('upload/user/'.$user->id.'/cover'), $mode = 0777, true, true);
        }
        File::copy(base_path('upload/cover.jpg'),base_path('upload/user/'.$user->id.'/cover/cover.jpg'));
        $verifyUser = new Verifyuser();
        $verifyUser->user_id = $user->id;
        $verifyUser->token = str_random(40);
        $verifyUser->save();
        Mail::send('emailverified',  ['verified' => $verifyUser,'password'=>$request->password], function($message) use($user){
            $message->to($user->email, $user->name)->subject
               ('SM Versity Activate Account');
            $message->from('trianadani01@gmail.com','Triana Dani');
        });
        Registeruser::where('email',$request->email)->orwhere('nim',$request->username)->orwhere('nickname',$request->nickname)->delete();
        return redirect()->back()->with('status','User berhasil di tambahkan');
    }
}
