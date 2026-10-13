@extends('layout.index')
@section('content')
<main class="main-content">
    <br><br><br><br>
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg p-4 rounded-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <div class="bg-primary-subtle p-3 rounded-3 text-primary">
                                <i class="fa-solid fa-file-shield" style="font-size: 30px"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold text-dark">Documentación por Vehículo</h4>
                            <small class="text-muted">Administra, edita, elimina y actualiza los expedientes digitales.</small>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-light text-dark fw-semibold" id="selectedVehicleBadge" 
                                  style="font-size:15px; background-color: rgb(146 255 175) !important; display: none;">
                            </span>
                        </div>
                    </div>

                    <div id="contenedorVehiculos" class="accordion accordion-flush rounded-3 overflow-hidden shadow-sm">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-2 text-muted small fw-semibold">Agrupando expedientes de vehículos...</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modalEditarDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header bg-dark text-white p-3 rounded-top-4">
                <h5 class="modal-title fw-bold small-title"><i class="fa-solid fa-file-pen me-2"></i>Editar Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarDocumento">
                <div class="modal-body p-4">
                    <input type="hidden" id="editDocId" name="id">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre del Archivo (Visual)</label>
                        <input type="text" class="form-control" id="editDocNombre" name="nombre_original" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Fecha de Vencimiento (Si aplica)</label>
                        <input type="date" class="form-control" id="editDocVencimiento" name="fecha_vencimiento">
                    </div>
                </div>
                <div class="modal-footer bg-light p-2 border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        allDocumentacion();
    });

    function allDocumentacion(){
        fetch('/documentacion/recuperar/admin')
        .then(response => response.json())
        .then(respuesta => {
            const documentos = respuesta.documentacion;
            
            // 1. Agrupar la colección por vehiculo_id
            const documentosAgrupados = documentos.reduce((groups, item) => {
                const group = (groups[item.vehiculo_id] || []);
                group.push(item);
                groups[item.vehiculo_id] = group;
                return groups;
            }, {});

            // 2. Renderizar la estructura
            renderizarAcordeonVehiculos(documentosAgrupados);
        })
        .catch(error => {
            console.error('Error al obtener documentación:', error);
            document.getElementById('contenedorVehiculos').innerHTML = `
                <div class="alert alert-danger m-3 border-0 small shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Error al cargar el expediente de documentos en el servidor.
                </div>`;
        });
    }

    function renderizarAcordeonVehiculos(grupos) {
        const contenedor = document.getElementById('contenedorVehiculos');
        contenedor.innerHTML = ''; // Limpiar el spinner

        if (Object.keys(grupos).length === 0) {
            contenedor.innerHTML = '<div class="text-center text-muted py-5 small fw-semibold">No se encontraron vehículos con documentación registrada.</div>';
            return;
        }

        // Iterar sobre cada grupo de vehículos
        Object.keys(grupos).forEach((vehiculoId, index) => {
            const docs = grupos[vehiculoId];
            const totalDocs = docs.length;
            console.log('grrupos', grupos);
 
            // Generar ID único para colapsar los acordeones de bootstrap
            const collapseId = `collapseVehiculo_${vehiculoId}`;
            const headingId = `headingVehiculo_${vehiculoId}`;

            let HTMLAcordeon = `
            <div class="accordion-item border border-bottom-0">
                <h2 class="accordion-header" id="${headingId}">
                    <button class="accordion-button ${index === 0 ? '' : 'collapsed'} bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="${index === 0 ? 'true' : 'false'}" aria-controls="${collapseId}">
                        <div class="d-flex align-items-center w-100 me-3">
                            <div class="bg-primary text-white px-3 py-1 rounded-3 me-3 small shadow-sm">
                                <i class="fa-solid fa-truck me-2"></i>VEHÍCULO ID: 
                            </div>
                            <span class="text-muted small fw-normal d-none d-sm-inline">Última actualización general: ${docs[0].updated_at}</span>
                            <span class="badge bg-secondary rounded-pill ms-auto">${totalDocs} ${totalDocs === 1 ? 'Documento' : 'Documentos'}</span>
                        </div>
                    </button>
                </h2>
                <div id="${collapseId}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="${headingId}" data-bs-parent="#contenedorVehiculos">
                    <div class="accordion-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3" style="width: 5%">ID</th>
                                    <th style="width: 40%">Nombre del Documento</th>
                                    <th style="width: 15%">Subido Por</th>
                                    <th style="width: 10%">Tamaño / Tipo</th>
                                    <th style="width: 15%">Fecha Registro</th>
                                    <th class="text-end pe-3" style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            // Construir las filas de la tabla de documentos de este vehículo específico
            docs.forEach(doc => {
                const iconoExtension = doc.formato === 'pdf' ? 'fa-file-pdf text-danger' : 'fa-file-image text-warning';
                const subidoPor = `${doc.nombre_usuario} ${doc.apellido_usuario_pa || ''}`;

                HTMLAcordeon += `
                    <tr>
                        <td class="ps-3 text-muted fw-bold">${doc.id}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid ${iconoExtension} fs-5 me-3"></i>
                                <div style="max-width: 320px;" class="text-truncate">
                                    <span class="fw-semibold text-dark d-block text-truncate" title="${doc.nombre_original}">${doc.nombre_original}</span>
                                    <small class="text-muted style="font-size:11px;">Ref: ${doc.nombre_archivo}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-secondary fw-semibold">${subidoPor}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">${doc.peso_archivo || '--'}</span>
                            <span class="text-uppercase text-muted px-1 fw-bold" style="font-size:10px;">${doc.formato}</span>
                        </td>
                        <td>
                            <span class="text-muted">${doc.created_at}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                <a href="/${doc.ruta_archivo}" target="_blank" class="btn btn-sm btn-white text-primary border-end" title="Ver Documento">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-white text-warning border-end" onclick="abrirModalEditarDoc(${doc.id}, '${doc.nombre_original}', '${doc.fecha_vencimiento}')" title="Editar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-white text-danger" onclick="eliminarDocumento(${doc.id}, ${doc.vehiculo_id})" title="Eliminar">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            HTMLAcordeon += `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            `;

            contenedor.insertAdjacentHTML('beforeend', HTMLAcordeon);
        });
    }

    // Funciones Controladoras de Eventos de Acción (Stubs de integración)
    function abrirModalEditarDoc(id, nombreOriginal, fechaVencimiento) {
        document.getElementById('editDocId').value = id;
        document.getElementById('editDocNombre').value = nombreOriginal;
        document.getElementById('editDocVencimiento').value = fechaVencimiento !== 'null' ? fechaVencimiento : '';
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditarDocumento'));
        modal.show();
    }

    function eliminarDocumento(docId, vehiculoId) {
        Swal.fire({
            title: '¿Eliminar Documento?',
            text: `Se borrará el archivo de expediente del Vehículo ID: ${vehiculoId}. Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aquí ejecutas tu fetch DELETE correspondiente a tu Backend en Laravel
                console.log(`Eliminando doc_id: ${docId}`);
                // Tras confirmación exitosa del backend, vuelves a llamar a allDocumentacion()
            }
        });
    }
</script>

@endsection