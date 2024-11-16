<div>
    <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{('/dashboard')}}">
          <i class="bi bi-speedometer"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
         
          <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>Form Layouts</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form Editors</span>
            </a>
          </li>
          
        </ul>
      </li><!-- End Forms Nav -->
      
      @can('role-view')
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-people"></i><span>Roles</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
         
          <li>
            <a href="{{ url('/permission')}}">
              <i class="bi bi-lock"></i><span>Permission</span>
            </a>
          </li>
          <li>
            <a href="{{url('/role-permission')}}">
              <i class="bi bi-person-lock"></i><span> Role</span>
            </a>
          </li>
          <li>
            <a href="{{url('/user-role')}}">
              <i class="bi bi-person-check"></i><span>User Role</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->   
      @endcan
      
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{('/item')}}">
          <i class="bi bi-handbag"></i>
          <span>Data Barang</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ url('/data-karyawan') }}">
            <i class="bi bi-file-earmark-person"></i>
            <span>Data Karyawan</span>
        </a>
    </li><!-- End Data Karyawan Nav -->    

    </ul>

  </aside><!-- End Sidebar-->
</div>
