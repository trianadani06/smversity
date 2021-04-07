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
<?php $article_id = 0;?>

@foreach(App\Artikel::where('id','<',$artikel_id)->where('user_NIM',$user_id)->orderBy('id','desc')->paginate(4) as $article)
    <div class="post-bar">
        <div class="post_topbar">
            <div class="usy-dt">
                <img src="{{url('/upload/user/'.$article->user_NIM.'/foto/'.$article->user->foto)}}" alt="" width="50">
                <div class="usy-name">
                    <h3>{{$article->user->nickname}}</h3>
                    <span><img src="{{url('/images/clock.png')}}" alt="">{{time_elapsed_string($article->created_at)}}</span>
                </div>
            </div>
            @if($article->user_NIM == Auth::user()->id||Auth::user()->role=="admin")
                <div class="ed-opts">
                    <a href="#" title="" class="ed-opts-open"><i class="la la-ellipsis-v"></i></a>
                    <ul class="ed-options">
                        @if($article->user_NIM == Auth::user()->id)
                            <li><a href="{{url('/article/'.$article->slug.'/edit')}}" title="">Edit</a></li>
                        @endif
                        <li><a onclick="delete{{$article->id}}.submit()" title="" style="cursor:pointer">Delete</a></li>
                    </ul>
                </div>
                <form name="delete{{$article->id}}" action="{{url('/article/'.$article->id)}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">
                </form>
            @endif
        </div>
        <div class="epi-sec">
            <ul class="descp">
                <li><img src="{{url('images/icon8.png')}}" alt=""><span>{{$article->user->jurusan->jurusan}}</span></li>
                <li><img src="{{url('images/icon9.png')}}" alt=""><span>{{$article->user->angkatan}}</span></li>
                <li><img src="{{url('images/tag.png')}}" alt="" width=13><span>{{$article->category->name}}</span></li>
            </ul>
        </div>
        <div class="job_descp">
            <ul class="job-dt" style="list-style: initial !important">
                <img src="{{url('/upload/artikel/'.$article->id.'/'.$article->foto)}}" width="100%">
            </ul>
            <a href="{{url('/viewarticle/'.$article->slug)}}"><h3>{{$article->name}}</h3></a>
            <p style="text-align:justify;;">{!!substr(strip_tags(nl2br($article->text),'<br>'),0,200)!!}...</p>
        </div>
        <div class="job-status-bar">
            <ul class="like-com">
                <li>
                    <?php $likes = $article->likes; ?>
                    @if($likes->count()>0&&$likes->where('user_NIM',Auth::user()->id)->count()>0)
                        <a onclick="likearticle(this.id)" id="likearticle{{$article->id}}" style="cursor:pointer;color:red"><i class="la la-heart" ></i> Like {{$likes->count()}}</a>
                    @else
                        <a onclick="likearticle(this.id)" id="likearticle{{$article->id}}" style="cursor:pointer"><i class="la la-heart"></i> Like {{$likes->count()}}</a>
                    @endif
                    <span style="visibility:hidden"></span>
                </li> 
                <li><a href="#" title="" class="com"><img src="{{url('images/com.png')}}" alt=""> Comment 15</a></li>
            </ul>
            <a href="{{url('/viewarticle/'.$article->slug)}}"><i class="la la-eye"></i>Views {{$article->views}}</a>
        </div>
    </div><!--post-bar end-->
    <?php $article_id = $article->id; ?>
    <script>
        article_id = {{$article->id}};
    </script>
    @endforeach
    @if(App\Artikel::where('id','<',$article_id)->where('user_NIM',$user_id)->orderBy('created_at','desc')->count() > 0)
        <div class="process-comm" id="process-article">
            <a onClick="moreuserarticle()" title=""><img src="{{url('images/process-icon.png')}}" alt=""></a>
        </div><!--process-comm end-->
    @endif