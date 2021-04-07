<!DOCTYPE html>
<html lang="en">
<head>
    <title>SM Versity</title>
    <link rel="icon" href="images/smversity2.png">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="{{url('css/croopie.css')}}">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		@if (session('status'))
			<div class="alert alert-success" style="margin-bottom:0px;text-align:center">
				{{ session('status') }}
			</div>
		@elseif ($errors->any())
			<div class="alert alert-danger" style="margin-bottom:0px;text-align:center">
				{{ implode('', $errors->all(':message')) }}
			</div>
		@endif
		<div class="container-login100" style="background-image:url('{{url('/images/universitastrilogy.jpg')}}');background-size:cover">
			
			<div class="wrap-login100" style="width: 450px;">
			
                <form method="POST" action="{{ url('registeruser') }}" id="register">
                    @csrf

					<span class="login100-form-title p-b-26">
						Register
                    </span>
                    <div class="wrap-input100">
                        KTP
                        <div id="upload-demo1" style="width:350px;margin: 0 auto;"></div><br><input type="file" id="upload1" accept="image/*">
                    </div>

                    <div class="wrap-input100">
                        Kartu Mahasiswa
                        <div id="upload-demo" style="width:350px;margin: 0 auto;"></div><br><input type="file" id="upload" accept="image/*">
                    </div>

                    <div class="wrap-input100">
						<input class="input100" type="text" name="nim">
						<span class="focus-input100" data-placeholder="NIM"></span>
                    </div>

                    <div class="wrap-input100">
						<input class="input100" type="nickname" name="nickname">
						<span class="focus-input100" data-placeholder="Nickname"></span>
                    </div>
                    
					<div class="wrap-input100">
						<input class="input100" type="email" name="email">
						<span class="focus-input100" data-placeholder="Email"></span>
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn">
								Register
							</button>
						</div>
					</div>

					<div class="text-center p-t-115">
						<span class="txt1">
							Have an account? <a href="{{url('/login')}}">login here</a>
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
<script type="text/javascript" src="{{url('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{url('js/croopie.js')}}"></script>
<script>
    $uploadCrop = $('#upload-demo').croppie({
            enableExif: true,
            viewport: {
                width: 120,
                height: 80,
            },
            boundary: {
                width: 150,
                height: 100
            },
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
                selectimage = false;
            }   
            
        });

        $uploadCrop1 = $('#upload-demo1').croppie({
            enableExif: true,
            viewport: {
                width: 120,
                height: 80,
            },
            boundary: {
                width: 150,
                height: 100
            },
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
                selectimage1 = true;
            }else{
                document.getElementById("upload1").value= "";
                document.getElementById("upload1").value=null;
                alert("Only jpg/jpeg and png files are allowed!");
                selectimage1 = false;
            }   
            
        });


        $('#register').submit(function() {
            if(selectimage&&selectimage1)
            {
                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: { width: 600, height: 400 },
                    quality: 1,
                    format: 'jpeg'
                }).then(function (resp) {
                    $('<input />').attr('type', 'hidden')
                    .attr('name', "image")
                    .attr('value', resp)
                    .appendTo('#register');
                });
                $uploadCrop1.croppie('result', {
                    type: 'canvas',
                    size: { width: 600, height: 400 },
                    quality: 1,
                    format: 'jpeg'
                }).then(function (resp) {
                    $('<input />').attr('type', 'hidden')
                    .attr('name', "image1")
                    .attr('value', resp)
                    .appendTo('#register');
                });
                return true;
            }
            else
            {
                return false;
            }
            
        });
</script>
</html>