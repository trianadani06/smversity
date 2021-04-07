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
<?php $notifications = Auth::user()->notifications->where('id','<',$notifsetting_id)->take(8); ?>
@foreach($notifications->sortByDesc('id') as $notification)
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
    <script>
        notifsetting_id = {{$notification->id}};
    </script>
@endforeach
@if(Auth::user()->notifications->where('id','<',$notifsetting_id)->count()>0)
    <div class="process-comm" id="process-notif">
        <a onClick="morenotif()" title="" style="cursor:pointer"><img src="images/process-icon.png" alt=""></a>
    </div><!--process-comm end-->
@endif