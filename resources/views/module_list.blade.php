 
@extends('layouts.app')
@section('header_css')

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-table-master/dist/bootstrap-table.min.css')}}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection
@section('content')

<?php
// print_r($categories);exit();

?>
    <!-- Main content -->
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-6 col-lg-4">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Module</h3>
                <!-- <ul class="navbar-right"> -->
                <h4> 
                <a href="javascript:void(0)"> 
                <!-- <i class="fa fa-wpforms"></i> -->
                  <button type="button" class="btn btn-success" style="float: right;" data-toggle="modal"              data-target="#module_form_modal">Add Module
                  </button>
                </a>
                </h4>
                <!-- </ul> -->
            </div> 

            <!-- The Modal -->
            <div class="modal" id="module_form_modal">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Add Module</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  @if($errors->any())
                  <div class="alert alert-danger">{{$errors->first()}}</div>
                  @endif

                  <!-- Modal body -->
                  <div class="modal-body">
                    <div class="box-body">
                      <form method="post" name="module_form" action="{{ route('store_module') }}" enctype="multipart/form-data">
                       @csrf

                        <input type="hidden" name="hdn_id" id="hidden_id"
                        @if(isset($categories)) value="" @endif> 

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
                          <input type="submit" name="" id="sub_button" value ="submit" class="btn btn-success">
                           <a href="{{ url('/module') }}" ><button style="cursor:pointer" type="button" class="btn btn-success">Cancel</button></a>
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

            <!-- <div class="box-body"> -->

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

            <!-- </div> -->
            <!-- /.box-body -->
          </div>
        </div>
        <div class="col-sm-9 col-md-6 col-lg-8">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Test Case</h3>
            </div> 

            <!-- The Modal -->
            <div class="modal" id="category_form_modal">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Add category</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <div class="box-body">
                      <form method="post" name="category_form" action="" enctype="multipart/form-data">
                       @csrf

                        <input type="hidden" name="hdn_id" id="hidden_id"
                        @if(isset($category)) value="{{ $category->category_id }}" @endif> 

                        <div class="form-group">
                          <label for="category_name">Category Name:</label><span style="color: red">*</span>
                          <input type="text"  class="form-control" id="category_name" autocomplete="off" name="category_name"
                          @if(isset($category)) 
                          value="{{ $category->category_name }}"
                          @else
                          value="{{ old('category_name') }}"
                          @endif> 
                        </div>

                        <div class="form-group">
                          <input type="submit" name="" id="sub_button" value ="submit" class="btn btn-success">
                           <a href="{{ url('/admin/category_listing') }}" ><button style="cursor:pointer" type="button" class="btn btn-success">Cancel</button></a>
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

                <table class="table datatable custom-table table-striped select-all" id="list" data-toggle="table" data-hover="true" data-striped="true" data-smart-display="true" data-sort-order="desc" data-page-size="10" data-pagination="true" data-side-pagination="server" data-page-list="[5, 10, 25,All]" data-url="" method="get">
                    
                <thead> 
                     <tr>
                      <th data-field="category_name" data-sortable="true">Test Case Summary</th>
                      <th data-field="category_name" data-sortable="true">Description</th>
                      <th data-field="category_name" data-sortable="true">Attachments</th>
                      <th data-field="status">status</th>
                      <th data-field="action">Action</th>
                    </tr>
                </thead>

              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
</div>    

@endsection
@section('footer_script')


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.js"></script>
<script src="{{ asset('assets/jquery/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/jquery/dist/additional-methods.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  
@if(Session::has('errors'))
<script>
$(document).ready(function(){
    $('#module_form_modal').modal({show: true});
});
</script>
@endif
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#module_form_modal').on('hidden.bs.modal', function (e) {
      $('#module_form').trigger('reset');
      location.reload();
  })

    //ajax call to master-category details for particular category details
  function ajx_category_edit(id)
  {
    $.ajax(
      {
          url:'/admin/category_update/'+id,
          type: 'get',
          // dataType: "JSON",
          data: {
              "id": id
          },
          success: function (data)
          {
            console.log(data);
            $('#hidden_id').val(data['category']['category_id']);
            $('#category_name').val(data['category']['category_name']);
            $('#category_form_modal').modal('show');
          }
      });
    $('#list').bootstrapTable('refresh');
  }
  </script>  
  <script src="{{ asset('assets/jquery/dist/jquery.base64.min.js/')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.18.1/bootstrap-table.min.js"></script>
@endsection
