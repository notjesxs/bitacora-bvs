<x-app-layout>
{{-- <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bitácoras</h2>
</x-slot> --}}

<style>
:root{
    --bg:#f4f7fb;
    --card:#ffffff;
    --dark:#0f172a;
    --muted:#64748b;
    --blue:#2563eb;
    --green:#16a34a;
    --orange:#f59e0b;
    --red:#dc2626;
    --border:#e5e7eb;
}

body{background:var(--bg)}
.page{padding:28px}
.header-card{
    background:linear-gradient(135deg,#0f172a,#1d4ed8);
    color:white;
    border-radius:26px;
    padding:30px;
    margin-bottom:24px;
    box-shadow:0 18px 45px rgba(37,99,235,.25);
}
.header-card h1{font-size:34px;font-weight:900;margin:0}
.header-card p{opacity:.85;margin-top:8px}
.stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin-bottom:24px;
}
.stat{
    background:var(--card);
    border-radius:22px;
    padding:22px;
    box-shadow:0 12px 35px rgba(15,23,42,.08);
    border:1px solid var(--border);
}
.stat span{color:var(--muted);font-weight:700;font-size:13px}
.stat h3{font-size:32px;margin:8px 0 0;color:var(--dark)}
.card{
    background:white;
    border-radius:26px;
    padding:24px;
    box-shadow:0 12px 35px rgba(15,23,42,.08);
    border:1px solid var(--border);
}
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:20px;
}
.topbar-actions{
    display:flex;
    align-items:center;
    gap:12px;
}
.title{font-size:24px;font-weight:900;color:var(--dark)}
.subtitle{color:var(--muted);font-size:14px}
.btn{
    padding:11px 17px;
    border-radius:14px;
    text-decoration:none;
    border:0;
    cursor:pointer;
    font-weight:800;
    display:inline-flex;
    align-items:center;
    gap:6px;
}
.btn-primary{background:var(--blue);color:white}
.btn-success{background:var(--green);color:white}
.btn-warning{background:var(--orange);color:white}
.btn-danger{background:var(--red);color:white}
.btn-orange{background:var(--orange);color:white}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:separate;border-spacing:0 10px}
thead th{
    color:var(--muted);
    font-size:12px;
    text-transform:uppercase;
    text-align:left;
    padding:10px 14px;
}
tbody tr{
    background:white;
    box-shadow:0 5px 18px rgba(15,23,42,.06);
}
tbody td{
    padding:16px 14px;
    border-top:1px solid var(--border);
    border-bottom:1px solid var(--border);
    color:#1e293b;
}
tbody td:first-child{
    border-left:1px solid var(--border);
    border-radius:16px 0 0 16px;
    font-weight:900;
}
tbody td:last-child{
    border-right:1px solid var(--border);
    border-radius:0 16px 16px 0;
}
.badge{
    padding:7px 11px;
    border-radius:999px;
    font-size:11px;
    font-weight:900;
}
.baja{background:#dcfce7;color:#166534}
.media{background:#dbeafe;color:#1d4ed8}
.alta{background:#fef3c7;color:#92400e}
.critica{background:#fee2e2;color:#991b1b}
.pendiente{background:#fee2e2;color:#991b1b}
.proceso{background:#fef3c7;color:#92400e}
.resuelto{background:#dcfce7;color:#166534}
.cerrado{background:#e5e7eb;color:#374151}
.user-pill{
    background:#f1f5f9;
    color:#0f172a;
    padding:8px 12px;
    border-radius:999px;
    font-weight:800;
}
.actions{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
}
.alert{
    background:#dcfce7;
    color:#166534;
    padding:14px;
    border-radius:16px;
    margin-bottom:15px;
    font-weight:800;
}

.pagination-box{
    margin-top:22px;
    padding:18px 20px;
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.filters{
    display:grid;
    grid-template-columns:2fr 1fr 1fr auto;
    gap:14px;
    margin-bottom:22px;
    padding:18px;
    background:#f8fafc;
    border:1px solid #e5e7eb;
    border-radius:20px;
}
.filters label{
    display:block;
    font-size:12px;
    font-weight:900;
    color:#64748b;
    text-transform:uppercase;
    margin-bottom:6px;
}
.filters select,
.filters input{
    width:100%;
    border:1px solid #d1d5db;
    border-radius:12px;
    padding:11px 12px;
    background:white;
}
.filter-actions{
    display:flex;
    gap:8px;
    align-items:end;
}
.btn-secondary{
    background:#64748b;
    color:white;
}

thead th,
tbody td {
    text-align: center;
}

.export-option{
    display:flex;
    align-items:center;
    gap:10px;
    padding:14px;
    background:#0f172a;
    border:1px solid #334155;
    border-radius:14px;
    cursor:pointer;
    font-weight:700;
    color:white;
}

.export-option:hover{
    border-color:#2563eb;
    background:#172554;
}
.detalle-modal{
    padding:8px !important;
}

.detalle-modal .swal2-title{
    font-size:24px !important;
    margin-bottom:14px !important;
}

.case-modal{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
    text-align:left;
}

.case-item{
    background:#0f172a;
    border:1px solid #334155;
    border-radius:12px;
    padding:10px 12px;
}

.case-item.full{
    grid-column:1 / 3;
}

.case-item span{
    display:block;
    color:#94a3b8;
    font-size:10px;
    font-weight:900;
    text-transform:uppercase;
    margin-bottom:5px;
}

.case-item strong{
    color:white;
    font-size:13px;
}

.case-item p{
    color:#e2e8f0;
    margin:0;
    font-size:13px;
    line-height:1.4;
    max-height:90px;
    overflow-y:auto;
}

@media (max-width: 768px){

    .page{
        padding:12px;
    }

    .stats{
        grid-template-columns:1fr 1fr;
        gap:10px;
    }

    .stat{
        padding:14px;
    }

    .stat h3{
        font-size:22px;
    }

    .topbar{
        flex-direction:column;
        align-items:stretch;
    }

    .topbar-actions{
        width:100%;
        flex-direction:column;
    }

    .topbar-actions .btn{
        width:100%;
        justify-content:center;
    }

    .filters{
        grid-template-columns:1fr;
    }

    .filter-actions{
        flex-direction:column;
    }

    .filter-actions .btn{
        width:100%;
        justify-content:center;
    }

    .card{
        padding:14px;
    }

    .title{
        font-size:20px;
    }

    table{
        min-width:950px;
    }

    .actions{
        flex-direction:column;
    }

    .actions .btn{
        width:100%;
        justify-content:center;
    }

    .pagination-box{
        overflow-x:auto;
        padding:12px;
    }

    .case-modal{
        grid-template-columns:1fr;
    }

    .case-item.full{
        grid-column:auto;
    }

    .detalle-modal{
        width:95% !important;
    }

    .detalle-modal .swal2-title{
        font-size:18px !important;
    }
}

</style>

<div class="page">

    {{-- <div class="header-card">
        <h1>Sistema de Bitácora</h1>
        <p>Control interno de casos, incidencias, requerimientos y seguimiento por responsable.</p>
    </div> --}}

    <div class="stats">
        <div class="stat">
            <span>Total de casos</span>
            <h3>{{ $bitacoras->total() }}</h3>
        </div>

        <div class="stat">
            <span>Baja Prioridad</span>
            <h3>{{ \App\Models\Bitacora::where('prioridad', 'BAJA')->count() }}</h3>
        </div>

        <div class="stat">
            <span>Media prioridad</span>
            <h3>{{ \App\Models\Bitacora::whereIn('prioridad', ['MEDIA'])->count() }}</h3>
        </div>
        
        <div class="stat">
            <span>Alta prioridad</span>
            <h3>{{ \App\Models\Bitacora::whereIn('prioridad', ['ALTA','CRITICA'])->count() }}</h3>
        </div>
    </div>

    <div class="card">
        <div class="topbar">
            <div>
                <div class="title">Listado de Casos</div>
                <div class="subtitle">Casos registrados recientemente</div>
            </div>

        <div class="topbar-actions">
                 @if(Auth::id() !== 8)
                    <a href="{{ route('bitacoras.create') }}" class="btn btn-primary">
                        + Nuevo Caso
                    </a>
                @endif

                <button type="button" class="btn btn-success" id="btnExportarExcel">
                    Exportar Excel
                </button>
            
                {{-- <button type="button" class="btn btn-orange" id="btnGenerarPpt">
                    Generar PPT
                </button> --}}

                <button type="button" class="btn btn-danger" id="btnGenerarPdf">
                    Generar PDF
                </button>
        </div>
    </div>

    @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('bitacoras.index') }}" class="filters">
            <div>
                <label>Encargado</label>
                <select name="encargado_id">
                    <option value="">Todos</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ request('encargado_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Fecha inicio</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
            </div>

            <div>
                <label>Fecha fin</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('bitacoras.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
    </form>

    <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center">ID</th>
                        <th style="text-align: center">Tipo Caso</th>
                        <th style="text-align: center">Proceso</th>
                        <th style="text-align: center">Prioridad</th>
                        <th style="text-align: center">Estado</th>
                        <th style="text-align: center">Área</th>
                        <th style="text-align: center">Encargado</th>
                        <th style="text-align: center">Fecha</th>
                        <th style="text-align: center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bitacoras as $bitacora)
                        <tr>
                            <td>#{{ $bitacora->id }}</td>
                            <td>{{ $bitacora->tipo_caso }}</td>
                            <td>{{ $bitacora->proceso }}</td>

                            <td>
                                <span class="badge {{ strtolower($bitacora->prioridad) }}">
                                    {{ $bitacora->prioridad }}
                                </span>
                            </td>

                            <td>
                                @php
                                    $estadoClass = $bitacora->estado == 'EN_PROCESO' ? 'proceso' : strtolower($bitacora->estado);
                                @endphp

                                <span class="badge {{ $estadoClass }}">
                                    {{ str_replace('_', ' ', $bitacora->estado) }}
                                </span>
                            </td>

                            <td>{{ $bitacora->area }}</td>

                            <td>
                                <span class="user-pill">
                                    {{ $bitacora->encargado?->name ?? 'Sin usuario' }}
                                </span>
                            </td>

                            <td>{{ $bitacora->fecha_registro->format('d/m/Y') }}</td>

                            <td>
                                @if(auth()->id() == $bitacora->user_id)

                                    <div class="actions">
                                        <a href="{{ route('bitacoras.edit', $bitacora) }}" class="btn btn-warning">
                                            Editar
                                        </a>

                                        <form action="{{ route('bitacoras.destroy', $bitacora) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-danger btn-delete">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-primary btn-view"
                                        data-id="{{ $bitacora->id }}"
                                        data-tipo="{{ $bitacora->tipo_caso }}"
                                        data-proceso="{{ $bitacora->proceso }}"
                                        data-descripcion="{{ $bitacora->descripcion }}"
                                        data-prioridad="{{ $bitacora->prioridad }}"
                                        data-solucion="{{ $bitacora->solucion }}"
                                        data-encargado="{{ $bitacora->encargado?->name ?? 'Sin usuario' }}"
                                        data-fecha="{{ $bitacora->fecha_registro?->format('d/m/Y') }}"
                                        data-tiempo="{{ $bitacora->tiempo_resolucion }}"
                                        data-estado="{{ $bitacora->estado }}"
                                        data-area="{{ $bitacora->area }}"
                                        data-personal="{{ $bitacora->personal }}"
                                    >
                                        Ver Detalle
                                    </button>

                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:30px">
                                No hay casos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-box">
        {{ $bitacoras->onEachSide(1)->links('pagination::simple-tailwind') }}
    </div>
</div>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');

            Swal.fire({
                title: '¿Eliminar caso?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                background: '#1e293b',
                color: '#ffffff',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<script>
document.getElementById('btnExportarExcel').addEventListener('click', function () {

    const hoy = new Date();

    const actual = hoy.getFullYear() + '-' +
        String(hoy.getMonth() + 1).padStart(2, '0');

    const anteriorDate = new Date(
        hoy.getFullYear(),
        hoy.getMonth() - 1,
        1
    );

    const anterior = anteriorDate.getFullYear() + '-' +
        String(anteriorDate.getMonth() + 1).padStart(2, '0');

    const nombresMes = [
        'Enero','Febrero','Marzo','Abril',
        'Mayo','Junio','Julio','Agosto',
        'Septiembre','Octubre','Noviembre','Diciembre'
    ];

    const actualTexto =
        nombresMes[hoy.getMonth()] +
        ' ' +
        hoy.getFullYear();

    const anteriorTexto =
        nombresMes[anteriorDate.getMonth()] +
        ' ' +
        anteriorDate.getFullYear();

    Swal.fire({
        title: 'Exportar Excel',
        html: `
            <div style="display:flex;flex-direction:column;gap:12px;margin-top:20px">

                <label class="export-option">
                    <input type="radio" name="mes_exportar" value="${actual}" checked>
                    ${actualTexto}
                </label>

                <label class="export-option">
                    <input type="radio" name="mes_exportar" value="${anterior}">
                    ${anteriorTexto}
                </label>

            </div>
        `,
        background:'#1e293b',
        color:'#fff',
        showCancelButton:true,
        confirmButtonText:'Exportar',
        cancelButtonText:'Cancelar',
        confirmButtonColor:'#16a34a',
        cancelButtonColor:'#64748b',

        preConfirm: () => {

            const mes = document.querySelector(
                'input[name="mes_exportar"]:checked'
            );

            return mes.value;
        }

    }).then((result) => {

        if(result.isConfirmed){

            window.location.href =
                "{{ route('bitacoras.exportar') }}?mes=" +
                result.value;

        }

    });

    

});
</script>

{{-- <script>
document.getElementById('btnGenerarPpt').addEventListener('click', function () {
    const hoy = new Date();

    const actual = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0');

    const anteriorDate = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
    const anterior = anteriorDate.getFullYear() + '-' + String(anteriorDate.getMonth() + 1).padStart(2, '0');

    const meses = [
        'Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
    ];

    Swal.fire({
        title: 'Generar informe PPT',
        html: `
            <div style="display:flex;flex-direction:column;gap:12px;margin-top:18px">
                <label class="export-option">
                    <input type="radio" name="mes_ppt" value="${actual}" checked>
                    ${meses[hoy.getMonth()]} ${hoy.getFullYear()}
                </label>

                <label class="export-option">
                    <input type="radio" name="mes_ppt" value="${anterior}">
                    ${meses[anteriorDate.getMonth()]} ${anteriorDate.getFullYear()}
                </label>
            </div>
        `,
        background:'#1e293b',
        color:'#fff',
        showCancelButton:true,
        confirmButtonText:'Generar PPT',
        cancelButtonText:'Cancelar',
        confirmButtonColor:'#2563eb',
        cancelButtonColor:'#64748b',
        preConfirm: () => {
            return document.querySelector('input[name="mes_ppt"]:checked').value;
        }
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href = "{{ route('bitacoras.generarPpt') }}?mes=" + result.value;
        }
    });
});
</script> --}}

<script>
document.getElementById('btnGenerarPdf').addEventListener('click', function () {
    const hoy = new Date();

    const actual = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0');

    const anteriorDate = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
    const anterior = anteriorDate.getFullYear() + '-' + String(anteriorDate.getMonth() + 1).padStart(2, '0');

    const meses = [
        'Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
    ];

    Swal.fire({
        title: 'Generar informe PDF',
        html: `
            <div style="display:flex;flex-direction:column;gap:12px;margin-top:18px">
                <label class="export-option">
                    <input type="radio" name="mes_pdf" value="${actual}" checked>
                    ${meses[hoy.getMonth()]} ${hoy.getFullYear()}
                </label>

                <label class="export-option">
                    <input type="radio" name="mes_pdf" value="${anterior}">
                    ${meses[anteriorDate.getMonth()]} ${anteriorDate.getFullYear()}
                </label>
            </div>
        `,
        background:'#1e293b',
        color:'#fff',
        showCancelButton:true,
        confirmButtonText:'Generar PDF',
        cancelButtonText:'Cancelar',
        confirmButtonColor:'#2563eb',
        cancelButtonColor:'#64748b',
        preConfirm: () => {
            return document.querySelector('input[name="mes_pdf"]:checked').value;
        }
    }).then((result) => {

        if(result.isConfirmed){

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('bitacoras.generarPdf') }}";

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";

            const mes = document.createElement('input');
            mes.type = 'hidden';
            mes.name = 'mes';
            mes.value = result.value;

            form.appendChild(csrf);
            form.appendChild(mes);

            document.body.appendChild(form);
            form.submit();
        }

    });
});
</script>



<script>
document.querySelectorAll('.btn-view').forEach(button => {
    button.addEventListener('click', function () {
        Swal.fire({
            title: 'Detalle del Caso #' + this.dataset.id,
            width: window.innerWidth < 768 ? '95%' : '650px',
            background: '#1e293b',
            color: '#ffffff',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'detalle-modal'
            },
            html: `
                <div class="case-modal">

                    <div class="case-item">
                        <span>Tipo de Caso</span>
                        <strong>${this.dataset.tipo || '-'}</strong>
                    </div>

                    <div class="case-item">
                        <span>Proceso</span>
                        <strong>${this.dataset.proceso || '-'}</strong>
                    </div>

                    <div class="case-item">
                        <span>Prioridad</span>
                        <strong>${this.dataset.prioridad || '-'}</strong>
                    </div>

                    <div class="case-item">
                        <span>Estado</span>
                        <strong>${this.dataset.estado || '-'}</strong>
                    </div>

                    <div class="case-item">
                        <span>Encargado</span>
                        <strong>${this.dataset.encargado || '-'}</strong>
                    </div>

                    <div class="case-item">
                        <span>Fecha Registro</span>
                        <strong>${this.dataset.fecha || '-'}</strong>
                    </div>

                    <div class="case-item">
                        <span>Tiempo Solución</span>
                        <strong>${this.dataset.tiempo || '-'}</strong>
                    </div>

                    <div class="case-item">
                        <span>Área</span>
                        <strong>${this.dataset.area || '-'}</strong>
                    </div>

                    <div class="case-item full">
                        <span>Personal</span>
                        <strong>${this.dataset.personal || '-'}</strong>
                    </div>

                    <div class="case-item full">
                        <span>Descripción</span>
                        <p>${this.dataset.descripcion || '-'}</p>
                    </div>

                    <div class="case-item full">
                        <span>Solución</span>
                        <p>${this.dataset.solucion || '-'}</p>
                    </div>

                </div>
            `
        });
    });
});
</script>

@if(session('error'))
<script>
Swal.fire({
    icon: 'warning',
    title: 'Sin información',
    text: @json(session('error')),
    background:'#1e293b',
    color:'#fff',
    confirmButtonColor:'#f59e0b'
});
</script>
@endif

</x-app-layout>