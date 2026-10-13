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
                            <i class="fa-solid fa-clock-rotate-left" style="font-size: 35px; color: #004a99;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-primary">SOLICITUDES FUERA DE PLAZO</h4>
                            <small class="text-muted">Gestiona las solicitudes de combustible fuera de plazo.</small>
                        </div>
                    </div>
                    <hr class="my-4">

                    <!-- FILTROS -->
                    <div class="row g-3">
                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small mb-1"># Folio:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-hashtag text-secondary"></i></span>
                                <input type="search" id="filterFolio" class="form-control border-start-0" placeholder="Ej. 5520...">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label fw-semibold small mb-1">Chofer:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-secondary"></i></span>
                                <input type="search" id="filterChofer" class="form-control border-start-0" placeholder="Nombre chofer...">
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold small mb-1">Vehículo:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-car-side"></i></span>
                                <input type="search" id="filterVehiculo" class="form-control border-start-0" placeholder="NISSAN NP300...">
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <label class="form-label fw-semibold small mb-1">Estatus:</label>
                            <select id="filterStatus" class="form-select shadow-sm">
                                <option value="">Todos</option>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="APROBADO">APROBADO</option>
                                <option value="USADO">USADO</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border-0" id="tablaAutorizados">
                                    <thead class="bg-light">
                                        <tr class="text-secondary">
                                            <th class="small fw-bold text-center">FOLIO</th>
                                            <th class="small fw-bold">FECHA</th>
                                            <th class="small fw-bold">CHOFER</th>
                                            <th class="small fw-bold">VEHÍCULO</th>
                                            <th class="small fw-bold">MOTIVO</th>
                                            <th class="small fw-bold text-center">ESTATUS</th>
                                            <th class="small fw-bold text-center">HABILITAR BOTÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyFueraPlazo">
                                        <!-- Data via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>

<script>
    let rawData = []; // Para guardar los datos originales y filtrar localmente

    document.addEventListener('DOMContentLoaded', () => {
        cargarDatos();
        
        // Listeners para filtros
        document.getElementById('filterFolio').addEventListener('input', filtrarTabla);
        document.getElementById('filterChofer').addEventListener('input', filtrarTabla);
        document.getElementById('filterVehiculo').addEventListener('input', filtrarTabla);
        document.getElementById('filterStatus').addEventListener('change', filtrarTabla);
    });

    function cargarDatos() {
        const tbody = $('#tbodyFueraPlazo');
         tbody.html(`
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted fw-bold">Consultando base de datos...</p>
                </td>
            </tr>
        `);

        fetch('/combustible/solicitudes/fuera-plazo/recuperar')
            .then(response => response.json())
            .then(respuesta => {
                rawData = respuesta;
                renderizarTabla(rawData);
        });
    }

    function renderizarTabla(datos) {
        let tbody = document.getElementById('tbodyFueraPlazo');
        tbody.innerHTML = '';

        if (datos.length === 0) {
            tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    <i class="fa-brands fa-sistrix" style='font-size:35px;'></i>
                    No se encontraron registros
                </td>
            </tr>`;
            return;
        }
        datos.forEach(solicitud => {
            let row = document.createElement('tr');
            
            // Formateo de fecha
            let fecha = new Date(solicitud.created_at);
            let fecha_formateada = fecha.toLocaleDateString('es-MX', { day: '2-digit', month: '2-digit', year: 'numeric' });
            let badgeClass = 'bg-warning text-dark';
            if(solicitud.estatus === 'APROBADO') badgeClass = 'bg-success';
            if(solicitud.estatus === 'USADO') badgeClass = 'bg-info text-white';

            let isChecked = solicitud.estatus !== 'PENDIENTE' ? 'checked' : '';
            let isDisabled = solicitud.estatus !== 'PENDIENTE' ? 'disabled' : '';
            //vamos adaptar el td cuando el motivo sea muy largo, para que se vea mejor en la tabla
            //<td class="small italic text-muted">${solicitud.motivo_carga}</td>
            row.innerHTML = `
                <td class="text-center fw-bold text-primary">#${solicitud.id}</td>
                <td class="small">${fecha_formateada}</td>
                <td>
                    <div class="fw-bold">${solicitud.nombre_chofer} ${solicitud.apellido_p_chofer}</div>
                    <div class="small text-muted">ID: ${solicitud.chofer_id}</div>
                </td>
                <td class="small text-truncate" style="max-width: 50px;">${solicitud.marca_vehiculo} ${solicitud.linea_vehiculo} <br> <span class="text-muted">(${solicitud.placas_vehiculo})</span></td>
                <td class="small text-truncate" style="max-width: 250px;" title="${solicitud.motivo_carga}">${solicitud.motivo_carga}</td>
                <td class="text-center">
                    <span class="badge ${badgeClass}">${solicitud.estatus}</span>
                </td>
                <td class="text-center">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input switch-habilitar" type="checkbox" role="switch" 
                            style="width: 2.5em; height: 1.25em; cursor: ${isDisabled ? 'default' : 'pointer'}"
                            ${isChecked} ${isDisabled}
                            onclick="confirmarHabilitacion(${solicitud.id}, ${solicitud.chofer_id}, this)">
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function filtrarTabla() {
        const fFolio = document.getElementById('filterFolio').value.toLowerCase();
        const fChofer = document.getElementById('filterChofer').value.toLowerCase();
        const fVehiculo = document.getElementById('filterVehiculo').value.toLowerCase();
        const fStatus = document.getElementById('filterStatus').value;

        const filtered = rawData.filter(item => {
            const matchFolio = item.id.toString().includes(fFolio);
            const nombreCompleto = `${item.nombre_chofer} ${item.apellido_p_chofer} ${item.apellido_m_chofer}`.toLowerCase();
            const matchChofer = nombreCompleto.includes(fChofer);
            const vehiculoStr = `${item.marca_vehiculo} ${item.linea_vehiculo} ${item.placas_vehiculo}`.toLowerCase();
            const matchVehiculo = vehiculoStr.includes(fVehiculo);
            const matchStatus = fStatus === "" || item.estatus === fStatus;

            return matchFolio && matchChofer && matchVehiculo && matchStatus;
        });

        renderizarTabla(filtered);
    }

    function confirmarHabilitacion(idPermiso, idChofer, element) {
        Swal.fire({
            title: '¿Habilitar botón?',
            text: "Le notificaremos al chofer para que pueda realizar su solicitud de combustible. ¿Deseas continuar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#009b3c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'No, esperar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Petición al servidor
                Swal.fire({
                        title: 'Enviando solicitud...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                });
                $.ajax({
                    url: '/combustible/solicitudes/fuera-plazo/habilitar',
                    method: 'POST',
                    data: {
                        id_permiso: idPermiso,
                        id_chofer: idChofer,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            '¡Listo!',
                            'Hemos notificado al chofer via correo electrónico.',
                            'success'
                        );
                        cargarDatos(); 
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error',
                            'Ocurrió un error al habilitar el botón. Intenta nuevamente.',
                            'error'
                        );
                        console.log('el error es ', xhr.responseText);
                        element.checked = false;
                    }
                });
            } else {
                element.checked = false; 
            }
        });
    }
</script>

<style>
    /* Estilo para que el switch se vea más profesional */
    .form-check-input:checked {
        background-color: #009b3c;
        border-color: #009b3c;
    }
    .table thead th {
        border-bottom: 2px solid #f0f0f0;
    }
</style>

</main>
@endsection