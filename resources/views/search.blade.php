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
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-user" role="tabpanel" aria-labelledby="nav-user-tab">
                                <div class="acc-setting">
                                    <h3>User</h3>
                                    <?php $follows = App\Follow::select(DB::RAW('following'))->where('user_NIM',Auth::user()->id)->get()->toArray(); ?>
                                    <div class="requests-list" style="padding-bottom: 0px">
                                        @foreach($users as $user)
                                            <div class="request-details">
                                                <div class="noty-user-img">
                                                    <img src="{{url('/upload/user/'.$user->id.'/foto/'.$user->foto)}}" alt="">
                                                </div>
                                                <div class="request-info">
                                                    <a href="{{url('/viewprofile/'.$user->nickname)}}"><h3>{{$user->nickname}}</h3></a>
                                                    <span>{{$user->jurusan->jurusan}} - {{$user->angkatan}}</span>
                                                </div>
                                                <div class="accept-feat">
                                                    <ul class="flw-hr">
                                                        @if(Auth::user()->followings->where('following',$user->id)->count()>0)
                                                        <li><a onclick="follow(this.id)" id="follow{{$user->id}}" title="" class="flww" style="background-color:red;cursor:pointer"><i class="la la-minus"></i> Unfollow</a></li>
                                                        @else
                                                        <li><a onclick="follow(this.id)" id="follow{{$user->id}}" title="" class="flww" style="cursor:pointer"><i class="la la-plus"></i> Follow</a></li>
                                                        @endif
                                                    </ul>
                                                </div><!--accept-feat end-->
                                            </div><!--request-detailse end-->
                                        @endforeach
                                    </div><!--requests-list end-->
                                </div><!--acc-setting end-->
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
                    $("#"+clicked_id).css("background-color", "red");
                    $("#"+clicked_id).html('<i class="la la-minus"></i> Unfollow');
                }
                else if(data["data"]=="unfollow")
                {
                    $("#"+clicked_id).css("background-color", "#53d690");
                    $("#"+clicked_id).html('<i class="la la-plus"></i> Follow');
                }
            }
        }); 
    }
    </script>
@endsection