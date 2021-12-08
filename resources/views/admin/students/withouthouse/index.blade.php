@extends('layouts.master')
@section('page-title')
<h1 class="m-0">Student without house</h1>
@endsection
@section('breadcrumb-item')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Without house</li>
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
        #select-house{
            color:#fff!important;
        }
    </style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
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
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('#student-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('student.without.house.get')}}",
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
                $('#student-datatable').DataTable().ajax.reload();
            },
            error: function(error){
               
            }
        })
    })
</script>
@endsection