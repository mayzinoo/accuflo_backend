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
   <link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <!-- Font Awesome Icons -->
   <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
   <!-- Theme style -->
   <link rel="stylesheet" href=" {{ asset('assets/dist/css/adminlte.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>

<body class="hold-transition sidebar-mini">

   <div class="wrapper">
      <img src="{{asset('images/logo.png')}}" alt="" srcset="" class="logo">
      <label class="name">Accuflo</label>
      <!-- Content Wrapper. Contains page content -->
      <div style="display: flex;height: 100vh;align-items: center;justify-content:center;">
      
         @yield('content')
      </div>

   </div>
   </div>
   <!-- ./wrapper -->

   <!-- REQUIRED SCRIPTS -->

   <!-- jQuery -->
   <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
   <!-- Bootstrap 4 -->
   <!-- AdminLTE App -->
   <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>

