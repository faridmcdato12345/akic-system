@extends('layouts.master')
@section('page-title')
<h1 class="m-0">Student</h1>
@endsection
@section('breadcrumb-item')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Student</li>
@endsection
@section('css')
    <style>
        /* .content .row{
            background-color: #f9f9f9;
            color: black;
            padding: 1%;
        } */
        .action-bttn{
            margin-right: 2px;
        }
        #select-house,#select-fine{
            color:#fff!important;
        }
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <button type='button' class='create-consumer btn btn-success' data-toggle="modal" data-target="#create-student">Create</button>
            </div>
            <br>
            <div class="panel-body">
                <table class="table" id="student-datatable">
                    <thead>
                        <tr>
                            <th>Fullname</th>
                            <th>Department</th>
                            <th>Course</th>
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
<!--- Modal to show houses--->
<div class="modal fade" id="select-house" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" value="" class="stud_id">
                <h5 class="modal-title" id="staticBackdropLabel">Select house</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="house-datatable">
                    <thead>
                        <tr>
                            <th>House name</th>
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
<!--- Modal to show fines--->
<div class="modal fade" id="select-fine" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" value="" class="stud_id">
                <h5 class="modal-title" id="staticBackdropLabel">Select fine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="fine-datatable">
                    <thead>
                        <tr>
                            <th>Fine name</th>
                            <th>Amount</th>
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
<div class="modal fade" id="create-student" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Create Student</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('student.store')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="firstname" id="first_name" class="form-control" autofocus>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name:</label>
                    <input type="text" name="middlename" id="middle_name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="lastname" id="last_name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="course_name">Course:</label>
                    <select name="course_id" id="course_id_modify" class="form-control">
                        <option value="">Choose course here...</option>
                        @forelse ($courses as $course)
                            <option value="{{$course->id}}">{{$course->name}}</option>
                        @empty
                            <option value="">No course found</option>
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
<div class="modal fade" id="modify-student" data-backdrop="cstatic" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Edit Student</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="modify-student-form">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="stud_id" value="">
                    <label for="department_name">First Name:</label>
                    <input type="text" name="name" id="first_name_update" class="form-control old-name" autofocus required>
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name:</label>
                    <input type="text" name="middlename" id="middle_name_update" class="form-control">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="lastname" id="last_name_update" class="form-control">
                </div>
                <div class="form-group">
                    <label for="course_name">Course:</label>
                    <select name="course_id" id="course_id_update" class="form-control">
                        <option value="">Choose course here...</option>
                        @forelse ($courses as $course)
                            <option value="{{$course->id}}">{{$course->name}}</option>
                        @empty
                            <option value="">No course found</option>
                        @endforelse
                    </select>
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
        $('#student-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('student.get.data')}}",
            "columns": [
                { "data": "firstname" },
                { "data": "department" },
                { "data": "course" },
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
        console.log($(this)[0].id)
        $('#modify-student .old-name').val($(this)[0].attributes[1].value)
        $('#modify-student-form .form-group #stud_id').val($(this)[0].id)
        let url = "{{route('student.show',':id')}}"
        let urlUpdate = url.replace(':id',$(this)[0].id)
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                $('#first_name_update').val(data.firstname)
                $('#middle_name_update').val(data.middlename)
                $('#last_name_update').val(data.lastname)
                $('#course_id_update option[value="'+data.course_id+'"]').prop('selected',true)
            },
            error: function(error){
                console.log(error)
            }
        })
        $('#modify-student').modal('show')
    })
    $('#modify-student-form').submit(function(e){
        e.preventDefault()
        var url = "{{route('student.update',':id')}}"
        var urlUpdate = url.replace(':id',$('#stud_id').val())
        $.ajax({
            url: urlUpdate,
            method: "patch",
            data: {
                firstname: $('#first_name_update').val(),
                middlename: $('#middle_name_update').val(),
                lastname: $('#last_name_update').val(),
                course_id: $('#course_id_update :selected').val()
            },
            dataType: "json",
            success: function(data){
                $('#modify-student').modal('hide')
                Toast.fire({
                    icon: 'success',
                    title: 'Student update successfully'
                })
                $('#student-datatable').DataTable().ajax.reload();
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
                let url = "{{route('student.destroy',':id')}}"
                let urlUpdate = url.replace(':id',$(this)[0].id)
                $.ajax({
                    url: urlUpdate,
                    method: "delete",
                    dataType: "json",
                    success: function(data){
                        Swal.fire(
                            'Deleted!',
                            'Student has been deleted.',
                            'success'
                        )
                        $('#student-datatable').DataTable().ajax.reload();
                    }
                })
                
            }
        })
    })
    /** add house to student **/
    $(document).on('click','.house',function(){
        if($('.exist-house-modal').length == 0){
            $('#house-datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('house.select.data')}}",
                "columns": [
                    { "data": "name" },
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'desc'] ]
            });
        }
        $('#select-house').modal('show')
        $('#select-house').addClass('exist-house-modal')
        $('.stud_id').val($(this)[0].id)
    })
    /** add fine to student **/
    $(document).on('click','.fine',function(){
        if($('.exist-fine-modal').length == 0){
            $('#fine-datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('fine.select.data')}}",
                "columns": [
                    { "data": "name" },
                    { "data": "amount" },
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'desc'] ]
            });
        }
        $('#select-fine').modal('show')
        $('#select-fine').addClass('exist-fine-modal')
        $('.stud_id').val($(this)[0].id)
    })
    $(document).on('click','.select-btn',function(){ 
        let url = "{{route('student.attach.house',':id')}}"
        let urlUpdate = url.replace(':id',$('.stud_id').val())
        $.ajax({
            url: urlUpdate,
            method: "post",
            dataType: "json",
            data: {
                house_id: $(this)[0].id
            },
            success:function(data){
                $('#select-house').modal('hide')
                console.log(data)
                Toast.fire({
                    icon: 'success',
                    title: 'Student was added to "' + data[0].name + '" house',
                })
            },
            error: function(error){
               
            }
        })
    })
    $(document).on('click','.select-fine-btn',function(){ 
        let url = "{{route('student.attach.fine',':id')}}"
        let urlUpdate = url.replace(':id',$('.stud_id').val())
        $.ajax({
            url: urlUpdate,
            method: "post",
            dataType: "json",
            data: {
                fine_id: $(this)[0].id
            },
            success:function(data){
                $('#select-fine').modal('hide')
                console.log(data)
                Toast.fire({
                    icon: 'success',
                    title: 'Student added an "' + data[0].name + '" fine',
                })
            },
            error: function(error){
               
            }
        })
    })
    
</script>
@endsection