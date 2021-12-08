@extends('layouts.master')
@section('page-title')
<h1 class="m-0">Courses</h1>
@endsection
@section('breadcrumb-item')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Course</li>
@endsection
@section('css')
    <style>
        .modify{
            margin-right: 5px;
        }
        .swal2-html-container{
            color: #fff!important;
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
                <button type='button' class='create-consumer btn btn-success' data-toggle="modal" data-target="#create-course">Create</button>
            </div>
            <br>
            <div class="panel-body">
                <table class="table" id="course-datatable">
                    <thead>
                        <tr>
                            <th>Department</th>
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
<div class="modal fade" id="create-course" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Create Course</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('course.store')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="department_name">Name:</label>
                    <input type="text" name="name" id="department_name" class="form-control" autofocus>
                </div>
                <div class="form-group">
                    <label for="department_name">Department:</label>
                    <select name="departments_id" id="dept_id" class="form-control">
                        <option value="">Choose department here...</option>
                        @forelse ($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                        @empty
                            <option value="">No department found</option>
                        @endforelse
                    </select>
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
<div class="modal fade" id="modify-course" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Modify Course</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="modify-course-form">
            <div class="modal-body">
                <div class="form-group">
                    <label for="department_name">Department:</label>
                    <select name="departments_id" id="course_id_modify" class="form-control">
                        <option value="">Choose department here...</option>
                        @forelse ($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                        @empty
                            <option value="">No department found</option>
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="course_id" value="">
                    <label for="course_name">Name:</label>
                    <input type="text" name="name" id="course_name_update" class="form-control old-name" autofocus required>
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
        $('#course-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('course.get.data')}}",
            "columns": [
                { "data": "departments" },
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
        let url = "{{route('course.show',':id')}}"
        let urlUpdate = url.replace(':id',$(this)[0].id)
        $.ajax({
            url: urlUpdate,
            method: "GET",
            dataType: "json",
            success: function(data){
                console.log(data)
                $('#course_id_modify option[value="'+data.departments_id+'"]').prop('selected',true)
                $('#course_name_update').val(data.name)
                $('#course_id').val(data.id)
            }
        })
        $('#modify-course').modal('show')
    })
    $('#modify-course-form').submit(function(e){
        e.preventDefault()
        var url = "{{route('course.update',':id')}}"
        var urlUpdate = url.replace(':id',$('#course_id').val())
        $.ajax({
            url: urlUpdate,
            method: "patch",
            data: {
                departments_id: $('#course_id_modify').find(":selected").val(),
                name:  $('#course_name_update').val()
            },
            dataType: "json",
            success: function(data){
                $('#modify-course').modal('hide')
                Toast.fire({
                    icon: 'success',
                    title: 'Course update successfully'
                })
                $('#course-datatable').DataTable().ajax.reload();
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
                let url = "{{route('course.destroy',':id')}}"
                let urlUpdate = url.replace(':id',$(this)[0].id)
                $.ajax({
                    url: urlUpdate,
                    method: "delete",
                    dataType: "json",
                    success: function(data){
                        Swal.fire(
                            'Deleted!',
                            'Course has been deleted.',
                            'success'
                        )
                        $('#course-datatable').DataTable().ajax.reload();
                    }
                })
                
            }
        })
    })
</script>
@endsection