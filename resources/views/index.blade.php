<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD App Laravel 8 & Ajax</title>
  <link rel='stylesheet' href='{{ asset('assets/css/bootstrap.min.css') }}' />
  <link rel='stylesheet'
    href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.css" />


</head>
{{-- add new  --}}
<div class="modal fade" id="addTitle" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_title_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="title">Title</label>
              <input type="text" name="title" class="form-control" placeholder="Title" >
              <span class="text-danger error-text title_error"></span>
            </div>
          </div>
          <div class="my-2">
            <label for="image">Select Image</label>
            <input type="file" name="image" class="form-control" >
            <span class="text-danger error-text image_error"></span>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="add_title_btn" class="btn btn-primary">Add Title</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- add new employee modal end --}}

{{-- edit employee modal start --}}
<div class="modal fade" id="editTitle" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_title_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="e_id" id="id">
        <input type="hidden" name="image_id" id="image_id">
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="title">Title</label>
              <input type="text" id="title" name="title" class="form-control" placeholder="Title" >
              <span class="text-danger error-text title_error"></span>
            </div>
          </div>
          <div class="my-2">
            <label for="image">Select Image</label>
            <input type="file"  name="image" class="form-control" >
            <span class="text-danger error-text image_error"></span>
        </div>
        </div>
        <div class="mt-2" id="image">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="edit_title_btn" class="btn btn-primary">Update Title</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- edit employee modal end --}}



<body class="bg-light" >
  <div class="container" >
    <div class="row my-5">
      <div class="col-lg-12">
        <div class="card shadow" >
          <div class="card-header bg-primary d-flex justify-content-between align-items-center" >
            <h3 class="text-light" >Manage Title</h3>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addTitle"><i
                class="bi-plus-circle me-2"></i>Add Title</button>
          </div>
          <div class="card-body" id="show_all_employees" >
            <h1 class="text-center text-secondary my-5">Loading...</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src='{{ asset('assets/js/jquery.js') }}'></script>
  <script src='{{ asset('assets/js/bootstrap.bundle.min.js') }}'></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/datatables.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(function(){

        $(document).on('click', '.editIcon', function(e){
            e.preventDefault()
            // console.log("edit");
            let id = $(this).attr('id');
            // console.log(id);
            $.ajax({
                url : '{{ route('edit') }}',
                method: 'get',
                data: {
                    id: id,
                      _token: '{{ csrf_token() }}'
                     },
                success: function(response){
                    console.log(response);
                    $("#title").val(response.post.title);
                    $("#image").html(
                        `<img src="storage/${response.post.image}" width="100" class="img-fluid img-thumbnail">`
                    );
                    $("#id").val(response.post.id);
                    $("#image_id").val(response.post.image)
                }
            })
        })

        // update request
        $("#edit_title_form").submit(function(e){
            e.preventDefault()
            // console.log("update");
            const fd = new FormData(this);
            $.ajax({
                url: '{{ route('update') }}',
                method: 'post',
                data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function(){
                  $(document).find('span.error-text').text('');
                },
                success: function(response){
                    if(response.status == 0){
                     $.each(response.error, function(prefix, val){
                        $('span.'+prefix+'_error').text(val[0]);
                           });
                 }else if(response.status == 200){
                    Swal.fire(
                      'Successfully Updated',
                     'Congrats'
                   )
                   $("#edit_title_btn").text("Updating...");
                   fetchAllEmployees();
                   console.log(response.post);
                   $("#edit_title_btn").text('Update Title');
                        $("#edit_title_form")[0].reset();
                        $("#editTitle").modal('hide')
                 } else{
                      Swal.fire(
                     'Something Isnt Right',
                     'Please Contact Admin'
                   )
                       }
                }
            })
        })


        $("#add_title_form").submit(function(e){
            e.preventDefault();
            // console.log("hello");
            const fd = new FormData(this)
            $.ajax({
                url: '{{ route('store') }}',
                method: 'post',
                data: fd,
                cache: false,
                contentType: false,
                processData:false,
                dataType: 'json',
                 beforeSend: function(){
                   $(document).find('span.error-text').text('');
                },
                success:function(response){
                    // console.log(response);
                    if(response.status == 0){
                    $.each(response.error, function(prefix, val){
                     $('span.'+prefix+'_error').text(val[0]);
                   });
                 }else if(response.status == 200){
                    $("#add_title_btn").text("Adding...");

                   Swal.fire(
                     'Added!',
                     'Employee Added Successfully!',
                        'success'
                      )
                      $("#add_title_btn").text('Add Title');
                     $("#add_title_form")[0].reset();
                       $("#addTitle").modal('hide');
                    console.log(response.post);
                    fetchAllEmployees()
                 }else{
          Swal.fire(
            'Something Isnt Right',
            'Please Contact Admin'
          )
        }
                }
            })
        })


        // delete
    $(document).on('click', '.deleteIcon', function(e){
        e.preventDefault();
        // console.log('delete');
        let id = $(this).attr('id')
        // console.log(id);
        let csrf = '{{ csrf_token() }}'
        Swal.fire({
            title: 'Are you sure?',
            text: "You can't revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it"
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: '{{ route('destroy') }}',
                    method: 'delete',
                    data: {
                        id:id,
                        _token:csrf
                    },
                    success: function(response){
                        // console.log(response);
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted',
                            'success'
                        )
                        fetchAllEmployees()
                    }
                })
            }
        })
    })

fetchAllEmployees();

function fetchAllEmployees() {
  $.ajax({
    url: '{{ route('fetchAll') }}',
    method: 'get',
    success: function(response) {
      $("#show_all_employees").html(response);
      $("table").DataTable({
        order: [0, 'desc']
      });
    }
  });
}
    })
  </script>
</body>



</html>
