<ul class="nav nav-pills flex-md-row  gap-3 mb-4 bg-white p-3 rounded-3   border-light" style="background: ffffff00;">
  <li class="nav-item">
    <a class="@if (request()->route()->getName() === 'vista.autorizacion_cotizacion') nav-link btn active @else nav-link btn @endif"
       href="{{ route('vista.autorizacion_cotizacion') }}">
      <i class="fa-solid fa-barcode me-2"></i> Folio Cotización
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link px-4 py-2 fw-semibold @if (request()->route()->getName() === 'vista.descargar_pdf_foliado') nav-link active @else nav-link @endif"
       href="{{ route('vista.descargar_pdf_foliado') }}">
      <i class="fa-solid fa-download me-2"></i> Descargar PDF foliados
    </a>
  </li>
</ul>