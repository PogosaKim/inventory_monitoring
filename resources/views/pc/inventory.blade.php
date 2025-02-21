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

		@include('pc_sidebar')

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
                  <h5 class="mb-2 mb-md-0">INVENTORY</h5>
                </div>
              </div>
            </div>
          </div>


          <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">Supplies</h5>
                    <div class="col-auto d-flex order-md-0"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addItemModal"> <span class="fas fa-plus me-2"></span> Add Item</button></div>
                </div>
                <table id="inventoryTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>BRAND</th>
                            <th>DESCRIPTION / TYPE</th>
                            <th>AMOUNT </th>
                            <th>QUANTITY</th>
                            <th>UNIT</th>
                            <th>TOTAL AMOUNT</th>
                            <th>LOCATION</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                </table>
            </div>
        </div>



        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- Large modal -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addInventory">
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Item Name</label>
                                <select name="inv_name_id" id="inv_name_id" class="form-control">
                                    <option value="0">--Select--</option>
                                    @foreach ($inventory_name_list as $inventory_name)
                                        <option value="{{ $inventory_name->id }}">{{ $inventory_name->name }}</option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Brand</label>
                                        <input type="text" class="form-control" name="inv_brand" id="inv_brand" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Description / Type</label>
                                        <input type="text" class="form-control" name="inv_desc" id="inv_desc">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Amount</label>
                                        <input type="number" class="form-control" name="inv_amount" id="inv_amount" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="md-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" name="inv_quantity" id="inv_quantity" min="1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <input type="text" class="form-control" name="inv_unit" id="inv_unit" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="div-md-3">
                                        <label for="datePurchase" class="form-label">Total Amount</label>
                                        <input type="number" class="form-control" name="inv_total_amount" id="inv_total_amount" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="div-md-3">
                                        <label for="datePurchase" class="form-label">Location</label>
                                        <input type="text" class="form-control" name="inv_location" id="inv_location" required>
                                    </div>
                                </div>
                            </div>
        

                            <button type="button" class="btn btn-primary px-4" id="submit">
                                <span class="fa fa-save"></span> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="modal fade" id="updateItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Update Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateInventory">
                            <input type="hidden" name="id" id="id" value="" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Item Name</label>
                                <select name="inv_name_id" id="inv_name_id" class="form-control">
                                    <option value="0">--Select--</option>
                                    @foreach ($inventory_name_list as $inventory_name)
                                        <option value="{{ $inventory_name->id }}">{{ $inventory_name->name }}</option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Brand</label>
                                        <input type="text" class="form-control" name="inv_brand" id="inv_brand" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Description / Type</label>
                                        <input type="text" class="form-control" name="inv_desc" id="inv_desc">
                                    </div>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Amount</label>
                                        <input type="number" class="form-control" name="inv_amount" id="inv_amount_update" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" name="inv_quantity" id="inv_quantity_update" min="1" required>
                                    </div>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <input type="text" class="form-control" name="inv_unit" id="inv_unit" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="datePurchase" class="form-label">Total Amount</label>
                                        <input type="number" class="form-control" name="inv_total_amount" id="inv_total_amount_update" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="datePurchase" class="form-label">Location</label>
                                        <input type="text" class="form-control" name="inv_location" id="inv_location" required>
                                    </div>
                                </div>
                            </div>
        
                            <button type="button" class="btn btn-primary px-4" id="updateSubmit">
                                <span class="fa fa-save"></span> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        




       


        
          
		  



@stop

@section('scripts')
<script>
 var oTable;
$(document).ready(function() {

    function calculateTotalAmount() {
        let amount = parseFloat(document.getElementById('inv_amount').value) || 0;
        let quantity = parseFloat(document.getElementById('inv_quantity').value) || 0;
        let totalAmount = amount * quantity;

        document.getElementById('inv_total_amount').value = totalAmount.toFixed(2); 
    }

    document.getElementById('inv_amount').addEventListener('input', calculateTotalAmount);
    document.getElementById('inv_quantity').addEventListener('input', calculateTotalAmount);


    function calculateTotalAmountUpdate() {
    let amount = parseFloat(document.getElementById('inv_amount_update').value) || 0;
    let quantity = parseFloat(document.getElementById('inv_quantity_update').value) || 0;
    let totalAmount = amount * quantity;

        document.getElementById('inv_total_amount_update').value = totalAmount.toFixed(2);
    }

    document.getElementById('inv_amount_update').addEventListener('input', calculateTotalAmountUpdate);
    document.getElementById('inv_quantity_update').addEventListener('input', calculateTotalAmountUpdate);
  
    oTable = $("#inventoryTable").DataTable({
        ajax: {
            url: "{{ url('pc/get_inventory') }}",
            type: "GET",
            data: function(d) { 
            },
            dataSrc: "",
        },
        columns: [
            {
                data: 'name'
            },
            {
                data: 'inv_brand'
            },
            {
                data: 'inv_desc'
            },
            {
                data: 'inv_amount'
            },
            {
                data: 'inv_quantity'
            },
            {
                data: 'inv_unit'
            },
            {
                data: 'inv_total_amount'
            },
            {
                data: 'inv_location'
            },
            {
                data: 'action'
            },

            ],
           
        });
    //   	oTable.on("click", ".viewDetail", function() {
    //     const person_id = $(this).data("person_id");
    //     const url = "{{ url('admin/student_profile') }}?person_id="+person_id;
    //     window.open(url);
    //   });

            $("#updateItemModal").on("shown.bs.modal", function(e) {
            const button = $(e.relatedTarget);
            var inventory_list = button.data("inventory_list");

            if (inventory_list !== undefined) {
                inventory_list = JSON.parse(atob(inventory_list));

                $("#updateInventory").find("#id").val(inventory_list.inventory_id);
                $("#updateInventory").find("#inv_name_id").val(inventory_list.inv_name_id);
                $("#updateInventory").find("#inv_unit").val(inventory_list.inv_unit);
                $("#updateInventory").find("#inv_quantity_update").val(inventory_list.inv_quantity);
                $("#updateInventory").find("#inv_brand").val(inventory_list.inv_brand);
                $("#updateInventory").find("#inv_desc").val(inventory_list.inv_desc);
                $("#updateInventory").find("#inv_amount_update").val(inventory_list.inv_amount);
                $("#updateInventory").find("#inv_total_amount_update").val(inventory_list.inv_total_amount);
                $("#updateInventory").find("#inv_location").val(inventory_list.inv_location);
                calculateTotalAmountUpdate();
            }
        });


        $('#submit').on('click', function(e) {
            e.preventDefault();

            const formData = new FormData(document.getElementById('addInventory'));approvedBtn
            var btn = $(this);
            var html = $(this).html();

            $.ajax({
                url: "{{ url('pc/create_inventory') }}",
                type: "POST",
                data: formData,
                processData: false,  
                contentType: false, 
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Iventory Update successfully!',
                        }).then(() => {
                            $('#addItemModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to create  the term. Please try again.',
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


        $('#updateSubmit').on('click', function(e) {
            e.preventDefault();

            var btn = $(this);
            var html = btn.html();

            btn.html('<span class="fa fa-spinner fa-spin"></span> Saving...');

            const formData = new FormData(document.getElementById("updateInventory"));
            
            $.ajax({
                url: "{{ url('pc/update_inventory') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    btn.html(html);

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Inventory updated successfully!',
                        }).then(() => {
                            $('#updateItemModal').modal('hide');
                            oTable.ajax.reload(); 
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update the inventory. Please try again.',
                        });
                    }
                },
                error: function(xhr) {
                    btn.html(html); 
                    
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


         
        oTable.on("click", ".deleteBtn", function() {
            const inventory_id = $(this).data("inventory_id");

            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('pc/destroy') }}",
                        type: "POST",
                        data: {
                            inventory_id: inventory_id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Inventory deleted successfully!',
                                }).then(() => {
                                    oTable.ajax.reload(); 
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Failed to delete the inventory. Please try again.',
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


      


});

</script>

@stop





