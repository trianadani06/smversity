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
<?php 
                                        $querystring = "";
                                        if(isset($_GET["title"]))
                                        {
                                            $querystring .= "?title=".$_GET["title"];
                                            $namesearch = $_GET["title"];
                                        }
                                        else
                                        {
                                            $namesearch = "";
                                        }
                                        if(isset($_GET["jurusan"])&&$_GET["jurusan"]!="all")
                                        {
                                            $jurusan = $_GET['jurusan'];
                                            $thisjobs = App\Job::where('id','<',$job_id)->whereHas('jurusans',function($request)use($jurusan){
                                                $request->where('jurusan_id',$jurusan);
                                            })->where('name','like','%'.$namesearch.'%')->orderBy('id','desc')->paginate('4');
                                            if($thisjobs->count()>0)
                                            {
                                                $firstjob= App\Job::where('id','<',$job_id)->whereHas('jurusans',function($request)use($jurusan){
                                                    $request->where('jurusan_id',$jurusan);
                                                })->where('name','like','%'.$namesearch.'%')->first()->id;
                                            }
                                            else
                                            {
                                                $firstjob = 0;
                                            }
                                            if($querystring!="")
                                            {
                                                $querystring .= "&jurusan=".$_GET["jurusan"];
                                            }
                                            else
                                            {
                                                $querystring .= "?jurusan=".$_GET["jurusan"];
                                            }
                                        }
                                        else
                                        {
                                            $thisjobs = App\Job::where('id','<',$job_id)->where('name','like','%'.$namesearch.'%')->orderBy('id','desc')->paginate('4');
                                            if($thisjobs->count()>0)
                                            {
                                                $firstjob= App\Job::where('id','<',$job_id)->where('name','like','%'.$namesearch.'%')->first()->id;
                                            }
                                            else
                                            {
                                                $firstjob = 0;
                                            } 
                                        }    
                                        $querystring = str_replace('&amp;', '&', $querystring);                      
                                    ?>@foreach($thisjobs as $job)
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
                                                <li><img src="images/icon8.png" alt=""><span>{{date('d M Y',strtotime($job->deadline))}}</span></li>
                                            </ul>
                                        </div>
                                        <div class="job_descp">
                                            <h3>{{$job->name}}</h3>
                                            <ul class="job-dt">
                                                <li><span>Sallary : {{$job->sallary}}</span></li>
                                            </ul>
                                            <p>{{substr($job->text,0,200)}}... <a href="{{url('/viewjob/'.$job->slug)}}" title="">view more</a></p>
                                            <ul class="skill-tags">
                                                @foreach($job->jurusans as $jurusan)
                                                    <li><a href="{{url('/')}}" title="">{{$jurusan->jurusan->jurusan}}</a></li>
                                                @endforeach	
                                            </ul>
                                        </div>
                                    </div><!--post-bar end-->
                                    @endforeach
                                    @if(isset($job))
                                            <?php $thisjob = $job->id; ?>
                                            @if($firstjob<$job->id)
                                                <div class="process-comm" style="cursor:pointer">
                                                    <a onClick="morearticle()" title=""><img src="images/process-icon.png" alt=""></a>
                                                </div><!--process-comm end-->
                                            @endif
                                        @else
                                            <?php $thisjob = 0; ?>
                                        @endif  
    <script>
        job_id = {{$job->id}};
    </script>