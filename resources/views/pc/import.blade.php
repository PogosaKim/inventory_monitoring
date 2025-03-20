@extends('site/layouts/main')
@section('title') Import Data :: @parent @endsection

@section('styles')
<link href="{{asset('assets/site/css/glyphicon.design.css')}}" rel="stylesheet">
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap");

    .card {
        border-radius: 10px;
        box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.3);
        width: 100%;
        height: 300px;
        background-color: #ffffff;
        padding: 10px 30px 40px;
    }

    .card h3 {
    font-size: 22px;
    font-weight: 600;
    }

    .drop_box {
    margin: 10px 0;
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    border: 3px dotted #a3a3a3;
    border-radius: 5px;
    }

    .drop_box h4 {
    font-size: 16px;
    font-weight: 400;
    color: #2e2e2e;
    }

    .drop_box p {
    margin-top: 10px;
    margin-bottom: 20px;
    font-size: 12px;
    color: #a3a3a3;
    }

    /* .btn {
    text-decoration: none;
    background-color: #005af0;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    outline: none;
    transition: 0.3s;
    } */
/* 
    .btn:hover {
    text-decoration: none;
    background-color: #ffffff;
    color: #005af0;
    padding: 10px 20px;
    border: none;
    outline: 1px solid #010101;
    } */
    .form input {
    margin: 10px 0;
    width: 100%;
    background-color: #e2e2e2;
    border: none;
    outline: none;
    padding: 12px 20px;
    border-radius: 4px;
    }

</style>
@endsection

@section('content')
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
			</div><a class="navbar-brand" href="#">
				<div class="d-flex align-items-center py-3">
					<span class="font-sans-serif" style="color:#DE9208; font-size:13px">
                        CUSTODIAN OFFICE 
                        <br> TRACK & REQUEST
					</span>
				</div>
			</a>
		</div>

		@include('pc_sidebar');

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
                        <p class="dropdown-item">Hi Custodian, {{ Auth::user()->name }}</p>
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
        <input type="file" hidden name="excelFile" accept=".xlsx" id="fileID">
        <div class="row">
            <br>
            <div class="page-header">
                <br />
                <h2>Import Data</h2>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="container">
                        <div class="card">
                            <h3><span class="fa fa-file-excel-o"></span> Import File</h3>
                            <div class="drop_box">
                                <header>
                                    <h4>Select File Here</h4>
                                </header>
                                <p>Supported File Type: XLSX</p>
                                <form id="excelFileForm" class="form-horizontal">
                                    <input type="file" hidden name="excelFile" accept=".xlsx" id="fileID" style="display:none;">
                                </form>
                                <button class="btn btn-primary-custom">Choose File</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       


        
          
		  



@stop

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
 var oTable;
 const dropArea = document.querySelector(".drop_box"),
    button = dropArea.querySelector("button"),
    input = dropArea.querySelector("input");

button.onclick = () => {
    input.click();
};

input.addEventListener("change", function (e) {
    const formData = new FormData(document.getElementById('excelFileForm'));
    formData.append("_token", "{{ csrf_token() }}");

    // Show loading state
    button.innerHTML = '<span class="fa fa-spinner fa-spin"></span> Importing...';
    button.disabled = true;

    $.ajax({
        url: "{{ url('import/import_excel') }}",
        type: 'POST',
        dataType: "JSON",
        contentType: false,
        processData: false,
        data: formData,
        success: function(res) {
            // Reset button
            button.innerHTML = 'Choose File';
            button.disabled = false;

            if (res.status === 'success') {
                swal({
                    type: 'success',
                    title: 'Success',
                    text: 'Data imported successfully!'
                }).then(function() {
                    window.location.reload();
                });
            } else {
                swal({
                    type: 'error',
                    title: 'Error',
                    text: res.message || 'Failed to import data'
                });
            }
        },
        error: function(xhr) {
            // Reset button
            button.innerHTML = 'Choose File';
            button.disabled = false;

            swal({
                type: 'error',
                title: 'Error',
                text: 'An error occurred while importing: ' + (xhr.responseJSON?.message || 'Unknown error')
            });
        }
    });
});




</script>

@stop



