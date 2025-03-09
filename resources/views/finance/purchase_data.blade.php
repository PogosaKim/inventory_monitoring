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
			</div><a class="navbar-brand" href="#">
				<div class="d-flex align-items-center py-3">
					<span class="font-sans-serif" style="color:#DE9208; font-size:13px">
                        CUSTODIAN OFFICE 
                        <br> TRACK & REQUEST
					</span>
				</div>
			</a>
		</div>

		@include('finance_sidebar');

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
                        <p class="dropdown-item">Hi Finance, {{ Auth::user()->name }}</p>
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
          <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">New Purchase Order</h5>
                    <button id="approveAllBtn" class="btn btn-success btn-sm">
                        <span class="fa fa-check"></span> Approve All
                    </button>
                </div>
                <table id="requestTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Requested By</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                </table>
            </div>
        </div>


       


        
          
		  



@stop

@section('scripts')
<script>
 var oTable;
$(document).ready(function() {
  
  oTable = $("#requestTable").DataTable({
      ajax: {
          url: "{{ url('finance/new_purchase_request') }}",
          type: "GET",
          data: function(d) { 
          },
          dataSrc: "",
      },
      columns: [
          {
              data: 'requested_by'
          },
          {
              data: 'item'
          },
          {
              data: 'quantity'
          },
          {
              data: 'date'
          },
          {
              data: 'status'
          },
          {
              data: 'action'
          },


          ],
          
      });

    //   $("#approveAllBtn").on("click", function() {
    // let pendingRequests = [];

    // // Get all pending requests from the table
    //     $("#requestTable .approvedBtn").each(function() {
    //         pendingRequests.push($(this).data("request_supplies_id"));
    //     });

    //     if (pendingRequests.length === 0) {
    //         Swal.fire({
    //             icon: 'info',
    //             title: 'No Pending Requests',
    //             text: 'There are no requests left to approve.',
    //         });
    //         return;
    //     }

    //     Swal.fire({
    //         title: "Are you sure?",
    //         text: "To approve all pending requests",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#3085d6",
    //         cancelButtonColor: "#d33",
    //         confirmButtonText: "Yes, approve all!"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: "{{ url('finance/approve_all_supplies') }}",
    //                 type: "POST",
    //                 data: {
    //                     request_supplies_ids: pendingRequests, 
    //                     _token: "{{ csrf_token() }}"
    //                 },
    //                 success: function(response) {
    //                     if (response.status === 'success') {
    //                         Swal.fire({
    //                             icon: 'success',
    //                             title: 'Approved!',
    //                             text: 'All pending requests have been approved successfully!',
    //                         }).then(() => {
    //                             oTable.ajax.reload();
    //                         });
    //                     } else {
    //                         Swal.fire({
    //                             icon: 'error',
    //                             title: 'Error',
    //                             text: response.message || 'Failed to approve requests. Please try again.',
    //                         });
    //                     }
    //                 },
    //                 error: function(xhr) {
    //                     let errorMessage = xhr.responseJSON?.message || 'An error occurred. Please try again.';
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'Error',
    //                         text: errorMessage,
    //                     });
    //                     console.log(xhr.responseText);
    //                 }
    //             });

    //         }
    //     });
    // });

    $("#approveAllBtn").on("click", function() {
    let pendingRequests = [];

    $("#requestTable .approvedBtn").each(function() {
        let requestId = $(this).data("request_supplies_id");

        if (requestId) {
            if (Array.isArray(requestId)) {
                pendingRequests = pendingRequests.concat(requestId); 
            } else {
                pendingRequests.push(requestId);
            }
        }
    });

    if (pendingRequests.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Pending Requests',
            text: 'There are no requests left to approve.',
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "To approve all pending requests",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, approve all!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('finance/approve_all_supplies') }}",
                type: "POST",
                data: $.param({ 'request_supplies_ids': pendingRequests }) + "&_token={{ csrf_token() }}", 
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: 'All pending requests have been approved successfully!',
                        }).then(() => {
                            oTable.ajax.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to approve requests. Please try again.',
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
        }
    });
});




      oTable.on("click", ".approvedBtn", function() {
        const request_supplies_ids = $(this).data("request_supplies_id"); 
            Swal.fire({
                title: "Are you sure?",
                text: "To Approved this request",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, approved it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('finance/approved_supplies') }}",
                        type: "POST",
                        data: {
                            request_supplies_ids: request_supplies_ids,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Approved!',
                                    text: 'Request Supplies Approved successfully!',
                                }).then(() => {
                                    oTable.ajax.reload(); 
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Failed to Request Supplies. Please try again.',
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
                }
            });
        });

        oTable.on("click", ".viewDetail", function() {
        const request_supplies_id = $(this).data("request_supplies_id");
        const request_supplies_code = $(this).data("request_supplies_code");
        const url = "{{ url('finance/my_request_purchase_data_form') }}?request_supplies_id=" + request_supplies_id + "&request_supplies_code=" + request_supplies_code;
        window.open(url);
    });




});

</script>

@stop





