<!DOCTYPE html>
<head>
<!--     <link rel="stylesheet" href="{{ asset('assets/images/admin.jpg')}}<?php //echo base_url();?>/assets/bootstrap-table-master/dist/bootstrap-table.min.css"> -->
</head>
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
<html>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layout.partials.links')
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  @include('layout.partials.main_header')
  @include('layout.partials.side_bar')
      
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Section
        <!-- <small>advanced tables</small> -->
      </h1>
    </section>    

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Section Report</h3>
                <!-- <ul class="navbar-right"> -->
                <h4> 
                <a href="javascript:void(0)"> 
                <!-- <i class="fa fa-wpforms"></i> -->
                  <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal"              data-target="#module_form_modal">Add Section
                  </button>
                </a>
                </h4>
                <!-- </ul> -->
            </div> 

            <!-- The Modal -->
            <div class="modal " id="module_form_modal">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Add section</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <div class="box-body">
                      <form method="post" name="module_form" action="{{ route('store_section') }}" enctype="multipart/form-data">
                       @csrf

                        <input type="hidden" name="hdn_id" id="hidden_id" value=""> 

                        <div class="form-group">
                          <label for="module">Select Module:</label><span style="color: red">*</span>
                          <select class="form-control select" id="module" name="module" >
                            <option value=" "> --Select category-- </option>
                            @foreach ($categories as $category)
                            <option value="{{$category->id}}">
                              {{$category->module_name}}
                            </option>
                            @endforeach
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="module_name">Module Name:</label><span style="color: red">*</span>
                          <input type="text"  class="form-control" id="module_name" autocomplete="off" name="module_name"> 
                        </div>

                        <div class="form-group">
                          <input type="submit" name="" id="sub_button" value ="submit" class="btn btn-primary">
                           <a href="{{ url('/section') }}" ><button style="cursor:pointer" type="button" class="btn btn-primary">Cancel</button></a>
                        </div>
                      </form>
                    </div>  
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

                </div>
              </div>
            </div>


            <div class="box-body">
              <div class="row">
                <div class="col-sm-3 col-md-6 col-lg-4">
                  <ul id="tree1" style="width:60px;">
                    @foreach($categories as $category)
                      <li>
                        {{ $category->module_name }}
                        @if(count($category->childs))
                        @include('manageChild',['childs' => $category->childs])
                        @endif
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div class="col-sm-3 col-md-6 col-lg-8">
                  <table class="table datatable custom-table table-striped select-all " id="list" data-toggle="table" data-hover="true" data-striped="true" data-smart-display="true" data-sort-order="desc" data-page-size="10" data-pagination="true" data-side-pagination="server" data-page-list="[5, 10, 25,All]" data-url="section_details" method="get">

                    <thead> 
                      <tr>
                      <th data-field="module_name" data-sortable="true">Module name</th>
                      <th data-field="action">Action</th>
                      </tr>
                    </thead>

                  </table>
                </div>
              </div>
            <!-- /.box-body -->
            </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->  
  @include('alert_modal')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('layout.partials.form_footer')

  <!-- page script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>  
  <script type="text/javascript" src="{{ asset('js/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#module_form_modal').on('hidden.bs.modal', function (e) {
      $('#category_form').trigger('reset');
      location.reload();
  })

  $(function()
  {
    jQuery.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "This field must contains characters.");
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
    return arg !== value;
    }, "This field is required.");

    $("form[name='module_form']").validate({  //https://www.sitepoint.com/basic-jquery-form-validation-tutorial/
      errorClass:'errors',
      rules: { 
        module:{
          required:true,
          valueNotEquals:" " 
        },
        module_name:{
          required:true,
          maxlength:50,
          alpha:true,
        },
      },
      highlight: function (element) {
        $(element).addClass('error');
      },
      unhighlight: function (element) {
        $(element).removeClass('error');
      },
      errorPlacement: function(error, element) {
        error.appendTo( element.parent("div"));
      },
    });
  });

  //ajax call to master-category details for particular category details
  function ajx_category_edit(id)
  {
    $.ajax(
      {
          url:'/section_update/'+id,
          type: 'get',
          // dataType: "JSON",
          data: {
              "id": id
          },
          success: function (data)
          {
            console.log(data);
            $('#hidden_id').val(data['category']['id']);
            $('#module_name').val(data['category']['module_name']);
            $('#module_form_modal').modal('show');
          }
      });
    $('#list').bootstrapTable('refresh');
  }
  </script>  
  <?php
    if(Session::get('session_msg'))
    {
      ?>
      <script type="text/javascript">
      toastr["success"]("<?php echo Session::get('session_msg'); ?>");
      </script>
      <?php
    }
  ?>
  </body>
</html>

