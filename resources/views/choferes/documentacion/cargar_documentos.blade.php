@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg p-4 rounded-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fa-solid fa-file-shield text-primary" style="font-size: 35px"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-primary">Carga tus expediente digital</h4>
                            <small class="text-muted">Selecciona la unidad y gestiona sus documentos PDF</small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-light text-dark fw-semibold" id="selectedVehicleBadge" 
                                  style="font-size:15px; background-color: rgb(146 255 175) !important; display: none;">
                            </span>
                        </div>
                    </div>

                    <form id="uploadForm" class="row g-3" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <label for="vehicleSelector" class="form-label fw-bold">Seleccionar Vehículo / Unidad</label>
                            <select id="vehicleSelector" name="vehiculo_id" class="form-select border-2" required>
                                <option value="" selected disabled>Cargando unidades...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="uploadDate" class="form-label fw-bold">Fecha de Registro</label>
                            <input type="date" id="uploadDate" name="fecha_carga" class="form-control border-2" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold">Documentación (Multicarga de PDF)</label>

                            <div id="dropArea" class="drop-area border border-2 border-dashed rounded-3 p-5 text-center bg-light-subtle" 
                                 tabindex="0" style="cursor: pointer; transition: 0.3s;">
                                <i class="fa-solid fa-cloud-arrow-up fa-2xl text-primary mb-3"></i>
                                <p class="mb-1 fw-bold text-secondary" id="dropAreaText">
                                    Arrastra y suelta los documentos aquí o haz clic para seleccionar
                                </p>
                                <small class="text-muted d-block">Solo archivos PDF — Máximo recomendado: 20MB por carga</small>
                                <input id="fileInput" name="documentos[]" type="file" accept="application/pdf" class="d-none" multiple required>
                            </div>
                            <div id="filePreviewList" class="mt-3 row g-2"></div>
                            <div class="mt-4 d-flex gap-2">
                                <button id="btnUpload" class="btn btn-primary px-4 shadow-sm" disabled>
                                    <i class="fa-solid fa-upload me-2"></i>Subir al Expediente
                                </button>
                                <button id="btnClear" type="button" class="btn btn-outline-secondary px-4">
                                    Limpiar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="pantallaSpline" class="pantalla-spline" style="display: none;">
    <div class="contenido text-center">
        <img src="{{ asset('img/cargando_inventario.gif') }}" alt="Cargando..." style="width: 150px;">
        <h5 class="mt-3 fw-bold">Procesando documentos...</h5>
        <p class="text-muted">Estamos guardando el expediente digital, espera un momento.</p>
    </div>
</div>

<style>
    .drop-area:hover {
        background-color: #e9ecef !important;
        border-color: #0d6efd !important;
    }
    .file-item {
        background: #f8f9fa;
        border-left: 4px solid #0d6efd;
        border-radius: 8px;
        padding: 10px;
    }
    .pantalla-spline {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vehicleSelector = document.getElementById('vehicleSelector');
    const badgePlaca = document.getElementById('selectedVehicleBadge');
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const filePreviewList = document.getElementById('filePreviewList');
    const btnUpload = document.getElementById('btnUpload');
    const uploadForm = document.getElementById('uploadForm');

    // Cargar vehículos
    fetch("{{ route('vehiculos.recuperar') }}")
        .then(response => response.json())
        .then(res => {
            if(res.success) {
                vehicleSelector.innerHTML = '<option value="" selected disabled>Seleccione una unidad...</option>';
                res.data.forEach(v => {
                    let option = document.createElement('option');
                    option.value = v.id;
                    option.text = `${v.marca} ${v.linea} (${v.modelo}) - ${v.placas}`;
                    option.dataset.placa = v.placas;
                    vehicleSelector.appendChild(option);
                });
            }
        });

    // Evento al cambiar vehículo
    vehicleSelector.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        badgePlaca.innerText = "PLACA: " + selectedOption.dataset.placa;
        badgePlaca.style.display = 'inline-block';
        btnUpload.disabled = false;
    });

    // Manejo de archivos
    dropArea.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', function() {
        showPreview(this.files);
    });

    function showPreview(files) {
        filePreviewList.innerHTML = '';
        Array.from(files).forEach(file => {
            const size = (file.size / (1024 * 1024)).toFixed(2);
            const html = `
                <div class="col-md-4">
                    <div class="file-item d-flex align-items-center shadow-sm">
                        <i class="fa-solid fa-file-pdf text-danger me-2 fa-lg"></i>
                        <div class="overflow-hidden text-start">
                            <div class="text-truncate fw-bold small" title="${file.name}">${file.name}</div>
                            <small class="text-muted">${size} MB</small>
                        </div>
                    </div>
                </div>`;
            filePreviewList.insertAdjacentHTML('beforeend', html);
        });
    }

    // FUNCIONALIDAD DEL BOTÓN UPLOAD (AJAX)
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Bloquear botón y mostrar loading
        btnUpload.disabled = true;
        document.getElementById('pantallaSpline').style.display = 'flex';

        let formData = new FormData(this);

        const datosPlanos = Object.fromEntries(formData.entries());
        console.log(datosPlanos);

        //enviamos al usuario logueado
        $id_usuario = {{ Auth::id() }};
        
        console.log('Enviando al servidor:', {
            vehiculo_id: formData.get('vehiculo_id'),
            fecha_carga: formData.get('fecha_carga'),
            documentos: formData.getAll('documentos[]').map(file => file.name),
            id_usuario: $id_usuario
        });
        
        $.ajax({
            url: '/subir/documentacion/vehicular/' + $id_usuario,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if(res.success) {
                    swal.fire({
                        icon: 'success',
                        title: '¡Documentos subido(s) exitosamente!',
                        text: res.message,
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    document.getElementById('pantallaSpline').style.display = 'none';
                } else {
                    swal.fire({
                        icon: 'error',
                        title: 'Error al subir los documentos',
                        text: res.message,
                        confirmButtonText: 'Aceptar',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                    btnUpload.disabled = false;
                    document.getElementById('pantallaSpline').style.display = 'none';
                }
            },
            error: function(xhr) {
                console.log('error response:', xhr.responseText);
                alert('Ocurrió un error inesperado. Por favor, inténtelo de nuevo.');
                btnUpload.disabled = false;
                document.getElementById('pantallaSpline').style.display = 'none';
            }
        });
    });

    document.getElementById('btnClear').addEventListener('click', () => location.reload());
});
</script>
@endsection