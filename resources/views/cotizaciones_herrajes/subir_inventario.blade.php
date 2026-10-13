@extends('layout.index')

@section('content')

<main class="main-content">

    <br>
    <br>
    <br>
    <br>
 <div class="container-fluid content-inner mt-n5 py-0">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card border-0 shadow-lg p-4 rounded-4">
        <div class="d-flex align-items-center mb-3">
          <div class="me-3">
            <i class="fa-solid fa-cart-flatbed" style="font-size: 35px"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold text-primary">Subir inventario</h4>
            <small class="text-muted">Selecciona la tienda y sube tu archivo Excel</small>
          </div>
          <div class="ms-auto">
            <span class="badge bg-light text-dark fw-semibold" id="selectedStoreBadge" style="font-size:15px; background-color: rgb(146 255 175) !important;"></span>
          </div>
        </div>

        <!-- Formulario -->
        <form id="uploadForm" class="row g-3" method="POST" enctype="multipart/form-data" action="">
          @csrf
          
          <div class="col-md-6">
            <label for="storeSelector" class="form-label">Seleccionar tienda</label>
            <select id="storeSelector" name="tienda" class="form-select" required>
             
              
            </select>
          </div>

          <div class="col-md-6">
            <label for="uploadDate" class="form-label">Fecha de carga</label>
            <input type="date" id="uploadDate" name="fecha_carga" class="form-control" value="{{ date('Y-m-d') }}">
          </div>

          <div class="col-12">
            <label class="form-label">Archivo de inventario</label>

            <div id="dropArea" class="drop-area border border-2 border-dashed rounded-3 p-4 text-center bg-light-subtle" tabindex="0">
              <p class="mb-1 fw-semibold text-secondary" id="dropAreaText">
                Arrastra y suelta tu archivo aquí o haz clic para seleccionarlo
              </p>
              <small class="text-muted">Soportado: .xlsx, .xls, .csv — Máx recomendado: 10MB</small>
              <input id="fileInput" name="archivo" type="file" accept=".xlsx,.xls,.csv" class="form-control d-none" required>
            </div>

            <br>
            
            <div class="mt-3 d-flex gap-2">
              <button id="btnPreview" type="button" class="btn btn-outline-primary btn-sm">Previsualizar</button>
              <button id="btnUpload" type="submit" class="btn btn-primary btn-sm">Subir inventario</button>
              <button id="btnClear" type="button" class="btn btn-light btn-sm">Borrar Inventario</button>
            </div>

            <br>
            <br>
            
          </div>

          <!-- Tabla de previsualización MANDARLO A UN MODAL XL-->
          <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="previewModalLabel">
                    <i class="bi bi-table me-2"></i>Previsualización del Inventario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light">
                    <div class="alert alert-info py-2 px-3 mb-3 border-start border-3 border-primary">
                    <small><i class="bi bi-info-circle-fill me-1"></i>Solo se muestran las primeras filas del archivo para validación previa.</small>
                    </div>

                    <div class="table-responsive" style="max-height: 420px;">
                    <table class="table table-sm table-striped table-inventario align-middle">
                        <thead id="previewEncabezado"></thead>
                        <tbody id="previewBody"></tbody>
                    </table>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <span class="text-muted small">Verifica los datos antes de subir el archivo.</span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
            </div>

            <!-- Progreso -->
            <div class="col-12" style="display: none;" id="progressSection">
                <div class="progress" id="uploadProgressWrap">
                <div class="progress-bar" id="uploadProgressBar"></div>
                </div>

            </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<div id="pantallaSpline" class="pantalla-spline">
  <div class="contenido">
    <img src="{{ asset('img/cargando_inventario.gif') }}" alt="">
    <p>Guardando los datos del Inventario, espera un momento por favor...</p>
  </div>
</div>
<style>

  .pantalla-spline {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background-color: #3c3a3acf;
  z-index: 9999;
  
  justify-content: center;
  align-items: center;
  flex-direction: column;
  text-align: center;
  }

  .pantalla-spline .contenido img {
    width: 150px; height: 150px;
    object-fit: cover;
    margin-bottom: 20px;
  }

  .pantalla-spline .contenido p {
    color: white;
    font-size: 1.2rem;
  }
  .drop-area {
    border: 2px dashed rgba(13,110,253,.3);
    transition: 0.3s;
    cursor: pointer;
  }
  .drop-area.dragover {
    background-color: rgba(13,110,253,.05);
    border-color: #0d6efd;
  }
  /* ===== ESTILO EMPRESARIAL PARA EL MODAL ===== */
#previewModal .modal-content {
  border-radius: 0.8rem;
  overflow: hidden;
}

#previewModal .modal-header {
  background: linear-gradient(90deg, #91c1ff 0%, #007bff 100%);
  color: #fff;
  border-bottom: none;
}

#previewModal .modal-body {
  background-color: #f8f9fc;
}

/* ===== TABLA EMPRESARIAL ===== */
.table-inventario {
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  background-color: #fff;
}

.table-inventario thead {
      background: linear-gradient(90deg, #87e5dc 0%, #b1cfef 100%);
  color: #fff;
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 0.4px;
}

.table-inventario tbody tr {
  transition: all 0.2s ease-in-out;
}

.table-inventario tbody tr:hover {
  background-color: #e9f2ff;
  transform: scale(1.01);
}

.table-inventario th,
.table-inventario td {
  padding: 0.65rem 0.9rem;
  vertical-align: middle;
  font-size: 0.9rem;
}

.table-inventario tbody td {
  color: #333;
}

.table-inventario tbody tr:nth-child(even) {
  background-color: #f5f7fa;
}

.table-inventario tbody tr:nth-child(odd) {
  background-color: #ffffff;
}

/* ===== SCROLL DEL MODAL ===== */
.modal-dialog-scrollable .modal-body::-webkit-scrollbar {
  width: 8px;
}
.modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
  background-color: #007bff;
  border-radius: 10px;
}
.modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
  background-color: #e9ecef;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // SELECTORES
  const storeSelector = document.getElementById('storeSelector');
  const selectedStoreBadge = document.getElementById('selectedStoreBadge');
  const dropArea = document.getElementById('dropArea');
  const fileInput = document.getElementById('fileInput');
  const dropAreaText = document.getElementById('dropAreaText');
  const btnPreview = document.getElementById('btnPreview');
  const btnClear = document.getElementById('btnClear');
  const btnUpload = document.getElementById('btnUpload');
  const previewHead = document.getElementById('previewEncabezado');
  const previewBody = document.getElementById('previewBody');
  const progressWrap = document.getElementById('uploadProgressWrap');
  const progressBarEl = document.getElementById('uploadProgressBar');

  let datos_inventario = null;
  const maxFileSize = 10 * 1024 * 1024; 
  const allowedExtensions = ['xlsx','xls','csv'];

  // inicializa estado
  btnUpload.disabled = true;

  function selectorSucursales(){
    fetch('/sucursales-selector')
      .then(r => r.json())
      .then(respuesta => {
        storeSelector.innerHTML = '<option value=\"\" selected disabled>-- Selecciona la tienda --</option>';
        (respuesta.sucursal || []).forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.id;
          opt.textContent = s.nombre_real;
          storeSelector.appendChild(opt);
        });
      });
  }
  selectorSucursales();
  var sin_inventario;
   fetch('/ultima-carga-de-inventario')
    .then(respuesta => respuesta.json())
    .then(respuesta => {
        console.log('Respuesta inventario: ', respuesta);
        
        if (respuesta.status === 'error') {
            // No hay inventario en la tabla temporal
            const mensaje_no_hay = 'No hay inventario que eliminar';
            sin_inventario = mensaje_no_hay;
            Swal.fire({
                icon: 'warning',
                title: 'Inventario vacío',
                text: respuesta.mensaje,
                confirmButtonText: 'Aceptar'
            });

            selectedStoreBadge.textContent = "No hay inventario cargado.";
            return;
        }

        // Si hay datos, mostramos la fecha

        const fecha_de_ultimo_inv = respuesta.ultima_carga.split(" ")[0];
        const fecha_Real = new Date(fecha_de_ultimo_inv + "T00:00:00");

        // Convertir a formato legible
        const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
        const fecha_legible = fecha_Real.toLocaleDateString('es-ES', opciones);

        const ulti = 'Ultima carga de inventario: ' + fecha_legible;
        selectedStoreBadge.textContent = ulti;
    })
    .catch(error => {
        console.error('Error al consultar inventario:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo consultar el inventario.',
            confirmButtonText: 'Cerrar'
        });
    });

  // actualizar badge y estado del boton
  storeSelector.addEventListener('change', () => {
    
    updateUploadButtonState();
  });

  function updateUploadButtonState(){
    btnUpload.disabled = !(storeSelector.value && datos_inventario);
  }

    // Manejo drag & drop / input
    dropArea.addEventListener('click', () => fileInput.click());
    ['dragenter','dragover'].forEach(ev => dropArea.addEventListener(ev, e => { e.preventDefault(); dropArea.classList.add('dragover'); }));
    ['dragleave','drop'].forEach(ev => dropArea.addEventListener(ev, e => { e.preventDefault(); dropArea.classList.remove('dragover'); }));
    dropArea.addEventListener('drop', e => handleFile(e.dataTransfer.files[0]));
    fileInput.addEventListener('change', e => handleFile(e.target.files[0]));

    function handleFile(file){
      if(!file) return;
      const ext = file.name.split('.').pop().toLowerCase();
      if(!allowedExtensions.includes(ext)){
        Swal.fire('Tipo inválido', 'Solo .xlsx, .xls o .csv', 'warning');
        return;
      }
      if(file.size > maxFileSize){
        Swal.fire('Archivo grande', 'Máximo recomendado: 10MB', 'warning');
        return;
      }

      dropAreaText.textContent = `Archivo seleccionado: ${file.name} (${(file.size/1024).toFixed(2)} KB)`;

      const reader = new FileReader();
      reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const sheet = workbook.Sheets[sheetName];
        const arr = XLSX.utils.sheet_to_json(sheet, { header: 1 }); // matriz
        datos_inventario = arr;
        Swal.fire('Archivo listo', 'Archivo cargado correctamente. Puedes previsualizar o subir.', 'success');
        updateUploadButtonState();
      };
      reader.readAsArrayBuffer(file);
    }

  // PREVISUALIZAR: asignamos un solo handler (no multiples)
  btnPreview.onclick = (e) => {
    e.preventDefault();
    if(!storeSelector.value){
      Swal.fire('Selecciona sucursal', 'Debes seleccionar una sucursal antes de previsualizar.', 'warning');
      return;
    }
    if(!datos_inventario){
      Swal.fire('Selecciona archivo', 'Debes cargar un archivo antes de previsualizar.', 'warning');
      return;
    }

    // limpiar tabla antes de render
    previewHead.innerHTML = '';
    previewBody.innerHTML = '';

    const encabezado = datos_inventario[0] || [];
    const filas = datos_inventario.slice(1, 21); // primeras 20 filas

    // encabezado (una fila <tr>)
    const trHead = document.createElement('tr');
    encabezado.forEach(col => {
      const th = document.createElement('th');
      th.textContent = col ?? '';
      th.classList.add('text-center','fw-semibold');
      trHead.appendChild(th);
    });
    previewHead.appendChild(trHead);

    // cuerpo
    filas.forEach(row => {
      const tr = document.createElement('tr');
      // si la fila tiene menos columnas que el encabezado, completamos con vacíos
      for(let i = 0; i < encabezado.length; i++){
        const td = document.createElement('td');
        td.textContent = row[i] ?? '';
        td.classList.add('text-center','align-middle');
        tr.appendChild(td);
      }
      previewBody.appendChild(tr);
    });

    // mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
  };

  // LIMPIAR
  btnClear.onclick = (e) => {
    /*e.preventDefault();
    datos_inventario = null;
    fileInput.value = '';
    dropAreaText.textContent = 'Arrastra y suelta tu archivo aquí o haz clic para seleccionarlo';
    previewHead.innerHTML = '';
    previewBody.innerHTML = '';
    storeSelector.value = '';
    selectedStoreBadge.textContent = 'Ninguna tienda seleccionada';
    updateUploadButtonState();
    */
    console.log('Esto limpiara los datos de la tabla de inventario de la DB');
      
    Swal.fire({
      title: '¿Estas seguro de eliminar los datos del inventario cargado?',
      text: "Esta accion no podra deshacerse despues de dar click en confirmar, ¿Deseas continuar?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar'
      }).then((result)=>{
        if (result.isConfirmed) {
          eliminarInventarioTemporal();
        }
      });
  
  };

  function eliminarInventarioTemporal() {
    $.ajax({
      url: '/eliminar-inventario-temporal',
      type: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        Swal.fire('¡Eliminado, ahora puedes subir tu nuevo inventario!', response.message, 'success').then(() => {
          location.reload(); // Recarga la página después de cerrar el SweetAlert
        });
      },
      error: function() {
        Swal.fire('Error', 'No se pudo eliminar el inventario temporal.', 'error');
      }
    });
  }

  // SUBIR ARHCIVOS
  btnUpload.onclick = (e) => {
    e.preventDefault();

    if(!storeSelector.value){
      Swal.fire('Falta sucursal', 'Selecciona la sucursal antes de subir.', 'error');
      return;
    }
    if(!datos_inventario){
      Swal.fire('Falta archivo', 'Debes seleccionar un archivo antes de subir.', 'error');
      return;
    }
    document.getElementById("pantallaSpline").style.display = "flex";
    // construir payload (solo 1 vez, sin duplicar)
    const filas = datos_inventario.slice(1);
    const excel_inventario = filas.map(row => ({
      sucursal_id: Number(storeSelector.value),
      producto_id: row[1] && !isNaN(row[1]) ? Number(row[1]) : 0,
      clave_producto: row[2] ? String(row[2]).trim() : 'N/A',
      descripcion_producto: row[3] ? String(row[3]).trim() : 'N/A',
      existencia: row[4] && !isNaN(row[4]) ? Number(row[4]) : 0,
      color:row[6] ? String(row[6]).trim() : 'N/A',
      sub_linea: row[7] ? String(row[7]).trim() : 'N/A',
      linea: row[8] ? String(row[8]).trim() : 'N/A',
      ultimo_costo: row[10] && !isNaN(row[10]) ? Number(row[10]) : 0,
      costo_inventario: row[11] && !isNaN(row[11]) ? Number(row[11]) : 0,
    }));

    // desactivar boton para evitar clicks repetidos
    btnUpload.disabled = true;

    progressWrap.style.display = 'block';
    progressBarEl.style.width = '0%';
    progressBarEl.textContent = '0%';

    $.ajax({
      url: '/subir-inventario-tiendas',
      method: 'POST',
      data: JSON.stringify(excel_inventario),
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
      beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
      },
      xhr: function() {
        const xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener('progress', function(evt) {
          if(evt.lengthComputable){
            const pct = Math.round((evt.loaded / evt.total) * 100);
            progressBarEl.style.width = pct + '%';
            progressBarEl.textContent = pct + '%';
          }
        });
        return xhr;
      },
      success: function(res) {
        progressBarEl.style.width = '100%';
        progressBarEl.textContent = '100%';
        Swal.fire('Éxito','Inventario subido correctamente.','success');
        btnClear.click();
        document.getElementById("pantallaSpline").style.display = "none";
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        Swal.fire('Error','Hubo un problema al subir el inventario. Revisa consola.', 'error');
      },
      complete: function() {
        progressWrap.style.display = 'none';
        btnUpload.disabled = false;
      }
    });
  };

    // Asegurar que al cerrar el modal no queden filas repetidas la próxima vez
    const previewModalEl = document.getElementById('previewModal');
    previewModalEl.addEventListener('hidden.bs.modal', () => {
      // No borramos datos_inventario porque aún puede querer subirlo,
    // solo limpiamos la tabla del modal
    previewHead.innerHTML = '';
    previewBody.innerHTML = '';
  });

});
</script>
</main>
@endsection
