@extends('layouts.master')
@section('page-title')
<h1 class="m-0">Student with fines</h1>
@endsection
@section('breadcrumb-item')
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">With fines</li>
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
        .close-button{
            width: 14px;
            font-size: 8px;
            border-radius: 10px;
            text-align: center;
            position: absolute;
            top: -5px;
            right: -3px;
        }
        .fine-container{
            display: inline-block;
            position: relative;
            margin-right: 10px;
        }
        .close-button:hover{
            cursor: pointer;
            background-color: #852a21;
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
                            <th>Fines</th>
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
            "ajax": "{{route('student.with.fines.get')}}",
            "columns": [
                { "data": "firstname" },
                { "data": "department" },
                { "data": "course" },
                { "data": 'fines'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
    })
    $(document).on('click','.close-button',function(){
        let url = "{{route('student_payment.pay.fines',':id')}}"
        let urlUpdate = url.replace(':id',$(this).parent().attr('id'))
        $.ajax({
            url: urlUpdate,
            method: "post",
            dataType: "json",
            data: {
                fines_id: $(this).closest('div').attr('id')
            },
            success: function(data){
                $('#select-fine').modal('hide')
                Toast.fire({
                    icon: 'success',
                    title: 'Fine was removed!'
                })
                $('#student-datatable').DataTable().ajax.reload();
            },
            error: function(error){
                console.log(error)
            }
        })
    })
</script>
@endsection