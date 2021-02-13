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
        Test Case
        <!-- <small>advanced tables</small> -->
      </h1>
    </section>    

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Test Case List</h3>
                <!-- <ul class="navbar-right"> -->
                <h4> 
                  <a href="javascript:void(0)"> 
                    <button type="button" class="btn btn-primary" style="float: right;" data-toggle="modal"              data-target="#test_case_form_modal">Add Test Case
                    </button>
                  </a>
                </h4>


                <h4>  
                  <a href="javascript:void(0)"> 
                    <button type="button" onclick="get_test_case()" class="btn btn-primary" style="float: right;" > <i class="fa fa-eye" aria-hidden="true"></i> View All Test Cases
                    </button>
                  </a>
                </h4>
                <!-- </ul> -->
            </div> 

            <!-- The Modal -->
            <div class="modal " id="test_case_form_modal">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Add Test Case</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <div class="box-body">
                      <form method="post" name="test_case_form" action="{{ route('store_test_case') }}" enctype="multipart/form-data">
                       @csrf

                        <input type="hidden" name="hdn_id" id="hidden_id" value="" > 

                        <div class="form-group">
                          <label for="module">Select Module:</label><span style="color: red">*</span>
                          <select class="form-control select" id="module" name="module" >
                            <option value="none"> --Select module-- </option>
                            @foreach ($category as $key=>$value)
                              <option value="{{$value->id}}"
                                @if(old('module') == "{{$value->id}}") 
                                  selected
                                @endif>
                                {{$value->module_name}}
                              </option>
                            @endforeach
                          </select>
                        </div>


                        <div class="form-group">
                          <label for="summary">Test Case Summary:</label><span style="color: red">*</span>
                          <textarea class="form-control" id="summary" name="summary">@php if(isset($test_case))echo $test_case->address; else echo old('summary');@endphp</textarea>
                        </div>

                        <div class="form-group">
                          <label for="description">Description:</label>
                          <input type="text" class="form-control" id="description" name="description" >
                        </div>


                        <div class="form-group">
                          <label for="attachment">Attachments:</label>
                          <input type="file" class="form-control"  id="attachment" name="attachment" >

                          <input type="text" class="form-control" id="temp_attachment"  name="temp_attachment" value="" >

                          @error('attachment')
                          <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <p id="error1" style="display:none; color:#FF0000;">
                        Invalid Image Format! Image Format Must Be JPG, JPEG, PNG.
                        </p>
                        <p id="error2" style="display:none; color:#FF0000;">
                        Maximum File Size Limit is 2MB.
                        </p>

                        <div class="form-group">
                          <input type="submit" name="" id="sub_button" value ="submit" class="btn btn-primary">
                           <a href="{{ url('/test_case') }}" ><button style="cursor:pointer" type="button" class="btn btn-primary">Cancel</button></a>
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
              <div class="row" style="margin-left: -9px;">
                <div class="card col-sm-3 col-md-6 col-lg-4">
                  <h4><b>Module</b></h4>
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
                <table class="table datatable custom-table table-striped select-all " id="list" data-toggle="table" data-hover="true" data-striped="true" data-smart-display="true" data-sort-order="desc" data-page-size="10" data-pagination="true" data-side-pagination="server" data-page-list="[5, 10, 25,All]" data-query-params="queryParams" data-url="test_case_details" method="get">
                    
                <thead> 
                     <tr>
                        <th data-field="module_name" data-sortable="true">Module name</th>
                        <th data-field="summary" data-sortable="true">Test Case Summary</th>
                        <th data-field="description" data-sortable="true">Description</th>
                        <th data-field="attachment" data-sortable="true">Attachments</th>                         
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
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
  <script type="text/javascript" src="{{ asset('js/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('js/toastr.min.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#test_case_form_modal').on('hidden.bs.modal', function (e) {
      $('#test_case_form').trigger('reset');
      location.reload();
  })

  var module_name='';

  function get_test_case(data=""){
    module_name = data;
    $('#list').bootstrapTable('refresh');
  }

  function queryParams(query_params) {

    var params = {};

    $('#toolbar').find('input[name]').each(function () {
    params[$(this).attr('name')] = $(this).val();
    });
    params['order'] = 'desc';
    params['limit'] = query_params.limit;
    params['offset'] = query_params.offset;
    params['module_name'] = module_name;
    return params;
  }

  $(function()
  {
    jQuery.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "This field must contains characters.");
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
    return arg !== value;
    }, "This field is required.");

    $("form[name='test_case_form']").validate({  //https://www.sitepoint.com/basic-jquery-form-validation-tutorial/
      errorClass:'errors',
      rules: { 
        module:{
          required:true,
          valueNotEquals:"none" 
        },
        summary:{
          required:true,
        },
        attachment:{
          extension: "pdf,docx", 
          // other_upl:true,
        },
      },
      messages:{
        attachment:"Please select only attachment with extension ( pdf | doc )"
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
  function ajx_test_case_edit(id)
  {
    $.ajax(
      {
          url:'/test_case_update/'+id,
          type: 'get',
          // dataType: "JSON",
          data: {
              "id": id
          },
          success: function (data)
          {
            // console.log(data);

            $('#module').find('option').remove();
            $.each(data['category'],function(key,value){
              $("#module").append('<option value="'+data['category'][key]['id']+'">'+data['category'][key]['module_name']+'</option>');  

              if(data['category'][key]['category_id']==data['test_case']['category_id'])
              {
                $("#module option[value='"+data['category'][key]['category_id']+"']").prop('selected', true);
              }
            });

            $('#hidden_id').val(data['test_case']['id']);
            $('#summary').val(data['test_case']['summary']);
            $('#description').val(data['test_case']['description']);
            if(data['test_case']['attachment']!=''){
              $('#temp_attachment').val(data['test_case']['attachment']);
            }
            $('#test_case_form_modal').modal('show');
          }
      });
    $('#list').bootstrapTable('refresh');
  }

    $('#attachment').on('change', function(){
    var picsize = (this.files[0].size);
      if(picsize > 2000000){ //1000000
        $('#error2').slideDown("slow");
        $('#error1').slideUp("slow");
      }
      else{
         $('#error1').slideUp("slow");
          $('#error2').slideUp("slow");
      }
    });
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

