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
    <section class="profile-account-setting">
        <div class="container">
            <div class="account-tabs-setting">
                <div class="row">
                    <div class="col-lg-12">
                    <ul class="nav nav-tabs nav-justified md-tabs indigo" id="myTabJust" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="followers-tab-just" data-toggle="tab" href="#followers-just" role="tab" aria-controls="followers-just"
                            aria-selected="true" style="background-color: white;">Followers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="followings-tab-just" data-toggle="tab" href="#followings-just" role="tab" aria-controls="followings-just"
                            aria-selected="false" style="background-color: white;">Followings</a>
                        </li>
                    </ul>
                    <div class="tab-content card" id="myTabContentJust">
                    
                        <div class="tab-pane fade show active" id="followers-just" role="tabpanel" aria-labelledby="followers-tab-just">
                        <div class="sd-title"><h3>{{$thisuser->nickname}}</h3></div>
                        <?php $follows = App\Follow::select(DB::RAW('following'))->where('user_NIM',Auth::user()->id)->get()->toArray(); ?>
                            <div class="requests-list" style="padding-bottom: 0px">
                                @foreach($thisuser->followers->sortByDesc('id') as $user)
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
                                                @if(Auth::user()->followings->where('following',$user->user->id)->count()>0)
                                                <li><a onclick="follow(this.id)" id="follow{{$user->user->id}}" title="" class="flww" style="background-color:red;cursor:pointer"><i class="la la-minus"></i> Unfollow</a></li>
                                                @elseif($user->user->id!=Auth::user()->id)
                                                <li><a onclick="follow(this.id)" id="follow{{$user->user->id}}" title="" class="flww" style="cursor:pointer"><i class="la la-plus"></i> Follow</a></li>
                                                @endif
                                            </ul>
                                        </div><!--accept-feat end-->
                                    </div><!--request-detailse end-->
                                @endforeach
                            </div><!--requests-list end-->
                        </div>
                        <div class="tab-pane fade" id="followings-just" role="tabpanel" aria-labelledby="followings-tab-just">
                        <div class="sd-title"><h3>{{$thisuser->nickname}}</h3></div>
                            <div class="requests-list" style="padding-bottom: 0px">
                                @foreach($thisuser->followings->sortByDesc('id') as $user)
                                    <div class="request-details">
                                        <div class="noty-user-img">
                                            <img src="{{url('/upload/user/'.$user->userfollow->id.'/foto/'.$user->userfollow->foto)}}" alt="">
                                        </div>
                                        <div class="request-info">
                                            <a href="{{url('/viewprofile/'.$user->userfollow->nickname)}}"><h3>{{$user->userfollow->nickname}}</h3></a>
                                            <span>{{$user->userfollow->jurusan->jurusan}} - {{$user->userfollow->angkatan}}</span>
                                        </div>
                                        <div class="accept-feat">
                                            <ul class="flw-hr">
                                                @if(Auth::user()->followings->where('following',$user->userfollow->id)->count()>0)
                                                <li><a onclick="following(this.id)" id="following{{$user->userfollow->id}}" title="" class="flww" style="background-color:red;cursor:pointer"><i class="la la-minus"></i> Unfollow</a></li>
                                                @elseif($user->userfollow->id!=Auth::user()->id)
                                                <li><a onclick="following(this.id)" id="following{{$user->userfollow->id}}" title="" class="flww" style="cursor:pointer"><i class="la la-plus"></i> Follow</a></li>
                                                @endif
                                            </ul>
                                        </div><!--accept-feat end-->
                                    </div><!--request-detailse end-->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--account-tabs-setting end-->
        </div>
    </section>
@endsection
@section('js')
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    function follow(clicked_id)
    {
        var id = clicked_id.substring(6,clicked_id.length);
        $.ajax({
            /* the route pointing to the post function */
            url: '{{url('/follow')}}',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, id:id},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                if(data["data"]=="follow")
                {
                    $("#follow"+id).css("background-color", "red");
                    $("#follow"+id).html('<i class="la la-minus"></i> Unfollow');
                    $("#following"+id).css("background-color", "red");
                    $("#following"+id).html('<i class="la la-minus"></i> Unfollow');
                }
                else if(data["data"]=="unfollow")
                {
                    $("#follow"+id).css("background-color", "#53d690");
                    $("#follow"+id).html('<i class="la la-plus"></i> Follow');
                    $("#following"+id).css("background-color", "#53d690");
                    $("#following"+id).html('<i class="la la-plus"></i> Follow');
                }
            }
        }); 
    }
    function following(clicked_id)
    {
        var id = clicked_id.substring(9,clicked_id.length);
        $.ajax({
            /* the route pointing to the post function */
            url: '{{url('/follow')}}',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, id:id},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                if(data["data"]=="follow")
                {
                    $("#follow"+id).css("background-color", "red");
                    $("#follow"+id).html('<i class="la la-minus"></i> Unfollow');
                    $("#following"+id).css("background-color", "red");
                    $("#following"+id).html('<i class="la la-minus"></i> Unfollow');
                }
                else if(data["data"]=="unfollow")
                {
                    $("#follow"+id).css("background-color", "#53d690");
                    $("#follow"+id).html('<i class="la la-plus"></i> Follow');
                    $("#following"+id).css("background-color", "#53d690");
                    $("#following"+id).html('<i class="la la-plus"></i> Follow');
                }
            }
        }); 
    }
    </script>
@endsection