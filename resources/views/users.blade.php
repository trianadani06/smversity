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
    <?php $users = App\user::all();?>
<div class="container">
    <button style="padding:2px;color:#333;border:none;background-color:transparent;outline:none !important;cursor:pointer" data-toggle="modal" data-target="#addusermodal"><i class="fa fa-plus"></i> Add User</button>
    <br>
    <div class="modal fade" id="addusermodal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Add user</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="color:black;width:400px">
                <form id="adduser" action="{{ url('user') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Name:</label>
                        <input type="text" class="form-control" id="recipient-name" name="name" value="">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Username(NIM):</label>
                        <input type="text" class="form-control" id="recipient-name" name="username" value="">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Email:</label>
                        <input type="email" class="form-control" id="recipient-name" name="email" value="">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Nickname:</label>
                        <input type="text" class="form-control" id="recipient-name" name="nickname" value="">
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
                                <option value="{{$jurusan->id}}" @if(isset($_GET["jurusan"])&&$_GET["jurusan"]==$jurusan->id) selected @endif>{{$jurusan->jurusan}}</option>
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
            document.getElementById('adduser').submit();" class="btn btn-info" data-dismiss="modal">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        
        </div>
    </div>
    
    <div class="table-responsive" style="color:black;margin-top:5px">
        <table id="myTable" class="table table-bordered table-striped">
        <thead>
            <tr style="background-color:black;color:white">
                <th>No</th>
                <th>Name</th>
                <th>Username(NIM)</th>
                <th>Role</th>
                <th>Jurusan</th>
                <th>Angkatan</th>
                <th>Status</th>
                <th width="100px" style="text-align:center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;$o=1;$p=1; ?>
            @foreach($users as $user)
                <tr>
                    <td><b><i>{{$i}}</i></b></td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->username}}</td>
                    <td>{{$user->role}}</td>
                    <td>{{$user->jurusan->jurusan}}</td>
                    <td>{{$user->angkatan}}</td>
                    <td>{{$user->stat}}</td>
                    <td><button class="action" data-toggle="modal" data-target="#userModal{{$user->id}}"><i class="fa fa-pencil"></i></button>@if($user->stat==0)<button class="action" onclick="event.preventDefault();document.getElementById('show{{$user->id}}').submit();"><i class="fa fa-eye"></i></button>@else<button class="action" onclick="event.preventDefault();document.getElementById('hide{{$user->id}}').submit();"><i class="fa fa-eye-slash"></i></button>@endif</td>
    
                    <form id="show{{$user->id}}" action="{{ url('user/unhide/'.$user->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <form id="hide{{$user->id}}" action="{{ url('user/hide/'.$user->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </tr>
                <script>
                    function delete1{{$user->id}}()
                    {
                        var txt;
                        var r = confirm("Apakah kamu serius ingin menghapus user ini?");
                        if (r == true) {
                            document.getElementById('delete{{$user->id}}').submit();
                        } else {
                        }
                    }
                </script>
                <!-- Modal -->
                <div class="modal fade" id="userModal{{$user->id}}" role="dialog">
                    <div class="modal-dialog" style="width:400px">
                    
                    <!-- Modal content-->
                    <div class="modal-content" >
                        <div class="modal-header">
                        <h4 class="modal-title">Edit user</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        <form id="input{{$user->id}}" action="{{ url('user/update/'.$user->id) }}" method="POST">
                            <input type="hidden" name="_method" value="PUT">
                            @csrf
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Name:</label>
                                <input type="text" class="form-control" id="recipient-name" name="name" value="{{$user->name}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Username(NIM):</label>
                                <input type="text" class="form-control" id="recipient-name" name="username" value="{{$user->username}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Email:</label>
                                <input type="email" class="form-control" id="recipient-name" name="email" value="{{$user->email}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Nickname:</label>
                                <input type="text" class="form-control" id="recipient-name" name="nickname" value="{{$user->nickname}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Tanggal Lahir:</label>
                                <input type="date" class="form-control" id="recipient-name" name="tanggallahir" value="{{$user->tanggallahir}}">
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Role:</label>
                                <select class="form-control" name="role" style="height:35px">
                                    <option value="admin" @if($user->role=="admin") selected @endif>Admin</option>
                                    <option value="mahasiswa" @if($user->role=="mahasiswa") selected @endif>Mahasiswa</option>
                                    <option value="alumni" @if($user->role=="alumni") selected @endif>Alumni</option>
                                    <option value="dosen" @if($user->role=="dosen") selected @endif>Dosen</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Jurusan</label>
                                <select class="form-control" id="exampleInputPassword1" name="jurusan">
                                    @foreach(App\Jurusan::all() as $jurusan)
                                        <option value="{{$jurusan->id}}" @if($user->jurusan_id==$jurusan->id) selected @endif>{{$jurusan->jurusan}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Angkatan</label>
                                <input type="number" class="form-control" id="recipient-name" name="angkatan" value="{{$user->angkatan}}">
                            </div>
                        </form>
                        </div>
                        
                        <div class="modal-footer">
                        <button type="button" onclick="event.preventDefault();
                        document.getElementById('input{{$user->id}}').submit();" class="btn btn-info" data-dismiss="modal">Submit</button>
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
                    "targets": 7,
                    "orderable": false
                } ],
            });
        });
    </script>
@endsection