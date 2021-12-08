@extends('layouts.master')
@section('page-title')
<h1 class="m-0">Users</h1>
@endsection
@section('breadcrumb-item')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Users</li>
@endsection
@section('css')
    <style>
        /* .content .row{
            background-color: #f9f9f9;
            color: black;
            padding: 1%;
        } */
        #select-student{
            color: #fff!important;
        }
        .modal-dialog{
            max-width: 800px!important;
        }
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button type='button' class='create-user btn btn-success' data-toggle="modal" data-target="#create-user">Create</button>
            </div>
            <br>
            <div class="panel-body">
                <table class="table" id="user-datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--- Modal to show students--->
<div class="modal fade" id="create-user" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" value="" class="stud_id">
                <h5 class="modal-title" id="staticBackdropLabel">Create user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('account.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Role:</label>
                        <select name="role" id="role_id" class="form-control">
                            <option value="adviser">Adviser</option>
                            <option value="ssc-officer">SSC Officer</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="Register" class="btn btn-primary">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('#user-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('user.get.data')}}",
            "columns": [
                { "data": "name" },
                { "data": "username" },
                { "data": "role" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
    })
    $(document).on('click','.delete-button',function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{route('account.destroy',':id')}}"
                let urlUpdate = url.replace(':id',$(this)[0].id)
                $.ajax({
                    url: urlUpdate,
                    method: "delete",
                    dataType: "json",
                    success: function(data){
                        Swal.fire(
                            'Removed!',
                            'Account has been removed.',
                            'success'
                        )
                        $('#user-datatable').DataTable().ajax.reload();
                    }
                })
                
            }
        })
    })
    $(document).on('click','.select-btn',function(){
        $.ajax({
            url: "{{route('officer.store')}}",
            method: "POST",
            dataType: "json",
            data: {
                students_id: $(this)[0].id,
            },
            success:function(data){
                $('#select-student').modal('hide')
                Toast.fire({
                    icon: 'success',
                    title: 'Student was added successfully!'
                })
                $('#officer-datatable').DataTable().ajax.reload();
            },
            error: function(error){
                Toast.fire({
                    icon: 'error',
                    title: error.responseJSON.message
                })
            }
        })
    })
</script>
@endsection