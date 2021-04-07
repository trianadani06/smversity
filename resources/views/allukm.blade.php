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
    <div class="search-sec" style="margin: 20px 0 0px 0;">
        <div class="container">
            <div class="search-box">
                <form action="{{url('/allukm')}}" method="GET">
                    <input type="text" name="search" placeholder="Search keywords" @if(isset($_GET['search'])) value="{{$_GET['search']}}" @endif>
                    <button type="submit">Search</button>
                </form>
            </div><!--search-box end-->
        </div>
    </div><!--search-sec end-->
    <section class="companies-info">
        <div class="container">
            <div class="company-title">
                <h3>All UKM </h3>
                <Br>
                @if(Auth::user()->role=="admin")<button onclick="location.href='{{url('addukm')}}'" style="background-color: #e44d3a;
    color: #fff;font-size: 16px;
    height: 40px;
    padding: 0 15px;
    line-height: 40px;
    font-weight: 500;border-radius: 8px;">Add UKM</button>@endif
            </div><!--company-title end-->
            <div class="companies-list">
                <div class="row" id="ukm">
                <?php 
                    if(isset($_GET['search']))
                    {
                        $ukms = \App\Ukm::where('name','like','%'.$_GET['search'].'%')->orderBy('id','desc')->paginate(4);
                        $countukm = \App\Ukm::where('name','like','%'.$_GET['search'].'%')->count();
                    }
                    else
                    {
                        $ukms = \App\Ukm::orderBy('id','desc')->paginate(4);
                        $countukm = \App\Ukm::count();
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
                                        <li><a href="#" title="" class="message-us"><i class="fa fa-comments-o"></i> {{$ukm->statuses->count()}}</a></li>
                                    </ul>
                                </div>
                                <a href="{{url('ukmprofile/'.$ukm->slug)}}" title="" class="view-more-pro">View UKM</a>
                            </div><!--company_profile_info end-->
                        </div>
                        <?php $ukm_id = $ukm->id; ?>
                    @endforeach
                </div>
            </div><!--companies-list end-->
            @if($countukm>4)
            <div class="process-comm">
                <a onClick="moreukm()" title=""><img src="images/process-icon.png" alt=""></a>
            </div>
            @endif
        </div>
    </section><!--companies-info end-->
@endsection
@section('js')
<script>
    var ukm_id = "{{$ukm_id}}";
    function moreukm() 
    {
        $( ".process-comm" ).remove();
        $.ajax({
            url: "{{url('/getukm')}}/"+ukm_id @if(isset($_GET['search']))+'?search={{$_GET['search']}}' @endif,
            success: function (data) { $('#ukm').append(data); },
            dataType: 'html'
        });
    }
</script>
@endsection