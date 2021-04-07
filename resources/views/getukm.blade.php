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
<?php 
    if(isset($_GET['search']))
    {
        $ukms = \App\Ukm::where('id','<',$ukm_id)->where('name','like','%'.$_GET['search'].'%')->orderBy('id','desc')->paginate(4);
    }
    else
    {
        $ukms = \App\Ukm::where('id','<',$ukm_id)->orderBy('id','desc')->paginate(4);
    }
    $ukm_id = 0;
?>
@foreach($ukms as $ukm)
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="company_profile_info">
            <div class="company-up-info">
                <img src="{{url('/upload/ukm/'.$ukm->id.'/'.$ukm->foto)}}" alt="" width="120">
                <h3>{{$ukm->name}}</h3>
                <h4>Establish {{date_format(date_create($ukm->created_at),'M, Y')}}}</h4>
                <ul>
                    <li><a href="#" title="" class="follow"><i class="fa fa-users"></i> {{$ukm->anggotas->where('stat','1')->count()}}</a></li>
                    <li><a href="#" title="" class="message-us"><i class="fa fa-image"></i> {{$ukm->galleries->count()}}</a></li>
                </ul>
            </div>
            <a href="{{url('ukmprofile/'.$ukm->slug)}}" title="" class="view-more-pro">View UKM</a>
        </div><!--company_profile_info end-->
    </div>
    <?php $ukm_id = $ukm->id; ?>
@endforeach
@if(App\Ukm::where('id','<',$ukm_id)->count()>0)
    <div class="process-comm">
        <a onClick="moreukm()" title=""><img src="images/process-icon.png" alt=""></a>
    </div>
@endif
<script>
    var ukm_id = "{{$ukm_id}}";
</script>