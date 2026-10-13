@extends('layout.index') <!-- Extiende del layout base -->
@section('content')

    <main class="main-content">
        <div class="position-relative iq-banner">
            <div class="iq-navbar-header dash-hero-ledger" style="height: 230px;">
                <div class="hero-dot-grid"></div>
                <div class="hero-glow"></div>
                <div class="hero-diamond-mark"></div>
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center">
                                <div class="position-relative">
                                    <span class="eyebrow-ledger"><span class="diamond-bullet"></span> Exportar Excel</span>
                                    <h1 class="hero-title-ledger">Carga tu archivo de remisiones</h1>
                                    <p class="hero-subtitle-ledger" id="nombre_sucursal"><i class="fa-solid fa-store"></i> Cargando sucursal...</p>
                                </div>
                                <button type="button" class="btn-ledger-primary" id="cargar_remisiones" disabled>
                                    <i class="fa-solid fa-floppy-disk" id="btnGuardarIcon"></i>
                                    <span id="btnGuardarText">Guardar datos</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ledger-card p-4">

                        <div class="d-flex align-items-center mb-4 pb-3 ledger-card-header-line">
                            <div class="diamond-bezel me-3">
                                <i class="fa-solid fa-file-excel"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold ledger-page-title">Selecciona tu archivo</h4>
                                <small class="ledger-page-subtitle">Formatos aceptados: .xls, .xlsx</small>
                            </div>
                        </div>

                        <div class="file-upload-area" id="dropArea">
                            <input type="file" id="file-upload" accept=".xls, .xlsx" style="display: none;" />

                            <div class="upload-idle-state" id="uploadIdleState">
                                <div class="doc-stack">
                                    <div class="doc doc--back"></div>
                                    <div class="doc doc--mid"></div>
                                    <div class="doc doc--front"><i class="fa-solid fa-file-excel"></i></div>
                                </div>
                                <p class="upload-label" id="upload-label">Arrastra tu archivo Excel aquí o haz clic para buscar</p>
                                <span class="upload-hint">Se validará el formato antes de procesar el contenido</span>
                            </div>

                            <div class="upload-file-state" id="uploadFileState" style="display:none;">
                                <div class="upload-file-icon"><i class="fa-solid fa-file-excel"></i></div>
                                <div class="flex-grow-1 text-start">
                                    <p class="upload-file-name" id="uploadFileName">archivo.xlsx</p>
                                    <span class="upload-file-status" id="uploadFileStatus">Procesado correctamente</span>
                                </div>
                                <button type="button" class="btn-change-file" id="btnChangeFile"><i class="fa-solid fa-rotate"></i> Cambiar</button>
                            </div>
                        </div>

                        <div class="vista_previa mt-4" id="mostrar_vista_previa" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="ledger-section-label mb-0"><span class="diamond-bullet diamond-bullet--sm"></span> Vista previa del archivo</div>
                                <span class="preview-note" id="previewNote"></span>
                            </div>
                            <div class="table-responsive preview-table-wrap">
                                <table class="table ledger-preview-table" id="table_remisiones">
                                    <thead id="columna_remisiones"></thead>
                                    <tbody id="filas_remisiones"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="loading-spinner" style="display: none">
            <div class="ledger-spinner"></div>
            <p class="loading-text">Espere por favor...</p>
        </div>
 <link rel="stylesheet" href="{{ asset('css/cargaexcel/cssremcaja.css') }}"> 

<script>
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('file-upload');
    const uploadIdleState = document.getElementById('uploadIdleState');
    const uploadFileState = document.getElementById('uploadFileState');
    const uploadFileName = document.getElementById('uploadFileName');
    const uploadFileStatus = document.getElementById('uploadFileStatus');
    const EXTENSIONES_VALIDAS = ['xls', 'xlsx'];

    // Clic para abrir el selector (pero no cuando se hace clic en "Cambiar")
    dropArea.addEventListener('click', (e) => {
        if (e.target.closest('#btnChangeFile')) return;
        fileInput.click();
    });

    document.getElementById('btnChangeFile').addEventListener('click', (e) => {
        e.stopPropagation();
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

    // Estado visual al arrastrar encima (antes la clase "highlight" no tenía CSS asociado)
    dropArea.addEventListener('dragover', () => {
        dropArea.classList.add('highlight');
    });
    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('highlight');
    });
    dropArea.addEventListener('drop', (e) => {
        dropArea.classList.remove('highlight');
        const file = e.dataTransfer.files[0];
        handleFile(file);
    });

    function extensionValida(nombreArchivo) {
        const partes = nombreArchivo.split('.');
        const ext = partes[partes.length - 1].toLowerCase();
        return EXTENSIONES_VALIDAS.includes(ext);
    }

    // Manejo del archivo
    function handleFile(file) {
        const spinner = document.getElementById('loading-spinner');

        if (!file) {
            Swal.fire({ title: "Oops..!", text: "No se seleccionó un archivo.", icon: "error" });
            return;
        }

        if (!extensionValida(file.name)) {
            Swal.fire({
                title: "Formato no válido",
                text: "Solo se aceptan archivos .xls o .xlsx.",
                icon: "error"
            });
            fileInput.value = '';
            return;
        }

        uploadIdleState.style.display = 'none';
        uploadFileState.style.display = 'flex';
        uploadFileName.textContent = file.name;
        uploadFileStatus.textContent = 'Procesando...';
        uploadFileStatus.style.color = 'var(--ink-faint)';

        if (spinner) spinner.style.display = "block";

        const reader = new FileReader();

        reader.onload = function (e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });

            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];

            const excel_remisiones = XLSX.utils.sheet_to_json(sheet, { header: 1 });
            console.log(excel_remisiones);

            if (spinner) spinner.style.display = "none";
            uploadFileStatus.textContent = 'Procesado correctamente';
            uploadFileStatus.style.color = 'var(--success)';
            displayPreview(excel_remisiones);
            cargar_excel(excel_remisiones);
        };

        reader.onerror = function () {
            if (spinner) spinner.style.display = "none";
            uploadFileStatus.textContent = 'Error al leer el archivo';
            uploadFileStatus.style.color = 'var(--danger)';
            Swal.fire({ title: "Error", text: "No se pudo leer el archivo. Verifica que no esté dañado.", icon: "error" });
        };

        reader.readAsArrayBuffer(file);


        fileInput.value = '';
    }

    function displayPreview(data) {
        document.getElementById('columna_remisiones').innerHTML = '';
        document.getElementById('filas_remisiones').innerHTML = '';
        const view_previe = document.getElementById('mostrar_vista_previa');

        view_previe.style.display = "block";

        const headers = ['N_Rem', 'Fecha', 'Cliente', 'Colonia', 'Municipio','Vendedor', 'Prod_id', 'Prod_clave','Prod_descripcion', 'Prod_linea', 'Cantidad_prod', 'Precio','Subtotal', 'Iva', 'Total', 'Vigente', 'Costo Promedio', 'Ultimo Costo', 'Descuento aplicado'];
        let headerHTML = '<tr>';
        headers.forEach((header) => {
            headerHTML += `<th>${header}</th>`;
        });
        headerHTML += '</tr>';
        document.getElementById('columna_remisiones').innerHTML = headerHTML;

        const totalFilasDatos = Math.max(data.length - 1, 0);
        const rows = data.slice(1, 10);
        document.getElementById('previewNote').textContent = totalFilasDatos > rows.length
            ? `Mostrando ${rows.length} de ${totalFilasDatos} filas`
            : `${totalFilasDatos} fila(s) detectada(s)`;

        rows.forEach((row) => {
            let rowHTML = '<tr>';
            rowHTML += `<td>${row[0] || ''}</td>`; 
            rowHTML += `<td>${convertExcelDate(row[1]) || ''}</td>`; 
            rowHTML += `<td>${row[4] || ''}</td>`; 
            rowHTML += `<td>${row[5] || ''}</td>`;
            rowHTML += `<td>${row[6] || ''}</td>`; 
            rowHTML += `<td>${row[7] || ''}</td>`;
            rowHTML += `<td>${row[8] || ''}</td>`; 
            rowHTML += `<td>${row[9] || ''}</td>`; 
            rowHTML += `<td>${row[10] || ''}</td>`; 
            rowHTML += `<td>${row[11] || ''}</td>`; 
            rowHTML += `<td>${row[13] || ''}</td>`; 
            rowHTML += `<td>${row[14] || ''}</td>`;
            rowHTML += `<td>${row[15] || ''}</td>`; 
            rowHTML += `<td>${row[16] || ''}</td>`;
            rowHTML += `<td>${row[17] || ''}</td>`;
            rowHTML += `<td>${row[18] || ''}</td>`;
            rowHTML += `<td>${row[19] || ''}</td>`; 
            rowHTML += `<td>${row[20] || ''}</td>`; 
            rowHTML += `<td>${row[21] || ''}</td>`;
            rowHTML += '</tr>';
            document.getElementById('filas_remisiones').innerHTML += rowHTML;
        });
    }

    let excelData = null;

    function cargar_excel(excel){ 
        excelData = excel;
        document.getElementById("cargar_remisiones").disabled = false;
    }

    fetch('/recuperar-usuario-logueado')
        .then(response => response.json())
        .then(respuesta => {
            usuarioLogueado(respuesta);
        })
        .catch(error => {
            console.error('Error al recuperar el usuario logueado:', error);
            document.getElementById('nombre_sucursal').innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> No se pudo identificar tu sucursal';
            Swal.fire({
                title: 'Error de sesión',
                text: 'No se pudo identificar tu sucursal. Recarga la página antes de continuar.',
                icon: 'error'
            });
        });

    let nombre_interno = null;
    let nombre_real = null;
    function usuarioLogueado(respuesta){
        nombre_interno = respuesta.usuario_login.nombre_interno;
        nombre_real = respuesta.usuario_login.nombre_real;

        const nombreSucursal = document.getElementById('nombre_sucursal');
        nombreSucursal.innerHTML = `<i class="fa-solid fa-store"></i>${nombre_real}`;
    }

    let enviandoRemisiones = false;

    document.getElementById("cargar_remisiones").addEventListener("click", function(){
        if (enviandoRemisiones) return;

        const spinner = document.getElementById('loading-spinner');
        const button_enviar = document.getElementById('cargar_remisiones');

        if (!excelData || excelData.length === 0) {
            Swal.fire({ title: "Oops..!", text: "No has seleccionado un archivo!", icon: "error" });
            return;
        }

        if (!nombre_interno) {
            Swal.fire({ title: "Oops..!", text: "No se pudo identificar tu sucursal. Recarga la página.", icon: "error" });
            return;
        }

        const tableData = [];
        const row_excel = excelData.slice(1);

        row_excel.forEach((row, index) => {
            try {
                tableData.push({
                    num_remision: row[0] ? row[0].toString().trim() : null,
                    fecha: convertExcelDate(row[1] ? row[1].toString().trim() : null),
                    cliente: row[4] ? row[4].toString().trim() : null,
                    colonia: row[5] ? row[5].toString().trim() : null,
                    municipio: row[6] ? row[6].toString().trim() : null,
                    vendedor: row[7] ? row[7].toString().trim() : null,
                    producto_id: row[8] ? row[8].toString().trim() : null,
                    producto_clave: row[9] ? row[9].toString().trim() : null,
                    producto_descripcion: row[10]? row[10].toString().trim() : null,
                    producto_linea: row[11] ? row[11].toString().trim() : null,
                    cantidad_producto: row[13] ? row[13].toString().trim() : null,
                    precio: row[14] ? row[14].toString().trim() : "0",
                    subtotal: row[15] ? row[15].toString().trim() : "0",
                    iva: row[16] ? parseFloat(row[16]) || null : null,
                    total: row[17] ? row[17].toString().trim() : null,
                    vigente: row[18] ? row[18].toString().trim() : null,
                    costo_promedio: row[19] ? row[19].toString().trim() : "0",
                    ultimo_costo: row[20] ? row[20].toString().trim() : "0",
                    descuento_apli: row[21] ? row[21].toString().trim() : "0",
                });
            } catch (error) {
                console.error(`Error en la fila ${index + 1}:`, error);
            }
        });

        if (tableData.length === 0) {
            Swal.fire({ title: "Oops..!", text: "No has seleccionado un archivo !.", icon: "error" });
            return;
        }

        enviandoRemisiones = true;
        button_enviar.disabled = true;
        document.getElementById('btnGuardarIcon').className = 'fa-solid fa-spinner fa-spin';
        document.getElementById('btnGuardarText').textContent = 'Guardando...';
        spinner.style.display = "block";

        $.ajax({
            url: '/cargar-datos-excel-remisiones/' + nombre_interno,
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(tableData),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                console.log("Respuesta del servidor:", response);
                spinner.style.display = "none";

                Swal.fire({
                    title: "Éxito!",
                    text: response.message,
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2000
                });

                excelData = null;
                document.getElementById('mostrar_vista_previa').style.display = "none";
                uploadFileState.style.display = 'none';
                uploadIdleState.style.display = 'block';
                document.getElementById('upload-label').textContent = "Arrastra tu archivo Excel aquí o haz clic para buscar";

                enviandoRemisiones = false;
                button_enviar.disabled = true;
                document.getElementById('btnGuardarIcon').className = 'fa-solid fa-floppy-disk';
                document.getElementById('btnGuardarText').textContent = 'Guardar datos';
            },
            error: function(error){
                console.error("Error al guardar los datos:", error);
                spinner.style.display = "none";
                enviandoRemisiones = false;
                button_enviar.disabled = false;
                document.getElementById('btnGuardarIcon').className = 'fa-solid fa-floppy-disk';
                document.getElementById('btnGuardarText').textContent = 'Guardar datos';
                Swal.fire({
                    title: "Error!",
                    text: "Error al guardar los datos, verifique que sea el excel correcto.",
                    icon: "error"
                });
            }
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
