
@extends('layout.index')

@section('content')
<main class="main-content">
    <div class="position-relative iq-banner">
        <div class="iq-navbar-header" style="height: 215px; background: linear-gradient(45deg, #004e92, #000428);">
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-wrap d-flex justify-content-between align-items-center text-white pt-4">
                            <div>
                                <h1 class="text-white">Carga de Movimientos</h1>
                                <p class="opacity-75" id="nombre_sucursal"><i class="fa-solid fa-store me-2"></i>Cargando sucursal...</p>
                            </div>
                            <button type="button" class="btn btn-light shadow-sm" id="cargar_utilidades" disabled>
                                <i class="fa-solid fa-cloud-arrow-up me-2"></i> Guardar en Base de Datos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img">
                <img src="{{ asset('img/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX" style="opacity: 0.2;">
            </div>
        </div>
    </div>

    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Importación de Archivos</h4>
                            <p class="text-muted">Formatos admitidos: .xls, .xlsx (Asegúrate de que las columnas coincidan con el formato estándar)</p>
                        </div>

                        <div id="progreso-container" class="mb-4" style="display:none;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-primary">Procesando datos...</span>
                                <span id="progreso-text" class="badge bg-primary">0%</span>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div id="progreso-barra" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="upload-container">
                            <input type="file" id="file-upload" accept=".xls, .xlsx" hidden />
                            <div class="file-upload-area" id="drop-area">
                                <div class="icon-box mb-3">
                                    <i class="fa-solid fa-file-excel fa-3x text-success"></i>
                                </div>
                                <h5 id="upload-label">Arrastra tu Excel aquí o haz clic para buscar</h5>
                                <p class="text-muted small">Máximo 10MB por archivo</p>
                            </div>
                        </div>

                        <div class="vista_previa mt-5" id="mostrar_vista_previa" style="display: none;">
                            <hr class="hr-text" data-content="VISTA PREVIA DE DATOS">
                            <div class="table-responsive rounded shadow-sm border mt-4">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-dark">
                                        <tr id="columna_utilidades"></tr>
                                    </thead>
                                    <tbody id="filas_utilidades" class="small"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-overlay" style="display: none;">
        <div class="text-center">
            <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status"></div>
            <p class="text-white mt-3">Procesando archivo, un momento...</p>
        </div>
    </div>

    <style>
        /* Estilos de la zona de carga */
        .upload-container {
            cursor: pointer;
        }
        .file-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        .file-upload-area:hover, .file-upload-area.highlight {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-2px);
        }
        .icon-box {
            transition: transform 0.3s ease;
        }
        .file-upload-area:hover .icon-box {
            transform: scale(1.1);
        }

        /* Overlay de carga */
        #loading-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Decoración de separador */
        .hr-text {
            line-height: 1em;
            position: relative;
            outline: 0;
            border: 0;
            color: black;
            text-align: center;
            height: 1.5em;
            opacity: .5;
        }
        .hr-text:before {
            content: '';
            background: linear-gradient(to right, transparent, #818078, transparent);
            position: absolute;
            left: 0; top: 50%;
            width: 100%; height: 1px;
        }
        .hr-text:after {
            content: attr(data-content);
            position: relative;
            display: inline-block;
            color: black;
            padding: 0 .5em;
            line-height: 1.5em;
            background-color: #fcfcfa;
        }

        /* Ajustes de tabla */
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
        thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
    </style>

    <script>
        // --- Lógica de Interfaz ---
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-upload');
        const label = document.getElementById('upload-label');
        const overlay = document.getElementById('loading-overlay');

        dropArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', (e) => handleFile(e.target.files[0]));

        ['dragenter', 'dragover'].forEach(name => {
            dropArea.addEventListener(name, () => dropArea.classList.add('highlight'));
        });

        ['dragleave', 'drop'].forEach(name => {
            dropArea.addEventListener(name, () => dropArea.classList.remove('highlight'));
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            handleFile(e.dataTransfer.files[0]);
        });

        function handleFile(file) {
            if (!file) return;
            
            // Validación básica de extensión
            const extension = file.name.split('.').pop().toLowerCase();
            if(!['xls', 'xlsx'].includes(extension)) {
                Swal.fire("Formato inválido", "Por favor sube solo archivos Excel", "warning");
                return;
            }

            label.innerHTML = `<span class="text-success fw-bold"><i class="fa-solid fa-check-circle"></i> ${file.name}</span>`;
            overlay.style.display = "flex";

            const reader = new FileReader();
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const excel_utilidades = XLSX.utils.sheet_to_json(sheet, { header: 1 });

                overlay.style.display = "none";
                vistaPredeterminada(excel_utilidades);
                cargar_excel(excel_utilidades);
            };
            reader.readAsArrayBuffer(file);
            fileInput.value = ''; // Reset
        }

        // ---
        function vistaPredeterminada(excel) {
            const container_preview = document.getElementById('mostrar_vista_previa');
            const head = document.getElementById('columna_utilidades');
            const body = document.getElementById('filas_utilidades');
            
            head.innerHTML = '';
            body.innerHTML = '';
            container_preview.style.display = 'block';

            const headers = ['N° movimiento', 'Suc. Origen','Suc. Destino', 'Clave' , 'Descripción', 'ID','Proovedor', 'Fecha',
            'Vigente', 'Tipo de Movimiento', 'Cantidad', 'Costo/Precio', 'Linea', 'Color',];


            headers.forEach(h => head.innerHTML += `<th>${h}</th>`);

            const rows = excel.slice(1, 30); // Solo 10 filas para no colapsar el DOM
            rows.forEach(row => {
                let html = `<tr>
                    <td>${row[0] || '-'}</td>
                    <td>${row[1] || '-'}</td>
                    <td>${row[2] || '-'}</td>
                    <td>${row[3] || '-'}</td>
                    <td>${row[4] || '-'}</td>
                    <td>${row[5] || '-'}</td>
                    <td>${row[6] || '-'}</td>
                    <td><span class="badge bg-light text-dark">${convertExcelDate(row[7])}</span></td>
                    <td><small class="fw-bold">${row[8] || '-'}</small></td>
                    <td>${row[9] || '-'}</td>
                    <td>${row[10] || '-'}</td>
                    <td>${row[11] || '-'}</td>
                    <td>${row[12] || '-'}</td>
                    <td>${row[13] || 0}</td>
                 
                </tr>`;
                body.innerHTML += html;
            });
        }

         let excel_utilidades = null;
        function cargar_excel(excel){
            excel_utilidades = excel;
            document.getElementById('cargar_utilidades').disabled = false; 
        }

        /*recuperando el usuario para que se pueda enviar los datos a su sucrusal destino*/

        fetch('/recuperar-usuario-logueado').then(response => response.json()).then(respuesta => {
            usuarioLogueado(respuesta);
            }).catch(error => {
                console.error('Error al recuperar el usuario logueado:', error);
            });
            let nombre_interno = null;
            let nombre_real = null;
            let id_sucursal = null;
            function usuarioLogueado(respuesta){
                console.log("datos usuarios logueado", respuesta.usuario_login.nombre_interno);
                console.log("datos", respuesta.usuario_login);
                nombre_interno = respuesta.usuario_login.nombre_interno;
                nombre_real = respuesta.usuario_login.nombre_real;

                //asignando el id de la sucursal
                id_sucursal = respuesta.usuario_login.id;

                console.log("esta es la variabl nombre_interno", nombre_interno);

                const nombreSucursal = document.getElementById('nombre_sucursal');
                nombreSucursal.textContent = `${nombre_real}`;

        }
            function chunkArray(array, size) {
                const result = [];
                for (let i = 0; i < array.length; i += size) {
                    result.push(array.slice(i, i + size));
                }
                return result;
            }

            /*EVENTO CLICK DEL BOTON ENVIAR*/
            document.getElementById('cargar_utilidades').addEventListener('click', async function(){
            const spinner = document.getElementById('loading-spinner');
            const button_enviar = document.getElementById('cargar_utilidades');
            const progresoContainer = document.getElementById('progreso-container');
            const progresoBarra = document.getElementById('progreso-barra');
            const progresoTexto = document.getElementById('progreso-text');

            console.log('id enviado al servidor:', id_sucursal);
            progresoContainer.style.display = "block";
            progresoBarra.style.width = "0%";
            progresoTexto.textContent = "0%";

            if (!excel_utilidades || excel_utilidades.length === 0) {
                Swal.fire({ title: "Oops..!", text: "No hay datos para cargar.", icon: "error" });
                if (spinner) spinner.style.display = "none";
                progresoContainer.style.display = "none";
                return;
            }

            const tableData = [];
            const row_excel_utilidades = excel_utilidades.slice(1);

            row_excel_utilidades.forEach((row) => {
                const rowData = {
                    num_transpaso: row[0] || '',
                    sucursal_origen: row[1] || '',
                    sucursal_destino: row[2] || '',
                    producto_clave: row[3] || '',
                    producto_descripcion: row[4] || '',
                    producto_id: row[5] || '.',
                    proovedor: row[6] || '',
                    fecha_transpaso: convertExcelDate(row[7]) || '',
                    vigente: row[8] || '',
                    tipo_movimiento: row[9] || '',
                    cantidad_transpaso: row[10] || '',
                    costo_total: row[11] || '',
                    linea: row[12] || '',
                    color: row[13] || 0,
                    sucursal_id: id_sucursal || '',
                   
                };
                tableData.push(rowData);
            });

            if (tableData.length === 0) {
                Swal.fire({ title: "Oops..!", text: "No hay datos para cargar.", icon: "error" });
                if (spinner) spinner.style.display = "none";
                progresoContainer.style.display = "none";
                return;
            }

            const chunks = chunkArray(tableData, 500);

            for (let i = 0; i < chunks.length; i++) {
                try {
                    await $.ajax({
                        url: '/subir-datos-utilidades/' + nombre_interno,
                        type: 'post',
                        data: JSON.stringify(chunks[i]),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    // Actualizar progreso
                    const progreso = Math.round(((i + 1) / chunks.length) * 100);
                    progresoBarra.style.width = `${progreso}%`;
                    progresoTexto.textContent = `${progreso}%`;
                    
                    
                    button_enviar.style.display = "none";
                    

                } catch (error) {
                    console.error(`Error al cargar el chunk ${i + 1}`, error);
                    Swal.fire({
                        title: "Error",
                        text: "Hubo un problema al cargar un bloque de datos.",
                        icon: "error"
                    });
                    progresoContainer.style.display = "none";
                    spinner.style.display = "none";
                    button_enviar.style.display = "none";
                   
                    return;
                }
            }

            button_enviar.style.display = "block";
            spinner.style.display = "none";
            progresoContainer.style.display = "none";
            excelData = null;
            document.getElementById('mostrar_vista_previa').style.display = "none";
            document.getElementById('upload-label').textContent = "Arrastra tu archivo Excel aquí o haz clic para buscar";

            Swal.fire({
                title: "¡Listo!",
                text: "Todos los datos se han cargado correctamente.",
                icon: "success"
            });

        });

        function convertExcelDate(excelDate) {
            if (!excelDate) return null; 
                const date = new Date((excelDate - 25569) * 86400 * 1000);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); 
                const day = String(date.getDate()).padStart(2, '0'); 
            return `${year}-${month}-${day}`; 
        }

    </script>
</main>
@endsection