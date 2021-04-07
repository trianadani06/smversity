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
<?php $messages = App\Message::where('id','<',$message_id)->where(function($query) use($user){
                                $query->where('sender_id',Auth::user()->id)->where('receiver_id',$user->id);
                            })->orwhere(function($query) use($user){
                                $query->where('sender_id',$user->id)->where('receiver_id',Auth::user()->id);
                            })->get();?>
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
    <script>
        message_id = {{$message->first()->id}};
        if(firstmessage<message_id)
        {
            $( "#moremessage" ).show();
        }
    </script>
@endforeach