<x-app-layout>
{{-- <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Bitácora</h2>
</x-slot> --}}

<style>
    .page-box{padding:30px}
    .card{background:white;border-radius:18px;padding:25px;box-shadow:0 10px 30px rgba(0,0,0,.08);max-width:1000px;margin:auto}
    .title{font-size:26px;font-weight:800;color:#1f2937;margin-bottom:20px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .full{grid-column:1 / 3}
    label{font-weight:800;color:#374151;margin-bottom:6px;display:block}
    input,select,textarea{width:100%;border:1px solid #d1d5db;border-radius:12px;padding:12px}
    textarea{resize:vertical}
    .actions{margin-top:20px;display:flex;gap:10px}
    .btn{padding:11px 18px;border-radius:10px;text-decoration:none;border:0;cursor:pointer;font-weight:800}
    .btn-success{background:#16a34a;color:white}
    .btn-secondary{background:#6b7280;color:white}
</style>

<div class="page-box">
    <div class="card">
        <div class="title">Editar Caso #{{ $bitacora->id }}</div>

        <form action="{{ route('bitacoras.update', $bitacora) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid">
                <div>
                    <label>Tipo de Caso</label>
                    <select autofocus>
                        <option value="TI" {{ $bitacora->tipo_caso == 'Requirimiento' ? 'selected' : '' }}>Requirimiento</option>
                        <option value="Equipo de Operaciones" {{ $bitacora->tipo_caso == 'Incidente' ? 'selected' : '' }}>Incidente</option>
                    </select>
                </div>

                <div>
                    <label>Proceso</label>
                    <input type="text" name="proceso" value="{{ $bitacora->proceso }}" required>
                </div>

                <div class="full">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="4" required>{{ $bitacora->descripcion }}</textarea>
                </div>

                <div class="full">
                    <label>Solución</label>
                    <textarea name="solucion" rows="4">{{ $bitacora->solucion }}</textarea>
                </div>

                <div>
                    <label>Prioridad</label>
                    <select name="prioridad">
                        <option value="BAJA" {{ $bitacora->prioridad == 'BAJA' ? 'selected' : '' }}>BAJA</option>
                        <option value="MEDIA" {{ $bitacora->prioridad == 'MEDIA' ? 'selected' : '' }}>MEDIA</option>
                        <option value="ALTA" {{ $bitacora->prioridad == 'ALTA' ? 'selected' : '' }}>ALTA</option>
                    </select>
                </div>

                <div>
                    <label>Estado</label>
                    <select name="estado">
                        <option value="PENDIENTE" {{ $bitacora->estado == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                        <option value="EN_PROCESO" {{ $bitacora->estado == 'EN_PROCESO' ? 'selected' : '' }}>EN PROCESO</option>
                        <option value="RESUELTO" {{ $bitacora->estado == 'RESUELTO' ? 'selected' : '' }}>RESUELTO</option>
                    </select>
                </div>

                <div>
                    <label>Área</label>
                    <select>
                        <option value="TI" {{ $bitacora->area == 'TI' ? 'selected' : '' }}>TI</option>
                        <option value="Equipo de Operaciones" {{ $bitacora->area == 'Equipo de Operaciones' ? 'selected' : '' }}>Equipo de Operaciones</option>
                        <option value="Linea Naviera" {{ $bitacora->area == 'Linea Naviera' ? 'selected' : '' }}>Línea Naviera</option>
                        <option value="Proteccion" {{ $bitacora->area == 'Proteccion' ? 'selected' : '' }}>Protección</option>
                        <option value="Comercial" {{ $bitacora->area == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                        <option value="Cliente" {{ $bitacora->area == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                        <option value="Reefer" {{ $bitacora->area == 'Reefer' ? 'selected' : '' }}>Reefer</option>
                    </select>
                </div>

                <div>
                    <label>Fecha de registro</label>
                    <input type="date" name="fecha_registro" value="{{ $bitacora->fecha_registro->format('Y-m-d') }}" required>
                </div>

                <div>
                    <label>Tiempo de Solución</label>
                    <input type="number" name="tiempo_resolucion" value="{{ $bitacora->tiempo_resolucion }}" required>
                </div>

                <div>
                    <label>Personal</label>
                    <input type="text" name="personal" value="{{ $bitacora->personal }}" required>
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-success">Actualizar Bitácora</button>
                <a href="{{ route('bitacoras.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>