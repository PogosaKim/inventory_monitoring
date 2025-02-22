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
			</div><a class="navbar-brand" href="{{URL::to('')}}">
				<div class="d-flex align-items-center py-3">
					<span class="font-sans-serif" style="color:#DE9208; font-size:13px">
         INVENTORY MONITORING
					</span>
				</div>
			</a>
		</div>

		@include('teacher_sidebar');

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
                        <p class="dropdown-item">Hi Teacher, {{ Auth::user()->name }}</p>
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
                    <h5 class="mb-2 mb-md-0">Tracking Request</h5>
                    </div>
                </div>
                </div>
                <div class="card-body px-sm-4 px-md-8 px-lg-6 px-xxl-8">
                    <div class="timeline-vertical">
                        <div class="timeline-item timeline-item-start">
                            <div class="timeline-icon icon-item icon-item-lg text-primary border-300">
                                {{-- @if($my_request_supplies->contains('action_type', 1))
                                    <i class="fas fa-check-circle text-success fs-1"></i> 
                                @else
                                    <i class="fas fa-hourglass-half text-warning fs-1"></i> 
                                @endif --}}
                                @if($my_request_supplies->contains('action_type', 5))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 4))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 3))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 2))
                                <i class="fas fa-check-circle text-success fs-1"></i>
                                @else
                                <i class="fas fa-hourglass-half text-warning fs-1"></i> 
                            @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-6 timeline-item-time">
                                    <div>
                                        <p class="fs--1 mb-0 fw-semi-bold">
                                            @if($my_request_supplies->contains('action_type', 5))
                                                ✅ Done Approved
                                            @elseif($my_request_supplies->contains('action_type', 4))
                                                ✅ Approved by Finance
                                            @elseif($my_request_supplies->contains('action_type', 3))
                                                ✅ Approved by President
                                            @elseif($my_request_supplies->contains('action_type', 2))
                                                ✅ Approved by Dean
                                            @else
                                                ⏳ Pending Request
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                
                                                               
                                <?php
                                    use Carbon\Carbon;
                                
                                    $total_requests = $my_request_supplies->count();
                                    $released_count = $my_request_supplies->where('action_type', 6)->count();
                                
                                    $today = Carbon::today();
                            
                                    $active_supplies = $my_request_supplies->filter(function ($request) use ($today) {
                                        return !($request->action_type == 6 && Carbon::parse($request->release_date)->lt($today));
                                    });
                                
                                    $active_count = $active_supplies->count();
                                 ?>
                                
                                <div class="col-lg-6">
                                    <div class="timeline-item-content">
                                        <div class="timeline-item-card">
                                            <h5 class="mb-2">Request Supplies</h5>
                                            <p class="fs--1 fw-bold">Total Active Requests: {{ $active_count }}</p>
                                            @if($active_count > 0)
                                                @foreach($active_supplies as $request)
                                                    <p class="fs--1 mb-0">
                                                        <span class="{{ $request->action_type == 6 ? 'text-decoration-line-through text-danger' : '' }}">
                                                            • {{ $request->name }} 
                                                            (Requested: {{ $request->request_quantity }} {{ $request->inv_unit }}, {{ $request->inv_brand }})
                                                        </span>
                                
                                                        @if($request->release_supplies_qty == $request->request_quantity) 
                                                            <p class="fs--1 text-success">
                                                                ✅ Fully Released: {{ $request->release_supplies_qty }} {{ $request->inv_unit }}
                                                            </p>
                                                        @elseif($request->purchase_order_id) 
                                                            <p class="fs--1 text-success">
                                                                ✅ Released: {{ $request->release_supplies_qty }} {{ $request->inv_unit }}
                                                            </p>
                                                            <p class="fs--1 text-warning">
                                                                ⚠️ Remaining {{ $request->request_quantity - $request->release_supplies_qty }} {{ $request->inv_unit }} waiting for purchase order.
                                                            </p>
                                                        @else
                                                            <p class="fs--1 text-danger">
                                                                ⚠️ Request still in process.
                                                            </p>
                                                        @endif
                                                    </p>
                                                @endforeach
                                            @else
                                                <p class="fs--1 text-danger fw-bold">🚀 All items have been released and removed.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                
                            
                                
                            </div>
                        </div>
                

                        <div class="timeline-item timeline-item-end">
                            <div class="timeline-icon icon-item icon-item-lg text-primary border-300">
                                {{-- @if($my_request_supplies->contains('action_type', 2))
                                    <i class="fas fa-check-circle text-success fs-1"></i> 
                                @else
                                    <i class="fas fa-user-clock text-warning fs-1"></i>
                                @endif --}}
                                @if($my_request_supplies->contains('action_type', 5))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 4))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 2))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 3))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @else
                                <i class="fas fa-user-clock text-warning fs-1"></i>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-6 timeline-item-time">
                                    <div>
                                        <p class="fs--1 mb-0 fw-semi-bold">
                                            @if($my_request_supplies->contains('action_type', 5))
                                                ✅ Done Approved
                                            @elseif($my_request_supplies->contains('action_type', 4))
                                                ✅ Approved by Finance
                                            @elseif($my_request_supplies->contains('action_type', 2))
                                            ✅ Approved by Dean
                                            @elseif($my_request_supplies->contains('action_type', 3))
                                                ✅ Approved by President 
                                            @else
                                                ⏳ Pending Request
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="timeline-item-content">
                                        <div class="timeline-item-card">
                                            <h5 class="mb-2">Dean</h5>
                                           
                                            @if($my_request_supplies->where('action_type', 1)->count() > 0)
                                                <p class="fs--1 fw-bold text-warning">Waiting for Dean's Approval ⏳</p>
                                                @foreach($my_request_supplies->where('action_type', 1) as $request)
                                                    <p class="fs--1 mb-0">• {{ $request->name }} ({{ $request->request_quantity }} {{ $request->inv_unit }}, {{ $request->inv_brand }})</p>
                                                @endforeach
                                            @endif
                                    
                                            @if($my_request_supplies->where('action_type', 2)->count() > 0)
                                                <p class="fs--1 fw-bold text-success mt-2">Approved by Dean ✅</p>
                                                @foreach($my_request_supplies->where('action_type', 2) as $request)
                                                    <p class="fs--1 mb-0">• {{ $request->name }} ({{ $request->request_quantity }} {{ $request->inv_unit }}, {{ $request->inv_brand }})</p>
                                                @endforeach
                                            @endif

                                            {{-- @if($my_request_supplies->where('action_type', 2)->count() == 0 && $my_request_supplies->where('action_type', 3)->count() == 0)
                                            <p class="fs--1 mb-0 text-danger fw-bold">Not Available</p>
                                            @endif --}}
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item timeline-item-start">
                            <div class="timeline-icon icon-item icon-item-lg text-primary border-300">
                                {{-- @if($my_request_supplies->contains('action_type', 3))
                                    <i class="fas fa-check-circle text-success fs-1"></i>
                                @else
                                    <i class="fas fa-book-open text-danger fs-1"></i> 
                                @endif --}}
                                @if($my_request_supplies->contains('action_type', 5))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 4))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 3))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @else
                                <i class="fas fa-book-open text-danger fs-1"></i> 
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-6 timeline-item-time">
                                    <div>
                                        <p class="fs--1 mb-0 fw-semi-bold">
                                            @if($my_request_supplies->contains('action_type', 5))
                                                ✅ Done Approved
                                            @elseif($my_request_supplies->contains('action_type', 4))
                                                ✅ Approved by Finance
                                            @elseif($my_request_supplies->contains('action_type', 3))
                                            ✅ Approved by President
                                            @else
                                                ⏳ Pending Request
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="timeline-item-content">
                                        <div class="timeline-item-card">
                                            <h5 class="mb-2">President</h5>
                
                                            @if($my_request_supplies->where('action_type', 2)->count() > 0)
                                                <p class="fs--1 fw-bold text-warning">Waiting for President's Approval ⏳</p>
                                                @foreach($my_request_supplies->where('action_type', 2) as $request)
                                                    <p class="fs--1 mb-0">• {{ $request->name }} ({{ $request->request_quantity }} {{ $request->inv_unit }}, {{ $request->inv_brand }})</p>
                                                @endforeach
                                            @endif
                
                                            @if($my_request_supplies->where('action_type', 3)->count() > 0)
                                                <p class="fs--1 fw-bold text-success mt-2">Approved by President ✅</p>
                                                @foreach($my_request_supplies->where('action_type', 3) as $request)
                                                    <p class="fs--1 mb-0">• {{ $request->name }} ({{ $request->request_quantity }} {{ $request->inv_unit }}, {{ $request->inv_brand }})</p>
                                                @endforeach
                                            @endif
                
                                            {{-- @if($my_request_supplies->where('action_type', 2)->count() == 0 && $my_request_supplies->where('action_type', 3)->count() == 0)
                                            <p class="fs--1 mb-0 text-success fw-bold">Passed</p>
                                            @endif --}}
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                

                        <div class="timeline-item timeline-item-end">
                            <div class="timeline-icon icon-item icon-item-lg text-primary border-300">
                                {{-- @if($my_request_supplies->contains('action_type', 4))
                                    <i class="fas fa-check-circle text-success fs-1"></i> 
                                @else
                                    <i class="fas fa-rocket text-warning fs-1"></i> 
                                @endif --}}
                                @if($my_request_supplies->contains('action_type', 5))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @elseif($my_request_supplies->contains('action_type', 4))
                                <i class="fas fa-check-circle text-success fs-1"></i> 
                                @else
                                <i class="fas fa-rocket text-warning fs-1"></i> 
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-6 timeline-item-time">
                                    <div>
                                        <p class="fs--1 mb-0 fw-semi-bold">
                                            @if($my_request_supplies->contains('action_type', 5))
                                                ✅ Done Approved
                                            @elseif($my_request_supplies->contains('action_type', 4))
                                            ✅ Approved by Finance
                                            @else
                                                ⏳ Pending Request
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="timeline-item-content">
                                        <div class="timeline-item-card">
                                            <h5 class="mb-2">Finance</h5>
                                            @if($my_request_supplies->where('action_type', 4)->count() > 0)
                                                <p class="fs--1 fw-bold text-success mt-2">Approved by Finance ✅</p>
                                                @foreach($my_request_supplies->where('action_type', 4) as $request)
                                                    <p class="fs--1 mb-0">• {{ $request->name }} ({{ $request->request_quantity }} {{ $request->inv_unit }}, {{ $request->inv_brand }})</p>
                                                @endforeach
                                            @else
                                            {{-- <p class="fs--1 mb-0 text-success fw-bold">Passed</p> --}}
                                            @endif
                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                     <div class="timeline-item timeline-item-start">
                        <div class="timeline-icon icon-item icon-item-lg text-primary border-300">
                            {{-- @if($my_request_supplies->contains('action_type', 5))
                                <i class="fas fa-check-circle text-success fs-1"></i>
                            @else
                                <i class="fas fa-truck text-info fs-1"></i> 
                            @endif --}}
                            @if($my_request_supplies->contains('action_type', 5))
                            <i class="fas fa-check-circle text-success fs-1"></i> 
                            @else
                            <i class="fas fa-truck text-info fs-1"></i> 
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-6 timeline-item-time">
                                <div>
                                    <p class="fs--1 mb-0 fw-semi-bold">
                                        @if($my_request_supplies->contains('action_type', 5))
                                            Ready for Pick-Up
                                        @else
                                            Not Ready for Pick-Up
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="timeline-item-content">
                                    <div class="timeline-item-card">
                                        <h5 class="mb-2">For Pick-Up</h5>
                                        
                                        @if($my_request_supplies->where('action_type', 5)->count() > 0)
                                            <p class="fs--1 mb-0">✅ Your request is ready for pick-up.</p>
                                            
                                            <p class="fs--1 fw-bold text-success mt-2">Supplies Ready for Pick-Up:</p>
                                            @foreach($my_request_supplies->where('action_type', 5) as $request)
                                                <p class="fs--1 mb-0">
                                                    • {{ $request->name }} 
                                                    (Released: {{ $request->release_supplies_qty }} {{ $request->inv_unit }}, 
                                                    {{ $request->inv_brand }})
                                                </p>
                        
                                                @if($request->request_quantity > $request->release_supplies_qty)
                                                    <p class="fs--1 text-warning">
                                                        ⚠️ Remaining {{ $request->request_quantity - $request->release_supplies_qty }} {{ $request->inv_unit }} 
                                                        is waiting.
                                                    </p>
                                                @endif
                                            @endforeach
                        
                                        @else
                                            <p class="fs--1 mb-0 text-danger">⚠️ Your request is still in process.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>


                    </div>
                  </div>
          </div>

          

       


        
          
		  



@stop

@section('scripts')
<script>
 var oTable;
$(document).ready(function() {
  
    oTable = $("#studentTable").DataTable({
        ajax: {
            url: "{{ url('admin/student_pds') }}",
            type: "GET",
            data: function(d) { 
            },
            dataSrc: "",
        },
        columns: [
            {
                data: 'name'
            },
            ],
           
        });
      	oTable.on("click", ".viewDetail", function() {
        const person_id = $(this).data("person_id");
        const url = "{{ url('admin/student_profile') }}?person_id="+person_id;
        window.open(url);
      });

});

</script>

@stop





