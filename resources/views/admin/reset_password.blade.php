@extends('site/layouts/main')
@stop


@section('content')

<style>
  .holiday {
    background-color: red !important;
    color: white !important;
  }
</style>

<div class="container-fluid">
	<script>
		var isFluid = JSON.parse(localStorage.getItem('isFluid'));
            if (isFluid) {
              var container = document.querySelector('[data-layout]');
              container.classList.remove('container');
              container.classList.add('container-fluid');
            }
	</script>

	<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
		<script>
			var navbarStyle = localStorage.getItem("navbarStyle");
                if (navbarStyle && navbarStyle !== 'transparent') {
                  document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
                }
		</script>
		<div class="d-flex align-items-center">
			<div class="toggle-icon-wrapper">
				{{-- <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
					data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
							class="toggle-line"></span></span></button> --}}
			</div><a class="navbar-brand" href="{{URL::to('dean/index')}}">
				<div class="d-flex align-items-center py-3">
					<span class="font-sans-serif" style="color:#DE9208; font-size:13px">
         INVENTORY MONITORING
					</span>
				</div>
			</a>
		</div>

		@include('admin_sidebar');

	</nav>


	<div class="content">
		<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
			{{-- <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
				aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span
					class="navbar-toggle-icon"><span class="toggle-line"></span></span></button> --}}


			<ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
				<li class="nav-item">
                    @if (Auth::check())
                        <p class="dropdown-item">Hi Admin, {{ Auth::user()->name }}</p>
                    @endif
					<div class="theme-control-toggle fa-icon-wait px-2"><input
							class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle"
							type="checkbox" data-theme-control="theme" value="dark" /><label
							class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle"
							data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span
								class="fas fa-sun fs-0"></span></label><label
							class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle"
							data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span
								class="fas fa-moon fs-0"></span></label></div>
				</li>



				<li class="nav-item dropdown"><a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button"
						data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="avatar avatar-xl">
              <img class="rounded-circle" src="{{ asset('assets/site/images/user.png') }}" alt="User Image" />
						</div>
					</a>
					<div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0"
						aria-labelledby="navbarDropdownUser">
						<div class="bg-white dark__bg-1000 rounded-2 py-2">
							{{-- <a class="dropdown-item" href="{{ url('') }}" target="_blank">Profile &amp; account</a> --}}
							<a class="dropdown-item" href="{{URL::to('auth/logout')}}">Logout</a>
						</div>
					</div>
				</li>
			</ul>
		</nav>

		<script>
			var navbarPosition = localStorage.getItem('navbarPosition');
                var navbarVertical = document.querySelector('.navbar-vertical');
                var navbarTopVertical = document.querySelector('.content .navbar-top');
                var navbarTop = document.querySelector('[data-layout] .navbar-top:not([data-double-top-nav');
                var navbarDoubleTop = document.querySelector('[data-double-top-nav]');
                var navbarTopCombo = document.querySelector('.content [data-navbar-top="combo"]');
    
                if (localStorage.getItem('navbarPosition') === 'double-top') {
                  document.documentElement.classList.toggle('double-top-nav-layout');
                }
    
                if (navbarPosition === 'top') {
                  navbarTop.removeAttribute('style');
                  navbarTopVertical.remove(navbarTopVertical);
                  navbarVertical.remove(navbarVertical);
                  navbarTopCombo.remove(navbarTopCombo);
                  navbarDoubleTop.remove(navbarDoubleTop);
                } else if (navbarPosition === 'combo') {
                  navbarVertical.removeAttribute('style');
                  navbarTopCombo.removeAttribute('style');
                  // navbarTop.remove(navbarTop);
                  navbarTopVertical.remove(navbarTopVertical);
                  navbarDoubleTop.remove(navbarDoubleTop);
                } else if (navbarPosition === 'double-top') {
                  navbarDoubleTop.removeAttribute('style');
                  navbarTopVertical.remove(navbarTopVertical);
                  navbarVertical.remove(navbarVertical);
                  // navbarTop.remove(navbarTop);
                  navbarTopCombo.remove(navbarTopCombo);
                } else {
                  navbarVertical.removeAttribute('style');
                  navbarTopVertical.removeAttribute('style');
                  // navbarTop.remove(navbarTop);
                  // navbarDoubleTop.remove(navbarDoubleTop);
                  // navbarTopCombo.remove(navbarTopCombo);
                }
		</script>

		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

        <div class="card mb-3">
            <div class="card-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h5 class="mb-2 mb-md-0">Change Password</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="center-form">
                    <div class="form-container active">
                        <form id="resetForm" autocomplete="off"> 
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <fieldset>
                                <h4 class="text-center">Enter Username</h4>
                                <div class="form-group">
                                    <label for="user_name" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="user_name" id="user_name" required>
                                </div>
                            </fieldset>
        
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success px-4 w-100" id="searchUsername">
                                    <span class="fa fa-search"></span> Check
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        


@stop

@section('scripts')
<script>
$(document).ready(function () {
    $('#searchUsername').click(function () {
    const username = $('#user_name').val().trim();

    if (username === '') {
        alert('Please enter a username.');
        return;
    }

    $.ajax({
        url: "{{url('find_reset_password')}}",
        type: 'POST',
        data: {
            user_name: username,
             _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            if (response.success) {
                let userOptions = '';

                response.persons.forEach(person => {
                    userOptions += `
                        <tr>
                            <td>${person.first_name} ${person.last_name}</td>
                            <td>
                                <button class="btn btn-primary reset-password-btn" 
                                        data-user-id="${person.user_id}">
                                    Reset Password
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $('#resetForm').html(`
                    <div class="reset-password-container">
                        <h4 class="reset-password-title">Select a Person to Reset Password</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${userOptions}
                            </tbody>
                        </table>
                    </div>
                `);

                $('.reset-password-btn').click(function () {
                    const userId = $(this).data('user-id');

                    $('#resetForm').html(`
                        <div class="reset-password-container">
                            <h4 class="reset-password-title">Reset Password</h4>
                            <form id="newPasswordForm" autocomplete="off">
                                <div class="form-group">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <br>
                                <button type="button" class="btn btn-success w-100" id="submitNewPassword">
                                    Reset Password
                                </button>
                            </form>
                        </div>
                    `);

                    $('#submitNewPassword').click(function () {
                        const newPassword = $('#new_password').val().trim();
                        if (newPassword === '') {
                            alert('Please enter a new password.');
                            return;
                        }

                        $.ajax({
                            url: "{{url('update_reset_password')}}",
                            type: 'POST',
                            data: {
                                user_id: userId,
                                new_password: newPassword,
                                 _token: "{{ csrf_token() }}"
                            },
                            success: function (res) {
                                if (res.success) {
                                    alert('Password reset successfully!');
                                } else {
                                    alert('Failed to reset password.');
                                }
                            }
                        });
                    });
                });
            } else {
                alert(response.message);
            }
        }
    });
});

});



</script>

@stop





