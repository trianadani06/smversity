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
    <?php $jurusans = App\Jurusan::all();?>
<div class="container">
    <button style="padding:2px;color:#333;border:none;background-color:transparent;outline:none !important;cursor:pointer" data-toggle="modal" data-target="#addusermodal"><i class="fa fa-plus"></i> Add Jurusan</button>
    <br>
    <div class="modal fade" id="addusermodal" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Add Jurusan</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="color:black;width:400px">
                <form id="adduser" action="{{ url('jurusan') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Name:</label>
                        <input type="text" class="form-control" id="recipient-name" name="jurusan" value="">
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
                <th>Total User</th>
                <th width="100px" style="text-align:center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;$o=1;$p=1; ?>
            @foreach($jurusans as $jurusan)
                <tr>
                    <td><b><i>{{$i}}</i></b></td>
                    <td>{{$jurusan->jurusan}}</td>
                    <td>{{$jurusan->users->count()}}</td>
                    <td style="text-align:center"><button class="action" data-toggle="modal" data-target="#userModal{{$jurusan->id}}" style="margin:0 auto"><i class="fa fa-pencil"></i></button></td>
                </tr>
                <script>
                    function delete1{{$jurusan->id}}()
                    {
                        var txt;
                        var r = confirm("Apakah kamu serius ingin menghapus user ini?");
                        if (r == true) {
                            document.getElementById('delete{{$jurusan->id}}').submit();
                        } else {
                        }
                    }
                </script>
                <!-- Modal -->
                <div class="modal fade" id="userModal{{$jurusan->id}}" role="dialog">
                    <div class="modal-dialog" style="width:400px">
                    
                    <!-- Modal content-->
                    <div class="modal-content" >
                        <div class="modal-header">
                        <h4 class="modal-title">Edit Jurusan</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        <form id="input{{$jurusan->id}}" action="{{ url('jurusan/update/'.$jurusan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Name:</label>
                                <input type="text" class="form-control" id="recipient-name" name="jurusan" value="{{$jurusan->jurusan}}">
                            </div>
                        </form>
                        </div>
                        
                        <div class="modal-footer">
                        <button type="button" onclick="event.preventDefault();
                        document.getElementById('input{{$jurusan->id}}').submit();" class="btn btn-info" data-dismiss="modal">Submit</button>
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
                    "targets": 3,
                    "orderable": false
                } ],
            });
        });
    </script>
@endsection