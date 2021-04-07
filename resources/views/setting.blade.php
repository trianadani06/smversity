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
                    <div class="col-lg-3">
                        <div class="acc-leftbar">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link @if(!isset($_GET["panel"])) active" @endif id="nav-password-tab" data-toggle="tab" href="#nav-password" role="tab" aria-controls="nav-password" aria-selected="false"><i class="fa fa-lock"></i>Change Password</a>
                                <a class="nav-item nav-link @if(isset($_GET["panel"])&&$_GET["panel"]=="notification") active" @endif id="nav-notification-tab" data-toggle="tab" href="#nav-notification" role="tab" aria-controls="nav-notification" aria-selected="false"><i class="fa fa-flash"></i>Notifications</a>
                                <a class="nav-item nav-link" id="nav-requests-tab" data-toggle="tab" href="#nav-requests" role="tab" aria-controls="nav-requests" aria-selected="false"><i class="fa fa-group"></i>Requests</a>
                                </div>
                        </div><!--acc-leftbar end-->
                    </div>
                    <div class="col-lg-9">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade @if(!isset($_GET["panel"])) show active" @endif" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab">
                                <div class="acc-setting">
                                    <h3>Change Password</h3>
                                    <form action="{{url('/changepassword')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" value="PUT">
                                        <div class="cp-field">
                                            <h5>Old Password</h5>
                                            <div class="cpp-fiel">
                                                <input type="password" name="oldpassword" placeholder="Old Password">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <div class="cp-field">
                                            <h5>New Password</h5>
                                            <div class="cpp-fiel">
                                                <input type="password" name="password" placeholder="New Password">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <div class="cp-field">
                                            <h5>Repeat Password</h5>
                                            <div class="cpp-fiel">
                                                <input type="password" name="password_confirmation" placeholder="Repeat Password">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <div class="save-stngs pd2">
                                            <ul>
                                                <li><button type="submit">Save Setting</button></li>
                                            </ul>
                                        </div><!--save-stngs end-->
                                    </form>
                                </div><!--acc-setting end-->
                            </div>
                            <div class="tab-pane fade @if(isset($_GET["panel"])&&$_GET["panel"]=="notification") show active" @endif" id="nav-notification" role="tabpanel" aria-labelledby="nav-notification-tab">
                                <div class="acc-setting">
                                    <h3>Notifications</h3>
                                    <div class="notifications-list" id="notificationsetting">
                                        <?php
                                            /*$notifupdate = Auth::user()->notifications()->where('read',0)->update(['read' => 1]);*/
                                        ?>
                                        <?php $notifsetting_id = 0; ?>
                                        @foreach(Auth::user()->notifications->sortByDesc('id')->take(8) as $notification)
                                            <?php
                                                $url = "";
                                                if($notification->type=="likeartikel")
												{
													$likeartikel = App\Artikel::find($notification->not_id);
													$url = url('/viewarticle/'.$likeartikel->slug);
												}
												elseif($notification->type=="komenartikel")
												{
													$komenartikel = App\Komenartikel::find($notification->not_id);
													$url = url('/viewarticle/'.$komenartikel->artikel->slug).'?komenartikel='.$komenartikel->id;
												}
												elseif($notification->type=="likestatus")
												{
													$url = url('/viewstatus/'.$notification->not_id);
												}
												elseif($notification->type=="komenstatus")
												{
													$komenstatus = App\Komenstatus::find($notification->not_id);
													$url = url('/viewstatus/'.$komenstatus->status_id).'?komenstatus='.$komenstatus->id;
												}
												elseif($notification->type=="follow")
												{
													$follow = App\Follow::find($notification->not_id);
													$url = url('/viewprofile/'.$follow->user->nickname);
												}
												elseif($notification->type=="joinukm")
												{
													$ukmanggota = App\Ukmanggota::find($notification->not_id);
													$url = url('/ukmprofile/'.$ukmanggota->ukm->slug.'?joined='.$notification->not_id);
												}
												elseif($notification->type=="quitukm")
												{
													$ukm = App\Ukm::find($notification->not_id);
													$url = url('/ukmprofile/'.$ukm->slug);
												}
												elseif($notification->type=="ukm")
												{
													$ukm = App\Ukm::find($notification->not_id);
													$url = url('/ukmprofile/'.$ukm->slug);
												}
                                            ?>
                                            <div class="notfication-details" onclick="location.href='{{$url}}'" @if($notification->read==0&&$notification->user_id==Auth::user()->id) style="background-color:#ffe94b;cursor:pointer" @endif onclick="location.href='{{$url}}'" style="cursor:pointer">
                                                <div class="noty-user-img">
                                                    <img src="{{url('/upload/user/'.$notification->sender_id.'/foto/'.$notification->sender->foto)}}" alt="">
                                                </div>
                                                <div class="notification-info">
                                                    <h3><a href="#" title="">{{$notification->sender->nickname}}</a> {{$notification->text}}</h3>
                                                    <span>{{time_elapsed_string($notification->created_at)}}</span>
                                                </div><!--notification-info -->
                                            </div><!--notfication-details end-->
                                            <?php $notifsetting_id = $notification->id; ?>
                                        @endforeach
                                    </div><!--notifications-list end-->
                                </div><!--acc-setting end-->
                                @if(Auth::user()->notifications->count()>8)
                                <div class="process-comm" id="process-notif">
                                    <a onClick="morenotif()" title="" style="cursor:pointer"><img src="images/process-icon.png" alt=""></a>
                                </div><!--process-comm end-->
                                @endif
                            </div>
                            <div class="tab-pane fade" id="nav-requests" role="tabpanel" aria-labelledby="nav-requests-tab">
                                <div class="acc-setting">
                                    <h3>Requests</h3>
                                    <?php $follows = App\Follow::select(DB::RAW('following'))->where('user_NIM',Auth::user()->id)->get()->toArray(); ?>
                                    <div class="requests-list" style="padding-bottom: 0px">
                                        @foreach(App\Follow::where('following',Auth::user()->id)->whereNotIn('user_NIM', $follows)->get() as $follower)
                                            <div class="request-details">
                                                <div class="noty-user-img">
                                                    <img src="{{url('/upload/user/'.$follower->user->id.'/foto/'.$follower->user->foto)}}" alt="">
                                                </div>
                                                <div class="request-info">
                                                    <h3>{{$follower->user->nickname}}</h3>
                                                    <span>{{$follower->user->jurusan->jurusan}} - {{$follower->user->angkatan}}</span>
                                                </div>
                                                <div class="accept-feat">
                                                    <ul>
                                                        <li><button onclick="follow(this.id)" id="follow{{$follower->user->id}}" type="submit" class="accept-req" style="background-color:#53d690;">Accept</button></li>
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
    var notifsetting_id = "{{$notifsetting_id}}";
    function morenotif() 
    {
        $( "#process-notif" ).remove();
        $.ajax({
            
            url: "{{url('/getmorenotif')}}/"+notifsetting_id,
            success: function (data) { $('#notificationsetting').append(data); },
            dataType: 'html'
        });
        console.log("{{url('/getmorenotif')}}/"+notifsetting_id);
    }
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
                        $("#"+clicked_id).html('Unfollow');
                    }
                    else if(data["data"]=="unfollow")
                    {
                        $("#"+clicked_id).css("background-color", "#53d690");
                        $("#"+clicked_id).html('follow');
                    }
                }
            }); 
        }
</script>
@endsection