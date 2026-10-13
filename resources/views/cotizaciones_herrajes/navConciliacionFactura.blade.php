<ul class="nav nav-pills flex-md-row  gap-3 mb-4 bg-white p-3 rounded-3   border-light" style="background: ffffff00;">
  <li class="nav-item">
    <a class="@if (request()->route()->getName() === 'vista.cotizaciones_facturadas') nav-link btn active @else nav-link btn @endif"
       href="{{ route('vista.cotizaciones_facturadas') }}">
      <i class="fa-solid fa-upload"></i> Cargar XML
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link px-4 py-2 fw-semibold @if (request()->route()->getName() === 'vista.presupuesto_conciliacion') nav-link active @else nav-link @endif"
       href="{{ route('vista.presupuesto_conciliacion') }}">
      <i class="fa-solid fa-shuffle"></i> Conciliar Facturas 
    </a>
  </li>
</ul>