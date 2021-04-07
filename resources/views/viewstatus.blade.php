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
        if($string ? implode(', ', $string) . ' ago' : 'just now'=="-1 year ago")
        {
            return 'just now';
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
                        <div class="col-lg-3 col-md-4 pd-left-none no-pd">
                            <div class="main-left-sidebar no-margin">
                                <div class="user-data full-width">
                                    <div class="user-profile">
                                        <div class="username-dt" style="padding-top: 25px;background-image: url({{url('/upload/user/'.Auth::user()->id.'/cover/'.Auth::user()->coverfoto)}});background-size: 100%;">
                                            <div class="usr-pic">
                                                <img src="{{url('/upload/user/'.Auth::user()->id.'/foto/'.Auth::user()->foto)}}" alt="">
                                            </div>
                                        </div><!--username-dt end-->
                                        <div class="user-specs">
                                            <h3>{{Auth::user()->nickname}}</h3>
                                            <span>{{Auth::user()->description}}</span>
                                        </div>
                                    </div><!--user-profile end-->
                                    <ul class="user-fw-status">
                                        <li>
                                            <h4>Following</h4>
                                            <span>{{Auth::user()->followings->count()}}</span>
                                        </li>
                                        <li>
                                            <h4>Followers</h4>
                                            <span>{{Auth::user()->followers->count()}}</span>
                                        </li>
                                        <li>
                                            <a href="{{url('/profile')}}" title="">View Profile</a>
                                        </li>
                                    </ul>
                                </div><!--user-data end-->
                                <div class="widget widget-jobs">
                                    <div class="sd-title">
                                        <h3>News</h3>
                                        <i class="la la-ellipsis-v"></i>
                                    </div>
                                    <div class="jobs-list">
                                        @foreach(App\Artikel::where('categoryartikel_id','3')->orderBy('id','desc')->paginate(3) as $article)
                                            <div class="job-info">
                                                <div class="hr-rate">
                                                    <img src="{{url('/upload/artikel/'.$article->id.'/'.$article->foto)}}" alt="" width="50">
                                                </div>
                                                <div class="job-details">
                                                    <h3><a href="#" style="color:#333">{{$article->name}}</a></h3>
                                                    <p>{{date_format(date_create($article->created_at),'d-m-Y')}}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div><!--jobs-list end-->
                                </div><!--widget-jobs end-->
                            </div><!--main-left-sidebar end-->
                        </div>
                        <div class="col-lg-6 col-md-8 no-pd">
                            <div class="main-ws-sec">
                                <div class="posts-section">
                                    <script>
                                        function autocomplete(inp, arr) {
                                            /*the autocomplete function takes two arguments,
                                            the text field element and an array of possible autocompleted values:*/
                                            var currentFocus;
                                            /*execute a function when someone writes in the text field:*/
                                            inp.addEventListener("input", function(e) {
                                                var a, b, i, val = this.value;
                                                /*close any already open lists of autocompleted values*/
                                                closeAllLists();
                                                if (!val) { return false;}
                                                currentFocus = -1;
                                                /*create a DIV element that will contain the items (values):*/
                                                a = document.createElement("DIV");
                                                a.setAttribute("id", this.id + "autocomplete-list");
                                                a.setAttribute("class", "autocomplete-items");
                                                /*append the DIV element as a child of the autocomplete container:*/
                                                this.parentNode.appendChild(a);
                                                var aftersplit =  val.split(' ');
                                                /*for each item in the array...*/
                                                var check = 0;
                                                for (i = 0; i < arr.length; i++) {
                                                    /*check if the item starts with the same letters as the text field value:*/
                                                    if(aftersplit[aftersplit.length-1].toUpperCase()[0]=="@"&&aftersplit[aftersplit.length-1][0]&&arr[i].substr(0, aftersplit[aftersplit.length-1].length).toUpperCase() == aftersplit[aftersplit.length-1].toUpperCase()) {
                                                    /*create a DIV element for each matching element:*/
                                                    check++;
                                                    b = document.createElement("DIV");
                                                    /*make the matching letters bold:*/
                                                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                                                    b.innerHTML += arr[i].substr(val.length);
                                                    /*insert a input field that will hold the current array item's value:*/
                                                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                                                    /*execute a function when someone clicks on the item value (DIV element):*/
                                                    b.addEventListener("click", function(e) {
                                                        /*insert the value for the autocomplete text field:*/
                                                        if(aftersplit.length>1)
                                                        {
                                                            inp.value = val.substr(0, val.length-aftersplit[aftersplit.length-1].length-1)+" "+this.getElementsByTagName("input")[0].value+" ";
                                                        }
                                                        else
                                                        {
                                                            inp.value = this.getElementsByTagName("input")[0].value+" ";
                                                        }
                                                          
                                                        /*close the list of autocompleted values,
                                                        (or any other open lists of autocompleted values:*/
                                                        closeAllLists();
                                                    });
                                                    a.appendChild(b);
                                                    }
                                                    if(check>5)
                                                    {
                                                        i = arr.length;
                                                         
                                                    }
                                                }
                                            });
                                            /*execute a function presses a key on the keyboard:*/
                                            inp.addEventListener("keydown", function(e) {
                                                var x = document.getElementById(this.id + "autocomplete-list");
                                                if (x) x = x.getElementsByTagName("div");
                                                if (e.keyCode == 40) {
                                                    /*If the arrow DOWN key is pressed,
                                                    increase the currentFocus variable:*/
                                                    currentFocus++;
                                                    /*and and make the current item more visible:*/
                                                    addActive(x);
                                                } else if (e.keyCode == 38) { //up
                                                    /*If the arrow UP key is pressed,
                                                    decrease the currentFocus variable:*/
                                                    currentFocus--;
                                                    /*and and make the current item more visible:*/
                                                    addActive(x);
                                                } else if (e.keyCode == 13) {
                                                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                                                    e.preventDefault();
                                                    if (currentFocus > -1) {
                                                    /*and simulate a click on the "active" item:*/
                                                    if (x) x[currentFocus].click();
                                                    }
                                                }
                                            });
                                            function addActive(x) {
                                                /*a function to classify an item as "active":*/
                                                if (!x) return false;
                                                /*start by removing the "active" class on all items:*/
                                                removeActive(x);
                                                if (currentFocus >= x.length) currentFocus = 0;
                                                if (currentFocus < 0) currentFocus = (x.length - 1);
                                                /*add class "autocomplete-active":*/
                                                x[currentFocus].classList.add("autocomplete-active");
                                            }
                                            function removeActive(x) {
                                                /*a function to remove the "active" class from all autocomplete items:*/
                                                for (var i = 0; i < x.length; i++) {
                                                x[i].classList.remove("autocomplete-active");
                                                }
                                            }
                                            function closeAllLists(elmnt) {
                                                /*close all autocomplete lists in the document,
                                                except the one passed as an argument:*/
                                                var x = document.getElementsByClassName("autocomplete-items");
                                                for (var i = 0; i < x.length; i++) {
                                                if (elmnt != x[i] && elmnt != inp) {
                                                    x[i].parentNode.removeChild(x[i]);
                                                }
                                                }
                                            }
                                            /*execute a function when someone clicks in the document:*/
                                            document.addEventListener("click", function (e) {
                                                closeAllLists(e.target);
                                            });
                                            }

                                            /*An array containing all the country names in the world:*/
                                            <?php
                                                $followings = "";
                                                foreach(Auth::user()->followings as $following)
                                                {
                                                    $followings .= "\"@".$following->userfollow->nickname."\",";
                                                }
                                                $followings = substr($followings,0,strlen($followings)-1);
                                            ?>
                                            var countries = [<?php echo $followings;?>];

                                            /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
                                    </script>
                                    <div class="posty">
                                        <div class="post-bar no-margin">
                                            <div class="post_topbar">
                                                <div class="usy-dt">
                                                    <img src="{{url('/upload/user/'.$status->user->id.'/foto/'.$status->user->foto)}}" alt="" width="50">
                                                    <div class="usy-name">
                                                        <a href="{{url('/viewprofile/'.$status->user->nickname)}}"><h3>{{$status->user->nickname}}</h3></a>
                                                        <span><img src="{{url('images/clock.png')}}" alt="">{{time_elapsed_string($status->created_at)}}</span>
                                                    </div>
                                                </div>
                                                @if($status->user_id==Auth::user()->id)
                                                <div class="ed-opts">
                                                    <a href="#" title="" class="ed-opts-open"><i class="la la-ellipsis-v"></i></a>
                                                    <ul class="ed-options">
                                                        <li><a href="#" title="">Delete</a></li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="epi-sec">
                                                <ul class="descp">
                                                    <li><img src="{{url('images/icon8.png')}}" alt=""><span>{{$status->user->jurusan->jurusan}}</span></li>
                                                    <li><img src="{{url('images/icon9.png')}}" alt=""><span>{{$status->user->angkatan}}</span></li>
                                                </ul>
                                            </div>
                                            <div class="job_descp">
                                                @if($status->foto)
                                                    <ul class="job-dt">
                                                        <img src="{{url('/upload/status/'.$status->id.'/'.$status->foto)}}" width="100%">
                                                    </ul>
                                                @endif
                                                <p style="text-align:justify">{{$status->text}}</p>
                                            </div>
                                            <div class="job-status-bar">
                                                <ul class="like-com">
                                                    <li>
                                                        <?php $likes = $status->likes; ?>
                                                        @if($likes->count()>0&&$likes->where('user_id',Auth::user()->id)->count()>0)
                                                            <a onclick="like(this.id)" id="like{{$status->id}}" style="cursor:pointer;color:red"><i class="la la-heart" ></i> Like {{$likes->count()}}</a>
                                                        @else
                                                            <a onclick="like(this.id)" id="like{{$status->id}}" style="cursor:pointer"><i class="la la-heart"></i> Like {{$likes->count()}}</a>
                                                        @endif
                                                        <span style="visibility:hidden"></span>
                                                    </li> 
                                                    <li><a href="#" title="" class="com"><img src="{{url('images/com.png')}}" alt=""> Comment {{$status->komenstatuses->count()}}</a></li>
                                                </ul>
                                            </div>
                                        </div><!--post-bar end-->
                                        <?php $komenstatuses = $status->komenstatuses->sortByDesc('created_at')->take(5); ?>
                                        <div class="comment-section">
                                            @if($status->komenstatuses->count()>4)
                                                <div class="plus-ic">
                                                    <i class="la la-plus" onclick="morecomment()" style="cursor:pointer" ></i>
                                                </div>
                                            @endif
                                            <div class="comment-sec">
                                                <ul id="comment">
                                                    @if(isset($_GET['komenstatus']))
                                                        <?php $komenstatus = App\Komenstatus::where('id',$_GET['komenstatus'])->where('status_id',$status->id)->get(); ?>
                                                        @if($komenstatus->count()>0)
                                                            <?php 
                                                                $notification = App\Notification::where('user_id',Auth::user()->id)->where('type','komenstatus')->where('not_id',$komenstatus->first()->id)->get();
                                                                if($notification->count()>0)
                                                                {
                                                                    $notification->first()->read = 1;
                                                                    $notification->first()->save();
                                                                }
                                                            ?>
                                                            <?php $komenstatus = $komenstatus->first(); ?>
                                                            <li>
                                                                <div class="comment-list">
                                                                    <div class="bg-img"  style="background-color:#ccecff">
                                                                        <img src="{{url('/upload/user/'.$komenstatus->user->id.'/foto/'.$komenstatus->user->foto)}}" alt="" width="50">
                                                                    </div>
                                                                    <div class="comment"  style="background-color:#ccecff">
                                                                        <a href="{{url('/viewprofile/'.$komenstatus->user->nickname)}}"><h3>{{$komenstatus->user->nickname}}</h3></a>
                                                                        <span><img src="{{url('/images/clock.png')}}" alt="">{{time_elapsed_string($komenstatus->created_at)}}</span>
                                                                        <p><?php echo preg_replace('/(^|\s)@([a-z0-9_]+)/i','$1<a href="'.url('/').'/profile/$2">@$2</a>',$komenstatus->text);?></p>
                                                                    </div>
                                                                </div><!--comment-list end-->
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if($komenstatuses->count()>0)
                                                        @foreach($komenstatuses->take(4)->sortBy('created_at') as $komenstatus)
                                                            <li>
                                                                <div class="comment-list">
                                                                    <div class="bg-img">
                                                                        <img src="{{url('/upload/user/'.$komenstatus->user->id.'/foto/'.$komenstatus->user->foto)}}" alt="" width="50">
                                                                    </div>
                                                                    <div class="comment">
                                                                        <a href="{{url('/viewprofile/'.$komenstatus->user->nickname)}}"><h3>{{$komenstatus->user->nickname}}</h3></a>
                                                                        <span><img src="{{url('/images/clock.png')}}" alt="">{{time_elapsed_string($komenstatus->created_at)}}</span>
                                                                        <p><?php echo preg_replace('/(^|\s)@([a-z0-9_]+)/i','$1<a href="'.url('/').'/profile/$2">@$2</a>',$komenstatus->text);?></p>
                                                                    </div>
                                                                </div><!--comment-list end-->
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul> 
                                            </div><!--comment-sec end-->
                                            
                                            <div class="post-comment">
                                                <div class="cm_img">
                                                    <img src="{{url('/upload/user/'.Auth::user()->id.'/foto/'.Auth::user()->foto)}}" alt="" width="40">
                                                </div>
                                                <div class="comment_box">
                                                    <form action="{{url('/comment')}}" method="post">
                                                        {{csrf_field()}}
                                                        <input type="hidden" name="status" value="{{$status->id}}">
                                                        <input type="text" name="comment" placeholder="Post a comment" id="myInput{{$status->id}}" autocomplete="off">
                                                        <script>
                                                            
                                                            autocomplete(document.getElementById("myInput{{$status->id}}"), countries);
                                                        </script>
                                                        <button type="submit">Send</button>
                                                    </form>
                                                </div>
                                            </div><!--post-comment end-->
                                        </div><!--comment-section end-->
                                    </div><!--posty end-->
                                </div><!--posts-section end-->
                            </div><!--main-ws-sec end-->
                        </div>
                        <div class="col-lg-3 pd-right-none no-pd">
                            <div class="right-sidebar">
                                <div class="widget widget-jobs">
                                    <div class="sd-title">
                                        <h3>Top Jobs</h3>
                                        <i class="la la-ellipsis-v"></i>
                                    </div>
                                    <div class="jobs-list">
                                        @foreach(App\Job::whereHas('jurusans', function ($query) {
                                            $query->where('jurusan_id', Auth::user()->jurusan_id);
                                        })->orderBy('id','desc')->paginate(4) as $job)
                                            <div class="job-info">
                                                <div class="job-details">
                                                    <a href="#"><h3>{{$job->name}}</h3></a>
                                                    <?php $jurusanya = ""; ?>
                                                    <p>@foreach($job->jurusans as $jurusan) <?php $jurusanya .= $jurusan->jurusan->jurusan.', ';?> @endforeach {{substr($jurusanya,0,strlen($jurusanya)-2)}}</p>
                                                </div>
                                                <div class="hr-rate">
                                                    <span>{{$job->sallary}}</span>
                                                </div>
                                            </div><!--job-info end-->
                                        @endforeach
                                    </div><!--jobs-list end-->
                                </div><!--widget-jobs end-->
                                <div class="widget widget-jobs">
                                    <div class="sd-title">
                                        <h3>Most Like Article this Week</h3>
                                        <i class="la la-ellipsis-v"></i>
                                    </div>
                                    <div class="jobs-list">
                                        @foreach(App\Likeartikel::select('artikel_id',DB::RAW("count(*) as 'like'"))->whereHas('artikel',function($query){
                                            $query->where('categoryartikel_id','<>',3);
                                        })->groupBy('artikel_id')->orderBy('like','desc')->paginate(3) as $article)
                                            <div class="job-info">
                                                <div class="hr-rate">
                                                    <img src="{{url('/upload/artikel/'.$article->artikel->id.'/'.$article->artikel->foto)}}" alt="" width="50">
                                                </div>
                                                <div class="job-details">
                                                    <h3><a href="#" style="color:#333">{{$article->artikel->name}}</a></h3>
                                                    <p>{{$article->artikel->category->name}} - {{date_format(date_create($article->artikel->created_at),'d-m-Y')}}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div><!--jobs-list end-->
                                </div><!--widget-jobs end-->
                            </div>
                        </div>
                    </div>
                </div><!-- main-section-data end-->
            </div> 
        </div>
    </main>
@endsection
@section('js')
    <script>
        var idstatus = {{$status->id}};
        @if($komenstatuses->count()>0)
            var idkomen = {{$komenstatuses->take(4)->sortBy('id')->first()->id}};
        @else
            var idkomen = 0;
        @endif
        function morecomment() 
        {
            $( ".plus-ic" ).hide();
            $.ajax({
                url: "{{url('/getkomenstatus')}}/"+idstatus+"/"+idkomen,
                success: function (data) { $('#comment').prepend(data); },
                dataType: 'html'
            });
        }
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        function like(clicked_id)
        {
            $.ajax({
                /* the route pointing to the post function */
                url: '{{url('/likestatus')}}',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, id:clicked_id.substring(4,clicked_id.length)},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    if(data["data"]=="like")
                    {
                        $("#"+clicked_id).css("color", "red");
                        $("#"+clicked_id).html('<i class="la la-heart" ></i> Like '+data["like"]);
                    }
                    else if(data["data"]=="unlike")
                    {
                        $("#"+clicked_id).css("color", "#b2b2b2");
                        $("#"+clicked_id).html('<i class="la la-heart" ></i> Like '+data["like"]);
                    }
                }
            }); 
        }
        </script>
    </script>
@endsection