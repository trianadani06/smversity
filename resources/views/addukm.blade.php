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
                                <h3>Add UKM</h3>
                                <div class="post-project-fields">
                                    <form method="post" action="{{url('/ukm')}}" id="poststat" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div id="upload-demo" style="width:350px;margin: 0 auto;padding:0px"></div><br><input type="file" id="upload" accept="image/*">
                                            </div>
                                            <div class="col-lg-12">
                                                <div id="upload-demo1" style="width:350px;margin: 0 auto;"></div><br><input type="file" id="upload1" accept="image/*">
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" placeholder="Title" name="title">
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" placeholder="Leader NIM" name="leader">
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
<script>
    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: 150,
            height: 150,
            type: 'square'
        },
        boundary: {
            width: 200,
            height: 200
        },
        size:{
            width: 400,
            height: 400, 
        }
    });

    var selectimage = false;

    $('#upload').on('change', function () { 
        var fileName = document.getElementById("upload").value;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
            var reader = new FileReader();
            reader.onload = function (e) {
                $uploadCrop.croppie('bind', {
                    url: e.target.result
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            selectimage = true;
        }else{
            document.getElementById("upload").value= "";
            document.getElementById("upload").value=null;
            alert("Only jpg/jpeg and png files are allowed!");
        }   
        
    });


    $('#poststat').submit(function() {
        if(selectimage)
        {
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: { width: 500, height: 500 },
            quality: 1,
            format: 'jpeg'
        }).then(function (resp) {
            $('<input />').attr('type', 'hidden')
            .attr('name', "image")
            .attr('value', resp)
            .appendTo('#poststat');
        });
        $uploadCrop1.croppie('result', {
                type: 'canvas',
                size: { width: 1500, height: 500 },
                quality: 2,
                format: 'jpeg'
            }).then(function (resp) {
                $('<input />').attr('type', 'hidden')
                .attr('name', "cover")
                .attr('value', resp)
                .appendTo('#poststat');
            });
    }
        return true;
    });
    $uploadCrop1 = $('#upload-demo1').croppie({
            enableExif: true,
            viewport: {
                width: 150,
                height: 50,
                type: 'square'
            },
            boundary: {
                width: 300,
                height: 100
            },
            size:{
                width: 900,
                height: 300, 
            }
        });

        var selectimage1 = false;

        $('#upload1').on('change', function () { 
            var fileName = document.getElementById("upload1").value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
                var reader = new FileReader();
                reader.onload = function (e) {
                    $uploadCrop1.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                selectimage = true;
            }else{
                document.getElementById("upload1").value= "";
                document.getElementById("upload1").value=null;
                alert("Only jpg/jpeg and png files are allowed!");
            }   
            
        });


        $('#poststat1').submit(function() {
            if(selectimage)
            {
            $uploadCrop1.croppie('result', {
                type: 'canvas',
                size: { width: 1500, height: 500 },
                quality: 2,
                format: 'jpeg'
            }).then(function (resp) {
                $('<input />').attr('type', 'hidden')
                .attr('name', "image")
                .attr('value', resp)
                .appendTo('#poststat1');
            });
        }
            return true;
        });
</script>
<script src="{{url('/js/ckeditor/ckeditor.js')}}"></script>
<script>
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace( 'editor1' );
</script>
@endsection