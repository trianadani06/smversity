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
    <main>
        <div class="main-section">
            <div class="container">
                <div class="main-section-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="post-project">
                                <h3>Post Job</h3>
                                <div class="post-project-fields">
                                    <form method="post" action="{{url('/job')}}" id="poststat" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type="text" placeholder="Title" name="title">
                                            </div>
                                            <style>
                                                .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
                                                    margin-bottom: 20px;
                                                    color: #b2b2b2;
                                                    font-size: 14px;
                                                    border: 1px solid #e5e5e5;
                                                    margin-bottom: 20px;
                                                    font-weight: 500;
                                                    width:100%
                                                }
                                                .post-project-fields form ul li {
                                                    display: inline-block;
                                                    margin-right: 0px;
                                                    width: 100%;
                                                }
                                            </style>
                                            <div class="col-lg-12">
                                                <select class="selectpicker" multiple name="jurusan[]">
                                                    @foreach(App\Jurusan::all() as $jurusan)
                                                        <option value="{{$jurusan->id}}">{{$jurusan->jurusan}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" placeholder="Sallary" name="sallary">
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="date" placeholder="Title" name="deadline">
                                            </div>
                                            <div class="col-lg-12">
                                                <textarea name="description" placeholder="Description"></textarea>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul>
                                                    <li><button class="active" type="submit" value="post" id="post">Post</button></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </form>
                                </div><!--post-project-fields end-->
                                <a href="#" title=""><i class="la la-times-circle-o"></i></a>
                            </div><!--post-project end-->
                        </div>
                    </div>
                </div><!-- main-section-data end-->
            </div> 
        </div>
    </main>
@endsection
@section('js')
<script src="{{url('/bootstrap-select-1.13.2/js/bootstrap-select.js')}}"></script>
<script>
    $('.selectpicker').selectpicker();
</script>
@endsection