
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('assets/images/dummy-profile-image.png')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
        <!-- <p>Alexander Pierce</p> -->
        <p>{{session()->get('sess_arr')['email']}}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
        <ul  class="sidebar-menu treeview" data-widget="tree">
          <li class="header">MAIN NAVIGATION</li>
          <li  @if($menu == "section_list")
                {
                  class="active";
                }
                @else
                {
                  class=" ";
                }
                @endif>
            <a href="{{url('/section')}}">
              <i class="fa fa-list"></i>
              <span>section list</span>
            </a>
          </li>
          <li  @if($menu == "test_case_list")
                {
                  class="active";
                }
                @else
                {
                  class=" ";
                }
                @endif>
            <a href="{{url('/test_case')}}">
              <i class="fa fa-list"></i>
              <span>Tets Case list</span>
            </a>
          </li>
        </ul>
    </section>
  </aside>

