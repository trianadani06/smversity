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
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                                    <form action="{{url('/searchartikel')}}" method="get">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Name</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Article Name" name="title" @if(isset($_GET["title"])) value="{{$_GET["title"]}}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Categories</label>
                                            <select class="form-control" id="exampleInputPassword1"  name="category">
                                                <option value="all" @if(isset($_GET["category"])&&$_GET["category"]=="semua") selected @endif>All</option>
                                                @foreach(App\Artikelcategory::all() as $category)
                                                    <option value="{{$category->id}}" @if(isset($_GET["category"])&&$_GET["category"]==$category->id) selected @endif>{{$category->name}}</option>
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
                                <div class="post-topbar">
                                    <div class="user-picy">
                                    </div>
                                    <div class="post-st">
                                        <ul>
                                            <li><a class="active" href="{{url('postarticle')}}" title="">Post a Article</a></li>
                                        </ul>
                                    </div><!--post-st end-->
                                </div><!--post-topbar end-->
                                <div class="posts-section" id="article">
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
                                        if(isset($_GET["category"])&&$_GET["category"]!="all")
                                        {
                                            $thisarticles = App\Artikel::where('categoryartikel_id',$_GET['category'])->where('name','like','%'.$namesearch.'%')->orderBy('id','desc')->paginate('4');
                                            if($thisarticles->count()>0)
                                            {
                                                $firstarticle= App\Artikel::where('categoryartikel_id',$_GET['category'])->where('name','like','%'.$namesearch.'%')->first()->id;
                                            }
                                            else
                                            {
                                                $firstarticle = 0;
                                            }
                                            if($querystring!="")
                                            {
                                                $querystring .= "&category=".$_GET["category"];
                                            }
                                            else
                                            {
                                                $querystring .= "?category=".$_GET["category"];
                                            }
                                        }
                                        else
                                        {
                                            $thisarticles = App\Artikel::where('name','like','%'.$namesearch.'%')->orderBy('id','desc')->paginate('4');
                                            if($thisarticles->count()>0)
                                            {
                                                $firstarticle= App\Artikel::where('name','like','%'.$namesearch.'%')->first()->id;
                                            }
                                            else
                                            {
                                                $firstarticle = 0;
                                            } 
                                        }    
                                        $querystring = str_replace('&amp;', '&', $querystring);                      
                                    ?>
                                    @foreach($thisarticles as $article)
                                    <div class="post-bar">
                                        <div class="post_topbar">
                                            <div class="usy-dt">
                                                <img src="{{url('/upload/user/'.$article->user_NIM.'/foto/'.$article->user->foto)}}" alt="" width="50">
                                                <div class="usy-name">
                                                    <a href="{{url('/viewprofile/'.$article->user->nickname)}}"><h3>{{$article->user->nickname}}</h3></a>
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
                                                        <a onclick="like(this.id)" id="like{{$article->id}}" style="cursor:pointer;color:red"><i class="la la-heart" ></i> Like {{$likes->count()}}</a>
                                                    @else
                                                        <a onclick="like(this.id)" id="like{{$article->id}}" style="cursor:pointer"><i class="la la-heart"></i> Like {{$likes->count()}}</a>
                                                    @endif
                                                    <span style="visibility:hidden"></span>
                                                </li> 
                                                <li><a href="#" title="" class="com"><img src="{{url('images/com.png')}}" alt=""> Comment {{$article->komenartikels->count()}}</a></li>
                                            </ul>
                                            <a href="{{url('/viewarticle/'.$article->slug)}}"><i class="la la-eye"></i>Views {{$article->views}}</a>
                                        </div>
                                    </div><!--post-bar end-->
                                    @endforeach
                                    @if(isset($article))
                                        <?php $thisarticle = $article->id; ?>
                                        @if($firstarticle<$article->id)
                                            <div class="process-comm" style="cursor:pointer">
                                                <a onClick="morearticle()" title=""><img src="images/process-icon.png" alt=""></a>
                                            </div><!--process-comm end-->
                                        @endif
                                    @else
                                        <?php $thisarticle = 0; ?>
                                    @endif  
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
                                                <a href="{{url('viewjob/'.$job->slug)}}"><h3>{{$job->name}}</h3></a>
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
                                                    <h3><a href="{{url('viewarticle/'.$article->artikel->slug)}}" style="color:#333">{{$article->artikel->name}}</a></h3>
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
@section('js')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        function like(clicked_id)
        {
            $.ajax({
                /* the route pointing to the post function */
                url: '{{url('/likearticle')}}',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, id:clicked_id.substring(4,clicked_id.length)},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    if(data["data"]=="like")
                    {
                        $("#"+clicked_id).css("color", "red");
                        $("#"+clicked_id).html('<i class="la la-heart" ></i> Like '+data["like"]);
                    }
                    else if(data["data"]=="unlike")
                    {
                        $("#"+clicked_id).css("color", "#b2b2b2");
                        $("#"+clicked_id).html('<i class="la la-heart" ></i> Like '+data["like"]);
                    }
                }
            }); 
        }
        var article_id = "{{$thisarticle}}";
        var querystring = "{{$querystring}}".replace(/&amp;/g, '&') ;
        console.log("{{url('/getarticle')}}/"+article_id+querystring);
        function morearticle() 
        {
            $( ".process-comm" ).remove();
            $.ajax({
                url: "{{url('/getarticle')}}/"+article_id+querystring,
                success: function (data) { $('#article').append(data); },
                dataType: 'html'
            });
        }
    </script>
@endsection
