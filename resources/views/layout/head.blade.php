
<!DOCTYPE html>
<html>

<!-- Mirrored from gambolthemes.net/workwise_demo/HTML/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 17 Jan 2019 07:19:11 GMT -->
<head>
<meta charset="UTF-8">
<title>SM Versity</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="stylesheet" type="text/css" href="{{url('css/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/line-awesome.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/line-awesome-font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/jquery.mCustomScrollbar.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('lib/slick/slick.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('lib/slick/slick-theme.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/style.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/responsive.css')}}">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="{{url('css/custom.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('css/croopie.css')}}">
<!-- Latest compiled and minified JavaScript -->
<link rel="icon" href="{{url('images/smversity2.png')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  


@yield('css')
</head>


<body>
<meta name="csrf-token" content="{{ csrf_token() }}">
	<div class="wrapper">
		<header>
			<div class="container">
				<div class="header-data">
					<div class="logo">
						<a href="{{url('/home')}}" title=""><img src="{{url('/images/smversity.png')}}" alt="" width="40"></a>
					</div><!--logo end-->
					<div class="search-bar">
						<form method="GET" action="{{url('search')}}">
							<input type="text" name="search" placeholder="Search...">
							<button type="submit"><i class="la la-search"></i></button>
						</form>
					</div><!--search-bar end-->
					<nav style="width:57%">
						<ul>
							<li>
								<a href="{{url('/home')}}" title="">
									<span><img src="{{url('/images/icon1.png')}}" alt=""></span>
									Home
								</a>
                            </li>
							<li>
								<a href="{{url('/allukm')}}" title="">
									<span><img src="{{url('/images/icon2.png')}}" alt=""></span>
									UKM
								</a>
							</li>
							<li>
								<a href="{{url('/articles')}}" title="">
									<span><img src="{{url('/images/icon3.png')}}" alt=""></span>
									Artikel
								</a>
							</li>
							<li>
								<a href="{{url('/alljobs')}}" title="">
									<span><img src="{{url('/images/icon5.png')}}" alt=""></span>
									Jobs
								</a>
							</li>
							<li>
								<?php 
									$messages = App\Message::whereIn('id',App\Message::select(DB::RAW('max(id) as id'))->where('sender_id',Auth::user()->id)->orWhere('receiver_id',Auth::user()->id)->groupBy(DB::RAW('IF ('.Auth::user()->id.' = sender_id,receiver_id,sender_id)'))->get()->toArray())->paginate(4);
								?>
								<a href="#" title="" class="not-box-open">
									<span><img src="{{url('/images/icon6.png')}}" alt=""><i class="message-count" id="messagecount">&nbsp;{{$messages->where('receiver_id',Auth::user()->id)->where('read','0')->count()}}&nbsp;</i></span>
									Messages
								</a>
								<div class="notification-box msg">
									<div class="nott-list" id="message">
									<?php $message = ""; ?>
										@foreach($messages->sortByDesc('id') as $message)
											<?php
												if(Auth::user()->id == $message->sender_id)
												{
													$url = url('viewmessage/'.$message->receiver->nickname);
												}
												else
												{
													$url = url('viewmessage/'.$message->sender->nickname);
												}
											?>
											<div class="notfication-details" @if($message->read==0&&$message->receiver_id==Auth::user()->id) style="background-color:#ffe94b;cursor:pointer" @endif @if(Auth::user()->id == $message->sender_id) id="message-{{$message->receiver->nickname}}" @else id="message-{{$message->sender->nickname}}" @endif onclick="location.href='{{$url}}'" style="cursor:pointer">
												@if(Auth::user()->id == $message->sender_id)
													<div class="noty-user-img" style="background-color:red">
														<img src="{{url('/upload/user/'.$message->receiver_id.'/foto/'.$message->receiver->foto)}}" alt="">
													</div>
													<div class="notification-info">
														<h3><a href='{{url('viewmessage/'.$message->receiver->nickname)}}' title="">{{$message->receiver->nickname}}</a> </h3>
														<p id="text-{{$message->receiver->nickname}}"> You: {{substr($message->text,0,40)}}</p>
														<span id="time-{{$message->receiver->nickname}}">{{time_elapsed_string($message->created_at)}}</span>
													</div><!--notification-info -->
												@else
													<div class="noty-user-img">
														<img src="{{url('/upload/user/'.$message->sender_id.'/foto/'.$message->sender->foto)}}" alt="">
													</div>
													<div class="notification-info">
														<h3><a href='{{url('viewmessage/'.$message->sender->nickname)}}' title="">{{$message->sender->nickname}}</a> </h3>
														<p id="text-{{$message->sender->nickname}}">{{substr($message->text,0,40)}}</p>
														<span id="time-{{$message->sender->nickname}}">{{time_elapsed_string($message->created_at)}}</span>
													</div><!--notification-info -->
												@endif
											</div>
										@endforeach
						  				<div class="view-all-nots">
						  					<a href="{{url('allmessage')}}" title="">View All Messsages</a>
						  				</div>
									</div><!--nott-list end-->
								</div><!--notification-box end-->
							</li>
							<li>
								<a href="#" title="" class="not-box-open">
									<span><img src="{{url('/images/icon7.png')}}" alt=""><i class="message-count" id="notificationcount">&nbsp;{{Auth::user()->notifications->where('read','0')->count()}}&nbsp;</i></span>
									Notification
								</a>
								<div class="notification-box">
									<div class="nott-list" id="notification">
										@foreach(Auth::user()->notifications->sortByDesc('id')->take(4) as $notification)
											<?php
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
											<div class="notfication-details" @if($notification->read==0&&$notification->user_id==Auth::user()->id) style="background-color:#ffe94b;cursor:pointer" @endif onclick="location.href='{{$url}}'" style="cursor:pointer">
												<div class="noty-user-img">
													<img src="{{url('/upload/user/'.$notification->sender_id.'/foto/'.$notification->sender->foto)}}" alt="" width="50">
												</div>
												<div class="notification-info">
													<h3>
														<a href="#" title="">{{$notification->sender->nickname}}</a> {{$notification->text}}</h3>
													<br>
													<span>{{time_elapsed_string($notification->created_at)}}</span>
												</div><!--notification-info -->
											</div>
						  				@endforeach
						  				<div class="view-all-nots">
						  					<a href="{{url('/setting?panel=notification')}}" title="">View All Notification</a>
						  				</div>
									</div><!--nott-list end-->
								</div><!--notification-box end-->
							</li>
						</ul>
					</nav><!--nav end-->
					<div class="menu-btn">
						<a href="#" title=""><i class="fa fa-bars"></i></a>
					</div><!--menu-btn end-->
					<div class="user-account" style="width:150px">
						<div class="user-info">
							<img src="{{url('/upload/user/'.Auth::user()->id.'/foto/'.Auth::user()->foto)}}" alt="" width="30px">
								<a href="#" title="">{{Auth::user()->nickname}}</a>
							<i class="la la-sort-down"></i>
						</div>
						<div class="user-account-settingss">
							<ul class="us-links">
								<li><a href="{{url('profile')}}" title="">My Profile</a></li>
								<li><a href="{{url('/setting')}}" title="">Setting</a></li>
								@if(Auth::user()->role=="admin")
									<li><a href="{{url('/users')}}" title="">Users</a></li>
									<li><a href="{{url('/registerusers')}}" title="">Register Users</a></li>
									<li><a href="{{url('/categoryarticles')}}" title="">Category Article</a></li>
									<li><a href="{{url('/jurusans')}}" title="">Jurusan</a></li>
								@endif
							</ul>
							<h3 class="tc"><a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form></h3>
						</div><!--user-account-settingss end-->
					</div>
				</div><!--header-data end-->
			</div>
        </header><!--header end-->	
			@if (session('status'))
				<div class="alert alert-success">
					{{ session('status') }}
				</div>
			@elseif ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>- {{$error}}</li>
						@endforeach
					</ul>
				</div>
			@endif
        @yield('content')
        
    </div>

    <script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/popper.js')}}"></script>
    <script type="text/javascript" src="{{url('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/jquery.mCustomScrollbar.js')}}"></script>
    <script type="text/javascript" src="{{url('lib/slick/slick.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/scrollbar.js')}}"></script>
    <script type="text/javascript" src="{{url('js/script.js')}}"></script>
	<script type="text/javascript" src="{{url('js/croopie.js')}}"></script>
    @yield('js')
	<script>
		var myid = {{Auth::user()->id}};
		var notifcount = {{Auth::user()->notifications->where('read','0')->count()}};
		@if(Auth::user()->notifications->count()>0)
			@if(Auth::user()->notifications->where('read','0')->count()>0)
				$('#notificationcount').show();
			@else
				$('#notificationcount').hide();
			@endif
			$('#notificationcount').html("&nbsp;"+notifcount+"&nbsp;");
			var idnotif = {{Auth::user()->notifications->sortByDesc('id')->take(4)->first()->id}};
		@else
			$('#notificationcount').hide();
			$('#notificationcount').html("&nbsp;"+notifcount+"&nbsp;");
			var idnotif = 0;
		@endif
		function get_notif() 
        {
			$.ajax({
                url: "{{url('/getnotif')}}" +"/"+idnotif,
                success: function (data) { $('#notification').prepend(data); },
                dataType: 'html'
            });
        }
        setInterval(function(){get_notif();}, 4000);
		var messagecount = {{$messages->where('receiver_id',Auth::user()->id)->where('read','0')->count()}};
		@if($messages->count()>0)
			@if($messages->where('receiver_id',Auth::user()->id)->where('read','0')->count()>0)
			$('#messagecount').show();
			@else
			$('#messagecount').hide();
			@endif
			$('#messagecount').html("&nbsp;"+messagecount+"&nbsp;");
			var idmessage = {{$messages->sortByDesc('id')->first()->id}};
		@else
			$('#messagecount').hide();
			$('#messagecount').html("&nbsp;"+messagecount+"&nbsp;");
			var idmessage = 0;
		@endif
		function get_message() 
        {
            $.get("{{url('/getmessage')}}" +"/"+idmessage, {}, 
            function(data1) 
            {
                $.each(data1, function(index, value) { 
					if(value["sender_id"]==myid)
					{
						var nickname = value["receiver"]["nickname"];
					}
					else
					{
						$('#messagecount').show();
						messagecount +=1;
						var nickname = value["sender"]["nickname"];
					}
					if($("#message-" + nickname).length == 0) 
					{
						if(value["sender_id"]==myid)
						{
							$('#message').prepend('<div class="notfication-details" style="cursor:pointer" id="message-'+value["receiver"]["nickname"]+'" onclick="location.href\'{{url('viewmessage/')}}/'+value["sender"]["nickname"]+'\'"><div class="noty-user-img" style="background-color:red"><img src="{{url('/upload/user/')}}/'+value["receiver_id"]+'/foto/'+value["receiver"]["foto"]+'" alt=""></div><div class="notification-info"><h3><a href="{{url('viewmessage/')}}/'+value["receiver"]["nickname"]+'" title="">'+value["receiver"]["nickname"]+'</a> </h3><p id="text-'+value["receiver"]["nickname"]+'"> You: '+value["text"]+'</p><span id="time-'+value["receiver"]["nickname"]+'">just now</span></div>');
						}
						else
						{
							$('#message').prepend('<div class="notfication-details" style="cursor:pointer" id="message-'+value["sender"]["nickname"]+'"  onclick="location.href\'{{url('viewmessage/')}}/'+value["sender"]["nickname"]+'\'"><div class="noty-user-img" style="background-color:red" styl><img src="{{url('/upload/user/')}}/'+value["sender_id"]+'/foto/'+value["sender"]["foto"]+'" alt=""></div><div class="notification-info"><h3><a href="{{url('viewmessage/')}}/'+value["sender"]["nickname"]+'" title="">'+value["sender"]["nickname"]+'</a> </h3><p id="text-'+value["sender"]["nickname"]+'"> '+value["text"]+'</p><span id="time-'+value["sender"]["nickname"]+'">just now</span></div>');
						}
					}
					else
					{
						if(value["sender_id"]==myid)
						{
							$('#message-'+value["receiver"]["nickname"]).css("background-color", "#ffe94b");
							$('#message').prepend($('#message-'+value["receiver"]["nickname"]));
							$('#text-'+value["receiver"]["nickname"]).html("You: "+value["text"]);
							$('#time-'+value["receiver"]["nickname"]).html("justnow");
						}
						else
						{
							$('#message-'+value["sender"]["nickname"]).css("background-color", "#ffe94b");
							$('#message').prepend($('#message-'+value["sender"]["nickname"]));
							$('#text-'+value["sender"]["nickname"]).html(value["text"]);
							$('#time-'+value["sender"]["nickname"]).html("justnow");
						}
					}
					@if(isset($allmessage)&&$allmessage)
						if($("#messagelist-" + nickname).length == 0) 
						{
							if(value["sender_id"]==myid)
							{
								$('#message').prepend('<div class="notfication-details" style="cursor:pointer" id="message-'+value["receiver"]["nickname"]+'" onclick="location.href\'{{url('viewmessage/')}}/'+value["sender"]["nickname"]+'\'"><div class="noty-user-img" style="background-color:red"><img src="{{url('/upload/user/')}}/'+value["receiver_id"]+'/foto/'+value["receiver"]["foto"]+'" alt=""></div><div class="notification-info"><h3><a href="{{url('viewmessage/')}}/'+value["receiver"]["nickname"]+'" title="">'+value["receiver"]["nickname"]+'</a> </h3><p id="text-'+value["receiver"]["nickname"]+'"> You: '+value["text"]+'</p><span id="time-'+value["receiver"]["nickname"]+'">just now</span></div>');
							}
							else
							{
								$('#message').prepend('<div class="notfication-details" style="cursor:pointer" id="message-'+value["sender"]["nickname"]+'"  onclick="location.href\'{{url('viewmessage/')}}/'+value["sender"]["nickname"]+'\'"><div class="noty-user-img" style="background-color:red" styl><img src="{{url('/upload/user/')}}/'+value["sender_id"]+'/foto/'+value["sender"]["foto"]+'" alt=""></div><div class="notification-info"><h3><a href="{{url('viewmessage/')}}/'+value["sender"]["nickname"]+'" title="">'+value["sender"]["nickname"]+'</a> </h3><p id="text-'+value["sender"]["nickname"]+'"> '+value["text"]+'</p><span id="time-'+value["sender"]["nickname"]+'">just now</span></div>');
							}
						}
						else
						{
							if(value["sender_id"]==myid)
							{
								$('#messagelist-'+value["receiver"]["nickname"]).addClass("active");
								$('#messagelist-'+value["receiver"]["nickname"]).css("background-color", "#ffe94b");
								$('#messagelist').prepend($('#messagelist-'+value["receiver"]["nickname"]));
								$('#textlist-'+value["receiver"]["nickname"]).html("You: "+value["text"]);
								$('#timelist-'+value["receiver"]["nickname"]).html("justnow");
							}
							else
							{
								$('#messagelist-'+value["sender"]["nickname"]).addClass("active");
								$('#messagelist-'+value["sender"]["nickname"]).css("background-color", "#ffe94b");
								$('#messagelist').prepend($('#messagelist-'+value["sender"]["nickname"]));
								$('#textlist-'+value["sender"]["nickname"]).html(value["text"]);
								$('#timelist-'+value["sender"]["nickname"]).html("justnow");
							}
						}
					@endif
					@if(isset($viewmessage))
						if(nickname=="{{$viewmessage->nickname}}")
						{
							if(value["sender_id"]!=myid)
							{
								$('#mCSB_1_container').append('<div class="main-message-box"><div class="message-dt st3"><div class="message-inner-dt"><p>'+value["text"]+'</p></div><span>just now</span></div></div>');
							}
							else
							{
								$('#mCSB_1_container').append('<div class="main-message-box ta-right"><div class="message-dt" style="float:right !important"><div class="message-inner-dt" ><p style="width: auto;padding:10px 15px">'+value["text"]+'</p></div><span>just now</span></div></div>');
							}
						}
						$("#mCSB_1_container").css({ top: '-999999px' });
						console.log($("#viewmessage"));
					@endif
					idmessage = value["id"];
					$('#messagecount').html("&nbsp;"+messagecount+"&nbsp;");
                });
            });
			
        }
        setInterval(function(){get_message();}, 1000);
	</script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

</body>

</html>