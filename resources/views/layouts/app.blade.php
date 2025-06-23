<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Accuflo') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href=" {{ asset('assets/dist/css/adminlte.min.css') }}">

  <link rel="stylesheet" href="{{asset('assets/css/accordion.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/collapse.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/dropdown.css')}}">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
  <style type="text/css">
    .card, input, select, ul.select2-results__options li, li.breadcrumb-item, textarea{
      font-size: 14px !important
    }
    .nav-treeview>.nav-item>.nav-link.active, [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:focus, [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:hover{
      background-color: #82c2ed !important;
      color: #fff;
    }
    .nav-treeview>.nav-item>.nav-link.active{
      color: #000 !important;
    }
    .spinner-overlay {
      width:100%;
      height:100%;
      position:fixed;
      top:0;
      left:0;
      z-index:2000;
      background-color: rgb(0,0,0);
      background-color: rgba(0,0,0,0.4);
      display:none;
    }
    .spinner-border-position{
      position:fixed;
      top:40%;
      left:50%
    }
  </style>
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
      @include('layouts.topnav')
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      @include('layouts.navigation')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        @include('includes.flash-message')
        @yield('content')
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->
      @include('layouts.control')
      <!-- /.control-sidebar -->

      <!-- Main Footer -->
      <div class="spinner-overlay">
        <div class="spinner-border spinner-border-position m-5" role="status">
            <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<!-- custom js -->
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('assets/js/custom.js') }}"></script> --}}
<script type="text/javascript" src="{{ asset('assets/js/countable-unit-change.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/countable-unit-id-change.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/density-unit-id-change.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/density-weight-id-change.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/empty-weight-change.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/empty-weight-id-change.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/full-weight-change.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/full-weight-id-change.js') }}"></script>


@stack('script')
</body>
</html>
