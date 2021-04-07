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
    <section class="cover-sec">
        <img src="{{url('upload/ukm/'.$ukm->id.'/cover/'.$ukm->cover)}}" alt="" width="100%">
        @if(Auth::user()->id==$ukm->user_NIM) <a class="post_project active" href="#" title=""><i class="fa fa-camera"></i> Change Image</a> @endif
    </section>
    <div class="post-popup job_post">
        <div class="post-project">
            <h3>Change Detail Profile</h3>
            <div class="post-project-fields">
                <form method="post" action="{{url('/changeukmimage/'.$ukm->id)}}" id="poststat" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="upload-demo" style="width:350px;margin: 0 auto;"></div><br><input type="file" id="upload" accept="image/*">
                        </div>
                        <div class="col-lg-12">
                            <input type="text" value="{{$ukm->text}}" name="description">
                        </div>
                        <div class="col-lg-12">
                            <ul>
                                <li><button class="active" type="submit" value="post" id="post">Post</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div><!--post-project-fields end-->
            <a href="#" title=""><i class="la la-times-circle-o"></i></a>
        </div><!--post-project end-->
    </div><!--post-project-popup end-->

    <div class="post-popup job_stat">
        <div class="post-project">
            <h3>Post Stat</h3>
            <div class="post-project-fields">
                <form method="post" action="{{url('/statusukm/'.$ukm->id)}}" id="poststat2" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="upload-demo2" style="width:350px;margin: 0 auto;"></div><br><input type="file" id="upload2" accept="image/*">
                        </div>
                        <div class="col-lg-12">
                            <input type="text" value="" name="description">
                        </div>
                        <div class="col-lg-12">
                            <ul>
                                <li><button class="active" type="submit" value="post" id="post">Post</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div><!--post-project-fields end-->
            <a href="#" title=""><i class="la la-times-circle-o"></i></a>
        </div><!--post-project end-->
    </div><!--post-project-popup end-->

    <div class="post-popup pst-pj">
        <div class="post-project">
            <h3>Change Profile Cover Image</h3>
            <div class="post-project-fields">
                <form method="post" action="{{url('/changeukmcoverimage/'.$ukm->id)}}" id="poststat1" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="upload-demo1" style="width:350px;margin: 0 auto;"></div><br><input type="file" id="upload1" accept="image/*">
                        </div>
                        <div class="col-lg-12">
                            <ul>
                                <li><button class="active" type="submit" value="post" id="post">Post</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div><!--post-project-fields end-->
            <a href="#" title=""><i class="la la-times-circle-o"></i></a>
        </div><!--post-project end-->
    </div><!--post-project-popup end-->

    <div class="post-popup post-leader">
        <div class="post-project">
            <h3>Change Leader</h3>
            <div class="post-project-fields">
                <form method="post" action="{{url('/changeukmleader/'.$ukm->id)}}" name="postleader">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-12">
                            <select name="leader" style="color:black">
                                @foreach($ukm->anggotas as $anggota)
                                    <option value="{{$anggota->user_id}}" @if($anggota->user_id==$ukm->user_NIM) selected @endif>{{$anggota->user->nickname}} - {{$anggota->user->username}}</option>
                                @endforeach 
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <ul>
                            <li><button class="active" onclick="postleader.submit();">Change</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div><!--post-project-fields end-->
            <a href="#" title=""><i class="la la-times-circle-o"></i></a>
        </div><!--post-project end-->
    </div><!--post-project-popup end-->

    <main>
        <div class="main-section">
            <div class="container">
                <div class="main-section-data">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="main-left-sidebar">
                                <div class="user_profile">
                                    <div class="user-pro-img">
                                        <img src="{{url('/upload/ukm/'.$ukm->id.'/'.$ukm->foto)}}" alt="" width="150">
                                        @if(Auth::user()->id==$ukm->user_NIM) <a class="post-jb active" href="#" title=""><i class="fa fa-pencil"></i></a> @endif
                                    </div><!--user-pro-img end-->
                                    <div class="user_pro_status">
                                        @if($ukm->user_NIM!=Auth::user()->id)
                                        <ul class="flw-hr">
                                            @if($ukm->anggotas->where('user_id',Auth::user()->id)->where('stat',1)->count()>0)
                                                <li><a onclick="follow(this.id)" id="follow{{$ukm->id}}" title="" class="flww" style="background-color:red;cursor:pointer"><i class="la la-minus"></i> Quit</a></li>
                                            @elseif($ukm->anggotas->where('user_id',Auth::user()->id)->where('stat',0)->count()==0)
                                                <li><a onclick="follow(this.id)" id="follow{{$ukm->id}}" title="" class="flww" style="cursor:pointer"><i class="la la-plus"></i> Join</a></li>
                                            @endif
                                        </ul>
                                        @endif
                                        <ul class="flw-status">
                                            <li>
                                                <span>Member</span>
                                                <b>{{$ukm->anggotas->where('stat','1')->count()}}</b>
                                            </li>
                                        </ul>
                                    </div><!--user_pro_status end-->
                                </div><!--user_profile end-->
                                <div class="suggestions full-width">
                                    <div class="sd-title">
                                        <h3>Members</h3>
                                        <i class="la la-ellipsis-v"></i>
                                    </div><!--sd-title end-->
                                    <div class="suggestions-list">
                                        @foreach($ukm->anggotas->where('user_id','<>',Auth::user()->id)->where('stat',1)->sortByDesc('id') as $anggota)
                                            <div class="suggestion-usd">
                                                <img src="{{url('/upload/user/'.$anggota->user->id.'/foto/'.$anggota->user->foto)}}" alt="" width="50">
                                                <div class="sgt-text">
                                                    <a href="{{url('viewprofile/'.$anggota->user->nickname)}}"><h4>{{$anggota->user->nickname}}</h4></a>
                                                    <span>{{$anggota->user->jurusan->jurusan}} - {{$anggota->user->angkatan}}</span>
                                                </div>
                                                <span onclick="followicon(this.id)" id="follow{{$anggota->user_id}}" style="cursor:pointer">
                                                    @if(Auth::user()->followings->where('following',$anggota->user_id)->count()>0)
                                                        <i class="la la-minus"></i>
                                                    @else 
                                                        <i class="la la-plus"></i>
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                        <div class="view-more">
                                            <a href="{{url('/viewallmembers/'.$ukm->slug)}}" title="">View More</a>
                                        </div>
                                    </div><!--suggestions-list end-->
                                </div><!--suggestions end-->
                            </div><!--main-left-sidebar end-->
                        </div>
                        <div class="col-lg-6">
                            <div class="main-ws-sec">
                                @if(Auth::user()->id == $ukm->user_NIM)
                                <div class="post-topbar">
                                    <div class="user-picy">
                                    </div>
                                    <div class="post-st">
                                        <ul>
                                            <li><a class="post-leader active" href="#" title="">Change Leader</a></li>
                                            <li><a class="post-stat active" href="#" title="">Post a Stat</a></li>
                                        </ul>
                                    </div><!--post-st end-->
                                </div><!--post-topbar end-->
                                @endif
                                <div class="user-tab-sec">
                                    <h3>{{$ukm->name}}</h3>
                                    <div class="star-descp">
                                        <span>{{$ukm->text}}</span>
                                    </div><!--star-descp end-->
                                
                                    <div class="tab-feed st2">
                                        <ul>
                                            <li data-tab="feed-dd" @if(!isset($_GET['joined'])) class="active" @endif>
                                                <a href="#" title="">
                                                    <img src="{{url('images/ic1.png')}}" alt="">
                                                    <span>Status</span>
                                                </a>
                                            </li>
                                            @if(Auth::user()->id==$ukm->user_NIM)
                                            <li data-tab="info-dd" @if(isset($_GET['joined'])) class="active" @endif>
                                                <a href="#" title="">
                                                    <img src="{{url('images/ic2.png')}}" alt="">
                                                    <span>Request Join</span>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div><!--user-tab-sec end-->
                                <div class="product-feed-tab @if(!isset($_GET['joined'])) current @endif" id="feed-dd">
                                    <div class="posts-section" id="status">
                                    
                                    @foreach(App\Status::where('ukm_id',$ukm->id)->orderBy('id','desc')->paginate(4) as $status)
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
                                                                <li><a onclick="deletestatus{{$status->id}}.submit()" title="" style="cursor:pointer">Delete</a></li>
                                                            </ul>
                                                        </div>
                                                        <form name="deletestatus{{$status->id}}" action="{{url('/status/'.$status->id)}}" method="POST">
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
                                                        <a href="{{url('/viewstatus/'.$status->id)}}"><i class="la la-eye"></i>View</a>
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
                                                                                <h3>{{$komenstatus->user->nickname}}</h3>
                                                                                <span><img src="images/clock.png" alt="">{{time_elapsed_string($komenstatus->created_at)}}</span>
                                                                                <p><?php echo preg_replace('/(^|\s)@([a-z0-9_]+)/i','$1<a href="'.url('/').'/profile/$2">@$2</a>',$komenstatus->text);?></p>
                                                                            </div>
                                                                        </div><!--comment-list end-->
                                                                    </li>
                                                                @endforeach
                                                            
                                                        </ul> 
                                                    </div><!--comment-sec end-->
                                                </div><!--comment-section end-->
                                                @endif
                                            </div><!--posty end-->
                                            <?php $thisstatus = $status->id; ?>
                                        @endforeach
                                        @if(!isset($thisstatus))
                                            <?php $thisstatus = 0; ?>
                                        @endif
                                        @if(App\Status::where('id','<',$thisstatus)->where('ukm_id',$ukm->id)->orderBy('created_at','desc')->count() > 0)
                                            <div class="process-comm" id="process-status">
                                                <a onclick="moreukmstatus()" stle="cursor:pointer" title=""><img src="{{url('images/process-icon.png')}}" alt=""></a>
                                            </div><!--process-comm end-->
                                        @endif
                                    </div>
                                </div><!--product-feed-tab end-->
                            </div><!--main-ws-sec end-->
                            @if(Auth::user()->id==$ukm->user_NIM)
                            <div class="product-feed-tab @if(isset($_GET['joined'])) current @endif" id="info-dd">
                                <div class="main-ws-sec">
                                <div class="posts-section" id="article">
                                    <div class="acc-setting">
                                        <?php 
                                        $users = $ukm->anggotas->where('stat','0'); 
                                        ?>
                                        <div class="requests-list" style="padding-bottom: 0px">
                                            @if(isset($_GET['joined'])&&$users->where('id',$_GET['joined'])->count()>0)
                                                <?php $user = $users->where('id',$_GET['joined'])->first(); ?>
                                                <?php App\Notification::where('not_id',$_GET['joined'])->where('type','joinukm')->update(['read'=>'1']); ?>
                                                <div class="request-details" style="background-color:#ccecff">
                                                    <div class="noty-user-img">
                                                        <img src="{{url('/upload/user/'.$user->user->id.'/foto/'.$user->user->foto)}}" alt="">
                                                    </div>
                                                    <div class="request-info">
                                                        <a href="{{url('/viewprofile/'.$user->user->nickname)}}"><h3>{{$user->user->nickname}}</h3></a>
                                                        <span>{{$user->user->jurusan->jurusan}} - {{$user->user->angkatan}}</span>
                                                    </div>
                                                    <div class="accept-feat">
                                                        <ul class="flw-hr">
                                                            <li><a onclick="accept{{$user->id}}.submit()" title="" class="flww" style="cursor:pointer"><i class="la la-plus"></i> Accept</a></li>
                                                            <li><a onclick="decline{{$user->id}}.submit()" title="" class="flww" style="background-color:red;cursor:pointer"><i class="la la-minus"></i> Decline</a></li>
                                                            <form id="accept{{$user->id}}" method="POST" action="{{url('/accept/'.$user->id)}}">
                                                                @csrf
                                                                <input type="hidden" name="_method" value="PUT">
                                                            </form>
                                                            <form method="POST" id="decline{{$user->id}}" action="{{url('/decline/'.$user->id)}}">
                                                                @csrf
                                                                <input type="hidden" name="_method" value="PUT">
                                                            </form>
                                                        </ul>
                                                    </div><!--accept-feat end-->
                                                </div><!--request-detailse end-->
                                            @endif
                                            <?php 
                                                if(isset($_GET['joined']))
                                                {
                                                    $users = $users->where('id','<>',$_GET['joined']);
                                                }   
                                            ?>
                                            @foreach($users as $user)
                                                <div class="request-details">
                                                    <div class="noty-user-img">
                                                        <img src="{{url('/upload/user/'.$user->user->id.'/foto/'.$user->user->foto)}}" alt="">
                                                    </div>
                                                    <div class="request-info">
                                                        <a href="{{url('/viewprofile/'.$user->user->nickname)}}"><h3>{{$user->user->nickname}}</h3></a>
                                                        <span>{{$user->user->jurusan->jurusan}} - {{$user->user->angkatan}}</span>
                                                    </div>
                                                    <div class="accept-feat">
                                                        <ul class="flw-hr">
                                                            <li><a onclick="accept{{$user->id}}.submit()" title="" class="flww" style="cursor:pointer"><i class="la la-plus"></i> Accept</a></li>
                                                            <li><a onclick="decline{{$user->id}}.submit()" title="" class="flww" style="background-color:red;cursor:pointer"><i class="la la-minus"></i> Decline</a></li>
                                                            <form id="accept{{$user->id}}" method="POST" action="{{url('/accept/'.$user->id)}}">
                                                                @csrf
                                                                <input type="hidden" name="_method" value="PUT">
                                                            </form>
                                                            <form method="POST" id="decline{{$user->id}}" action="{{url('/decline/'.$user->id)}}">
                                                                @csrf
                                                                <input type="hidden" name="_method" value="PUT">
                                                            </form>
                                                        </ul>
                                                    </div><!--accept-feat end-->
                                                </div><!--request-detailse end-->
                                            @endforeach
                                        </div><!--requests-list end-->
                                    </div><!--acc-setting end-->
                                </div><!--posts-section end-->
                            </div><!--main-ws-sec end-->
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            <div class="right-sidebar">
                                <div class="widget widget-jobs">
                                    <div class="sd-title">
                                        <h3>Most Like Article this Week</h3>
                                        <i class="la la-ellipsis-v"></i>
                                    </div>
                                    <div class="jobs-list">
                                        @foreach(App\Likeartikel::select('artikel_id',DB::RAW("count(*) as 'like'"))->whereHas('artikel',function($query){
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
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        var status_id = "{{$thisstatus}}";
        function moreukmstatus() 
        {
            $( "#process-status" ).remove();
            $.ajax({
                url: "{{url('/getukmstatus')}}/{{$ukm->id}}/"+status_id,
                success: function (data) { $('#status').append(data); },
                dataType: 'html'
            });
        }
        function follow(clicked_id)
        {
            console.log(clicked_id.substring(6,clicked_id.length));
            $.ajax({
                /* the route pointing to the post function */
                url: '{{url('/joinukm')}}',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, id:clicked_id.substring(6,clicked_id.length)},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    if(data["data"]=="follow")
                    {
                        $("#"+clicked_id).remove();
                    }
                    else if(data["data"]=="unfollow")
                    {
                        $("#"+clicked_id).css("background-color", "#53d690");
                        $("#"+clicked_id).html('<i class="la la-minus"></i> Join');
                    }
                }
            }); 
        }
        function followicon(clicked_id)
        {
            console.log(clicked_id.substring(6,clicked_id.length));
            $.ajax({
                /* the route pointing to the post function */
                url: '{{url('/follow')}}',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, id:clicked_id.substring(6,clicked_id.length)},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    if(data["data"]=="follow")
                    {
                        $("#"+clicked_id).html('<i class="la la-minus"></i>');
                    }
                    else if(data["data"]=="unfollow")
                    {
                        $("#"+clicked_id).html('<i class="la la-plus"></i>');
                    }
                }
            }); 
        }
        $uploadCrop = $('#upload-demo').croppie({
            enableExif: true,
            viewport: {
                width: 150,
                height: 150,
                type: 'circle'
            },
            boundary: {
                width: 200,
                height: 200
            },
            size:{
                width: 400,
                height: 400, 
            }
        });

        var selectimage = false;

        $('#upload').on('change', function () { 
            var fileName = document.getElementById("upload").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                var reader = new FileReader();
                reader.onload = function (e) {
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                selectimage = true;
            }else{
                document.getElementById("upload").value= "";
                document.getElementById("upload").value=null;
                alert("Only jpg/jpeg and png files are allowed!");
            }   
            
        });


        $('#poststat').submit(function() {
            if(selectimage)
            {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: { width: 500, height: 500 },
                quality: 1,
                format: 'jpeg'
            }).then(function (resp) {
                $('<input />').attr('type', 'hidden')
                .attr('name', "image")
                .attr('value', resp)
                .appendTo('#poststat');
            });
        }
            return true;
        });

        $uploadCrop1 = $('#upload-demo1').croppie({
            enableExif: true,
            viewport: {
                width: 150,
                height: 50,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 100
            },
            size:{
                width: 900,
                height: 300, 
            }
        });

        var selectimage1 = false;

        $('#upload1').on('change', function () { 
            var fileName = document.getElementById("upload1").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                var reader = new FileReader();
                reader.onload = function (e) {
                    $uploadCrop1.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                selectimage1 = true;
            }else{
                document.getElementById("upload1").value= "";
                document.getElementById("upload1").value=null;
                alert("Only jpg/jpeg and png files are allowed!");
            }   
            
        });


        $('#poststat1').submit(function() {
            if(selectimage1)
            {
            $uploadCrop1.croppie('result', {
                type: 'canvas',
                size: { width: 1500, height: 500 },
                quality: 2,
                format: 'jpeg'
            }).then(function (resp) {
                $('<input />').attr('type', 'hidden')
                .attr('name', "image")
                .attr('value', resp)
                .appendTo('#poststat1');
            });
        }
            return true;
        });
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });


        $uploadCrop2 = $('#upload-demo2').croppie({
            enableExif: true,
            viewport: {
                width: 150,
                height: 150,
                type: 'square'
            },
            boundary: {
                width: 200,
                height: 200
            },
            size:{
                width: 400,
                height: 400, 
            }
        });

        var selectimage2 = false;

        $('#upload2').on('change', function () { 
            var fileName = document.getElementById("upload2").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                var reader = new FileReader();
                reader.onload = function (e) {
                    $uploadCrop2.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                selectimage2 = true;
            }else{
                document.getElementById("upload2").value= "";
                document.getElementById("upload2").value=null;
                alert("Only jpg/jpeg and png files are allowed!");
            }   
            
        });


        $('#poststat2').submit(function() {
            if(selectimage2)
            {
            $uploadCrop2.croppie('result', {
                type: 'canvas',
                size: { width: 500, height: 500 },
                quality: 1,
                format: 'jpeg'
            }).then(function (resp) {
                $('<input />').attr('type', 'hidden')
                .attr('name', "image")
                .attr('value', resp)
                .appendTo('#poststat2');
            });
        }
            return true;
        });
    </script>
@endsection