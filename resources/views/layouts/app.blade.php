<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- oncontextmenu="return false" --}}
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">        

    <meta name="viewport" content=" width=1024, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>ESSENTIEL | Cashier</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style type="text/css">
        html{
            height: 100% !important;
        }
    </style>
    
<!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/all.min.css')}}">
    {{-- <script type="text/javascript" src="{{asset('assets/point_of_sale.assets.js')}}"></script> --}}
    <script type="text/javascript" src="{{asset('assets/en_US')}}"></script>
    <script type="text/javascript" src="{{asset('assets/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/adminlte.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/alertifyjs/alertify.min.js')}}"></script>
    
    
    
    @if(DB::table('schoolinfo')->first()->cashierversion == 1)
        <link rel="shortcut icon" sizes="196x196" href="{{asset('assets/img/touch-icon-196.png')}}">
        <link rel="shortcut icon" sizes="128x128" href="{{asset('assets/img/touch-icon-128.png')}}">
        <link rel="apple-touch-icon" href="{{asset('assets/img/touch-icon-iphone.png')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/touch-icon-ipad.png')}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/img/touch-icon-iphone-retina.png')}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{asset('assets/img/touch-icon-ipad-retina.png')}}">
    @endif

    <link rel="shortcut icon" href="{{asset('assets/ckicon.ico')}}" type="image/x-icon"/>

    <style> body { background: #222; } </style>
   
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/adminlte.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/alertifyjs/css/alertify.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/alertifyjs/css/themes/bootstrap.min.css')}}">


    @if(DB::table('schoolinfo')->first()->cashierversion == 1)
        {{-- <link href="{{asset('assets/all.min.css')}}" rel="stylesheet"> --}}
        <link href="{{asset('web/static/lib/fontawesome/css/font-awesome.css')}}" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="{{asset('assets/css/point_of_sale.assets.css')}}">
        <link href="{{asset('assets/css/chrome50.css')}}" rel="stylesheet" type="text/css">
        <link type="text/css" href="{{asset('assets/css/customer_facing_display.css')}}" rel="stylesheet">
        <link href="{{asset('assets/css/keyboard.css')}}" rel="stylesheet" type="text/css">
        {{-- <link href="{{asset('assets/css/pos.css')}}" rel="stylesheet" type="text/css"> --}}
        <link href="{{asset('assets/css/pos_receipts.css')}}" rel="stylesheet" type="text/css">
    @elseif(DB::table('schoolinfo')->first()->cashierversion == 2)
        <link href="{{asset('assets/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/fontawesome-free-6.1.1-web/css/all.min.css')}}" rel="stylesheet">
    @endif

    @yield('jsUP') 
  </head>
  <body class="overflow-hidden">

    @yield('content')

    @if(DB::table('schoolinfo')->first()->cashierversion == 1)
        @include('ecashierjs')
    @else
        @include('ecashierjsV2')
    @endif

    {{-- @include('ecashierjs') --}}

    @include('include.js.reports')
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

  </body>
</html>
<script type="text/javascript" src="{{asset('assets/jquery.number.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('assets/ecashier.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('assets/js/bootstrap.bundle.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/jquery.inputmask.bundle.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/sweetalert2.all.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/daterangepicker.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('assets/fontawesome-free-6.1.1-web/js/all.min.js')}}"></script> --}}



@yield('modal')
@yield('js')