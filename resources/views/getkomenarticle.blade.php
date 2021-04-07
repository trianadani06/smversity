<?php $komenarticles = $article->komenartikels->where('id','<',$idkomen)->sortByDesc('created_at')->take(4); ?>
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
@foreach($komenarticles->sortBy('created_at') as $komenarticle)
    <li>
        <div class="comment-list">
            <div class="bg-img">
                <img src="{{url('/upload/user/'.$komenarticle->user->id.'/foto/'.$komenarticle->user->foto)}}" alt="" width="50">
            </div>
            <div class="comment">
                <a href="{{url('/viewprofile/'.$komenarticle->user->nickname)}}"><h3>{{$komenarticle->user->nickname}}</h3></a>
                <span><img src="{{url('/images/clock.png')}}" alt="">{{time_elapsed_string($komenarticle->created_at)}}</span>
                <p><?php echo preg_replace('/(^|\s)@([a-z0-9_]+)/i','$1<a href="'.url('/').'/profile/$2">@$2</a>',$komenarticle->text);?></p>
            </div>
        </div><!--comment-list end-->
    </li>
@endforeach
<script>
    idkomen = {{$komenarticles->sortBy('id')->take(4)->first()->id}};
    @if($komenarticles->take(4)->sortBy('id')->first()->id>$article->komenartikels->first()->id)
        $( ".plus-ic" ).show()
    @endif
</script>