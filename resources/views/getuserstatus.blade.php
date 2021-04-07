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
@foreach(App\Status::where('id','<',$stat_id)->where('user_id',$user_id)->orderBy('created_at','desc')->paginate(4) as $status)
    <div class="posty" style="margin-top:20px;">
        <div class="post-bar no-margin">
            <div class="post_topbar">
                <div class="usy-dt">
                    <img src="{{url('/upload/user/'.$status->user->id.'/foto/'.$status->user->foto)}}" alt="" width="50">
                    <div class="usy-name">
                    <h3><a href="{{url('/viewprofile/'.$status->user->nickname)}}">{{$status->user->nickname}} </a>@if($status->ukm) <i class="fa fa-arrow-right"></i> <a href="{{url('ukmprofile/'.$status->ukm->slug)}}">{{$status->ukm->name}}</a>@endif</h3>
                        <span><img src="{{url('images/clock.png')}}" alt="">{{time_elapsed_string($status->created_at)}}</span>
                    </div>
                </div>
                @if($status->user_id==Auth::user()->id||Auth::user()->role=="admin")
                <div class="ed-opts">
                    <a href="#" title="" class="ed-opts-open"><i class="la la-ellipsis-v"></i></a>
                    <ul class="ed-options">
                        <li><a onclick="delete{{$status->id}}.submit()" title="" style="cursor:pointer">Delete</a></li>
                    </ul>
                </div>
                <form name="delete{{$status->id}}" action="{{url('/status/'.$status->id)}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">
                </form>
                @endif
            </div>
            <div class="epi-sec">
                <ul class="descp">
                    <li><img src="{{url('images/icon8.png')}}" alt=""><span>{{$status->user->jurusan->jurusan}}</span></li>
                    <li><img src="{{url('images/icon9.png')}}" alt=""><span>{{$status->user->angkatan}}</span></li>
                </ul>
            </div>
            <div class="job_descp">
                @if($status->foto)
                    <ul class="job-dt">
                        <img src="{{url('/upload/status/'.$status->id.'/'.$status->foto)}}" width="100%">
                    </ul>
                @endif
                <p style="text-align:justify">{{$status->text}}</p>
            </div>
            <div class="job-status-bar">
                <ul class="like-com">
                    <li>
                        <?php $likes = $status->likes; ?>
                        @if($likes->count()>0&&$likes->where('user_id',Auth::user()->id)->count()>0)
                            <a onclick="like(this.id)" id="like{{$status->id}}" style="cursor:pointer;color:red"><i class="la la-heart" ></i> Like {{$likes->count()}}</a>
                        @else
                            <a onclick="like(this.id)" id="like{{$status->id}}" style="cursor:pointer"><i class="la la-heart"></i> Like {{$likes->count()}}</a>
                        @endif
                        <span style="visibility:hidden"></span>
                    </li>  
                    <li><a href="#" title="" class="com"><img src="{{url('images/com.png')}}" alt=""> Comment {{$status->komenstatuses->count()}}</a></li>
                </ul>
                <a href="{{url('/viewstatus/'.$status->id)}}"}><i class="la la-eye"></i>View</a>
            </div>
        </div><!--post-bar end-->
        <?php $komenstatuses = $status->komenstatuses; ?>
        @if($komenstatuses->count()>0)
        <div class="comment-section">
            @if($komenstatuses->count()>3)
                <div class="plus-ic">
                    <i class="la la-plus"></i>
                </div>
            @endif
            <div class="comment-sec">
                <ul>
                @foreach($komenstatuses->sortByDesc('created_at')->take(2)->sortBy('created_at') as $komenstatus)
                    <li>
                        <div class="comment-list">
                            <div class="bg-img">
                                <img src="{{url('/upload/user/'.$komenstatus->user->id.'/foto/'.$komenstatus->user->foto)}}" alt="" width="50">
                            </div>
                            <div class="comment">
                                <a href="{{url('/viewprofile/'.$komenstatus->user->nickname)}}"><h3>{{$komenstatus->user->nickname}}</h3></a>
                                <span><img src="{{url('images/clock.png')}}" alt="">{{time_elapsed_string($komenstatus->created_at)}}</span>
                                <p><?php echo preg_replace('/(^|\s)@([a-z0-9_]+)/i','$1<a href="'.url('/').'/viewprofile/$2">@$2</a>',$komenstatus->text);?></p>
                            </div>
                        </div><!--comment-list end-->
                    </li>
                @endforeach
                    
                </ul> 
            </div><!--comment-sec end-->
            <!--
            <div class="post-comment">
                <div class="cm_img">
                    <img src="{{url('/upload/user/'.Auth::user()->id.'/foto/'.Auth::user()->foto)}}" alt="" width="40">
                </div>
                <div class="comment_box">
                    <form>
                        <input type="text" name="comment" placeholder="Post a comment" id="myInput{{$status->id}}" autocomplete="off">
                        <script>
                            
                            autocomplete(document.getElementById("myInput{{$status->id}}"), countries);
                        </script>
                        <button type="submit">Send</button>
                    </form>
                </div>
            </div>
            -->
        </div><!--comment-section end-->
        @endif
    </div><!--posty end-->
    <?php $stat_id = $status->id; ?>
    <script>
        status_id = {{$status->id}};
    </script>
    @endforeach
    @if(App\Status::where('id','<',$stat_id)->where('user_id',$user_id)->orderBy('created_at','desc')->count() > 0)
        <div class="process-comm" id="process-status">
            <a onClick="moreuserstatus()" title=""><img src="images/process-icon.png" alt=""></a>
        </div><!--process-comm end-->
    @endif