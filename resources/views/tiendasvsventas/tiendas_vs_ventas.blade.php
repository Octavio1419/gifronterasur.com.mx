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
                <h1>Tiendas Vs Ventas</h1>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="iq-header-img">
        <img src="{{ asset('img/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
      </div>
    </div>

    <!-- Modal de ayuda -->
    <div class="modal fade" id="ayudaModal" tabindex="-1" aria-labelledby="ayudaModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ayudaModalLabel">Instrucciones</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            El sistema retomará las fechas cargadas en nuestra Base de datos para poder comparar entre ambas tiendas, esto para generar un reporte consolidado de las ventas realizadas en cada una de ellas y sacar su venta menos productiva.
            <br>
            <strong>Ejemplo:</strong> Podrás realizar el reporte de los meses que tú desees, siempre y cuando la fecha inicial no sea mayor a la fecha final.
            <br>
            <strong>Nota:</strong> El reporte se generará en formato PDF, y se descargará automáticamente después de dar clic en el botón (Generar PDF).
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
      <div class="col-lg-12">
        <div class="card rounded">
          <div class="card-body">
            <div class="row mt-4" id="fechas_rango">
              <strong>Seleccione el rango de fecha
                <a href="#" data-bs-toggle="modal" data-bs-target="#ayudaModal"><i class="fas fa-question-circle"></i></a>
              </strong>
              <br><br>
              <div class="col-md-6">
                <label for="startDate"><strong>FECHA INICIO</strong></label>
                <input id="startDate" class="form-control" type="date" max="<?php echo date('Y-m-d'); ?>" />
              </div>

              <div class="col-md-6">
                <label for="endDate"><strong>FECHA FINAL</strong></label>
                <input id="endDate" class="form-control" type="date" />
              </div>

              <div class="mt-4 d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-primary" type="button" id="genera_reporte" disabled>
                  Generar PDF <i class="fa-solid fa-file-pdf"></i>
                </button>
              </div>
            </div>

            <!-- Visualización -->
            <div class="row mt-5" style="display: none;" id="resumenGrafico">
              <div class="col-12">
                <h5 class="mb-3">Resumen: Día menos vendido por mes (por tienda)</h5>
                <canvas id="graficoDias" height="120"></canvas>
              </div>
              <div class="col-12 mt-4">
                <div class="table-responsive">
                  <table class="table table-striped" id="tablaResumen">
                    <thead>
                      <tr>
                        <th>Mes</th>
                        <th>Tienda</th>
                        <th>Fecha menos vendida</th>
                        <th>Total del día</th>
                        <th>Semana del mes</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
                <small class="text-muted">La semana se calcula con inicio en lunes.</small>
              </div>
            </div>
            <div class="col-12 mt-5" style="display: none" id="promedioVentas">
                <h5>Promedio de ventas por día de la semana</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tablaPorDia">
                    <thead>
                        <tr>
                        <th>Día</th>
                        <th>Tienda</th>
                        <th>Total</th>
                        <th>Cantidad de días</th>
                        <th>Promedio</th>
                        <th>% del total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Libs necesarias -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 (usado por tus validaciones) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js para la visualización -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- jsPDF + AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
  // --- Estado global
  let teran, quinta;
  let sucursalesReady = false;
  let chartInstance = null;

  // --- Utilidades de fecha / mes / semana
  function getMonthKey(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    return `${y}-${m}`; // YYYY-MM
  }

  function formatMonthES(monthKey) {
    const [y, m] = monthKey.split('-').map(Number);
    const d = new Date(y, m - 1, 1);
    return d.toLocaleDateString('es-MX', { month: 'long', year: 'numeric' });
  }

  // Lunes = 0 ... Domingo = 6
  function mondayIndex(dow) { return (dow + 6) % 7; }

  function weeksInMonth(year, monthZeroBased) {
    const first = new Date(year, monthZeroBased, 1);
    const last = new Date(year, monthZeroBased + 1, 0);
    const firstDow = mondayIndex(first.getDay()); // 0..6
    const days = last.getDate();
    return Math.ceil((firstDow + days) / 7);
  }

  function weekOfMonth(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    const first = new Date(d.getFullYear(), d.getMonth(), 1);
    const firstDow = mondayIndex(first.getDay()); // 0..6
    return Math.floor((firstDow + d.getDate() - 1) / 7) + 1;
  }

  // --- Normalización y cálculo por mes
  function normalizeDias(dias) {
    return dias.map(x => ({
      fecha: x.fecha_pago,
      total: Number(x.total_dia) // puede venir string; lo forzamos a número
    }));
  }
  // Nombres de los días de la semana
    const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

// Agrupa ventas por día de la semana
function groupByWeekday(data) {
  const result = Array(7).fill(null).map(() => ({ total: 0, count: 0 }));

  data.forEach(entry => {
    const date = new Date(entry.fecha + 'T00:00:00');
    const dayIndex = (date.getDay() + 6) % 7; // Lunes = 0, Domingo = 6
    result[dayIndex].total += entry.total;
    result[dayIndex].count += 1;
  });

  return result.map((entry, index) => {
    const promedio = entry.count > 0 ? entry.total / entry.count : 0;
    return {
      dia: diasSemana[index],
      total: entry.total,
      cantidad: entry.count,
      promedio,
    };
  });
}

// Agrega % de participación por día
function calcularPorcentajeDias(dataResumen) {
  const totalGlobal = dataResumen.reduce((acc, d) => acc + d.total, 0);
  return dataResumen.map(item => ({
    ...item,
    porcentaje: totalGlobal > 0 ? (item.total / totalGlobal) * 100 : 0
  }));
}


  function minByMonth(dias) {
    const map = {};
    dias.forEach(item => {
      const key = getMonthKey(item.fecha);
      if (!map[key] || item.total < map[key].total) {
        const d = new Date(item.fecha + 'T00:00:00');
        map[key] = {
          mes: key,
          fecha: item.fecha,
          total: item.total,
          semana: weekOfMonth(item.fecha),
          semanas_mes: weeksInMonth(d.getFullYear(), d.getMonth())
        };
      }
    });
    return Object.values(map).sort((a, b) => a.mes.localeCompare(b.mes));
  }

  function mergeMonthLabels(r1, r2) {
    const set = new Set([
      ...r1.map(r => r.mes),
      ...r2.map(r => r.mes)
    ]);
    return Array.from(set).sort();
  }

  // --- Render del gráfico
  function renderChart(resTeran, resQuinta) {
    const labelsKeys = mergeMonthLabels(resTeran, resQuinta);
    const labels = labelsKeys.map(formatMonthES);

    const mapTeran = new Map(resTeran.map(r => [r.mes, r]));
    const mapQuinta = new Map(resQuinta.map(r => [r.mes, r]));

    const dataTeran = labelsKeys.map(k => mapTeran.get(k)?.total ?? null);
    const dataQuinta = labelsKeys.map(k => mapQuinta.get(k)?.total ?? null);

    const metasTeran = labelsKeys.map(k => mapTeran.get(k) || null);
    const metasQuinta = labelsKeys.map(k => mapQuinta.get(k) || null);

    if (chartInstance) chartInstance.destroy();

    const ctx = document.getElementById('graficoDias').getContext('2d');
    chartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          { label: 'Tienda Herralum Bvoulevard', data: dataTeran },
          { label: 'Tienda Herralum 5ta', data: dataQuinta }
        ]
      },
      options: {
        responsive: true,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
          tooltip: {
            callbacks: {
              afterLabel: (ctx) => {
                const idx = ctx.dataIndex;
                const ds = ctx.datasetIndex;
                const meta = ds === 0 ? metasTeran[idx] : metasQuinta[idx];
                if (!meta) return '';
                return `\nFecha: ${meta.fecha}\nSemana: ${meta.semana}/${meta.semanas_mes}`;
              },
              label: (ctx) => {
                const val = ctx.raw;
                if (val == null) return `${ctx.dataset.label}: sin datos`;
                try {
                  return `${ctx.dataset.label}: ${Number(val).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}`;
                } catch {
                  return `${ctx.dataset.label}: ${val}`;
                }
              }
            }
          },
          legend: { position: 'top' }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: (value) => {
                try {
                  return Number(value).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
                } catch {
                  return value;
                }
              }
            }
          }
        }
      }
    });
  }

  // --- Render de la tabla
  function renderTabla(resTeran, resQuinta) {
    const tbody = document.querySelector('#tablaResumen tbody');
    tbody.innerHTML = '';
    const labelsKeys = mergeMonthLabels(resTeran, resQuinta);
    const mapT = new Map(resTeran.map(r => [r.mes, r]));
    const mapQ = new Map(resQuinta.map(r => [r.mes, r]));

    labelsKeys.forEach(m => {
      const addRow = (tienda, r) => {
        const tr = document.createElement('tr');
        const tdMes = document.createElement('td'); tdMes.textContent = formatMonthES(m);
        const tdTienda = document.createElement('td'); tdTienda.textContent = tienda;
        const tdFecha = document.createElement('td'); tdFecha.textContent = r ? r.fecha : '-';
        const tdTotal = document.createElement('td'); tdTotal.textContent = r ? r.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }) : '-';
        const tdSemana = document.createElement('td'); tdSemana.textContent = r ? `${r.semana} de ${r.semanas_mes}` : '-';
        tr.append(tdMes, tdTienda, tdFecha, tdTotal, tdSemana);
        tbody.appendChild(tr);
      };
      addRow('Terán', mapT.get(m) || null);
      addRow('Quinta', mapQ.get(m) || null);
    });
  }
function renderPromedioPorDia(diasTeran, diasQuinta) {
  const tbody = document.querySelector('#tablaPorDia tbody');
  tbody.innerHTML = '';

  const resumenTeran = calcularPorcentajeDias(groupByWeekday(diasTeran));
  const resumenQuinta = calcularPorcentajeDias(groupByWeekday(diasQuinta));

  for (let i = 0; i < 7; i++) {
    const filaTeran = resumenTeran[i];
    const filaQuinta = resumenQuinta[i];

    const trTeran = document.createElement('tr');
    trTeran.innerHTML = `
      <td>${filaTeran.dia}</td>
      <td>Terán</td>
      <td>${filaTeran.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}</td>
      <td>${filaTeran.cantidad}</td>
      <td>${filaTeran.promedio.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}</td>
      <td>${filaTeran.porcentaje.toFixed(2)}%</td>
    `;
    const trQuinta = document.createElement('tr');
    trQuinta.innerHTML = `
      <td>${filaQuinta.dia}</td>
      <td>Quinta</td>
      <td>${filaQuinta.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}</td>
      <td>${filaQuinta.cantidad}</td>
      <td>${filaQuinta.promedio.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}</td>
      <td>${filaQuinta.porcentaje.toFixed(2)}%</td>
    `;

    tbody.appendChild(trTeran);
    tbody.appendChild(trQuinta);
  }
}

  // --- Generación del PDF
 function generarPDF(resTeran, resQuinta, rangoFechas, diasTeran, diasQuinta) {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('p', 'pt', 'a4');

  const titulo = 'Tiendas Vs Ventas — Días menos vendidos por mes';
  doc.setFontSize(14);
  doc.text(titulo, 40, 40);
  doc.setFontSize(10);
  doc.text(`Rango: ${rangoFechas.inicio} a ${rangoFechas.fin}`, 40, 60);

  const rows = [];
  const labelsKeys = mergeMonthLabels(resTeran, resQuinta);
  const mapT = new Map(resTeran.map(r => [r.mes, r]));
  const mapQ = new Map(resQuinta.map(r => [r.mes, r]));

  labelsKeys.forEach(m => {
    const mesStr = formatMonthES(m);
    const rT = mapT.get(m);
    const rQ = mapQ.get(m);
    rows.push([
      mesStr,
      'Terán',
      rT ? rT.fecha : '-',
      rT ? rT.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }) : '-',
      rT ? `${rT.semana}/${rT.semanas_mes}` : '-'
    ]);
    rows.push([
      mesStr,
      'Quinta',
      rQ ? rQ.fecha : '-',
      rQ ? rQ.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }) : '-',
      rQ ? `${rQ.semana}/${rQ.semanas_mes}` : '-'
    ]);
  });

  doc.autoTable({
    startY: 80,
    head: [['Mes', 'Tienda', 'Fecha menos vendida', 'Total', 'Semana']],
    body: rows,
    styles: { fontSize: 9, cellPadding: 5 }
  });

  // Agregar tabla de promedios por día
  const resumenTeran = calcularPorcentajeDias(groupByWeekday(diasTeran));
  const resumenQuinta = calcularPorcentajeDias(groupByWeekday(diasQuinta));
  const rows2 = [];

  for (let i = 0; i < 7; i++) {
    const rT = resumenTeran[i];
    const rQ = resumenQuinta[i];
    rows2.push([
      rT.dia,
      'Terán',
      rT.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }),
      rT.cantidad,
      rT.promedio.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }),
      `${rT.porcentaje.toFixed(2)}%`
    ]);
    rows2.push([
      rQ.dia,
      'Quinta',
      rQ.total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }),
      rQ.cantidad,
      rQ.promedio.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }),
      `${rQ.porcentaje.toFixed(2)}%`
    ]);
  }

  doc.autoTable({
    startY: doc.lastAutoTable.finalY + 20,
    head: [['Día', 'Tienda', 'Total', 'Cantidad', 'Promedio', '% del total']],
    body: rows2,
    styles: { fontSize: 9, cellPadding: 5 }
  });

  const fileName = `reporte_dia_menos_vendido_${rangoFechas.inicio}_a_${rangoFechas.fin}.pdf`;
  doc.save(fileName);
}

  // --- Cargar sucursales y habilitar botón
  $(document).ready(function () {
    // Deshabilitamos el botón hasta tener sucursales
    $('#genera_reporte').prop('disabled', true);

    fetch('/sucursales-destinos')
      .then(r => r.json())
      .then(data => {
        // Ajusta si la estructura difiere
        teran = data.sucursales[0].nombre_interno;
        quinta = data.sucursales[1].nombre_interno;
        sucursalesReady = true;
        $('#genera_reporte').prop('disabled', false);
        console.log('Sucursales:', { teran, quinta });
      })
      .catch(err => {
        console.error(err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No se pudieron cargar las sucursales. Intente más tarde.'
        });
      });

    // Click para generar reporte (visual + PDF)
    $('#genera_reporte').off('click').on('click', function () {
      if (!sucursalesReady) {
        Swal.fire({
          icon: 'warning',
          title: 'Cargando sucursales',
          text: 'Espera a que carguen las sucursales antes de generar el reporte.'
        });
        return;
      }
      
      const fecha_inicio = $('#startDate').val();
      const fecha_fin = $('#endDate').val();

      if (!fecha_inicio || !fecha_fin) {
        Swal.fire({
          icon: 'warning',
          title: 'Fechas requeridas',
          text: 'Seleccione ambas fechas antes de continuar.',
          confirmButtonText: 'Aceptar'
        });
        return;
      }

      if (fecha_inicio > fecha_fin) {
        Swal.fire({
          icon: 'warning',
          title: 'Rango de fechas inválido',
          text: 'La fecha de inicio no puede ser mayor a la fecha de fin.',
          confirmButtonText: 'Aceptar'
        });
        return;
      }
      document.querySelector('#resumenGrafico').style.display = 'block';
      document.querySelector('#promedioVentas').style.display = 'block';

      const $btn = $(this);
      $btn.prop('disabled', true).text('Estamos generando tu pdf...');

      const url = `/recupera-todos-los-dias-ventas/${encodeURIComponent(fecha_inicio)}/${encodeURIComponent(fecha_fin)}/${encodeURIComponent(teran)}/${encodeURIComponent(quinta)}`;

      fetch(url)
        .then(r => r.json())
        .then(data => {
          // Validación básica
          if (!data || !Array.isArray(data.dias_teran) || !Array.isArray(data.dias_quinta)) {
            throw new Error('Respuesta inválida del servidor.');
          }
            // Después de obtener datos:
            const diasTeran = normalizeDias(data.dias_teran);
            const diasQuinta = normalizeDias(data.dias_quinta);

            const resumenTeran = minByMonth(diasTeran);
            const resumenQuinta = minByMonth(diasQuinta);

            // Visual
            renderChart(resumenTeran, resumenQuinta);
            renderTabla(resumenTeran, resumenQuinta);
            renderPromedioPorDia(diasTeran, diasQuinta);

            // PDF
            generarPDF(resumenTeran, resumenQuinta, { inicio: fecha_inicio, fin: fecha_fin }, diasTeran, diasQuinta);

          console.log('PDF generado y descargado', data);
          Swal.fire({
            icon: 'success',
            title: 'Reporte generado',
            text: 'Se descargó el PDF y se mostraron los resultados en pantalla.'
          });
        })
        .catch(err => {
          console.error(err);
          Swal.fire({
            icon: 'error',
            title: 'Error al generar',
            text: 'No se pudo generar el reporte. Intente nuevamente.'
          });
        })
        .finally(() => {
          $btn.prop('disabled', false).html('Generar PDF <i class="fa-solid fa-file-pdf"></i>');
        });
    });
  });
</script>


@endsection