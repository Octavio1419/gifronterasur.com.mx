<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación Vehicular</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --bg-dark: #0f172a; 
            --card-dark: #1e293b; 
            --accent-blue: #38bdf8; 
            --wa-dark: #1c2733;
        }
        
        body { background-color: var(--bg-dark); color: white; font-family: 'Inter', sans-serif; }
        
        /* Contenedor Principal de la Burbuja */
        .file-bubble {
            max-width: 420px;
            background: var(--wa-dark);
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            margin: 20px auto;
            transition: all 0.3s ease;
        }

        .preview-wrapper {
            position: relative;
            width: 100%;
            height: 220px; 
            background: white;
            overflow: hidden;
        }

        .preview-wrapper iframe {
            width: 150%; 
            height: 800px;
            border: none;
            transform: scale(0.65); 
            transform-origin: 0 0;
            pointer-events: none; 
            user-select: none;
        }

        .click-layer {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: transparent;
            cursor: pointer;
            z-index: 10;
        }

        .preview-overlay-icon {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.4);
            width: 50px; height: 50px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: 0.3s;
            z-index: 11;
        }
        .preview-wrapper:hover .preview-overlay-icon { opacity: 1; }

        .file-info-bottom { 
            padding: 14px 18px; 
            display: flex; 
            align-items: center; 
            background: rgba(255,255,255,0.03);
        }

        .pdf-icon-square {
            background: #ef4444; 
            color: white; 
            width: 44px; height: 44px;
            border-radius: 10px; 
            display: flex; 
            align-items: center;
            justify-content: center; 
            font-size: 1.3rem; 
            margin-right: 15px;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }

        .file-details { flex-grow: 1; overflow: hidden; }
        .file-name { font-size: 0.95rem; font-weight: 600; margin: 0; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; color: #e2e8f0; }
        .file-meta { font-size: 0.8rem; opacity: 0.5; margin: 0; letter-spacing: 0.5px; }

        .btn-download-pro {
            background: rgba(56, 189, 248, 0.15);
            color: var(--accent-blue);
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-download-pro:hover { background: var(--accent-blue); color: var(--bg-dark); }

        /* Visor de PDF Expandido */
        .pdf-viewer-wrap {
            background: var(--card-dark); border-radius: 24px;
            padding: 15px; display: none; margin-top: 25px;
            border: 1px solid rgba(56, 189, 248, 0.3);
            animation: slideUp 0.4s ease-out;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .full-iframe { width: 100%; height: 75vh; border-radius: 14px; border: none; }
    </style>
</head>
<body>

<div class="container py-4">
    <!-- Encabezado -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('choferes.dashboard') }}" class="text-white me-3 text-decoration-none">
            <i class="fas fa-chevron-left fa-lg"></i>
        </a>
        <h5 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">Documentación Vehicular</h5>
    </div>

    <div id="contenedor-documentos" class="row">
      
        <div class="text-center py-5" id="loader">
            <div class="spinner-border text-info" role="status"></div>
            <p class="mt-2 opacity-50">Cargando expediente digital...</p>
        </div>
    </div>
    <div id="viewer-wrap" class="pdf-viewer-wrap">
        <div class="d-flex justify-content-between align-items-center p-2 mb-3">
            <span id="pdf-name-display" class="small fw-bold text-info text-uppercase"></span>
            <button class="btn btn-sm btn-link text-danger text-decoration-none fw-bold" onclick="cerrarVisor()">
                <i class="fas fa-times me-1"></i> CERRAR
            </button>
        </div>
        <iframe id="pdf-frame" class="full-iframe" src=""></iframe>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        cargarDocumentos();
    });

    function cargarDocumentos() {
        const contenedor = document.getElementById('contenedor-documentos');
        const loader = document.getElementById('loader');

        fetch("{{ route('recuperar.documentacion.vehicular') }}")
            .then(response => response.json())
            .then(res => {
                loader.style.display = 'none';
                if (res.documentos && res.documentos.length > 0) {
                    res.documentos.forEach(doc => {
                        const urlArchivo = `/storage/${doc.ruta_archivo}`;
                        console.log('url de archivos', urlArchivo);
                        
                        const burbujaHtml = `
                            <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <div class="file-bubble">
                                    <div class="preview-wrapper">
                                        <div class="click-layer" onclick="verPdf('${urlArchivo}', '${doc.nombre_original}')"></div>
                                        <div class="preview-overlay-icon text-white">
                                            <i class="fas fa-expand-alt fa-lg"></i>
                                        </div>
                                        <iframe src="${urlArchivo}#toolbar=0&navpanes=0&scrollbar=0"></iframe>
                                    </div>
                                    <div class="file-info-bottom">
                                        <div class="pdf-icon-square">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div class="file-details">
                                            <p class="file-name" title="${doc.nombre_original}">${doc.nombre_original}</p>
                                            <p class="file-meta">
                                                ${doc.peso_archivo} • PDF <br>
                                                <small class="opacity-75">Subido por: ${doc.nombre_usuario} ${doc.apellido_usuario_pa}</small>
                                            </p>
                                        </div>
                                        <a href="${urlArchivo}" download="${doc.nombre_original}" class="btn-download-pro">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        contenedor.insertAdjacentHTML('beforeend', burbujaHtml);
                    });
                } else {
                    contenedor.innerHTML = `
                        <div class="text-center py-5 opacity-50">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <p>No hay documentos registrados para esta unidad.</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                contenedor.innerHTML = '<p class="text-danger text-center">Error al conectar con el servidor.</p>';
            });
    }

    function verPdf(url, nombre) {
        const viewer = document.getElementById('viewer-wrap');
        const frame = document.getElementById('pdf-frame');
        const label = document.getElementById('pdf-name-display');

        label.innerHTML = `<i class="fas fa-file-invoice me-2"></i> ${nombre}`;
        frame.src = url;
        viewer.style.display = 'block';

        setTimeout(() => {
            viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }

    function cerrarVisor() {
        document.getElementById('viewer-wrap').style.display = 'none';
        document.getElementById('pdf-frame').src = '';
    }
</script>
</body>
</html>