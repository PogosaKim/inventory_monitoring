<div class="collapse navbar-collapse" id="navbarVerticalCollapse">
    <div class="navbar-vertical-content scrollbar">
      <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
        <li class="nav-item">
          <!-- label-->
          <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">Dashboard</div>
            <div class="col ps-0">
              <hr class="mb-0 navbar-vertical-divider" />
            </div>
          </div>
          <a class="nav-link {{ Request::is('pc/inventory') ? 'active' : '' }}" href="{{URL::to('pc/inventory')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Inventory</span></div>
          </a>
          <a class="nav-link {{ Request::is('pc/release_data') ? 'active' : '' }}" href="{{URL::to('pc/release_data')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">For Release</span></div>
          </a>
          <a class="nav-link {{ Request::is('pc/purchase_order') ? 'active' : '' }}" href="{{URL::to('pc/purchase_order')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">For Purchase Order</span></div>
          </a>
          <a class="nav-link {{ Request::is('pc/scanner') ? 'active' : '' }}" href="{{URL::to('pc/scanner')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Scan Inventory</span></div>
          </a>
          {{-- <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{URL::to('admin/dashboard')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Dashboard</span></div>
          </a>  
          <a class="nav-link {{ Request::is('admin/calendar') ? 'active' : '' }}" href="{{URL::to('admin/calendar')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Calendar</span></div>
          </a>
          <a class="nav-link dropdown-indicator" href="#manage" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="manage">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fab fa-slack-hash"></span></span><span class="nav-link-text ps-1">Manage</span></div>
          </a> --}}
          {{-- <ul class="nav collapse" id="manage">
            <li class="nav-item"><a class="nav-link {{ Request::is('admin/assign_adviser') ? 'active' : '' }}" href="{{URL::to('admin/assign_adviser')}}">
              <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Class</span></div>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link {{ Request::is('admin/school_years') ? 'active' : '' }}" href="{{URL::to('admin/school_years')}}">
            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">School Year</span></div>
          </a>
        </li>
          <li class="nav-item"><a class="nav-link {{ Request::is('admin/year_level') ? 'active' : '' }}" href="{{URL::to('admin/year_level')}}">
            <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Grade Level</span></div>
          </a>
        </li>
          <li class="nav-item"><a class="nav-link {{ Request::is('admin/section') ? 'active' : '' }}" href="{{URL::to('admin/section')}}">
              <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Section</span></div>
            </a>
          </li>

          <li class="nav-item"><a class="nav-link {{ Request::is('admin/manage_user') ? 'active' : '' }}" href="{{URL::to('admin/manage_user')}}">
              <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Manage User</span></div>
            </a>
          </li>
          
          </ul> --}}
        </li>
      </ul>
    </div>
  </div>