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
                    <div class="col-lg-12 col-md-12 no-pdd">
                        <div class="msgs-list">
                            <div class="msg-title">
                                <h3>Messages</h3>
                                <ul>
                                    <li><a href="#" title=""><i class="fa fa-ellipsis-v"></i></a></li>
                                </ul>
                            </div><!--msg-title end-->
                            <div class="messages-list">
                                <ul id="messagelist">
                                <?php 
									$messages = App\Message::whereIn('id',App\Message::select(DB::RAW('max(id) as id'))->where('sender_id',Auth::user()->id)->orWhere('receiver_id',Auth::user()->id)->groupBy(DB::RAW('IF ('.Auth::user()->id.' = sender_id,receiver_id,sender_id)'))->get()->toArray())->get();
                                ?>
                                    @foreach($messages->sortByDesc('id') as $message)
                                    <li @if($message->read==0&&$message->receiver_id==Auth::user()->id) style="background-color:#ffe94b" @endif @if(Auth::user()->id == $message->sender_id) onclick="location.href='{{url('viewmessage/'.$message->receiver->nickname)}}'" @else onclick="location.href='{{url('viewmessage/'.$message->sender->nickname)}}'" @endif @if(Auth::user()->id == $message->sender_id) id="messagelist-{{$message->receiver->nickname}}" @else id="messagelist-{{$message->sender->nickname}}" @endif>
                                        @if(Auth::user()->id == $message->sender_id)
                                            <div class="usr-msg-details">
                                                <div class="usr-ms-img">
                                                    <img src="{{url('/upload/user/'.$message->receiver_id.'/foto/'.$message->receiver->foto)}}" alt="">
                                                </div>
                                                <div class="usr-mg-info">
                                                    <h3>{{$message->receiver->nickname}}</h3>
                                                    <p id="textlist-{{$message->receiver->nickname}}">You: {{substr($message->text,0,100)}}</p>
                                                </div><!--usr-mg-info end-->
                                                <span class="posted_time" id="timelist-{{$message->receiver->nickname}}">{{time_elapsed_string($message->created_at)}}</span>
                                        @else
                                            <div class="usr-msg-details">
                                                <div class="usr-ms-img">
                                                    <img src="{{url('/upload/user/'.$message->sender_id.'/foto/'.$message->sender->foto)}}" alt="">
                                                </div>
                                                <div class="usr-mg-info">
                                                    <h3>{{$message->sender->nickname}}</h3>
                                                    <p id="textlist-{{$message->sender->nickname}}">{{substr($message->text,0,100)}}</p>
                                                </div><!--usr-mg-info end-->
                                                <span class="posted_time" id="timelist-{{$message->sender->nickname}}">{{time_elapsed_string($message->created_at)}}</span>
                                        @endif
                                        </div><!--usr-msg-details end-->
                                    </li>
                                    @endforeach
                                </ul>
                            </div><!--messages-list end-->
                        </div><!--msgs-list end-->
                    </div>
                    
                </div>
            </div><!--messages-sec end-->
        </div>
    </section><!--messages-page end-->
@endsection
@section('js')
    <script>
    
    </script>
@endsection