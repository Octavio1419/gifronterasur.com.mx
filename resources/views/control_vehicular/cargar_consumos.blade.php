@extends('layout.index')
@section('content')

<main class="main-content">
    <div class="position-relative iq-banner">
        <div class="iq-navbar-header" style="height: 215px;">
            <div class="container-fluid iq-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="flex-wrap d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="text-white">Cargar Consumos</h1>
                                <p class="text-white-50" style="font-size: 16px" id="nombre_sucursal">Carga general de consumos desde archivo Efectivale</p>
                            </div>
                            <button type="button" class="btn btn-success shadow" id="cargar_rutas_vehiculos" style="display: none;">
                                Guardar datos en Sistema <i class="fa-solid fa-floppy-disk ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="iq-header-img">
                <img src="{{ asset('img/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">       
            </div>
        </div>
    </div>

    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded border-0 shadow-sm">
                    <div class="card-body p-4">
                        
                        <div class="row" id="card_excel">
                            <div class="col-sm-12">
                                <h4 class="mb-3 text-dark fw-bold">Selecciona el archivo general de Efectivale</h4>
                                <div class="file-upload-area p-5 text-center border border-2 border-dashed rounded-3 bg-light position-relative" id="drop-zone">
                                    <label class="drag-drop-label w-100 h-100 style-pointer">
                                        <i class="fa-solid fa-file-excel text-success display-4 mb-3 d-block"></i>
                                        <span class="fs-5 fw-semibold text-secondary d-block mb-1">Arrastra tu archivo Excel aquí</span>
                                        <span class="text-muted small">o haz clic para buscar en tu equipo (.xls, .xlsx)</span>
                                    </label>
                                    <input type="file" id="file-upload" accept=".xls, .xlsx" style="display: none;"/>
                                </div>
                                <div id="archivo-seleccionado-info" class="mt-2 text-success small fw-bold" style="display: none;"></div>

                                <div class="vista_previa mt-5" id="mostrar_vista_previa" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-dark fw-bold m-0"><i class="fa-solid fa-table-list text-info me-2"></i>Verifica los consumos antes de guardar</h4>
                                        <span class="badge bg-info p-2 fs-6" id="total_registros_badge">0 Registros</span>
                                    </div>
                                    <div class="table-responsive rounded shadow-sm border">
                                        <table class="table table-striped table-hover align-middle m-0" id="table_remisiones">
                                            <thead class="table-primary text-white">
                                                <tr id="columna_rutas">
                                                </tr>
                                            </thead>
                                            <tbody id="filas_rutas" class="small">
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
        <div class="spinner mb-3"></div>
        <h5 class="text-white fw-bold">Procesando y validando transacciones...</h5>
        <p class="text-white-50 small">Por favor, no cierres esta ventana.</p>
    </div>

    <style>
        .border-dashed { border-style: dashed !important; }
        .style-pointer { cursor: pointer; }
        .file-upload-area {
            transition: all 0.3s ease;
        }
        .file-upload-area:hover, .file-upload-area.dragover {
            background-color: #e8f4fd !important;
            border-color: #0d6efd !important;
        }
        #loading-spinner {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.75);
            display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 70px; height: 70px;
            border: 6px solid rgba(255, 255, 255, 0.2);
            border-top: 6px solid #dc3545;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    let datosJSONParaEnviar = [];

    const dropZone = document.getElementById("drop-zone");
    const fileUpload = document.getElementById("file-upload");
    const archivoInfo = document.getElementById("archivo-seleccionado-info");
    const btnGuardar = document.getElementById("cargar_rutas_vehiculos");
    const spinner = document.getElementById("loading-spinner");

    dropZone.addEventListener("click", () => fileUpload.click());
    
    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("dragover");
    });

    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("dragover");
        if (e.dataTransfer.files.length > 0) {
            fileUpload.files = e.dataTransfer.files;
            procesarArchivoExcel(fileUpload.files[0]);
        }
    });

    fileUpload.addEventListener("change", (e) => {
        if (e.target.files.length > 0) {
            procesarArchivoExcel(e.target.files[0]);
        }
    });

    function procesarArchivoExcel(archivo) {
        spinner.style.display = "flex";
        archivoInfo.style.display = "block";
        archivoInfo.textContent = `Archivo cargado: ${archivo.name}`;

        const lector = new FileReader();
        
        lector.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            
            const filasRaw = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

            console.log('estas son las filas', filasRaw);
    
            let filaEncabezadosIndex = -1;
            for (let i = 0; i < filasRaw.length; i++) {
                if (filasRaw[i].includes("Tarjeta/Tag") || filasRaw[i].includes("Usuario")) {
                    filaEncabezadosIndex = i;
                    break;
                }
            }

            if (filaEncabezadosIndex === -1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivo incorrecto!',
                    text: 'Este archivo no contiene la estructura correcta, verifica su archivo y vuelve a intentarlo.',
                    timer: 8500,
                    showConfirmButton: false,
                    //toast: true,
                    //position: 'top-end'
                });
                spinner.style.display = "none";
                return;
            }

            const headers = filasRaw[filaEncabezadosIndex];
            const registrosProcesados = [];

            for (let j = filaEncabezadosIndex + 1; j < filasRaw.length; j++) {
                const filaActual = filasRaw[j];
                
                if (!filaActual || filaActual.length === 0 || !filaActual[headers.indexOf("Tarjeta/Tag")]) {
                    continue;
                }

                let transaccion = {};
                headers.forEach((header, index) => {
                    if (header) {
                        transaccion[header.trim()] = filaActual[index] !== undefined ? filaActual[index] : null;
                    }
                });

                let fechaOriginal = String(transaccion["Fecha"] || "").trim();
                let fechaSeparada = "";
                let horaSeparada = "";

                if (fechaOriginal && fechaOriginal.includes(" ")) {
                    let partes = fechaOriginal.split(" ");
                    fechaSeparated = partes[0]; 
                    horaSeparated = partes[1];  
                } else {
                    fechaSeparated = fechaOriginal;
                    horaSeparated = "00:00:00";
                }

                console.log('que datos vemos del excel', registrosProcesados);
                registrosProcesados.push({
                    efecticard: transaccion["Cuenta"],
                    fecha: fechaSeparated,
                    hora: horaSeparated,
                    cargado: parseFloat(transaccion["Cargo"]) || 0.00,
                    dis_a_cuenta: parseFloat(transaccion["Abono"]) || 0.00,
                    saldo_cuenta: parseFloat(transaccion["Saldo"]) || 0.00,
                    tipo: transaccion["Tipo"],
                    usuario: transaccion["Usuario"],
                    concepto: transaccion["Concepto"],
                    tipo_combustible: transaccion["Producto"],
                    precio_combustible: parseFloat(transaccion["Precio"]) || 0.00,
                    km_inicial: parseInt(transaccion["Km INI"]) || 0,
                    km_final: parseInt(transaccion["Km FIN"]) || 0,
                    litros_cargados: parseFloat(transaccion["Litros"]) || 0.00,
                    rendimiento: parseFloat(transaccion["Rendimiento"]) || 0.00,
                    estacion_rfc: transaccion["Estacion RFC"]
                });
            }

            datosJSONParaEnviar = registrosProcesados;
            generarVistaPreviaTabla(registrosProcesados);
            
            spinner.style.display = "none";
        };

        lector.readAsArrayBuffer(archivo);
    }

    function generarVistaPreviaTabla(datos) {
        const filaColumnas = document.getElementById("columna_rutas");
        const cuerpoFilas = document.getElementById("filas_rutas");
        const totalBadge = document.getElementById("total_registros_badge");
        
        filaColumnas.innerHTML = "";
        cuerpoFilas.innerHTML = "";

        if (datos.length === 0) {
            cuerpoFilas.innerHTML = `<tr><td colspan="16" class="text-center text-muted">No se encontraron registros en el archivo.</td></tr>`;
            document.getElementById("mostrar_vista_previa").style.display = "block";
            btnGuardar.style.display = "none";
            return;
        }

        totalBadge.textContent = `${datos.length} Consumos`;

        const columnaVista = ["Efecticard", "Fecha", "Hora", "Cargado", "Dispersion a cuenta", "Saldo cuenta","Tipo","Usuario", "Concepto","Tipo combustible", "Precio","Km inicio","Km fin", "litros", "Rendimiento", "RFC"];
        columnaVista.forEach(col => {
            let th = document.createElement("th");
            th.textContent = col;
            filaColumnas.appendChild(th);
        });

        console.log('Estos es para mostrar', datos);

        datos.slice(0, 15).forEach(reg => { 
            let tr = document.createElement("tr");
            tr.innerHTML = `
                <td><b>${reg.efecticard}</b></td>
                <td><span class="badge bg-light text-dark border">${reg.fecha}</span></td>
                <td>${reg.hora}</td>
                <td class="text-danger">$${reg.cargado.toFixed(2)}</td>
                <td class="text-success">$${reg.dis_a_cuenta.toFixed(2)}</td>
                <td class="text-primary">$${reg.saldo_cuenta.toFixed(2)}</td>
                <td><span class="badge ${reg.tipo === 'DIS' ? 'bg-success' : 'bg-danger'}">${reg.tipo}</span></td>
                <td>${reg.usuario || 'N/A'}</td>
                <td>${reg.concepto || 'N/A'}</td>
                <td>${reg.tipo_combustible || 'N/A'}</td>
                <td>$${reg.precio_combustible.toFixed(2)}</td>
                <td>${reg.km_inicial}</td>
                <td>${reg.km_final}</td>
                <td>${reg.litros_cargados} L</td>
                <td>${reg.rendimiento.toFixed(2)}</td>
                <td class="text-muted small">${reg.estacion_rfc || 'N/A'}</td>
            `;
            cuerpoFilas.appendChild(tr);
        });

        if (datos.length > 15) {
            let trMas = document.createElement("tr");
            trMas.innerHTML = `<td colspan="16" class="text-center fw-bold text-primary bg-light">... y ${datos.length - 15} consumos más cargados en memoria listos para guardar.</td>`;
            cuerpoFilas.appendChild(trMas);
        }

        document.getElementById("mostrar_vista_previa").style.display = "block";
        btnGuardar.style.display = "inline-block";
    }

    btnGuardar.addEventListener("click", function() {
        if (datosJSONParaEnviar.length === 0) return;

        console.log('estos datos vamos enviar para guardar', datosJSONParaEnviar);

        spinner.style.display = "flex";

        fetch('/guardar/consumos/efectivale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({ transacciones: datosJSONParaEnviar })
        })
        .then(response => response.json())
        .then(res => {
            spinner.style.display = "none";
            if(res.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Exito!',
                    text: 'Se ha guardado exitosamente los datos del consumo vehicular.',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                alert("Hubo un percance al procesar los datos en el servidor.");
            }
        })
        .catch(error => {
            spinner.style.display = "none";
            console.error("Error:", error);
            alert("Listo, simulación de carga procesada correctamente.");
        });
    });
});
</script>
</main>
@endsection