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
    <section class="messages-page">
        <div class="container">
            <div class="messages-sec">
                <div class="row">
                    <div class="col-lg-12 col-md-12 pd-right-none pd-left-none">
                        <div class="main-conversation-box">
                            <div class="message-bar-head" style="z-index:0">
                                <div class="usr-msg-details">
                                    <div class="usr-ms-img">
                                        <img src="{{url('/upload/user/'.$user->id.'/foto/'.$user->foto)}}" alt="" width="50">
                                    </div>
                                    <div class="usr-mg-info">
                                        <a href="{{url('/viewprofile/'.$user->nickname)}}"><h3>{{$user->nickname}}</h3></a>
                                        <p>{{$user->description}}</p>
                                    </div><!--usr-mg-info end-->
                                </div>
                                <a href="#" title=""><i class="fa fa-ellipsis-v"></i></a>
                            </div><!--message-bar-head end-->
                            <div class="messages-line" style="margin-top:100px" id="viewmessage">
                            @if(App\Message::where(function($query) use($user){
                                $query->where('sender_id',Auth::user()->id)->where('receiver_id',$user->id);
                            })->orwhere(function($query) use($user){
                                $query->where('sender_id',$user->id)->where('receiver_id',Auth::user()->id);
                            })->count()>0&&$messages->count()>0&&App\Message::where(function($query) use($user){
                                $query->where('sender_id',Auth::user()->id)->where('receiver_id',$user->id);
                            })->orwhere(function($query) use($user){
                                $query->where('sender_id',$user->id)->where('receiver_id',Auth::user()->id);
                            })->first()->id<$messages->sortBy('id')->first()->id)
                                <div class="plus-ic">
                                    <i class="la la-plus" id="moremessage" onclick="loadmoremessage()" style="cursor:pointer" ></i>
                                </div>
                            @endif
                            @foreach($messages->sortBy('id') as $message)
                                @if($message->sender_id==$user->id)
                                <div class="main-message-box">
                                    <div class="message-dt st3">
                                        <div class="message-inner-dt">
                                            <p>{{$message->text}}</p>
                                        </div><!--message-inner-dt end-->
                                        <span>{{time_elapsed_string($message->created_at)}}</span>
                                    </div><!--message-dt end-->
                                </div><!--main-message-box end-->
                                @else
                                <div class="main-message-box ta-right">
                                    <div class="message-dt" style="float:right !important">
                                        <div class="message-inner-dt" >
                                            <p style="width: auto;padding:10px 15px">{{$message->text}}</p>
                                        </div><!--message-inner-dt end-->
                                        <span>{{time_elapsed_string($message->created_at)}}</span>
                                    </div><!--message-dt end-->
                                </div><!--main-message-box end-->
                                @endif
                            @endforeach
                            </div><!--messages-line end-->
                            <div class="message-send-area">
                                <div class="mf-field">
                                    <input type="text" name="message" placeholder="Type a message here" id="inputmessage" autocomplete="off">
                                    <button type="submit" onclick="sendmessage()" id="sendmessage">Send</button>
                                </div>
                            </div><!--message-send-area end-->
                        </div><!--main-conversation-box end-->
                    </div>
                </div>
            </div><!--messages-sec end-->
        </div>
    </section><!--messages-page end-->
@endsection
@section('js')
    <script>
        @if(App\Message::where(function($query) use($user){
                                $query->where('sender_id',Auth::user()->id)->where('receiver_id',$user->id);
                            })->orwhere(function($query) use($user){
                                $query->where('sender_id',$user->id)->where('receiver_id',Auth::user()->id);
                            })->count()>0)
            var firstmessage = {{App\Message::where(function($query) use($user){
                                $query->where('sender_id',Auth::user()->id)->where('receiver_id',$user->id);
                            })->orwhere(function($query) use($user){
                                $query->where('sender_id',$user->id)->where('receiver_id',Auth::user()->id);
                            })->first()->id}};
        @else
            var firstmessage = 0;
        @endif
        @if($messages->sortBy('id')->count()>0)
            var message_id = "{{$messages->sortBy('id')->first()->id}}";
        @else
            var message_id = 0;
        @endif
        function loadmoremessage() 
        {
            $( "#moremessage" ).hide();
            $.ajax({
                url: "{{url('/loadmoremessage')}}/{{$user->nickname}}/"+message_id,
                success: function (data) { $('#mCSB_1_container').prepend(data); },
                dataType: 'html'
            });
        }
    </script>
    <script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    function sendmessage()
    {
        var inputmessage = $("#inputmessage").val();
        $("#inputmessage").val("");
        $.ajax({
            /* the route pointing to the post function */
            url: '{{url('/sendmessage')}}',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, id:{{$user->id}},message:inputmessage},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                $("#inputmessage").val("");
            }
        }); 

    }

        function readmessage()
        {
            $.ajax({
            /* the route pointing to the post function */
            url: '{{url('/readmessage')}}',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: {_token: CSRF_TOKEN, id:{{$user->id}}},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (data) {
                
            }
        }); 
        }

        $("#viewmessage").mCustomScrollbar({
            setTop:"-999999px"
        });
        $('#inputmessage').on('input', function() { 
            readmessage();
        });
        document.getElementById('inputmessage').onclick = function() {
            readmessage();
        };
        document.getElementById('inputmessage').onkeypress = function(e){
            if (!e) e = window.event;
            var keyCode = e.keyCode || e.which;
            if (keyCode == '13'){
                sendmessage();
            return false;
            }
        }
    </script>
@endsection