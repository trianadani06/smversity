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
@section('css')
    <style>
    .dropdowntext:hover{
        color: black !important;
    }

    .dropdown-user
    {
        width:190px;
        padding:5px;
    }
    iframe{
        margin: 0 auto;
    }
    .dropdown-user{
        width: 210px;
    }
    .dropdowntext .active{
        color:red !important;
    }
        .action{
            cursor:pointer;
        }
        .dropdown-subuser {
    position: relative;
    }

    .dropdown-subuser .dropdown-user {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }
    
    .dropdown-subuser>a{
        color:#333 !important;
        font-family: 'Open Sans',sans-serif !important;
        font-style:none !important;
        font-weight:0 !important;
        font-size:12px !important;
    }
    @media only screen and (max-width: 600px) {
        .dropdown-subuser .dropdown-user 
        {
            top: 100%;
            left: 0;
            margin-top: -1px;
        }
    }
    .dropdown-subuser>ul>li>a{
        color:#333 !important;
        font-family: 'Open Sans',sans-serif !important;
        font-style:none !important;
        font-weight:0 !important;
        font-size:12px !important;
    }
    .dropdown-subuser>ul{
        width:auto;
    }
    .dropdown-user>li {
        margin-right:0 !important;
    }

    .fade{
        opacity:1;
    }

    .fade.in {
        opacity: 1;
    }

    .modal.in .modal-dialog {
        -webkit-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0);
    }

    .modal-backdrop .fade .in {
        opacity: 0.5 !important;
    }

    .modal-backdrop.fade {
        opacity: 0.5 !important;
    }
    
    .modal{
        margin:0 auto;
    }

    .modal {
  text-align: center;
  padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
.form-control
{
    color:black !important;
}
    </style>
@endsection

@section('content')
<main>
    <div class="main-section">
    <?php $registerusers = App\Registeruser::all();?>
<div class="container">
    <br>
    
    <div class="table-responsive" style="color:black;margin-top:5px">
        <table id="myTable" class="table table-bordered table-striped">
        <thead>
            <tr style="background-color:black;color:white">
                <th>No</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Nickname</th>
                <th>Foto KTM</th>
                <th>Foto KTP</th>
                <th>Status</th>
                <th width="100px" style="text-align:center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;$o=1;$p=1; ?>
            @foreach($registerusers as $registeruser)
                <tr>
                    <td><b><i>{{$i}}</i></b></td>
                    <td>{{$registeruser->email}}</td>
                    <td>{{$registeruser->nim}}</td>
                    <td>{{$registeruser->nickname}}</td>
                    <td><img src="{{url('/upload/register/'.$registeruser->id.'/'.$registeruser->foto)}}" width="200px"></td>
                    <td><img src="{{url('/upload/register/'.$registeruser->id.'/ktp/'.$registeruser->ktp)}}" width="200px"></td>
                    <td>{{$registeruser->stat}}</td>
                    <td><button class="action" data-toggle="modal" data-target="#userModal{{$registeruser->id}}"><i class="fa fa-check"></i></button><button class="action" onclick="event.preventDefault();document.getElementById('delete{{$registeruser->id}}').submit();"><i class="fa fa-trash"></i></button></td>
                    <form id="delete{{$registeruser->id}}" action="{{ url('registeruser/delete/'.$registeruser->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </tr>
                <div class="modal fade" id="userModal{{$registeruser->id}}" role="dialog">
                    <div class="modal-dialog" style="width:400px">
                    
                    <!-- Modal content-->
                    <div class="modal-content" >
                        <div class="modal-header">
                        <h4 class="modal-title">Confirm user</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        <form id="input{{$registeruser->id}}" action="{{ url('user') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Name:</label>
                                <input type="text" class="form-control" id="recipient-name" name="name" value="">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Username(NIM):</label>
                                <input type="text" class="form-control" id="recipient-name" name="username" value="{{$registeruser->nim}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Email:</label>
                                <input type="email" class="form-control" id="recipient-name" name="email" value="{{$registeruser->email}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Nickname:</label>
                                <input type="text" class="form-control" id="recipient-name" name="nickname" value="{{$registeruser->nickname}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Tanggal Lahir:</label>
                                <input type="date" class="form-control" id="recipient-name" name="tanggallahir" value="">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Role:</label>
                                <select class="form-control" name="role" style="height:35px">
                                    <option value="admin">Admin</option>
                                    <option value="mahasiswa">Mahasiswa</option>
                                    <option value="alumni">Alumni</option>
                                    <option value="dosen">Dosen</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Jurusan</label>
                                <select class="form-control" id="exampleInputPassword1" name="jurusan">
                                    @foreach(App\Jurusan::all() as $jurusan)
                                        <option value="{{$jurusan->id}}">{{$jurusan->jurusan}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Angkatan</label>
                                <input type="number" class="form-control" id="recipient-name" name="angkatan" value="">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Password:</label>
                                <input type="password" class="form-control" id="recipient-name" name="password" value="">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Password Confirmation:</label>
                                <input type="password" class="form-control" id="recipient-name" name="password_confirmation" value="">
                            </div>
                        </form>
                        </div>
                        
                        <div class="modal-footer">
                        <button type="button" onclick="event.preventDefault();
                        document.getElementById('input{{$registeruser->id}}').submit();" class="btn btn-info" data-dismiss="modal">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    
                    </div>
                </div>
                <?php $i+=1?>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('js')
<script src="{{url('/js/datatables/jquery.dataTables.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({   
                "order": [[ 0, "asc" ]],
                "paging": false, 
                "info": false,
                "columnDefs": [ {
                    "targets": [4,5,7],
                    "orderable": false
                } ],
            });
        });
    </script>
@endsection