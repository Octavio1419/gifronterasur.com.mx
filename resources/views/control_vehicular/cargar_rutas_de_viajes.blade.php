@extends('layout.index') <!-- Extiende del layout base -->
@section('content')
 
<main class="main-content">
    <div class="position-relative iq-banner">
            <!-- Nav Header Component Start -->
             <div class="iq-navbar-header" style="height: 215px;">
                 <div class="container-fluid iq-container">
                     <div class="row">
                         <div class="col-md-12">
                             <div class="flex-wrap d-flex justify-content-between align-items-center">
                                 <div>
                                     <h1>Carga tus rutas de viajes</h1>
                                     <p style="font-size: 20px" id="nombre_sucursal"></p>
                                 </div>
                                 <button  type="button" class="btn btn-info" id="cargar_rutas_vehiculos">Guardar datos <i class="fa-solid fa-floppy-disk"></i></button>
                             </div>
                             
                         </div>
                         
                     </div>
                 </div>
                 <div class="iq-header-img">
                     <img src="{{ asset('img/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">       
                 </div>
             </div>          <!-- Nav Header Component End -->
           <!--Nav End-->
         </div>
         <br>
         <br>
          <div class="container-fluid content-inner mt-n5 py-0">
            <div class="row">
               <div class="col-lg-12">
                  <div class="card rounded">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-sm-12">
                               <div class="row">
                     <div class="col-sm-12">    
                        <h4 class="mb-2">Selecciona el vehiculo al cual cargaras los viajes</h4>                              
                        <div class="form-group">
                            <br>
                            <select class="form-select mb-3 shadow-none" id="selector_vehiculos">
                                
                            </select>
                        </div>
                        <br>
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-primary" type="button" id="buscar_vehiculos">Buscar</button>
                        </div>
                     </div>
                  </div>
                       
                    </div>
                        </div>
                        <br>
                        <br>
                        <div class="row" style="display: none;" id="card_excel" >
                             <h4 class="mb-2">Selecciona tu archivo</h4>
                           <div class="col-sm-12 mt-4">
                              <div class="file-upload-area">
                                 <label for="file-upload" id="upload-label" class="drag-drop-label">
                                    Arrastra tu archivo Excel aquí o haz clic para buscar
                                 </label>
                                 <input type="file" id="file-upload" accept=".xls, .xlsx"  style="display: none;"/>
                              </div>

                              <br>
                              <br>
                            <div class="vista_previa" id="mostrar_vista_previa" style="display: none;" >
                                <h3>Vista previa del archivo precargado</h3>
                                <br>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table_remisiones">
                                    <thead id="columna_rutas">
                                    </thead>
                                    <tbody id="filas_rutas">
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>     
                        </div>
                     </div>
                  </div>
                
                  
               </div>
            </div>
         </div>  
         <div id="loading-spinner" style="display: none">
            <div class="spinner"></div>
           
            <p style="color">Espere porfavor...</p>
          </div>

    <style>
        #loading-spinner {
        position: fixed;
        top: 55%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 1000;
        }
    
        .spinner {
        width: 95px;
        height: 95px;
        border: 7px solid rgb(255, 255, 255);
        border-top: 6px solid #ff0000; /* Blue spinner */
        border-radius: 50%;
        animation: spin 1s linear infinite;
        }
    
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
             /*RECUPERANDO TODOS LOS VEHICULOS*/
                selector();
                var nombre_interno_vehiculo;
                
                function selector(){
                    /*rellendo los datos al selector*/
                fetch('/recuperar-vehiculo').then(response=>response.json()).then(respuesta=>{

                    let selectorVehiculos = document.getElementById('selector_vehiculos');
                    selectorVehiculos.innerHTML = '<option value="">Selecciona el vehiculo</option>';
                    let resp = respuesta.vehiculo;
                    console.log(resp);
                    resp.forEach(carros => {
                        let option = document.createElement('option');
                        option.value = carros.nombre_in_vehiculo;
                        option.textContent = carros.nombre_rel_vehiculo;
                        selectorVehiculos.appendChild(option);
                    });
                    $('#selector_vehiculos').val('').change();
                });  
            }

            $('#buscar_vehiculos').click(function(){
                nombre_interno_vehiculo = $('#selector_vehiculos').val();
                console.log('el cliente dio click al selector con el vehiculo', nombre_interno_vehiculo);

                if(nombre_interno_vehiculo){
                    const excel_card = document.getElementById('card_excel');
                    excel_card.style.display = 'flex';
                    const dropArea = document.querySelector('.file-upload-area');
                    const fileInput = document.getElementById('file-upload');
                    const label = document.getElementById('upload-label');

                    // Clic para abrir el selector
                    dropArea.addEventListener('click', () => {
                            fileInput.click();
                    });
                    // Manejo del input por clic
                    fileInput.addEventListener('change', (event) => {
                            const file = event.target.files[0];
                            handleFile(file);
                    });
                    // Evitar comportamiento por defecto en drag & drop
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                            dropArea.addEventListener(eventName, (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                            });
                    });
                    // Cambiar estilo al arrastrar encima (opcional)
                    dropArea.addEventListener('dragover', () => {
                            label.classList.add('highlight');
                    });
                    dropArea.addEventListener('dragleave', () => {
                            label.classList.remove('highlight');
                    });
                    dropArea.addEventListener('drop', (e) => {
                            label.classList.remove('highlight');
                            const file = e.dataTransfer.files[0];
                            handleFile(file);
                    });

                // Manejo del archivo
                function handleFile(file) {
                        const spinner = document.getElementById('loading-spinner');

                        if (!file) {
                            Swal.fire({
                                title: "Oops..!",
                                text: "No se seleccionó un archivo.",
                                icon: "error"
                            });
                            label.textContent = "Arrastra tu archivo Excel aquí o haz clic para buscar";
                            return;
                        }

                        label.textContent = `Archivo seleccionado: ${file.name}`;
                        console.log(`Archivo seleccionado: ${file.name}`);
                        if (spinner) spinner.style.display = "block";

                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const data = new Uint8Array(e.target.result);
                            const workbook = XLSX.read(data, { type: 'array' });

                            const sheetName = workbook.SheetNames[0];
                            const sheet = workbook.Sheets[sheetName];

                            const excel_rutas = XLSX.utils.sheet_to_json(sheet, { header: 1 });
                            console.log(excel_rutas);

                            if (spinner) spinner.style.display = "none";
                            vistaPredeterminada(excel_rutas);
                            cargar_excel(excel_rutas);
                            
                        };

                        reader.readAsArrayBuffer(file);

                        // Reseteo para permitir re-selección del mismo archivo
                        fileInput.value = '';
                }

                function vistaPredeterminada(data){
                    document.getElementById('columna_rutas').innerHTML = '';
                    document.getElementById('filas_rutas').innerHTML = '';
                    const view_previe = document.getElementById('mostrar_vista_previa');

                    view_previe.style.display = "block";

                    const headers = ['N°', 'Nombre','Imei','Model','Ignition', 'Fecha', 'Hora','Velocidad','Tipo posicion', 'tipo dato', 'coordenadas', 'direccion'];

                    let headerHTML = '<tr>';
                        headers.forEach((header)=>{
                            headerHTML += `<th>${header}</th>`;
                        });
                    headerHTML += '</tr>';
                    document.getElementById('columna_rutas').innerHTML = headerHTML;
                const rows = data.slice(2,10);
                console.log(rows);
                rows.forEach((row) => {
                    let rowHTML = '<tr>';
                    const datetime = row[9] || ''; // Tomamos el valor de la fecha y hora
                    const [fecha, hora] = datetime.split(' '); // Separamos la fecha y la hora
                    rowHTML += `<td>${row[0] || ''}</td>`;
                    rowHTML += `<td>${row[1] || ''}</td>`;
                    rowHTML += `<td>${row[2] || ''}</td>`;
                    rowHTML += `<td>${row[3] || ''}</td>`;
                    rowHTML += `<td>${row[8] || ''}</td>`;
                    rowHTML += `<td>${fecha || ''}</td>`; // Agregamos la fecha separada
                    rowHTML += `<td>${hora || ''}</td>`; // Agregamos la hora separada
                    rowHTML += `<td>${row[10] !== undefined ? row[10] : '0'}</td>`;
                    rowHTML += `<td>${row[12] || ''}</td>`;
                    rowHTML += `<td>${row[14] || ''}</td>`;
                    rowHTML += `<td>${row[15] || ''}</td>`;
                    rowHTML += `<td>${row[16] || ''}</td>`;

                    rowHTML += '</tr>';
                    document.getElementById('filas_rutas').innerHTML += rowHTML;
                        });
                    }
                    let excelData = null;

                    function cargar_excel(excel){ 
                        excelData = excel;
                        document.getElementById("cargar_rutas_vehiculos").disabled = false;
                    }
                    document.getElementById("cargar_rutas_vehiculos").addEventListener("click", function(){
                        console.log('El usuario dio click para subir archivo, envio a ', nombre_interno_vehiculo);

                        const spinner = document.getElementById('loading-spinner');
                        const button_enviar = document.getElementById('cargar_rutas_vehiculos');
                            
                        if (!excelData || excelData.length === 0) {
                            Swal.fire({
                                title: "Oops..!",
                                text: "No has seleccionado un archivo!",
                                icon: "error",
                            });
                            return;
                        }
                        const tableRutas = [];
                        const row_excel_rutas = excelData.slice(2);

                        row_excel_rutas.forEach((row, index)=>{
                            try {
                               const datetime = row[9] ? row[9].toString() : "";
                                let fecha = "-";
                                let hora = "00:00:00";

                                if (datetime.includes(' ')) {
                                    [fecha, hora] = datetime.split(' ');
                                }

                                tableRutas.push({
                                    // Usamos || "-" para asegurar que si el campo está vacío, mande el guion
                                    nombre_vehiculo: (row[1] || "-").toString().trim(),
                                    imei: (row[2] || "-").toString().trim(),
                                    model: (row[3] || "-").toString().trim(),
                                    on_off: (row[8] || "-").toString().trim(),
                                    fecha_recorrido: fecha || "-",
                                    hora_recorrido: hora || "00:00:00",
                                    velocidad: (row[10] !== undefined && row[10] !== "") ? row[10].toString() : "0",
                                    tipo_posicion: (row[12] || "-").toString().trim(),
                                    n_satelite: (row[13] !== undefined && row[13] !== "") ? row[13].toString() : "0",
                                    tipo_de_datos: (row[14] || "-").toString().trim(),
                                    coordenadas: (row[15] || "-").toString().trim(),
                                    direccion: (row[16] || "-").toString().trim()
                                });
                            } catch(error){
                                 console.error(`Error en la fila ${index + 1}:`, error);
                            }
                        });

                          if(tableRutas.length === 0){
                                Swal.fire({
                                        title: "Oops..!",
                                        text: "No has seleccionado un archivo !.",
                                        icon: "error"
                                    });
                                return;
                            }

                        console.log('Datos recopilados', tableRutas);

                         if(tableRutas.length === 0){
                            Swal.fire({
                                    title: "Oops..!",
                                    text: "No has seleccionado un archivo !.",
                                    icon: "error"
                                });
                            return;
                        }
                        spinner.style.display = "block";

                        $.ajax({
                            url: "/guardar-rutas-vehiculos/" + nombre_interno_vehiculo,
                            type: "POST",
                            contentType: "application/json",
                            data: JSON.stringify(tableRutas),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function (response){
                                if (response.error) {  // <-- Aquí verificamos si hay un error en la respuesta
                                    Swal.fire({
                                        title: "Error",
                                        text: response.error,
                                        icon: "error",
                                    });
                                } else {
                                    console.log("Respuesta del servidor:", response);
                                    spinner.style.display = "none";
                                    Swal.fire({
                                        title: "Éxito!",
                                        text: response.message,
                                        icon: "success",
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                }
                            },
                             error: function (xhr, status, error) {
                                console.error("Error en la petición:", xhr.responseText);
                                Swal.fire({
                                    title: "Error",
                                    text: xhr.responseJSON?.error || "Ocurrió un problema inesperado",
                                    icon: "error",
                                });
                            }

                        });
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Por favor, seleccione un vehiculo en el selector.`,
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    </script>
</main>

  
@endsection





