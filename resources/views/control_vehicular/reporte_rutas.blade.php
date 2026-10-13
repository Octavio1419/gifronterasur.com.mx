@extends('layout.index')
@section('content')
<main class="main-content">
    <div class="container-fluid content-inner mt-n5 py-0">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="display-6 fw-bold">REPORTE DE RUTAS POR GPS</h2>
                    <p class="text-muted">Gestiona y visualiza el recorrido de las unidades en tiempo real.</p>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card report-card shadow-sm mb-4" >
                    <div class="card-body">
                        <h5 class="card-title mb-4"><span class="step-number">1</span>Selección de Unidad</h5>
                        <div class="form-group mb-4">
                            <label class="form-label" for="selector_vehiculos">Vehículo</label>
                            <select class="form-select shadow-none p-3" id="selector_vehiculos">
                                </select>
                        </div>
                        <button class="btn btn-primary w-100 shadow-sm" type="button" id="buscar_vehiculo">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Vehículo
                        </button>
                    </div>
                </div>
 
                <div id="fechas_rango" class="card report-card shadow-sm section-disabled">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><span class="step-number">2</span>Rango de Fecha</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Fecha de Inicio</label>
                                <input id="startDate" class="form-control p-3" type="date" max="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Fecha Final</label>
                                <input id="endDate" class="form-control p-3" type="date" />
                            </div>
                            <div class="col-12 mt-4">
                                <button class="btn btn-success w-100 p-3 fw-bold shadow" type="button" id="genera_consulta_rutas" disabled>
                                    <i class="fas fa-map-marked-alt me-2"></i>GENERAR RUTAS
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
           <div class="col-lg-8">
                <div class="card report-card shadow-sm">
                    <div class="card-header bg-white py-2" id="header_mapa" style="display: flex !important; justify-content: space-between; align-items: center;">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-map-marked-alt me-2"></i>Vista trazada del recorrido
                        </h5>
                        <button class="btn btn-danger btn-sm shadow-sm" id="genera_pdf_rutas" disabled>
                            <i class="fas fa-file-pdf me-1"></i> Descargar PDF
                        </button>
                    </div>
                    
                    <div class="card-body p-0">
                        <div id="map" style="height: 620px; width: 100%; border-radius: 0 0 15px 15px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="qr-temp" style="display: none;"></div>
    
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
      
<img id="logo_frontera" src="{{ asset('img/logo_frontera_sin.png') }}" style="display:none;">
<img id="logo_m&s" src="{{ asset('img/vss_logo.jpeg') }}" style="display:none;">
<img id="icono_gas" src="{{ asset('img/icono_gas.svg') }}" style="display:none;">
  
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet-image/leaflet-image.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
    .report-card { border-radius: 15px; border: none; transition: all 0.3s ease; }
    .step-number { 
        width: 30px; height: 30px; background: #0d6efd; color: white; 
        display: inline-flex; align-items: center; justify-content: center; 
        border-radius: 50%; font-weight: bold; margin-right: 10px;
    }
    .section-disabled { 
        pointer-events: none; opacity: 0.5; filter: grayscale(1); 
        transition: all 0.4s ease;
    }
    .main-content { padding-top: 80px; background-color: #f4f7f9; min-height: 100vh; }
    .btn-primary { border-radius: 8px; padding: 10px 20px; font-weight: 600; }
    .form-label { font-weight: 600; color: #444; }
</style>
<script>
  $(document).ready(function() {
    // --- VARIABLES GLOBALES ---
    var nombre_interno_vehiculo = 0;
    var id_vehiculo = 0;
    var nombre_vehiculo = 0;
    let mapaInstancia = null;
    let imagenMapaGlobal = null;

    function initMapDefault() {
        if (mapaInstancia) { mapaInstancia.remove(); }
       
        mapaInstancia = L.map('map').setView([20.967, -89.623], 12); 
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            crossOrigin: true 
        }).addTo(mapaInstancia);
        
        $('#genera_pdf_rutas').prop('disabled', true);
    }
    
    // Ejecutar mapa por defecto al cargar la página
    initMapDefault();

    selectorVehiculos();

    function selectorVehiculos() {
        fetch('/recuperar-vehiculo')
            .then(response => response.json())
            .then(respuesta => {
                let selector = document.getElementById('selector_vehiculos');
                selector.innerHTML = '<option value="">Selecciona el vehículo</option>';
                let resp = respuesta.vehiculo;
                resp.forEach(carros => {
                    let option = document.createElement('option');
                    option.value = carros.nombre_in_vehiculo;
                    option.textContent = carros.nombre_rel_vehiculo;
                    option.setAttribute('data-idvehiculo', carros.id);
                    selector.appendChild(option);
                });
                $('#selector_vehiculos').val('').change();
            })
            .catch(err => console.error('Error recuperando vehiculos:', err));
    }

    $('#buscar_vehiculo').click(function() {
        nombre_interno_vehiculo = $('#selector_vehiculos').val();
        nombre_vehiculo = $('#selector_vehiculos option:selected').text();
        id_vehiculo = $('#selector_vehiculos option:selected').data('idvehiculo');

        if (nombre_interno_vehiculo) {
            $('#fechas_rango').removeClass('section-disabled').css({'opacity': '1', 'pointer-events': 'auto'});
            $('#startDate, #endDate, #genera_consulta_rutas').prop('disabled', false);
            
            Swal.fire({
                icon: 'success',
                title: 'Vehículo seleccionado',
                text: 'Ahora define el rango de fechas.',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            Swal.fire('Error', 'Por favor, selecciona un vehículo.', 'error');
        }
    });

    $('#genera_consulta_rutas').off('click').click(async function() {
        const fecha_i = document.getElementById('startDate').value;
        const fecha_f = document.getElementById('endDate').value;

        if (!fecha_i || !fecha_f) {
            Swal.fire('Atención', 'Seleccione ambas fechas antes de continuar.', 'warning');
            return;
        }

        if (fecha_f < fecha_i) {
            Swal.fire('Error', 'La fecha final no puede ser menor a la de inicio.', 'error');
            return;
        }

        Swal.fire({
            title: 'Trazando Rutas...', 
            html: `
                <div class="text-center mt-2">
                    <i class="fas fa-satellite-dish fa-spin fa-3x text-primary mb-3"></i>
                    <p style="font-size: 1.1rem; font-weight: 500;">
                        Consultando datos del servidor
                    </p>
                    <p class="text-muted small">
                        Estamos procesando las coordenadas GPS
                    </p>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            width: '500px',
            padding: '2rem',
            customClass: {
                popup: 'shadow-lg border-0 rounded-20', 
                title: 'fw-bold text-primary',
            },
        });

        try {
            const url = `/recupera-datos-de-rutas/${encodeURIComponent(nombre_interno_vehiculo)}/${encodeURIComponent(fecha_i)}/${encodeURIComponent(fecha_f)}/${encodeURIComponent(id_vehiculo)}`;
            const resp = await fetch(url);
            if (!resp.ok) throw new Error('Error en la respuesta del servidor');
            const respuestaServidor = await resp.json();

            await trazarMapasOptimizado(respuestaServidor, { fecha_i, fecha_f });

            Swal.close();

            if (respuestaServidor.viajes && respuestaServidor.viajes.length > 0) {
                $('#genera_pdf_rutas').prop('disabled', false);
                generaPDF(respuestaServidor, { fecha_i, fecha_f });
            } else {
                $('#genera_pdf_rutas').prop('disabled', true);
            }

        } catch (err) {
            console.error('Error:', err);
            Swal.fire('Error', 'No se pudieron recuperar los datos del servidor.', 'error');
        }
    });

    async function trazarMapasOptimizado(respuesta, meta = {}) {
        if (mapaInstancia) {
            mapaInstancia.remove();
            mapaInstancia = null;
        }

        const mapDiv = document.getElementById('map');
        if (!respuesta.viajes || respuesta.viajes.length === 0) {
            mapDiv.innerHTML = '<div class="alert alert-warning m-3">No hay viajes registrados en este periodo.</div>';
            return;
        }

        const primeraCoord = respuesta.viajes[0].coordenadas_salida.split(',').map(Number);
        mapaInstancia = L.map('map').setView(primeraCoord, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19,
            crossOrigin: true 
        }).addTo(mapaInstancia);

        const bounds = L.latLngBounds([]);

        for (let i = 0; i < respuesta.viajes.length; i++) {
            const viaje = respuesta.viajes[i];
            const salida = viaje.coordenadas_salida.split(',').map(Number);
            const llegada = viaje.coordenadas_llegada.split(',').map(Number);

            bounds.extend(salida);
            bounds.extend(llegada);

            L.circleMarker(salida, { color: 'green', radius: 6, fillOpacity: 0.8 })
                .addTo(mapaInstancia)
                .bindPopup(`<b>Salida:</b> ${viaje.hora_inicio}<br>${viaje.direccion_salida}`);

            L.circleMarker(llegada, { color: 'red', radius: 6, fillOpacity: 0.8 })
                .addTo(mapaInstancia)
                .bindPopup(`<b>Llegada:</b> ${viaje.hora_fin}<br>${viaje.direccion_llegada}`);

            let coords = await obtenerRutaConRetries(salida, llegada, i);
            if (!coords) coords = [salida, llegada]; 

            L.polyline(coords, { color: '#0d6efd', weight: 4, opacity: 0.7 }).addTo(mapaInstancia);
        }

        mapaInstancia.fitBounds(bounds, { padding: [50, 50] });

        await new Promise(r => setTimeout(r, 1000));
        await capturarImagenMapaPromesa();
    }

    async function obtenerRutaConRetries(salida, llegada, index) {
        const url = `https://router.project-osrm.org/route/v1/driving/${salida[1]},${salida[0]};${llegada[1]},${llegada[0]}?overview=full&geometries=geojson`;
        try {
            const resp = await fetch(url);
            const data = await resp.json();
            if (data.code === 'Ok') return data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
        } catch (e) { console.warn(`Viaje ${index + 1}: Falló ruta.`); }
        return null;
    }

    function capturarImagenMapaPromesa() {
        return new Promise((resolve) => {
            leafletImage(mapaInstancia, function(err, canvas) {
                if (!err) {
                    imagenMapaGlobal = canvas.toDataURL('image/png');
                }
                resolve();
            });
        });
    }
    function generaPDF(respuesta, meta = {}) {
        $('#genera_pdf_rutas').off('click').on('click', function () {
            let datos_de_rutas = respuesta;
            let fecha_i = meta.fecha_i || document.getElementById('startDate').value;
            let fecha_f = meta.fecha_f || document.getElementById('endDate').value;

            console.log('Datos que nos manda el servidor', datos_de_rutas);

            let parametros = btoa(nombre_interno_vehiculo + '/' + fecha_i + '/' + fecha_f + '/' + id_vehiculo);
            console.log('estos son la url para la vista derl mapa', parametros);
            let urlQR = 'http://192.168.100.238:8080/view/gps/ruta/' + parametros;

            let qrContainer = document.getElementById('qr-temp');
            qrContainer.innerHTML = '';

            new QRCode(qrContainer, {
                text: urlQR,
                width: 150,
                height: 150
            });

            let qrInterval = setInterval(() => {
                let qrImg = qrContainer.querySelector('img');
                if (qrImg) {
                    clearInterval(qrInterval);
                    let qrDataURL = qrImg.src;
                    generarPDFConQR(datos_de_rutas, qrDataURL, urlQR, { fecha_i, fecha_f });
                }
            }, 100);
        });
    }

   function generarPDFConQR(respuesta, qrDataURL, urlQR, meta) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

        const COLOR_PRIMARY = [15, 23, 42];      
        const COLOR_ACCENT = [0, 145, 36];    
        const COLOR_TEXT_MUTED = [120, 120, 120]; 
        const COLOR_TEXT_DARK = [51, 65, 85];     
        const COLOR_BORDER = [184, 184, 184];    
        const COLOR_CEBRA = [250, 251, 253];    

        const ultimo_viaje = respuesta.ultimo_viaje || {};
        const km_recorridos = Number(ultimo_viaje.km_recorridos || 0);
        const rendimiento_x_litro = Number(ultimo_viaje.rendimiento_x_litro || 1);
        const precio_combustible = Number(ultimo_viaje.precio_combustible || 0);

        const costo_viaje = (km_recorridos / rendimiento_x_litro) * precio_combustible;
        const gasolina_viaje = (km_recorridos / rendimiento_x_litro);

        const consumos_efectivale = respuesta.consumos || [];
        let total_costo_efectivale = 0;
        let total_litros_efectivale = 0;

        consumos_efectivale.forEach(item => {
            total_costo_efectivale += Number(item.cargado || 0);
            total_litros_efectivale += Number(item.litros_cargados || 0);
        });

        const img1 = document.getElementById('logo_frontera');
        const img2 = document.getElementById('logo_m&s');
        const img3 = document.getElementById('icono_gas');

        let imgData1 = null, imgData2 = null, imgData3 = null;
        try {
            if (img1) {
                const canvas1 = document.createElement('canvas');
                canvas1.width = img1.naturalWidth; canvas1.height = img1.naturalHeight;
                canvas1.getContext('2d').drawImage(img1, 0, 0);
                imgData1 = canvas1.toDataURL('image/png');
            }
            if (img2) {
                const canvas2 = document.createElement('canvas');
                canvas2.width = img2.naturalWidth; canvas2.height = img2.naturalHeight;
                canvas2.getContext('2d').drawImage(img2, 0, 0);
                imgData2 = canvas2.toDataURL('image/png');
            }
            if (img3) {
                const canvas3 = document.createElement('canvas');
                canvas3.width = img3.naturalWidth; canvas3.height = img3.naturalHeight;
                canvas3.getContext('2d').drawImage(img3, 0, 0);
                imgData3 = canvas3.toDataURL('image/png');
            }
        } catch (err) { console.warn('Error imágenes:', err); }

        const dibujarHeaderPagina = (documento, esHorizontal = false) => {
            if (imgData2) documento.addImage(imgData2, 'PNG', 15, 6, 38, 10);
            if (imgData1) {
                const posX = esHorizontal ? 242 : 157;
                documento.addImage(imgData1, 'PNG', posX, 6, 38, 10);
            }
        };

        dibujarHeaderPagina(doc, false);

        // --- ENCABEZADO PRINCIPAL  ---
        doc.setFillColor(...COLOR_PRIMARY);
        doc.rect(15, 22, 180, 12, 'F'); 

        doc.setFont("helvetica", "bold");
        doc.setFontSize(14);
        doc.setTextColor(255, 255, 255);
        doc.text("REPORTE DE CONTROL DE RUTA VEHICULAR", 20, 29.5);

        const formatearFechaSimple = (fechaStr) => {
            if(!fechaStr) return '';
            return fechaStr.split('-').reverse().join('/');
        };
        doc.setFont("helvetica", "bold");
        doc.setFontSize(10);
        doc.setTextColor(255,255,255);
        const textoFechas = `${formatearFechaSimple(meta.fecha_i)} - ${formatearFechaSimple(meta.fecha_f)}`;
        doc.text(textoFechas, 190 - doc.getTextWidth(textoFechas), 29.5);

        let startY = 38;
        doc.setFontSize(10);
        doc.setTextColor(...COLOR_TEXT_MUTED);
        doc.text("UNIDAD OPERATIVA:", 15, startY);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(...COLOR_PRIMARY);
        doc.text(nombre_vehiculo || 'N/A', 53, startY);

        doc.setFont("helvetica", "normal");
        doc.setTextColor(...COLOR_TEXT_MUTED);
        doc.text("Costo Sistema:", 95, startY);
        doc.setTextColor(...COLOR_PRIMARY);
        //CONVERSION A PESOS MX
        const formatearMXN = (valor) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(valor);
        doc.text(`${formatearMXN(costo_viaje)}`, 120, startY);
    

        doc.setTextColor(...COLOR_TEXT_MUTED);
        doc.text("Costo Efectivale:", 140, startY);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(...COLOR_ACCENT); 
        doc.text(`${formatearMXN(total_costo_efectivale)}`, 168, startY);

        doc.setDrawColor(...COLOR_PRIMARY);
        doc.setLineWidth(0.4);
        doc.line(15, 41, 195, 41);

        let yCards = 46;
        //  Viajes
        doc.setFillColor(248, 250, 252);
        doc.rect(15, yCards, 40, 18, 'F');
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.setTextColor(...COLOR_TEXT_MUTED);
        doc.text("VIAJES REGISTRADOS", 19, yCards + 5);
        doc.setFont("helvetica", "bold");
        doc.setFontSize(14);
        doc.setTextColor(...COLOR_PRIMARY);
        const total_viajes = respuesta.total_viajes || (respuesta.viajes ? respuesta.viajes.length : 0);
        doc.text(`${total_viajes}`, 19, yCards + 13);

        //  Distancia
        doc.setFillColor(248, 250, 252);
        doc.rect(59, yCards, 42, 18, 'F');
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.setTextColor(...COLOR_TEXT_MUTED);
        doc.text("DISTANCIA TOTAL", 63, yCards + 5);
        doc.setFont("helvetica", "bold");
        doc.setFontSize(14);
        doc.text(`${respuesta.distancia_total_km || 0} Km`, 63, yCards + 13);

        //  Horas de motor
        doc.setFillColor(248, 250, 252);
        doc.rect(105, yCards, 42, 18, 'F');
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.setTextColor(...COLOR_TEXT_MUTED);
        doc.text("DURACIÓN EN RUTA", 109, yCards + 5);
        
        const hora_total = respuesta.duracion_total || "00:00:00";
        const partes = hora_total.split(":");
        const horas = parseInt(partes[0] || '0', 10);
        const minutes = parseInt(partes[1] || '0', 10);
        let duracion_formateada = horas >= 24 ? `${Math.floor(horas / 24)}d ${horas % 24}h ${minutes}m` : `${horas}h ${minutes}m`;
        
        doc.setFont("helvetica", "bold");
        doc.setFontSize(14);
        doc.text(duracion_formateada, 109, yCards + 13);

        // QR Area
        doc.setDrawColor(226, 232, 240);
        doc.setLineWidth(0.2);
        doc.rect(150, yCards, 49, 25, 'S');
        doc.addImage(qrDataURL, 'PNG', 153, yCards + 2.5, 20, 20);
        doc.setFontSize(6.5);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(...COLOR_PRIMARY);
        doc.textWithLink('TRACKING DIGITAL', 175, yCards + 7, { url: urlQR });
        doc.setFont("helvetica", "normal");
        doc.setTextColor(...COLOR_TEXT_MUTED);
        doc.text('Escanea mapa', 175, yCards + 12);

        // --- TABLA DE CONSUMOS EFECTIVALE ---
        startY = 73; 

        if (consumos_efectivale.length > 0) {
            doc.setFontSize(10);
            doc.setFont("helvetica", "bold");
            doc.setTextColor(...COLOR_PRIMARY);
            doc.text("HIST. DE CONSUMOS (EFECTIVALE)", 15, startY);
            startY += 4;

            const colX = [15, 37, 55, 75, 98, 142, 166, 195]; 

            // Encabezado estilizado con la nueva paleta oscura
            doc.setFillColor(...COLOR_PRIMARY); 
            doc.rect(colX[0], startY, colX[7] - colX[0], 6, 'F');

            doc.setFontSize(7);
            doc.setFont("helvetica", "bold");
            doc.setTextColor(255, 255, 255);
            
            doc.text("Fecha / Hora", colX[0] + 2, startY + 4.2);
            doc.text("Cargado", colX[1] + 2, startY + 4.2);
            doc.text("Disp. a cuenta", colX[2] + 2, startY + 4.2);
            doc.text("Saldo en cuenta", colX[3] + 2, startY + 4.2);
            doc.text("Concepto / Estación de Servicio", colX[4] + 2, startY + 4.2);
            doc.text("Precio Litro", colX[5] + 2, startY + 4.2);
            doc.text("Volumen", colX[6] + 2, startY + 4.2);
            
            startY += 6;

            let indexFila = 0;
            const limpiarHTML = (txt) => {
                if(!txt) return '';
                return txt.replace(/&oacute;/g, 'ó').replace(/&aacute;/g, 'á')
                        .replace(/&eacute;/g, 'é').replace(/&iacute;/g, 'í')
                        .replace(/&uacute;/g, 'ú').replace(/&Ntilde;/g, 'Ñ')
                        .replace(/&ntilde;/g, 'ñ');
            };

            consumos_efectivale.forEach((item) => {
                doc.setFont("helvetica", "normal");
                doc.setFontSize(6.5);
                
            const fecha_hora = `${item.fecha || ''} ${item.hora || ''}`;
            const precio_gas = `${formatearMXN(Number(item.precio_combustible || 0))}`;
            const litros     = `${Number(item.litros_cargados || 0).toFixed(2)} L`; // Se mantiene en Litros (L)
            const cargado    = `${formatearMXN(Number(item.cargado || 0))}`;
            const dis_a_cuenta = `${formatearMXN(Number(item.dis_a_cuenta || 0))}`;
            const saldo_cuenta = `${formatearMXN(Number(item.saldo_cuenta || 0))}`;

            const conceptoCompleto = limpiarHTML(item.concepto || 'Consumo');
            const anchoColConcepto = (colX[5] - colX[4]) - 3;
            const lineasConcepto = doc.splitTextToSize(conceptoCompleto, anchoColConcepto);
                
                const altoLineaTexto = 3.2; 
                const altoFila = Math.max(5.8, (lineasConcepto.length * altoLineaTexto) + 2);

                if (startY + altoFila > 275) {
                    doc.addPage();
                    dibujarHeaderPagina(doc, false);
                    startY = 22;

                    doc.setFillColor(...COLOR_PRIMARY); 
                    doc.rect(colX[0], startY, colX[7] - colX[0], 6, 'F');
                    doc.setTextColor(255, 255, 255);
                    doc.setFont("helvetica", "bold");
                    doc.text("Fecha / Hora", colX[0] + 2, startY + 4.2);
                    doc.text("Cargado", colX[1] + 2, startY + 4.2);
                    doc.text("Disp. a cuenta", colX[2] + 2, startY + 4.2);
                    doc.text("Saldo en cuenta", colX[3] + 2, startY + 4.2);
                    doc.text("Concepto / Estación de Servicio", colX[4] + 2, startY + 4.2);
                    doc.text("Precio Litro", colX[5] + 2, startY + 4.2);
                    doc.text("Volumen", colX[6] + 2, startY + 4.2);
                    startY += 6;
                }

                if (indexFila % 2 === 0) {
                    doc.setFillColor(...COLOR_CEBRA);
                    doc.rect(colX[0], startY, colX[7] - colX[0], altoFila, 'F');
                }

                doc.setTextColor(...COLOR_TEXT_DARK);
                const paddingTop = 3.8;
                doc.text(fecha_hora, colX[0] + 0.5, startY + paddingTop);
                doc.text(cargado, colX[1] + 1.5, startY + paddingTop);
                doc.text(dis_a_cuenta, colX[2] + 1.5, startY + paddingTop);
                doc.text(saldo_cuenta, colX[3] + 1.5, startY + paddingTop);
                doc.text(lineasConcepto, colX[4] + 1.5, startY + paddingTop);
                doc.text(precio_gas, colX[5] + 1.5, startY + paddingTop);
                doc.text(litros, colX[6] + 1.5, startY + paddingTop);

                // Estructura grid sutil sin sobrecargar de tinta
                doc.setLineWidth(0.1);
                doc.setDrawColor(...COLOR_BORDER);
                doc.rect(colX[0], startY, colX[7] - colX[0], altoFila, 'S');

                for (let k = 1; k < colX.length; k++) {
                    doc.line(colX[k], startY, colX[k], startY + altoFila);
                }

                startY += altoFila;
                indexFila++;
            });
            startY += 8; 
        }

        if (startY > 235) { 
            doc.addPage();
            dibujarHeaderPagina(doc, false);
            startY = 22;
        }

        doc.setFontSize(10);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(...COLOR_PRIMARY);
        doc.text("DETALLE DE RECORRIDOS", 15, startY);
        startY += 5;

        const viajes = respuesta.viajes || [];
        const colWidth = 58;
        const paddingX = 4;

        for (let i = 0; i < viajes.length; i += 3) {
            let maxBlockHeight = 0;
            let calculosColumnas = [];

            for (let j = 0; j < 3 && (i + j) < viajes.length; j++) {
                const viaje = viajes[i + j];
                doc.setFontSize(7.5);
                
                const origenLineas = doc.splitTextToSize(`Orig: ${viaje.direccion_llegada || 'N/R'}`, colWidth - paddingX);
                const destinoLineas = doc.splitTextToSize(`Dest: ${viaje.direccion_salida || 'N/R'}`, colWidth - paddingX);
                
                const totalLineasCalculadas = 4 + origenLineas.length + destinoLineas.length; 
                const alturaContenedor = (totalLineasCalculadas * 4) + 4;

                if (alturaContenedor > maxBlockHeight) maxBlockHeight = alturaContenedor;
                calculosColumnas.push({ viaje, origenLineas, destinoLineas });
            }

            if (startY + maxBlockHeight > 275) {
                doc.addPage();
                dibujarHeaderPagina(doc, false);
                startY = 22;
            }

            for (let j = 0; j < calculosColumnas.length; j++) {
                const { viaje, origenLineas, destinoLineas } = calculosColumnas[j];
                const offsetX = 15 + (j * 61);

                doc.setFillColor(253, 254, 255);
                doc.setLineWidth(0.15);
                doc.setDrawColor(218, 226, 237);
                doc.rect(offsetX, startY, colWidth, maxBlockHeight, 'DF');

                doc.setFillColor(...COLOR_ACCENT);
                doc.rect(offsetX, startY, 1.2, maxBlockHeight, 'F');

                let yInterna = startY + 4.5;

                doc.setFont("helvetica", "bold");
                doc.setFontSize(8);
                doc.setTextColor(...COLOR_PRIMARY);
                doc.text(`${viaje.destino_cliente || 'Ruta Local'}`, offsetX + 3, yInterna);
                yInterna += 4;

                doc.setFont("helvetica", "normal");
                doc.setFontSize(6.8);
                doc.setTextColor(140, 140, 140);
                doc.text(`${viaje.hora_inicio || ''} - ${viaje.hora_fin || ''}`, offsetX + 3, yInterna);
                yInterna += 4.5;

                doc.setTextColor(...COLOR_TEXT_DARK);
                doc.text(origenLineas, offsetX + 3, yInterna);
                yInterna += (origenLineas.length * 3.6);

                doc.text(destinoLineas, offsetX + 3, yInterna);
                yInterna += (destinoLineas.length * 3.6) + 1;

                doc.setFontSize(6.8);
                doc.setTextColor(...COLOR_TEXT_MUTED);
                doc.text(`Tiempo: ${viaje.duracion || 'N/A'}`, offsetX + 3, yInterna);
                yInterna += 3.5;

                doc.text(`Distancia: ${viaje.distancia_km || 0} km`, offsetX + 3, yInterna);
                yInterna += 4;

                if (viaje.link_ruta) {
                    doc.setFontSize(6.5);
                    doc.setTextColor(...COLOR_ACCENT);
                    doc.setFont("helvetica", "bold");
                    doc.textWithLink('IR AL MAPA', offsetX + 4, yInterna, { url: viaje.link_ruta });
                }
            }
            startY += maxBlockHeight + 6;
        }

        // --- HOJA HORIZONTAL FINAL: MAPA ---
        /*
        if (typeof imagenMapaGlobal !== 'undefined' && imagenMapaGlobal) {
            doc.addPage('a4', 'l');
            dibujarHeaderPagina(doc, true);

            doc.setFontSize(12);
            doc.setFont("helvetica", "bold");
            doc.setTextColor(...COLOR_PRIMARY);
            doc.text("TRAZADO GEOGRÁFICO DE RECORRIDOS", 15, 22);

            const pageWidth = doc.internal.pageSize.getWidth();

            doc.setDrawColor(218, 226, 237);
            doc.setLineWidth(0.2);
            doc.rect(14, 26, pageWidth - 28, doc.internal.pageSize.getHeight() - 40, 'S');

            doc.addImage(imagenMapaGlobal, 'PNG', 14.5, 26.5, pageWidth - 29, doc.internal.pageSize.getHeight() - 41);
        }
        */
        doc.save(`Reporte_Ruta_${nombre_vehiculo || 'Unidad'}_${meta.fecha_i}.pdf`);
    }
        

});

</script>

@endsection


