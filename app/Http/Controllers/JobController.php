<?php

namespace App\Http\Controllers;

use App\Job;
use App\Jobjurusan;
use Illuminate\Http\Request;
use Auth;
use File;

class JobController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|max:50',
            'description'=>'required',
            'jurusan.*'=>'required|exists:jurusans,id',
            'deadline'=>'required|date',
            'sallary'=>'required',
        ]);
        $job = new Job();
        $job->name = $request->title;
        $job->text = $request->description;
        $job->user_NIM = Auth::user()->id;
        $job->status = 1;
        $job->sallary=$request->sallary;
        $job->deadline=$request->deadline;
        $job->save();
        for($i=0;$i<count($request->jurusan);$i++)
        {
            $jobjurusan = new Jobjurusan();
            $jobjurusan->job_id = $job->id;
            $jobjurusan->jurusan_id = $request->jurusan[$i];
            $jobjurusan->save();
        }
        return redirect('/alljobs');
    }

    public function edit($job)
    {
        $job = Job::where('slug',$job)->firstorfail();
        return view('editjob',compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */

    public function view($id) {
        $job = Job::where('slug',$id)->firstorfail();
        return view('viewjob',compact('job'));
    }

    public function getjob($id) {
        $job_id = $id;
        return view('getjob',compact('job_id'));
    }

    public function update(Request $request,Job $job)
    {
        $request->validate([
            'title'=>'required|max:50',
            'description'=>'required',
            'jurusan.*'=>'required|exists:jurusans,id',
            'deadline'=>'required|date',
            'sallary'=>'required',
        ]);
        $job->name = $request->title;
        $job->text = $request->description;
        $job->user_NIM = Auth::user()->id;
        $job->status = 1;
        $job->sallary=$request->sallary;
        $job->deadline=$request->deadline;
        $job->save();
        $job->jurusans()->delete();
        for($i=0;$i<count($request->jurusan);$i++)
        {
            $jobjurusan = new Jobjurusan();
            $jobjurusan->job_id = $job->id;
            $jobjurusan->jurusan_id = $request->jurusan[$i];
            $jobjurusan->save();
        }
        return redirect('/alljobs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->jurusans()->delete();
        $job->delete();
        return redirect('alljobs')->with('status','Lowongan berhasil di hapus');
    }
}
