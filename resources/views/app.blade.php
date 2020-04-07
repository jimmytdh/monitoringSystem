<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Jimmy Parker">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('/images') }}/favicon.png" sizes="16x16" type="image/png">
    <title>Covid-19 Monitoring System</title>
    <!-- Custom styles for this template -->
    <link href="{{ url('/css') }}/bootstrap.css" rel="stylesheet">
    <link href="{{ url('/css') }}/font-awesome.css" rel="stylesheet">
    <link href="{{ url('/css') }}/loader.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/plugins/bootstrap-editable/css/bootstrap-editable.css') }}">
    @yield('css')

    <style>
        fieldset {
            margin-top: 12px;
            border: 1px solid #39c;
            padding: 12px;
            -moz-border-radius: 8px;
            border-radius: 8px;
        }
        legend {
            color: #39c;
            font-style: italic;
            padding-left: 12px;
            padding-right: 12px;
            font-size:0.9em;
            width: auto !important;
        }
    </style>
</head>

<body>
<div id="loader-wrapper">
    <div id="loader"></div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Monitoring<font class="text-yellow"> COVID-19</font></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item {{ ($menu=='home') ? 'active':'' }}">
                    <a class="nav-link" href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                </li>
                <li class="nav-item dropdown {{ ($menu=='patients') ? 'active':'' }}">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <i class="fa fa-users"></i> Manage Patients
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {{ (isset($sub) && $sub=='patients') ? 'active':'' }}" href="{{ url('/patients') }}"><i class="fa fa-stethoscope"></i> Patients</a>
                        <a class="dropdown-item {{ (isset($sub) && $sub=='admit') ? 'active':'' }}" href="{{ url('/admitted') }}"><i class="fa fa-wheelchair"></i> In-Patient</a>
                    </div>
                </li>
                <li class="nav-item {{ ($menu=='report') ? 'active':'' }}">
                    <a class="nav-link" href="{{ url('/report') }}"><i class="fa fa-print"></i> Generate Report</a>
                </li>
                <li class="nav-item dropdown {{ ($menu=='lib') ? 'active':'' }}">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <i class="fa fa-book"></i> Library
                    </a>
                    <div class="dropdown-menu">
{{--                        <a class="dropdown-item {{ (isset($sub) && $sub=='brgy') ? 'active':'' }}" href="{{ url('/library/brgy') }}"><i class="fa fa-map"></i> Barangay</a>--}}
{{--                        <a class="dropdown-item {{ (isset($sub) && $sub=='muncity') ? 'active':'' }}" href="{{ url('/library/muncity') }}"><i class="fa fa-map"></i> Municipality/City</a>--}}
{{--                        <a class="dropdown-item {{ (isset($sub) && $sub=='province') ? 'active':'' }}" href="{{ url('/library/province') }}"><i class="fa fa-map"></i> Province</a>--}}
                        <a class="dropdown-item {{ (isset($sub) && $sub=='brgy') ? 'active':'' }}" href="{{ url('/library/brgy') }}"><i class="fa fa-map"></i> Barangay</a>
                        <a class="dropdown-item {{ (isset($sub) && $sub=='comorbid') ? 'active':'' }}" href="{{ url('/library/comorbid') }}"><i class="fa fa-wheelchair"></i> Co-Morbidity</a>
                        <a class="dropdown-item {{ (isset($sub) && $sub=='charges') ? 'active':'' }}" href="{{ url('/library/charges') }}"><i class="fa fa-money"></i> Manage Charges</a>
                        <a class="dropdown-item {{ (isset($sub) && $sub=='services') ? 'active':'' }}" href="{{ url('/library/services') }}"><i class="fa fa-stethoscope"></i> Services</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <i class="fa fa-gears"></i> Settings
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ url('/user/password') }}"><i class="fa fa-lock mr-1"></i> Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('/logout') }}"><i class="fa fa-sign-out mr-1"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header -->
<header class="bg-success py-3 mb-5">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-12">
                <div class="banner mt-5">
                    <img src="{{ url('/images') }}/banner.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Page Content -->
<div class="wrapper pb-5">
    <div class="container">
        <div class="loading"></div>
        @yield('body')
    </div>
</div>

@yield('modal')
<!-- /.container -->
<!-- Footer -->
<footer class="py-md-3 bg-dark footer">
    <div class="container">
        <font class="text-white">Copyright &copy; TDH iHOMP 2020</font>
    </div>
    <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src="{{ url('/js') }}/jquery.min.js"></script>
<script src="{{ url('/js') }}/bootstrap.bundle.min.js"></script>
<script src="{{ url('/plugins/bootstrap-editable/js/bootstrap-editable.min.js') }}"></script>
@yield('js')

<script>
    $(document).ready(function(){

        $(".btn-upload").click(function(){
            $("#loader-wrapper").css('visibility','visible');
            $(this).addClass('disabled');
        });

        $("a[href='#taxEmployee']").on('click',function(){
            $(".taxContent").html('Loading...').load("{{ url('/load/employee/year') }}");
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.fn.editable.defaults.mode = 'popup';

    $(document).ready(function() {
        $('#year').editable({
            type: 'number',
            name: 'year',
            pk: 1,
            url: "{{ url('/library/year') }}",
            success: function(data,value){
                $("#loader-wrapper").css('visibility','visible');
                window.location.reload();
            }
        });
    });
</script>
</body>

</html>
