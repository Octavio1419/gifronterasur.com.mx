<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Recorido GPS | Ruta trazada</title>
    <link rel="icon" type="image/png" href="{{ asset('img/mapas.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/viewGps.css') }}"> 
</head>
<body>

    <nav>
        <div class="logo-container">
            <img src="{{ asset('img/logo_frontera.png') }}" alt="logo">
            <div class="logo-text">Grupo Industrial<br>Frontera Sur</div>
        </div>
        <div id="darkToggle"></div>
    </nav>

    <div class="container">
        <header>
            <div class="header-info">
                <p>Análisis de Trayectoria</p>
                <h1 id="nombre_vehiculo"> Cargando nombre vehiculo... </h1>
            </div>
            <div class="header-date">
                <span style="color: var(--accent);">
                    <i class="far fa-calendar-alt"></i> {{ $fecha_i ?? 'N/A' }} 
                    <span style="margin: 0 5px; opacity: 0.5;">—</span> 
                    {{ $fecha_f ?? 'N/A' }}
                </span>
            </div>
        </header>

        <div class="dashboard-grid">
            <div class="main-panel">
                <div id="map-container">
                    <div id="map"></div>
                </div>
                <div class="tramos-card" style="margin-bottom: 25px;">
                    <h3><span><i class="fas fa-truck" style="color:var(--accent)"></i> Detalles del vehiculo</span></h3>
                    <table class="tabla-tramos">
                        <thead>
                            <tr>
                                <th>Placas</th>
                                <th>Estado Placa</th>
                                <th>Número de Serie</th>
                                <th>Tarjeta de Circulación</th>
                                <th>Última Tenencia</th>
                                <th>KM en Sistema</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="v-placas" style="font-weight: 700;">—</td>
                                <td id="v-estado">—</td>
                                <td id="v-serie" style="font-family: monospace;">—</td>
                                <td id="v-tarjeta">—</td>
                                <td id="v-tenencia">—</td>
                                <td id="v-km" style="font-weight: 700;">—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tramos-card">
                    <h3>
                        <span><i class="fas fa-gas-pump" style="color:var(--success)"></i> Transacciones de Combustible Real (Efectivale)</span>
                    </h3>
                    <div class="tabla-scroll">
                        <table class="tabla-tramos">
                            <thead>
                                <tr>
                                    <th>Fecha y Hora</th>
                                    <th>Cargado</th>
                                    <th>Dis. a cuenta</th>
                                    <th>Saldo en cuenta</th>
                                    <th>Tipo</th>
                                    <th>Concepto / Establecimiento</th>
                                    <th>Volumen / Producto</th>
                                    <th>Precio / Litro</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-efectivale">
                                <tr>
                                    <td colspan="8" style="text-align: center; color: var(--text-muted)">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando transacciones de combustible...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                 <div class="tramos-card" style="margin-top: 25px;">
                <h3>
                    <span><i class="fas fa-clipboard-check" style="color:var(--accent)"></i> Hist. Combustible Autorizados</span>
                </h3>
                <div class="timeline" id="timeline-combustible">
                    <div style="text-align: center; color: var(--text-muted); padding: 20px;" id="timeline-loading">
                        <i class="fas fa-spinner fa-spin"></i> Cargando historial de autorizaciones...
                    </div>
                </div>
            </div>
            </div>
            <aside class="metrics-sidebar">
                <div class="driver-card" id="driver-container" style="display: none;">
                    <img id="driver-img" src="" alt="Chofer" class="driver-avatar">
                    <div class="driver-info">
                        <span class="label">Operador Asignado</span>
                        <span class="name" id="driver-name">Cargando...</span>
                        <span class="phone" id="driver-phone"><i class="fas fa-phone-alt"></i> — </span>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="icon-box bg-dist"><i class="fas fa-route"></i></div>
                    <span>Recorrido</span>
                    <div class="value" id="metric-recorrido"><i class="fas fa-spinner fa-spin" style="font-size: 1.1rem; color: var(--text-muted)"></i></div>
                </div>
                <div class="metric-card">
                    <div class="icon-box bg-time"><i class="far fa-clock"></i></div>
                    <span>Tiempo Total</span>
                    <div class="value" id="metric-tiempo"><i class="fas fa-spinner fa-spin" style="font-size: 1.1rem; color: var(--text-muted)"></i></div>
                </div>
                <div class="metric-card">
                    <div class="icon-box bg-cost"><i class="fas fa-hand-holding-dollar"></i></div>
                    <span>Costo Estimado</span>
                    <div class="value" id="costo-viaje">—</div>
                </div>
                <div class="metric-card">
                    <div class="icon-box bg-fuel"><i class="fas fa-gas-pump"></i></div>
                    <span>Consumo</span>
                    <div class="value" id="litros-consumidos">—</div>
                </div>
            </aside>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inicialización inmediata del esqueleto del Mapa
        const tileLight = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution: '© CartoDB' });
        const tileDark  = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CartoDB' });
        const tileSat   = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });

        let mapa = L.map('map', { 
            zoomControl: false,
            layers: [tileLight]
        }).setView([16.75, -93.12], 11);
        
        L.control.zoom({ position: 'bottomright' }).addTo(mapa);

        const mapasBase = {
            "Mapa Claro": tileLight,
            "Mapa Oscuro": tileDark,
            "Satélite": tileSat
        };
        L.control.layers(mapasBase, null, { position: 'topright' }).addTo(mapa);

        const bounds = L.latLngBounds([]);

        const queryParams = new URLSearchParams({
            vehiculo: "{{ $vehiculo }}",
            fecha_i: "{{ $fecha_i }}",
            fecha_f: "{{ $fecha_f }}",
            id_vehiculo: "{{ $id_vehiculo }}"
        });
        //variabel con el nombre del vehiculo
        let nombre_vehiculo;
        fetch("{{ route('gps.datos.json') }}?" + queryParams.toString())
            .then(response => {
                if (!response.ok) throw new Error('No se pudieron recuperar los registros del trayecto.');
                
                return response.json();
            })
            .then(data => {
                renderizarDashboard(data);
                console.log('aca vempos que nos llega', data);
                nombre_vehiculo = data.datos_vehiculo;
                console.log('esto es el dato del vehiculo', nombre_vehiculo);
                const linea = data.datos_vehiculo[0].linea;
                const marca = data.datos_vehiculo[0].marca;
                const modelo = data.datos_vehiculo[0].modelo;
                console.log('esta es  la linea del carro ' + marca + ' ' + linea + ' ' + modelo);
                const nombre_completo_vehiculo = marca + ' ' + linea + ' ' + modelo
                document.getElementById('nombre_vehiculo').innerHTML = nombre_completo_vehiculo;

                if (data.datos_vehiculo && data.datos_vehiculo.length > 0) {
                    const v = data.datos_vehiculo[0];
                    const nombre_completo_vehiculo = `${v.marca} ${v.linea} ${v.modelo}`;
                    document.getElementById('nombre_vehiculo').innerHTML = nombre_completo_vehiculo;

                    document.getElementById('v-placas').innerText = v.placas || 'SIN PLACA';
                    document.getElementById('v-estado').innerText = v.estado_placa || 'N/A';
                    document.getElementById('v-serie').innerText = v.serie || 'N/A';
                    document.getElementById('v-tarjeta').innerText = v.tarjeta_circulacion || 'N/A';
                    document.getElementById('v-tenencia').innerText = v.ultima_tenencia || 'N/A';
                    document.getElementById('v-km').innerText = v.km_actual ? `${v.km_actual.toLocaleString()} km` : '—';
                }

            // Render de la Tarjeta del Chofer
            if (data.datos_chofer && data.datos_chofer.length > 0) {
                const chofer = data.datos_chofer[0];
                const contenedorChofer = document.getElementById('driver-container');
                
                // Formar nombre completo
                const nombreCompleto = `${chofer.nombre} ${chofer.apellido_p} ${chofer.apellido_m || ''}`.trim();
                document.getElementById('driver-name').innerText = nombreCompleto;
                document.getElementById('driver-phone').innerHTML = `<i class="fas fa-phone-alt"></i> ${chofer.telefono || 'Sin teléfono'}`;
                
                // Manejo de la foto de perfil (con fallback por si viene null)
                const imgChofer = document.getElementById('driver-img');
                if (chofer.foto_chofer) {
                    imgChofer.src = `/storage/${chofer.foto_chofer}`; 
                } else {
                    imgChofer.src = 'https://www.pngall.com/wp-content/uploads/5/Profile-Avatar-PNG.png'; 
                }
                
                contenedorChofer.style.display = 'flex'; 

                // 3. Render de Historial de Recargas Internas (Auditoría)
                const timelineContainer = document.getElementById('timeline-combustible');

                if (data.hist_recargas_combustible && data.hist_recargas_combustible.length > 0) {
                    timelineContainer.innerHTML = ''; // Limpiar spinner

                    data.hist_recargas_combustible.forEach(reg => {
                        // Formateadores de dinero
                        const fmtMXN = (val) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(val);
                        
                        // Determinar color del puntito según el estatus
                        let dotColorClass = '';
                        if (reg.estatus === 'autorizado') dotColorClass = '';
                        if (reg.estatus === 'rechazado') dotColorClass = 'danger';
                        if (reg.estatus === 'pendiente') dotColorClass = 'warning';

                        // Badge de Estatus de entrega (A_TIEMPO, etc.)
                        const badgeTiempo = reg.estatus_solicitud === 'A_TIEMPO' 
                            ? `<span class="badge-trans badge-dis" style="padding:2px 6px;">${reg.estatus_solicitud}</span>`
                            : `<span class="badge-trans badge-con" style="padding:2px 6px;">${reg.estatus_solicitud || 'N/A'}</span>`;

                        timelineContainer.innerHTML += `
                            <div class="timeline-item">
                                <div class="timeline-dot ${dotColorClass}"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="timeline-title">
                                            Folio Ant.: <strong style="color:var(--accent)">#${reg.folio_autorizacion || reg.id}</strong> 
                                            | Combustible: <strong>${reg.tipo_combustible}</strong>
                                        </span>
                                        <span class="timeline-date"><i class="far fa-clock"></i> ${reg.created_at}</span>
                                    </div>
                                    <div class="timeline-body">
                                        <div><span style="color:var(--text-muted)">Solicitado:</span> <br><strong>${fmtMXN(reg.monto_solicitado)}</strong></div>
                                        <div><span style="color:var(--text-muted)">Autorizado Real:</span> <br><strong style="color:var(--success)">${fmtMXN(reg.monto_real_cargado)}</strong></div>
                                        <div><span style="color:var(--text-muted)">KM al Cargar:</span> <br><strong>${parseInt(reg.km_actual).toLocaleString()} km</strong></div>
                                        <div><span style="color:var(--text-muted)">Despacho:</span> <br>${badgeTiempo}</div>
                                    </div>
                                    <div class="timeline-footer">
                                        ${reg.notas_chofer ? `<div><strong>Obs. Chofer:</strong> "${reg.notas_chofer}"</div>` : ''}
                                        ${reg.notas_admin ? `<div style="margin-top:2px;"><strong>Mánager:</strong> <span style="font-style: italic;">${reg.notas_admin}</span></div>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    timelineContainer.innerHTML = `
                        <div style="text-align: center; color: var(--text-muted); padding: 20px;">
                            <i class="fas fa-info-circle"></i> No hay registros de solicitudes internas para este periodo.
                        </div>`;
                }
            }

            })
            .catch(error => {
                console.error(error);
                document.getElementById('tbody-efectivale').innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--danger)">
                            <i class="fas fa-exclamation-circle"></i> Error: ${error.message}
                        </td>
                    </tr>`;
                document.getElementById('metric-recorrido').innerText = "0 km";
                document.getElementById('metric-tiempo').innerText = "N/A";
        });

        // Renderizado dinámico de la data JSON recibida
        function renderizarDashboard(data) {
            // Actualizar Métricas de la barra lateral
            document.getElementById('metric-recorrido').innerHTML = `${data.distanciaTotalKm} <small style="font-size: 0.8rem; font-weight:600;">km</small>`;
            document.getElementById('metric-tiempo').innerText = data.duracionRecorrido;

            if (data.ultimoViaje) {
                const km_r = parseFloat(data.ultimoViaje.km_recorridos) || 0;
                const precio_c = parseFloat(data.ultimoViaje.precio_combustible) || 0;
                const rend = parseFloat(data.ultimoViaje.rendimiento_x_litro) || 1;

                const costo = (km_r / rend) * precio_c;
                const litros = (km_r / rend);

                document.getElementById('costo-viaje').innerHTML = costo.toLocaleString('es-MX', {style: 'currency', currency: 'MXN'});
                document.getElementById('litros-consumidos').innerHTML = `${litros.toLocaleString('es-MX', {maximumFractionDigits:1})} <small style="font-size:0.8rem; font-weight:600">Lts</small>`;
            }

            if (data.coordenadas && data.coordenadas.length > 1) {
                L.polyline(data.coordenadas, {
                    color: '#3b82f6',
                    weight: 5,
                    opacity: 0.85,
                    lineJoin: 'round'
                }).addTo(mapa);
                data.coordenadas.forEach(p => bounds.extend(p));

                // Marcadores de Inicio y Fin
                const startIcon = L.divIcon({
                    html: '<i class="fas fa-circle-play" style="color:#10b981; font-size: 22px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.25));"></i>',
                    className: 'custom-div-icon', iconSize: [22, 22], iconAnchor: [11, 11]
                });
                const endIcon = L.divIcon({
                    html: '<i class="fas fa-location-dot" style="color:#ef4444; font-size: 22px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.25));"></i>',
                    className: 'custom-div-icon', iconSize: [22, 22], iconAnchor: [11, 11]
                });

                L.marker(data.coordenadas[0], {icon: startIcon}).addTo(mapa).bindPopup('<b>Inicio de Jornada</b>', {className: 'popup-custom'});
                L.marker(data.coordenadas[data.coordenadas.length - 1], {icon: endIcon}).addTo(mapa).bindPopup('<b>Ubicación Final</b>', {className: 'popup-custom'});
            }

            // Puntos de detención intermedios
            if (data.paradas) {
                data.paradas.forEach(p => {
                    if(p.lat && p.lon) {
                        L.circleMarker([p.lat, p.lon], {
                            radius: 7, color: '#f59e0b', fillColor: '#fbbf24', fillOpacity: 0.9, weight: 2
                        }).addTo(mapa).bindPopup(`<b>Punto de Detención</b><br>Duración: ${p.duracion || 0} min`, {className: 'popup-custom'});
                    }
                });
            }

            // Marcadores de Clientes (Locales y Foráneos combinados)
            const todosClientes = [...(data.clientes || []), ...(data.clientes_locales || [])];
            todosClientes.forEach(cliente => {
                if (cliente && cliente.coordenadas) {
                    const cCoord = cliente.coordenadas.split(',').map(Number);
                    if(cCoord.length === 2 && !isNaN(cCoord[0])) {
                        const marker = L.marker(cCoord, {
                            icon: L.icon({
                                iconUrl: "{{ asset('img/tienda.png') }}",
                                iconSize: [28, 28], iconAnchor: [14, 28]
                            })
                        }).addTo(mapa);
                        
                        const label = cliente.nombre_negocio || 'Cliente';
                        const sublabel = cliente.colonia || cliente.municipio || '';
                        marker.bindPopup(`<b>${label}</b><br>${sublabel} (${cliente.ciudad || ''})`, {className: 'popup-custom'});
                        bounds.extend(cCoord);
                    }
                }
            });

            // Auto-encuadre del mapa
            if (bounds.isValid()) {
                mapa.fitBounds(bounds, {padding: [40, 40]});
            }

            // Inyección dinámica de las filas en la tabla Efectivale
            const tbodyEfe = document.getElementById('tbody-efectivale');
            tbodyEfe.innerHTML = '';

            if(!data.consumos_efectivale || data.consumos_efectivale.length === 0) {
                tbodyEfe.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted)">
                            <i class="fas fa-info-circle"></i> No se encontraron dispersiones o consumos registrados en Efecticard para este rango de fechas.
                        </td>
                    </tr>`;
            } else {
                data.consumos_efectivale.forEach(c => {
                    const esDispersion = (c.tipo || c.Tipo) === 'DIS';
                    const badge = esDispersion ? 
                        '<span class="badge-trans badge-dis">Dispersión</span>' : 
                        '<span class="badge-trans badge-con">Consumo</span>';
                    
                    const volumen = !esDispersion ? 
                        `${parseFloat(c.litros_cargados || c.litros_cargados || 0).toFixed(2)} Lts <small style="color: var(--text-muted);">(${c.tipo_combustible || c.tipo_combustible || 'Gasolina'})</small>` : 
                        '—';
                    const formatearMXN = (valor) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(valor);
                    const montoNum = parseFloat(c.cargo || c.Cargo || c.abono || c.Abono || 0);
                    const montoFormateado = montoNum.toLocaleString('es-MX', {style: 'currency', currency: 'MXN'});
                    const colorMonto = esDispersion ? 'var(--success)' : 'var(--primary)';

                    // Formateador rápido de fecha en JS
                    const fechaRaw = `${c.fecha} ${c.hora || '00:00:00'}`;
                    
                    tbodyEfe.innerHTML += `
                        <tr>
                            <td>${c.fecha} ${c.hora || ''}</td>
                            <td>${formatearMXN(c.cargado)}</td>
                            <td>${formatearMXN(c.dis_a_cuenta)}</td>
                            <td>${formatearMXN(c.saldo_cuenta)}</td>
                            <td>${badge}</td>
                            <td>
                                <strong>${c.concepto || c.Concepto || 'Transacción de Combustible'}</strong><br>
                                <small style="color: var(--text-muted);">${c.ciudad || c.Ciudad || ''}, ${c.estado || c.Estado || ''}</small>
                            </td>
                            <td>${volumen}</td>
                            <td style="font-weight: 700; color: ${colorMonto}">${formatearMXN(c.precio_combustible)}</td>
                        </tr>`;
                });
            }
        }

        // Sincronización del Modo Oscuro
        const toggle = document.getElementById("darkToggle");
        if(document.body.classList.contains('dark')) {
            mapa.removeLayer(tileLight);
            mapa.addLayer(tileDark);
        }

        toggle.addEventListener("click", () => {
            const isDark = document.body.classList.toggle("dark");
            if(isDark) {
                mapa.removeLayer(tileLight);
                mapa.addLayer(tileDark);
            } else {
                mapa.removeLayer(tileDark);
                mapa.addLayer(tileLight);
            }
        });     
    </script>
</body>
</html>