<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Inicia sesion | Frontera Sur</title>
      <link rel="icon" type="image/png" href="{{ asset('img/logo_frontera.png') }}">
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="../../assets/images/favicon.ico">
      <link rel="stylesheet" href="{{ asset('css/cssreportes/libs.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/cssreportes/hope.css') }}">
      <link rel="stylesheet" href="{{ asset('css/cssreportes/custom.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/cssreportes/dark.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/cssreportes/customizer.min.css') }}"> 
      <link rel="stylesheet" href="{{ asset('css/cssreportes/rtl.min.css') }}"> 
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
      
      
  </head>
  <body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
   <div id="loading">
      <div class="loader simple-loader">
         <div class="loader-body">
         </div>
      </div>    
   </div>
    <!-- loader END -->
    
   <div class="wrapper">
      <section class="login-content">
         <div class="row m-0 align-items-center bg-white vh-100">            
            <div class="col-md-6">
               <div class="row justify-content-center">
                  <div class="col-md-10">
                     <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                        <div class="card-body">
                           <a href="" class="navbar-brand d-flex align-items-center mb-3">
                              
                              <!--Logo start-->
                              <div class="logo-main">
                                  <div class="logo-normal">
                                    <img style="width: 115px; display:flex; height:100px;  " src="{{asset('img/logo_frontera.png')}}" alt="">
                                  </div>
                                  <div class="logo-mini">
                                    <img style="width: 45px; display:flex;  " src="{{asset('img/logo_frontera.png')}}" alt="">
                                  </div>
                              </div>
                              <!--logo End-->
                        
                              <!--<h4 class="logo-title ms-3">Acerinox</h4>-->
                           </a>
                           <h2 class="mb-2 text-center">Iniciar Sesion</h2>
                           <p class="text-center">Sistema De Control Inteligente.</p>
                           <p class="text-center" style="color: #1e4cff; font-size:12px;">@VSS MATERIALES DE CONSTRUCCION SA DE CV</p>
                           <form id="login_form" action="{{route('validar.login')}}"  method="POST" class="mt-4" autocomplete="on" >
                                @csrf
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="nombre_sistema" class="form-label">Nombre usuario</label>
                                       <input type="text" name="nombre_sistema" class="form-control" id="nombre_sistema" aria-describedby="nombre_sistema" placeholder="user123" required>
                                       
                                      <div class="invalid-feedback" id="usuario_no_encontrado"></div>
                                    </div>
                                    
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Contraseña</label>
                                       <input type="password" name="password" class="form-control" id="password" aria-describedby="password" placeholder="**********" required>
                                       <div class="invalid-feedback" id="contraseña_incorrecta"></div>
                                    </div>
                                 </div>
                                 <div class="col-lg-12 d-flex justify-content-between">
                                    <div class="form-check mb-3">
                                       <input type="checkbox" class="form-check-input" id="customCheck1">
                                       <label class="form-check-label" for="customCheck1">Recordarme? </label>
                                    </div>
                                     <a href="/choferes/registrar">Crear un chofer?</a>
                                 </div>
                              </div>
                              <div class="d-flex justify-content-center">
                                 <button type="submit" class="btn btn-primary" id="logout_msj">Ingresar</button>
                              </div>
                            
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sign-bg">
                  <img src="{{asset('img/logo_frontera.png')}}" alt="" style="width: 200px; height: 170px; opacity:0.35" >  
               </div>
            </div>
            <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
               <img src="{{asset('img/tienda_5ta.jpeg')}}" class="img-fluid gradient-main " alt="images">
            </div>
         </div>
      </section>
      </div>
    
      <script src="{{ asset('js/jsreportes/libs.min.js') }}"></script>
      <script src="{{ asset('js/jsreportes/external.min.js') }}"></script>
      <script src="{{ asset('js/jsreportes/widgetcharts.js') }}"></script>
      <script src="{{ asset('js/jsreportes/widgetcharts.js') }}"></script>
      <script src="{{ asset('js/jsreportes/vectore-chart.js') }}"></script>
      <script src="{{ asset('js/jsreportes/dashboard.js') }}"></script>
      <script src="{{ asset('js/jsreportes/fslightbox.js') }}"></script>
      <script src="{{ asset('js/jsreportes/slider-tabs.js') }}"></script>
      <script src="{{ asset('js/jsreportes/form-wizard.js') }}"></script>
      <script src="{{ asset('js/jsreportes/aos.js') }}"></script>
      <script src="{{ asset('js/jsreportes/hope-ui.js') }}"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        $(document).ready(function() {
            $('#login_form').on('submit', function(e) {
            e.preventDefault(); 

            $('#usuario_no_encontrado').html('');
            $('#contraseña_incorrecta').html('');

            const btn =$('#logout_in');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Iniciando sesion...');
            
            var formdata = $(this).serialize(); 
            console.log(formdata);
            $.ajax({
                url: '/validar-login',
                type: 'get',
                data: formdata,
                success: function(response) {
                    console.log(response);
                    // MENSAGE DE EXITO DE INICIO DE SESION 
                    Toastify({
                        text: response.message, 
                        duration: 2000, 
                        gravity: "top", 
                        position: "center", 
                        backgroundColor: "linear-gradient(to right, #296be7, #638fe2)", 
                    }).showToast();

                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);
                },
                error: function(xhr) {
                    //console.log(xhr.responseText); 
                    if (xhr.responseJSON.usuario_no_encontrado) {
                        $('#usuario_no_encontrado')
                            .html(xhr.responseJSON.usuario_no_encontrado)
                            .show();     
                    }if(xhr.responseJSON.password_incorrecta) {
                        $('#contraseña_incorrecta')
                            .html(xhr.responseJSON.password_incorrecta)
                            .show();       
                    }if(xhr.responseJSON.usuario_inactivo) {
                        $('#usuario_no_encontrado')
                            .html(xhr.responseJSON.usuario_inactivo)
                            .show();       
                    }
                    // Habilitar el botOn nuevamente
                     btn.prop('disabled', false).html('Ingresar');
                  }
               });
            });
        });
    </script>
  </body>
</html>

   