<!doctype html>
<html lang="en" >
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>FRONTERA | PRUEBAS UNITARIAS</title>
      <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">
      
      <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    <link rel="stylesheet" href="{{ asset('css/cssreportes/hope.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

      
  </head>
  <body class="  ">
      @include('partials.navbarSuperior')
      @include('partials.navbarIzquierdo')
    
      @yield('content')

      @include('partials.configuraciones')
    
    <script src="{{ asset('js/jsreportes/libs.min.js') }}"></script>
    <script src="{{ asset('js/jsreportes/external.min.js') }}"></script>
    <script src="{{ asset('js/jsreportes/widgetcharts.js') }}"></script>
    <script src="{{ asset('js/jsreportes/vectore-chart.js') }}"></script>
  
    <script src="{{ asset('js/jsreportes/fslightbox.js') }}"></script>
    <script src="{{ asset('js/jsreportes/setting.js') }}"></script>
    <script src="{{ asset('js/jsreportes/slider-tabs.js') }}"></script>
    <script src="{{ asset('js/jsreportes/form-wizard.js') }}"></script>
    <script src="{{ asset('js/jsreportes/aos.js') }}"></script>
    <script src="{{ asset('js/jsreportes/hope-ui.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
  </body>
</html>
