<x-app-layout>
{{-- <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva Bitácora</h2>
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
    .btn-primary{background:#2563eb;color:white}
    .btn-secondary{background:#6b7280;color:white}
</style>

<div class="page-box">
    <div class="card">
        <div class="title">Registrar nuevo caso</div>

        <form action="{{ route('bitacoras.store') }}" method="POST">
            @csrf

            <div class="grid">
                <div>
                    <label>Tipo de Caso</label>
                    <input type="text" name="tipo_caso" required autofocus>
                </div>

                <div>
                    <label>Proceso</label>
                    <input type="text" name="proceso" required>
                </div>

                <div class="full">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="4" required></textarea>
                </div>

                <div class="full">
                    <label>Solución</label>
                    <textarea name="solucion" rows="4"></textarea>
                </div>

                <div>
                    <label>Prioridad</label>
                    <select name="prioridad">
                        <option value="BAJA">BAJA</option>
                        <option value="MEDIA">MEDIA</option>
                        <option value="ALTA">ALTA</option>
                    </select>
                </div>

                <div>
                    <label>Estado</label>
                    <select name="estado">
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="EN_PROCESO">EN PROCESO</option>
                        <option value="RESUELTO">RESUELTO</option>
                    </select>
                </div>

                <div>
                    <label>Área</label>
                    <select name="area">
                        <option value="TI">TI</option>
                        <option value="Equipo de Operaciones">Equipo de Operaciones</option>
                        <option value="Linea Naviera">Línea Naviera</option>
                        <option value="Proteccion">Protección</option>
                        <option value="Comercial">Comercial</option>
                        <option value="Cliente">Cliente</option>
                        <option value="Reefer">Reefer</option>
                    </select>
                </div>

                <div>
                    <label>Personal</label>
                    <input type="text" name="personal" required>
                </div>

                <div>
                    <label>Fecha de registro</label>
                    <input type="date" name="fecha_registro" disabled value="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div>
                    <label>Tiempo de Solución</label>
                    <input type="number" name="tiempo_resolucion" required>
                </div>

                <input type="hidden" name="fecha_registro" value="{{ now()->format('Y-m-d H:i:s') }}">
            </div>

            <div class="actions">
                <button class="btn btn-primary">Guardar Bitácora</button>
                <a href="{{ route('bitacoras.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>