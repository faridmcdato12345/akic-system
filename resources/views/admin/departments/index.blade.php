@extends('layouts.master')
@section('page-title')
<h1 class="m-0">Departments</h1>
@endsection
@section('breadcrumb-item')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Departments</li>
@endsection
@section('css')
    <style>
        .modify{
            margin-right: 5px;
        }
    </style>
@endsection
@section('content')
@if (session('success_message'))
    <div class="alert alert-success">
        {{ session('success_message') }}
    </div>
@endif
<div class="row">
    <div class="col-md-12 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button type='button' class='create-consumer btn btn-success' data-toggle="modal" data-target="#create-department">Create</button>
            </div>
            <br>
            <div class="panel-body">
                <table class="table" id="department-datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
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
<!-- Modal -->
<div class="modal fade" id="create-department" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Create Department</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('department.store')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="department_name">Name:</label>
                    <input type="text" name="name" id="department_name" class="form-control" autofocus>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="Save" class="btn btn-primary">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
</div>
<!---modify modal--->
<div class="modal fade" id="modify-department" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Modify Department</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="modify-department-form">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="dept_id" value="">
                    <label for="department_name">Name:</label>
                    <input type="text" name="name" id="department_name_update" class="form-control old-name" autofocus required>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="Update" class="btn btn-primary">
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
        $('#department-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('department.get.data')}}",
            "columns": [
                { "data": "name" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
    })
    $('.modal').on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
    });
    $(document).on('click','.modify',function(){
        $('#modify-department .old-name').val($(this)[0].attributes[1].value)
        $('#modify-department-form .form-group #dept_id').val($(this)[0].id)
        $('#modify-department').modal('show')
    })
    $('#modify-department-form').submit(function(e){
        e.preventDefault()
        console.log($('#dept_id').val())
        var url = "{{route('department.update',':id')}}"
        var urlUpdate = url.replace(':id',$('#dept_id').val())
        $.ajax({
            url: urlUpdate,
            method: "patch",
            data: {
                name: $('#department_name_update').val()
            },
            dataType: "json",
            success: function(data){
                $('#modify-department').modal('hide')
                Toast.fire({
                    icon: 'success',
                    title: 'Department update successfully'
                })
                $('#department-datatable').DataTable().ajax.reload();
            }
        })
    })
    $(document).on('click','.delete-button',function(){
        console.log($(this)[0].id)
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
                let url = "{{route('department.destroy',':id')}}"
                let urlUpdate = url.replace(':id',$(this)[0].id)
                $.ajax({
                    url: urlUpdate,
                    method: "delete",
                    dataType: "json",
                    success: function(data){
                        Swal.fire(
                            'Deleted!',
                            'Department has been deleted.',
                            'success'
                        )
                        $('#department-datatable').DataTable().ajax.reload();
                    }
                })
                
            }
        })
    })
</script>
@endsection