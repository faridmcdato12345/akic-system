@extends('layouts.master')
@section('page-title')
<h1 class="m-0">Officers</h1>
@endsection
@section('breadcrumb-item')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Officers</li>
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
                <button type='button' class='create-officer btn btn-success'>Add Officer</button>
            </div>
            <br>
            <div class="panel-body">
                <table class="table" id="officer-datatable">
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
<!--- Modal to show students--->
<div class="modal fade" id="select-student" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" value="" class="stud_id">
                <h5 class="modal-title" id="staticBackdropLabel">Select student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('#officer-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('officer.get.data')}}",
            "columns": [
                { "data": "students_id" },
                { "data": "department" },
                { "data": "course" },
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
                let url = "{{route('officer.destroy',':id')}}"
                let urlUpdate = url.replace(':id',$(this)[0].id)
                $.ajax({
                    url: urlUpdate,
                    method: "delete",
                    dataType: "json",
                    success: function(data){
                        Swal.fire(
                            'Removed!',
                            'Officer has been removed.',
                            'success'
                        )
                        $('#officer-datatable').DataTable().ajax.reload();
                    }
                })
                
            }
        })
    })
    $(document).on('click','.create-officer',function(){
        if($('.exist-student-modal').length == 0){
            $('#student-datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('student.select.data')}}",
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
        }
        $('#select-student').modal('show')
        $('#select-student').addClass('exist-student-modal')
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