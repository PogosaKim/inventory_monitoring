@extends('site/layouts/main')
@stop


@section('content')

<style>
  .holiday {
    background-color: red !important;
    color: white !important;
  }

</style>

<div class="container-fluid background_sidebar">
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
                        <p class="dropdown-item">Hi Property Custodian, {{ Auth::user()->name }}</p>
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
                  <h5 class="mb-2 mb-md-0">Welcome Back Property Custodian!</h5>
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
                <h5 style="font-size:18px;">PURCHASE ORDER FORM</h5>
            </div>
        
         
            <form id="teacherRequestForm" style="margin: 20px;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="d-flex justify-content-between mb-3">
                    <div style="flex-basis: 48%;">Thru:   
                        <select name="user_role_id" id="user_role_id" class="form-control">
                            <option value="{{ $role->id }}"> {{ $role->name }}</option>
                        </select>
                    </div>                    
                    <div style="flex-basis: 48%;">Date: <input type="date" name="date" class="form-control" id="date-input" /></div>
                </div>
        
            
                <div class="d-flex justify-content-start mb-3">
                    <div style="flex-basis: 48%;">From (DEPT.): 
                        {{-- <select name="school_department_id" id="school_department_id" class="form-control">
                            <option value="{{ $teacher->school_department_id }}"> {{ $teacher->name }}</option>
                        </select> --}}
                    </div>
                                       
                </div>
        
               
                <div class="mb-4">
                    <table class="table table-bordered">
                        <button type="button" id="add-row" class="btn btn-primary" style="margin-bottom:10px; margin-left:90%;">Add</button>
                        <thead>
                            <tr>
                                <th>ITEM</th>
                                <th>QUANTITY</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="inventory_id[]" id="inventory_id" class="form-control">
                                        <option value="0">--Select--</option>
                                        @foreach ($inventory_list as $inventory)
                                            <option value="{{ $inventory->inventory_id }}"> {{ $inventory->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="request_quantity[]" id="request_quantity" class="form-control"  min="1" /></td>
                                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td> 
                            </tr>
                        </tbody>
                    </table>
                
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-2 text-center">
                        <p>Requested by: </p>
                        TEACHER 
                        <hr style="width: 50%; border-color: #333; margin: 10px auto;">
                    </div>
                    <div class="col-md-2 text-center">
                        <p>Recommending Approval: </p>
                        DEAN
                        <hr style="width: 50%; border-color: #333; margin: 10px auto;">
                    </div>
                    <div class="col-md-2 text-center">
                        <p>Approved By: </p>
                        SP / FH
                        <hr style="width: 50%; border-color: #333; margin: 10px auto;">
                    </div>
                    <div class="col-md-2 text-center">
                        <p>Recieved By:</p>
                        PC
                        <hr style="width: 50%; border-color: #333; margin: 10px auto;">
                    </div>
                </div>

                <div class="row justify-content-between">
                    <div class="col-md-2 text-center">
                        <p>Confirmed as to Budget:</p>
                    
                        @if (!empty($finance_head->signature))
                            <div style="display: flex; justify-content: center; margin-top: 10px;">
                                <img src="{{ asset($person->signature) }}" alt="HR Signature" 
                                     style="width: 60%; height: auto;">
                            </div>
                        @else
                            <p></p>
                        @endif
                    
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
                        @if (!empty($pc->signature))
                            <div style="display: flex; justify-content: center; margin-top: 10px;">
                                <img src="{{ asset($person->signature) }}" alt="HR Signature" 
                                     style="width: 60%; height: auto;">
                            </div>
                        @else
                            <p></p>
                        @endif
                    
                        <b style="display: block; margin-top: 5px;">
                            {{ $pc->last_name }}, {{ $pc->first_name }} {{ $pc->middle_name }}
                        </b>
                    
                        <hr style="width: 60%; border: 1px solid #333; margin: 5px auto;">
                    
                        <p style="margin-top: -5px;">Properly Custodian</p>
                    </div>
                </div>

              
                
                
                
                

               
                
                
                
             
                <div class="text-center">
                    <button class="btn btn-primary" id="requestSubmit">Submit Request</button>
                </div>

                <br>
            </form>
        </div>
        

       


        
          
		  



@stop

@section('scripts')
<script>

    document.getElementById('date-input').value = new Date().toISOString().split('T')[0];
 
 $(document).ready(function() {
    $('#inventory_id').select2({
            placeholder: "--Select--",
            allowClear: true
        });
    $('#add-row').on('click', function() {
    var newRow = `<tr>
                    <td>
                        <select name="inventory_id[]" class="form-control">
                            <option value="0">--Select--</option>
                            @foreach ($inventory_list as $inventory)
                                <option value="{{ $inventory->inventory_id }}">{{ $inventory->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="request_quantity[]" class="form-control" min="1" /></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>`;
            $('table tbody').append(newRow);
            toggleRemoveButtonState();
        });

        $('table').on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            toggleRemoveButtonState();
        });

        function toggleRemoveButtonState() {
            if ($('table tbody tr').length > 1) {
                $('table tbody tr:first-child .remove-row').prop('disabled', false);
            } else {
                $('table tbody tr .remove-row').prop('disabled', true);
            }
        }

        
        $('#requestSubmit').on('click', function(e) {
            e.preventDefault();

            var isValid = true;
            $('table tbody tr').each(function() {
                var inventoryId = $(this).find('select[name="inventory_id[]"]').val();
                var quantity = $(this).find('input[name="request_quantity[]"]').val();
                
                if (inventoryId == '0' || quantity <= 0) {
                    isValid = false;
                    $(this).addClass('bg-warning');  
                } else {
                    $(this).removeClass('bg-warning');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Data',
                    text: 'Please fill in all the fields before submitting.',
                });
                return;  
            }

            const formData = new FormData(document.getElementById('teacherRequestForm'));
            var btn = $(this);
            var html = $(this).html();

            $.ajax({
                url: "{{ url('pc/create_request') }}",
                type: "POST",
                data: formData,
                processData: false,  
                contentType: false, 
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Request successfully submitted!',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to create the request. Please try again.',
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





