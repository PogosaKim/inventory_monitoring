<!DOCTYPE html>

<html lang="en">

<head id="Starter-Site">
    <link href="{{asset('assets/site/css/flash.css')}}" rel="stylesheet">
    <script src="{{asset('assets/site/js/pace.min.js')}}"></script>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Monitoring</title>
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="description" content="" />
    <meta name="google-site-verification" content="">
    <meta name="DC.title" content="Laravel 5 Starter Site">
    <meta name="DC.subject" content="">
    <meta name="DC.creator" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    @yield('styles')

    
    <script src="{{asset('assets_test/js/config.js')}}"></script>
    <script src="{{asset('vendors/simplebar/simplebar.min.js')}}"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{asset('vendors/simplebar/simplebar.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/site/css/jquery.dataTables.css')}}" rel="stylesheet">
    <link href="{{asset('assets/site/css/jquery.jqplot.css')}}" rel="stylesheet">
    <link href="{{asset('assets_test/css/theme-rtl.min.css')}}" rel="stylesheet" id="style-rtl">
    <link href="{{asset('assets_test/css/theme.min.css')}}" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets_test/css/user-rtl.min.css') }}" rel="stylesheet" id="user-style-rtl">
    
    <link href="{{ asset('assets_test/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
    <link href="{{ asset('assets/site/datatables/cdn.datatables.net_1.13.6_css_jquery.dataTables.min.css') }}" rel="stylesheet" id="user-style-default">
    <link href="{{asset('assets/site/fullcalendar/dist/fullcalendar.css')}}" rel="stylesheet">
    <link href="{{asset('css/iziToast.min.css') }}"  rel="stylesheet"> 
    <link href="{{asset('vendors/flatpicker/flatpickr.min.css')}}"  rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="{{asset('vendors/fontawesome/all.min.css')}}" rel="stylesheet">

    {{-- <link rel="stylesheet" href="iziToast.min.css"/> --}}


    <script>
        var isRTL = JSON.parse(localStorage.getItem('isRTL'));
        if (isRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
        </script>

        <style>
              .logo-container {
                display: flex;
                justify-content: center;
                align-items: center;
                }

    
            .logo-img {
            width: 70%;
            max-width: 200px; 
            }
            
        </style>

</head>
<body>
  
    <main class="main" id="top">
        @yield('content')
    </main>

    <script src="{{asset('assets/site/js/jquery-2.1.1.min.js')}}"></script>
    {{-- <script src="{{asset('assets_test/js/theme.js')}}"></script> --}}
    <script src="{{asset('vendors/popper/popper.min.js')}}"></script>
    <script src="{{asset('vendors/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('vendors/anchorjs/anchor.min.js')}}"></script>
    <script src="{{asset('vendors/is/is.min.js')}}"></script>
    <script src="{{asset('vendors/dayjs/dayjs.min.js')}}"></script>
    <script src="{{asset('vendors/flatpicker/flatpickr.js')}}"></script>
    <script src="{{asset('vendors/fullcalendar/main.min.js.js')}}"></script>
    <script src="{{asset('vendors/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('vendors/fontawesome/all.min.js')}}"></script>
    <script src="{{asset('vendors/lodash/lodash.min.js')}}"></script>
    <script src="{{asset('assets_test/js/theme.js')}}"></script>
    <script src="{{asset('assets/site/chartist/chartist.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery-ui.1.11.2.min.js')}}"></script>
    <script src="{{asset('assets/site/js/plugins/metisMenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/site/js/sb-admin-2.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap-datepicker.min.js')}}"></script>         
    <script src="{{asset('assets/site/datatables/cdn.datatables.net_1.13.6_js_jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/site/js/moment.min.js')}}"></script> 
    <script src="{{asset('assets/site/js/flatpickr.js')}}"></script> 
    <script src="{{asset('assets/site/js/bootstrap-datetimepicker.min.js')}}"></script> 
    <script src="{{asset('assets/site/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('assets/site/js/bootstrap-dataTables-paging.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery.colorbox.js')}}"></script>
    <script src="{{asset('assets/site/js/jquery.numeric.js')}}"></script>
    <script src="{{asset('assets/site/js/list.js')}}"></script> 
    <script src="{{asset('assets/site/js/modal.js')}}"></script>
    <script src="{{asset('assets/site/js/SelectListChange.js')}}"></script> 
    <script src="{{asset('assets/site/js/typeahead.bundle.js')}}"></script>
    <script src="{{asset('assets/site/js/select2.js')}}"></script>
    <script src="{{asset('assets/site/js/fullcalendar.min.js')}}"></script> 
    <script src="{{asset('assets/site/js/jquery.print.js')}}"></script>
    <script src="{{asset('assets/site/js/jtheme.js')}}"></script>
    <script src="{{asset('assets/site/theme.js')}}"></script>
    <script src="{{asset('assets/site/js/handsontable.full.js')}}"></script>  
    <script src="{{asset('assets/site/js/sweetalert2.min.js')}}"></script> 
    <script src="{{asset('assets/site/js/jquery.dragable.js')}}"></script> 
    <script src="{{asset('assets/site/canvas/jquery.canvasjs.min.js')}}"></script> 
    <script src="{{asset('js/iziToast.min.js')}}"></script>
    <script src="{{asset('assets/site/fullcalendar/dist/fullcalendar.min.js')}}"></script> 
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
        @yield('scripts')
    </body>
</html>




