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
                <button type='button' class='create-consumer btn btn-success' data-toggle="modal" data-target="#create-fine">Create</button>
            </div>
            <br>
            <div class="panel-body">
                <table class="table" id="fine-datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
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
<!--- Modal to show houses--->
<div class="modal fade" id="select-house" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" value="" class="fine_id">
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
<!-- Modal -->
<div class="modal fade" id="create-fine" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Create Student</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('fine.store')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="fine_name">Name:</label>
                    <input type="text" name="name" id="fine_name" class="form-control" autofocus>
                </div>
                <div class="form-group">
                    <label for="fine_amount">Amount:</label>
                    <input type="number" name="amount" id="fine_amount" class="form-control">
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
<div class="modal fade" id="modify-fine" data-backdrop="cstatic" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Edit Fine</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="modify-fine-form">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="fine_id" value="">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name_update" class="form-control old-name" autofocus required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" name="amount" id="amount_update" class="form-control">
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
        $('#fine-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('fine.get.data')}}",
            "columns": [
                { "data": "name" },
                { "data": "amount" },
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
        $('#modify-fine .old-name').val($(this)[0].attributes[1].value)
        $('#modify-fine-form .form-group #fine_id').val($(this)[0].id)
        let url = "{{route('fine.show',':id')}}"
        let urlUpdate = url.replace(':id',$(this)[0].id)
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                $('#name_update').val(data.name)
                $('#amount_update').val(data.amount)
            },
            error: function(error){
                console.log(error)
            }
        })
        $('#modify-fine').modal('show')
    })
    $('#modify-fine-form').submit(function(e){
        e.preventDefault()
        var url = "{{route('fine.update',':id')}}"
        var urlUpdate = url.replace(':id',$('#fine_id').val())
        $.ajax({
            url: urlUpdate,
            method: "patch",
            data: {
                name: $('#name_update').val(),
                amount: $('#amount_update').val(),
            },
            dataType: "json",
            success: function(data){
                $('#modify-fine').modal('hide')
                Toast.fire({
                    icon: 'success',
                    title: 'Fine update successfully'
                })
                $('#fine-datatable').DataTable().ajax.reload();
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
                let url = "{{route('fine.destroy',':id')}}"
                let urlUpdate = url.replace(':id',$(this)[0].id)
                $.ajax({
                    url: urlUpdate,
                    method: "delete",
                    dataType: "json",
                    success: function(data){
                        Swal.fire(
                            'Deleted!',
                            'Fine has been deleted.',
                            'success'
                        )
                        $('#fine-datatable').DataTable().ajax.reload();
                    }
                })
                
            }
        })
    })
</script>
@endsection