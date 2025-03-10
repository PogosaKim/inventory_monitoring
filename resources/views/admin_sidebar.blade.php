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
          <a class="nav-link {{ Request::is('admin/create_user') ? 'active' : '' }}" href="{{URL::to('admin/create_user')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Create User</span></div>
          </a>
          <a class="nav-link {{ Request::is('admin/reset_password') ? 'active' : '' }}" href="{{URL::to('admin/reset_password')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Change Password</span></div>
          </a>
        </li>
      </ul>
    </div>
  </div>