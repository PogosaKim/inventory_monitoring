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

		@include('president_sidebar');

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
                        <p class="dropdown-item">Hi President, {{ Auth::user()->name }}</p>
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
                  <h5 class="mb-2 mb-md-0">MY REQUEST SUPPLIES</h5>
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
                <h5 style="font-size:18px;">REQUEST FORM</h5>
            </div>
        
         
            <form id="teacherRequestForm" style="margin: 20px;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="d-flex justify-content-between mb-3">
                    <div style="flex-basis: 48%;">Thru:   
                        <select name="user_role_id" id="user_role_id" class="form-control">
                            <option value="{{ $role->id }}"> {{ $role->name }}</option>
                        </select>
                    </div>                    
                    <div style="flex-basis: 48%;">
                        Date: <input type="date" name="date" class="form-control" id="date-input" 
                               value="{{ isset($release_date) ? $release_date : date('Y-m-d') }}" />
                    </div>
                    
                </div>
        
            
                <div class="d-flex justify-content-start mb-3">
                    <div style="flex-basis: 48%;">From (DEPT.): 
                        <input type="text" class="form-control" value="{{ $my_request_supplies_details->department_name }}" readonly>
                    </div>
                                       
                </div>
        
               
                <div class="mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ITEMS / DESCRIPTION</th>
                                <th>QUANTITY</th>
                                <th>UNIT PRICE</th>
                                <th>TOTAL AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($my_request_supplies as $request)
                            <tr>
                                <td>
                                    <input type="text" class="form-control" value="{{ $request->name }}" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="{{ $request->request_quantity }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="₱{{ number_format($request->inv_unit_price, 2) }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="₱{{ number_format($request->inv_unit_total_price, 2) }}" readonly>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                
                </div>

                <div class="row justify-content-center">
                  
                    <div class="col-md-2 text-center">
                        <p>Requested by:</p>
                        
                        @if (!empty($my_request_supplies_details->requested_signature))
                            <img src="{{ asset($my_request_supplies_details->requested_signature) }}" alt="HR Signature" 
                                 style="width: 100%; height: auto; margin-bottom: 10px;">
                        @else
                            <p>No signature available</p>
                        @endif
                        <b>{{ $my_request_supplies_details->requested_last_name }}, {{ $my_request_supplies_details->requested_first_name }} {{ $my_request_supplies_details->requested_middle_name }}</b>
                        <hr style="width: 50%; border-color: #333; margin: 10px auto;">
                    </div>
                
               
                    <div class="col-md-2 text-center">
                      <p>Recommending Approval:</p>
                      
                      @if (!empty($my_request_supplies_details->approved_signature))
                          <img src="{{ asset($my_request_supplies_details->approved_signature) }}" alt="HR Signature" 
                               style="width: 100%; height: auto; margin-bottom: 10px;">
                      @else
                          <p>No signature available</p>
                      @endif
                      <b>{{ $my_request_supplies_details->approved_last_name }}, {{ $my_request_supplies_details->approved_first_name }} {{ $my_request_supplies_details->approved_middle_name }}</b>
                      <hr style="width: 50%; border-color: #333; margin: 10px auto;">
                  </div>
                
                
                    <div class="col-md-2 text-center">
                        <p>Approved By:</p>
                        
                        <img src="{{ asset('path/to/signature.png') }}" alt="Signature" 
                             style="width: 50%; height: auto; margin-bottom: 10px; display: none;"> 
                        
                        <hr style="width: 50%; border-color: #333; margin: 10px auto;">
              
                    </div>
                
              
                    <div class="col-md-2 text-center">
                        <p>Received By:</p>
                        
                        <img src="{{ asset('path/to/signature.png') }}" alt="Signature" 
                             style="width: 50%; height: auto; margin-bottom: 10px; display: none;"> 
                        
                        <hr style="width: 50%; border-color: #333; margin: 10px auto;">
                        
                    </div>
                </div>
                

                <div class="row justify-content-between">
                    <div class="col-md-2 text-center">
                        <p>Confirmed as to Budget:</p>
                    
                        {{-- @if (!empty($finance_head->signature))
                            <div style="display: flex; justify-content: center; margin-top: 10px;">
                                <img src="{{ asset($my_request_supplies_details->approved_signature) }}" alt="HR Signature" 
                                     style="width: 100%; height: auto;">
                            </div>
                        @else
                            <p></p>
                        @endif --}}
                    
                        <b style="display: block; margin-top: 5px;">
                            @if(isset($finance_head) && $finance_head)
                            {{ $finance_head->last_name }}, {{ $finance_head->first_name }} {{ $finance_head->middle_name }}
                        @else
                            
                        @endif
                        </b>
                    
                        <hr style="width: 60%; border: 1px solid #333; margin: 5px auto;">
                    
                        <p style="margin-top: -5px;">Finance Head</p>
                    </div>
                    
                    <div class="col-md-2 text-center">
                        <p>Received for P.O:</p>
                        {{-- @if (!empty($pc->signature))
                            <div style="display: flex; justify-content: center; margin-top: 10px;">
                                <img src="{{ asset($my_request_supplies_details->approved_signature) }}" alt="HR Signature" 
                                     style="width: 100%; height: auto;">
                            </div>
                        @else
                            <p></p>
                        @endif --}}
                    
                        <b style="display: block; margin-top: 5px;">
                            {{ $pc->last_name }}, {{ $pc->first_name }} {{ $pc->middle_name }}
                        </b>
                    
                        <hr style="width: 60%; border: 1px solid #333; margin: 5px auto;">
                    
                        <p style="margin-top: -5px;">Properly Custodian</p>
                    </div>
                </div>


                <br>
            </form>
        </div>
        
        
        


        
          
		  



@stop

@section('scripts')
<script>

</script>

@stop





