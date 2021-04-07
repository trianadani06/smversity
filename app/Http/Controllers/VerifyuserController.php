<?php

namespace App\Http\Controllers;

use App\Verifyuser;
use Illuminate\Http\Request;
use File;

class VerifyuserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notme($token)
    {
        $token = Verifyuser::where('token',$token)->firstorfail();
        $user = $token->user;
        if($user->coverfoto)
        {
            File::delete(base_path('/upload/user/'.$user->id.'/cover/').$user->coverfoto);
        }
        if($user->foto)
        {
            File::delete(base_path('/upload/user/'.$user->id.'/foto/').$user->foto);
        }
        $token->user->delete();
        $token->delete();
        return redirect('login')->with('status','Akun berhasil di nonaktifkan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate($token)
    {
        $token = Verifyuser::where('token',$token)->firstorfail();
        $token->user->verifiedBy = 1;
        $token->user->save();
        $token->delete();
        return redirect('login')->with('status','Akun berhasil di aktifkan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Verifyuser  $verifyuser
     * @return \Illuminate\Http\Response
     */
    public function show(Verifyuser $verifyuser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Verifyuser  $verifyuser
     * @return \Illuminate\Http\Response
     */
    public function edit(Verifyuser $verifyuser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Verifyuser  $verifyuser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Verifyuser $verifyuser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Verifyuser  $verifyuser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Verifyuser $verifyuser)
    {
        //
    }
}
