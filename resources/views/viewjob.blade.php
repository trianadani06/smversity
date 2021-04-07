@extends('layout.head')
<?php 
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
?>
@section('content') 
    <main>
        <div class="main-section">
            <div class="container">
                <div class="main-section-data">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="filter-secs">
                                <div class="filter-heading">
                                    <h3>Filters</h3>
                                </div><!--filter-heading end-->
                                <div class="paddy" style="padding-bottom:15px">
                                    <form>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Name</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Jurusan</label>
                                            <select class="form-control" id="exampleInputPassword1">
                                                @foreach(App\Jurusan::where('id','<>',4)->get() as $jurusan)
                                                    <option value="">{{$jurusan->jurusan}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div><!--filter-secs end-->
                        </div>
                        <div class="col-lg-6">
                            <div class="main-ws-sec">
                                <div class="posts-section">
                                    <div class="post-bar">
                                        <div class="post_topbar">
                                            <div class="usy-dt">
                                                <img src="{{url('/upload/user/'.$job->user->id.'/foto/'.$job->user->foto)}}" alt="" width="50">
                                                <div class="usy-name">
                                                    <h3>{{$job->user->nickname}}</h3>
                                                    <span><img src="{{url('images/clock.png')}}" alt="">{{time_elapsed_string($job->created_at)}}</span>
                                                </div>
                                            </div>
                                            @if(Auth::user()->role=="admin")
                                                <div class="ed-opts">
                                                    <a href="#" title="" class="ed-opts-open"><i class="la la-ellipsis-v"></i></a>
                                                    <ul class="ed-options">
                                                        <li><a href="{{url('/job/'.$job->slug.'/edit')}}" title="">Edit</a></li>
                                                        <li><a onclick="delete{{$job->id}}.submit()" title="" style="cursor:pointer">Delete</a></li>
                                                    </ul>
                                                </div>
                                                <form name="delete{{$job->id}}" action="{{url('/job/'.$job->id)}}" method="POST">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="_method" value="DELETE">
                                                </form>
                                            @endif
                                        </div>
                                        <div class="epi-sec">
                                            <ul class="descp">
                                                <li><img src="{{url('images/icon8.png')}}" alt=""><span>{{date('d M Y',strtotime($job->deadline))}}</span></li>
                                            </ul>
                                        </div>
                                        <div class="job_descp">
                                            <h3>{{$job->name}}</h3>
                                            <ul class="job-dt">
                                                <li><span>{{$job->sallary}}</span></li>
                                            </ul>
                                            <p>{!!strip_tags(nl2br($job->text),'<br>')!!}</p>
                                            <ul class="skill-tags">
                                                @foreach($job->jurusans as $jurusan)
                                                    <li><a href="{{url('/')}}" title="">{{$jurusan->jurusan->jurusan}}</a></li>
                                                @endforeach	
                                            </ul>
                                        </div>
                                    </div><!--post-bar end-->
                                </div><!--posts-section end-->
                            </div><!--main-ws-sec end-->
                        </div>
                        <div class="col-lg-3">
                            <div class="right-sidebar">
                            <div class="widget widget-jobs">
                                    <div class="sd-title">
                                        <h3>Top Jobs</h3>
                                        <i class="la la-ellipsis-v"></i>
                                    </div>
                                    <div class="jobs-list">
                                        @foreach(\App\Job::whereHas('jurusans', function ($query) {
                                            $query->where('jurusan_id', Auth::user()->jurusan_id);
                                        })->orderBy('id','desc')->paginate(4) as $job)
                                            <div class="job-info">
                                                <div class="job-details">
                                                    <a href="#"><h3>{{$job->name}}</h3></a>
                                                    <?php $jurusanya = ""; ?>
                                                    <p>@foreach($job->jurusans as $jurusan) <?php $jurusanya .= $jurusan->jurusan->jurusan.', ';?> @endforeach {{substr($jurusanya,0,strlen($jurusanya)-2)}}</p>
                                                </div>
                                                <div class="hr-rate">
                                                    <span>{{$job->sallary}}</span>
                                                </div>
                                            </div><!--job-info end-->
                                        @endforeach
                                    </div><!--jobs-list end-->
                                </div><!--widget-jobs end-->
                                <div class="widget widget-jobs">
                                    <div class="sd-title">
                                        <h3>Most Like Article this Week</h3>
                                        <i class="la la-ellipsis-v"></i>
                                    </div>
                                    <div class="jobs-list">
                                        @foreach(\App\Likeartikel::select('artikel_id',DB::RAW("count(*) as 'like'"))->whereHas('artikel',function($query){
                                            $query->where('categoryartikel_id','<>',3);
                                        })->groupBy('artikel_id')->orderBy('like','desc')->paginate(3) as $article)
                                            <div class="job-info">
                                                <div class="hr-rate">
                                                    <img src="{{url('/upload/artikel/'.$article->artikel->id.'/'.$article->artikel->foto)}}" alt="" width="50">
                                                </div>
                                                <div class="job-details">
                                                    <h3><a href="#" style="color:#333">{{$article->artikel->name}}</a></h3>
                                                    <p>{{$article->artikel->category->name}} - {{date_format(date_create($article->artikel->created_at),'d-m-Y')}}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div><!--jobs-list end-->
                                </div><!--widget-jobs end-->
                            </div><!--right-sidebar end-->
                        </div>
                    </div>
                </div><!-- main-section-data end-->
            </div> 
        </div>
    </main>
@endsection