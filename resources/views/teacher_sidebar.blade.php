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
          <a class="nav-link {{ Request::is('teacher/request') ? 'active' : '' }}" href="{{URL::to('teacher/request')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Request Supplies</span></div>
          </a>
          <a class="nav-link {{ Request::is('teacher/track_request') ? 'active' : '' }}" href="{{URL::to('teacher/track_request')}}" role="button">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-calendar"></span></span><span class="nav-link-text ps-1">Track Request</span></div>
          </a>
        </li>
      </ul>
    </div>
  </div>