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
        .role-container{
            display: inline-block;
            margin-right: 2px;
        }
        #student-datatable tbody tr:hover{
            cursor: pointer;
            background: #f9f9f9;
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
                <h5 class="modal-title" id="staticBackdropLabel">Pay fine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="fines">Fines:</label>
                    <select name="fines" id="fine_id" class="form-control">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <input type="submit" value="Pay" class="btn btn-primary paid_submit">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
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
            "ajax": "{{route('student.payment.get')}}",
            "columns": [
                { "data": "firstname" },
                { "data": "department" },
                { "data": "course" },
                { "data": 'fines'},
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
    })
    /** show the fines of each student **/
    $(document).on('click','.select-btn',function(){
        console.log($(this)[0].id)
        let url = "{{route('student_payment.show.fines',':id')}}"
        let urlUpdate = url.replace(':id',$(this)[0].id)
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success:function(data){
                console.log(data)
                console.log(data.data[0].fines)
                let fines = data.data[0].fines
                $('#fine_id').html(data.option)
                $('#select-fine').modal('show')
                $('.stud_id').val(data.student_id)
            },
            error:function(error){
                console.log(error)
            }
        })
    })
    /** student paid **/
    $(document).on('click','.paid_submit',function(){
        let url = "{{route('student_payment.pay.fines',':id')}}"
        let urlUpdate = url.replace(':id',$('.stud_id').val())
        $.ajax({
            url: urlUpdate,
            method: "post",
            dataType: "json",
            data: {
                fines_id: $('#fine_id').find(':selected').val()
            },
            success: function(data){
                $('#select-fine').modal('hide')
                Toast.fire({
                    icon: 'success',
                    title: 'Student was paid successfully!'
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