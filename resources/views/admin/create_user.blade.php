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
            <div class="row flex-between-center">
                <div class="col-md">
                <h5 class="mb-2 mb-md-0">CREATE USERS</h5>
                </div>
            </div>
            </div>
            </div>

            <div class="card-body bg-light">

            <br>
            <div class="text-center mb-4">
                <h5 style="font-size:18px;">ANDRES SORIANO COLLEGES OF BISLIG</h5>
                <h6 style="font-size:15px;">Mangagoy, Bislig City</h6>
            </div>

            <div class="text-center mb-4">
                <h5 style="font-size:18px;">CREATE USERS</h5>
            </div>


            <form id="createUserForm" style="margin: 20px;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="d-flex justify-content-between mb-3">
                    <div style="flex-basis: 48%;">Role:   
                        <select name="user_role_id" id="user_role_id" class="form-control">
                            @foreach ($user_role_list as $user_role)
                                <option value="{{ $user_role->id }}">{{ $user_role->name }}</option>
                            @endforeach
                        </select>
                    </div>                    
                    <div style="flex-basis: 48%;">Department: 
                        <select name="school_department_id" id="school_department_id" class="form-control">
                            <option value="0">--Select--</option>
                                @foreach ($school_department_list as $school_department)
                                    <option value="{{ $school_department->id }}">{{ $school_department->name }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>


                <div class="d-flex justify-content-between mb-3">
                    <div style="flex-basis: 32%;">First Name: 
                        <input type="text" name="first_name" id="first_name" class="form-control"/>
                    </div>    
                    <div style="flex-basis: 32%;">Middle Name: 
                        <input type="text" name="middle_name" id="middle_name" class="form-control"/>
                    </div>             
                    <div style="flex-basis: 32%;">Last Name: 
                        <input type="text" name="last_name" id="last_name" class="form-control"/>
                    </div>             
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div style="flex-basis: 48%;">Password: 
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control"/>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>    
                    <div style="flex-basis: 48%;">Confirm Password: 
                        <div class="input-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"/>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>                     
                </div>
                

                <div class="mb-3">
                    <label for="signature" class="form-label">Signature:</label>
                    <div style="border: 2px solid #000; width: 100%; height: 150px; position: relative;">
                        <canvas id="signatureCanvas" style="width: 100%; height: 100%;"></canvas>
                    </div>
                    <button type="button" id="clearSignature" class="btn btn-danger mt-2">Clear Signature</button>
                    <input type="hidden" name="signature" id="signatureInput">
                </div>

            
                <div class="text-center">
                    <button class="btn btn-primary" id="createUserBtn">Create User</button>
                </div>
        <br>
    </form>
    </div>



@stop

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let canvas = document.getElementById("signatureCanvas");
    let ctx = canvas.getContext("2d");
    let isDrawing = false;
    function resizeCanvas() {
        let rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;
    }
    resizeCanvas();

    canvas.addEventListener("mousedown", (e) => {
        isDrawing = true;
        ctx.beginPath();
        ctx.moveTo(e.offsetX, e.offsetY);
    });

    canvas.addEventListener("mousemove", (e) => {
        if (isDrawing) {
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        }
    });

    canvas.addEventListener("mouseup", () => {
        isDrawing = false;
        saveSignature();
    });

    canvas.addEventListener("mouseleave", () => {
        isDrawing = false;
    });

    function saveSignature() {
        let signatureData = canvas.toDataURL("image/png");
        document.getElementById("signatureInput").value = signatureData;
    }

    document.getElementById("clearSignature").addEventListener("click", function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById("signatureInput").value = "";
    });
});

$(document).ready(function() {

    $(".toggle-password").on("click", function () {
        let target = $(this).data("target");
        let input = $("#" + target);
        let icon = $(this).find("i");

        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    $("#confirm_password").on("keyup", function () {
        let password = $("#password").val();
        let confirmPassword = $(this).val();

        if (password !== confirmPassword) {
            $(this).css("border", "2px solid red");
        } else {
            $(this).css("border", "2px solid green");
        }
    });


    $('#createUserBtn').on('click', function(e) {
        e.preventDefault();
        let signatureData = document.getElementById("signatureCanvas").toDataURL("image/png");
        document.getElementById("signatureInput").value = signatureData;

        // Prepare FormData
        let formData = new FormData(document.getElementById('createUserForm'));

        $.ajax({
            url: "{{ url('admin/create_users') }}",
            type: "POST",
            data: formData,
            processData: false,  
            contentType: false, 
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User successfully created!',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to create user. Please try again.',
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON?.message || 'An error occurred. Please try again.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                });
                console.log(xhr.responseText);
            }
        });
    });


});
</script>

@stop





