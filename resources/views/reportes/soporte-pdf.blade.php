<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>FOR-SER-12_INFORME SOPORTE SOFTWARE -V.1.0</title>

    <style>
        @page {
            size: 1600px 900px;
            margin: 0;
        }

        :root {
            --blue: #2f6bff;
            --blue-dark: #062b6f;
            --blue-title: #0b2344;
            --green: #20a64a;
            --orange: #e18400;
            --text-muted: #5d7085;
            --border: #dbe3ec;
            --soft-blue: #f4f9ff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Liberation Sans", Arial, sans-serif;
            background: #fff;
            color: #111;
        }

        .page {
            width: 1600px;
            height: 900px;
            padding: 25px 65px 55px;
            box-sizing: border-box;
            page-break-after: always;
            position: relative;
            overflow: hidden;
        }

        .title {
            margin-bottom: 8px;
            padding-left: 18px;
            border-left: 12px solid var(--blue);
            color: #111;
            font-size: 44px;
            font-weight: 800;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #3366ff;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
        }

        .bvs {
            position: absolute;
            right: 50px;
            bottom: 24px;
            color: #3366ff;
            font-size: 46px;
            font-weight: 800;
        }

        .blue { background: var(--blue); }
        .green { background: var(--green); }
        .orange { background: var(--orange); }
        .navy { background: var(--blue-dark); }

        .line-colors {
            display: flex;
            gap: 4px;
            margin-top: 14px;
        }

        .line-colors span {
            display: block;
            width: 58px;
            height: 5px;
            border-radius: 4px;
        }

        .dashboard-card {
            width: 100%;
            max-width: 1450px;
            height: 720px;
            margin: 0 auto;
            padding: 28px 38px;
            border: 1px solid #e5e7eb;
            border-radius: 28px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .08);
        }

        .header-row,
        .chart-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-row {
            margin-bottom: 10px;
        }

        .header-left h1 {
            margin: 0;
            color: var(--blue-dark);
            font-size: 42px;
            font-weight: 800;
        }

        .header-left p {
            margin: 8px 0 0;
            color: #555;
            font-size: 22px;
        }

        .total-box {
            width: 300px;
            display: flex;
            align-items: center;
            margin-top: 5px;
            gap: 16px;
            padding: 18px 22px;
            border-radius: 18px;
            color: var(--blue-dark);
            box-shadow: 0 4px 14px rgba(0, 0, 0, .12);
        }

        .total-icon,
        .percent-icon,
        .message-icon,
        .info-icon,
        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .total-icon {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: #eef5ff;
        }

        .total-icon i,
        .percent-icon i,
        .message-icon i {
            color: #3366ff;
        }

        .total-icon i { font-size: 30px; }
        .percent-icon i { font-size: 26px; }
        .message-icon i { font-size: 32px; }

        .total-box span,
        .total-box small {
            display: block;
            color: #777;
            font-size: 16px;
        }

        .total-box strong {
            display: block;
            color: var(--blue-dark);
            font-size: 56px;
            line-height: 56px;
        }

        .chart-container {
            margin-top: 15px;
            padding: 15px 20px;
            border: 1px solid #eef1f5;
            border-radius: 18px;
        }

        .chart-title {
            margin-bottom: 8px;
            color: var(--blue-dark);
            font-size: 18px;
            font-weight: 800;
        }

        .legend-box {
            width: 260px;
            color: #333;
            font-size: 20px;
        }

        .legend-item {
            margin-bottom: 22px;
            line-height: 22px;
        }

        .legend-item strong {
            color: var(--blue-dark);
            font-size: 24px;
        }

        .dot {
            display: inline-block;
            width: 14px;
            height: 14px;
            margin-right: 8px;
            border-radius: 50%;
        }

        .bottom-row {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 15px;
            align-items: center;
            margin-top: 14px;
            padding: 12px 20px;
            border-radius: 18px;
            background: var(--soft-blue);
        }

        .percent-box {
            display: flex;
            align-items: center;
            gap: 10px;
            border-right: 1px solid #d8e3f0;
        }

        .percent-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #e9f2ff;
        }

        .percent-box strong {
            display: block;
            color: var(--blue-dark);
            font-size: 40px;
        }

        .percent-box span {
            color: #333;
            font-size: 18px;
            font-weight: 700;
        }

        .message {
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--blue-dark);
            font-size: 18px;
            font-weight: 700;
        }

        .slide-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 18px;
        }

        .icon-circle {
            width: 100px;
            height: 100px;
            border: 4px solid #1f5fa8;
            border-radius: 50%;
        }

        .icon-circle i {
            color: #111;
            font-size: 48px;
        }

        .slide-header h1 {
            margin: 0;
            color: var(--blue-title);
            font-size: 46px;
            font-weight: 800;
        }

        .slide-header p {
            margin: 8px 0;
            color: var(--text-muted);
            font-size: 22px;
        }

        .content-row {
            display: grid;
            grid-template-columns: 78% 22%;
            gap: 20px;
            align-items: stretch;
        }

        .chart-card {
            min-width: 0;
            padding: 22px 26px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #fff;
        }

        .chart-card canvas {
            display: block;
            max-width: 100%;
            margin: 0 auto;
        }

        .chart-label {
            width: 420px;
            margin-bottom: 14px;
            padding: 12px 16px;
            border-radius: 12px;
            background: #0b376f;
            color: #fff;
            text-align: center;
            font-size: 16px;
            font-weight: 800;
        }

        .side-cards {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-card {
            position: relative;
            height: 178px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            border: 1px solid var(--border);
            background: #fff;
            overflow: hidden;
        }

        .info-card::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 7px;
            height: 100%;
        }

        .blue-border::after { background: #1f5fa8; }
        .green-border::after { background: var(--green); }
        .orange-border::after { background: var(--orange); }

        .info-icon {
            flex: 0 0 68px;
            width: 68px;
            height: 68px;
            border-radius: 50%;
        }

        .info-icon i {
            color: #111;
            font-size: 32px;
        }

        .blue-bg { background: #e8f1ff; }
        .green-bg { background: #e6faee; }
        .orange-bg { background: #fff3cf; }

        .info-card h3 {
            margin: 0 0 7px;
            color: var(--text-muted);
            font-size: 17px;
            font-weight: 800;
        }

        .info-card strong {
            display: block;
            color: #1f5fa8;
            font-size: 54px;
            line-height: 1.05;
        }

        .info-card p {
            margin: 8px 0 0;
            color: #111;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.25;
        }

        .green-text {
            color: var(--green) !important;
            font-size: 38px !important;
        }

        .orange-text {
            color: var(--orange) !important;
            font-size: 36px !important;
        }

        .bottom-note {
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            gap: 18px;
            align-items: center;
            margin-top: 18px;
            padding: 14px 20px;
            border-radius: 16px;
            background: var(--soft-blue);
            color: #425b76;
            font-size: 17px;
        }

        .bottom-note div {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bottom-note i {
            color: #111;
            font-size: 30px;
        }

        .portada-page{
            padding:0;
            overflow:hidden;
        }

        .portada-img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .portada-texto{
            position:absolute;
            left:15px;
            bottom:0;

            width:625px;
            height:305px;

            display:flex;
            align-items:center;
            justify-content:center;

            color:white;
            text-align:center;

            font-size:50px;
            line-height:1.15;
            font-weight:400;
        }


        .objective-content {
            margin-top: 5px;
            padding: 0 20px;
        }

        .objective-content h2 {
            margin: 0 0 12px;
            color: #0070c0;
            font-size: 40px;
            font-weight: 800;
        }

        .objective-content p,
        .objective-content li {
            color: #000;
            font-size: 26px;
            line-height: 1.42;
            text-align: justify;
        }

        .objective-content p {
            margin: 0 0 28px;
        }

        .objective-content ul {
            margin: 0;
            padding-left: 46px;
        }

        .objective-content li {
            margin-bottom: 16px;
        }
    </style>
</head>

<body>

@php
    $chartPalette = [
        '#2f6bff', '#20a64a', '#f5b21b', '#062b6f',
        '#8b5cf6', '#ef4444', '#14b8a6', '#ec4899',
        '#f97316', '#84cc16', '#6366f1', '#0ea5e9',
        '#22c55e', '#eab308', '#a855f7'
    ];

    $areasIncidenciaLabels = $areasIncidenciaLabels ?? [];
    $areasIncidenciaData = $areasIncidenciaData ?? [];

    $tiposIncidentesLabels = $tiposIncidentesLabels ?? ($tiposIncidenciaLabels ?? []);
    $tiposIncidentesData = $tiposIncidentesData ?? ($tiposIncidenciaData ?? []);

    $usuariosIncidentesLabels = $usuariosIncidentesLabels ?? ($usuariosIncidenciaLabels ?? []);
    $usuariosIncidentesData = $usuariosIncidentesData ?? ($usuariosIncidenciaData ?? []);

    $areasRequerimientoLabels = $areasRequerimientoLabels ?? [];
    $areasRequerimientoData = $areasRequerimientoData ?? [];

    $tiposRequerimientoLabels = $tiposRequerimientoLabels ?? [];
    $tiposRequerimientoData = $tiposRequerimientoData ?? [];

    $usuariosRequerimientoLabels = $usuariosRequerimientoLabels ?? [];
    $usuariosRequerimientoData = $usuariosRequerimientoData ?? [];
@endphp

<section class="page portada-page">

    <img
        src="{{ public_path('images/portada-bvs.png') }}"
        class="portada-img"
    >

    <div class="portada-texto">
        <div>
            Informe del Servicio de<br>
            Soporte software<br>
            Incidencias y<br>
            requerimientos<br>
            Mes de {{ $nombreMes }}.
        </div>
    </div>

</section>

<section class="page">

    <div class="objective-content">

        <h2>Objetivo:</h2>

        <p>
            Presentamos los resultados obtenidos durante la prestación del servicio de soporte de software correspondientes al mes de
            <strong>{{ $nombreMes }}</strong>,
            resaltando el cumplimiento de los niveles de servicio comprometidos, la calidad de atención brindada a los usuarios y la continuidad operativa asegurada por el equipo conformado por cuatro especialistas asignados al servicio.
        </p>

        <ul>
            <li>
                El informe detalla las actividades desarrolladas por cada uno de los especialistas,
                quienes brindaron atención de manera continua y coordinada durante los tres turnos
                establecidos, garantizando la cobertura permanente del servicio y la atención oportuna
                de incidentes y requerimientos reportados por los usuarios.
            </li>

            <li>
                Asimismo, se incluye un análisis consolidado de los incidentes y requerimientos
                gestionados durante el período evaluado, permitiendo visualizar el volumen total de
                atenciones realizadas, identificar tendencias operativas y evaluar la carga de trabajo
                atendida por el equipo de soporte.
            </li>

            <li>
                Se presentan los principales indicadores de desempeño (KPI) del servicio,
                considerando métricas como los tiempos de respuesta, tiempos de resolución,
                cumplimiento de acuerdos de nivel de servicio (SLA) y eficiencia en la gestión
                de tickets, con el objetivo de medir la efectividad y calidad del soporte brindado.
            </li>
        </ul>

        <p>
            Finalmente, se identifican las áreas con mayor demanda de atención, los tipos de incidencias más recurrentes y los usuarios que registraron un mayor volumen de solicitudes. Esta información permite reconocer patrones de comportamiento, detectar oportunidades de mejora, optimizar la asignación de recursos y definir acciones preventivas que contribuyan a fortalecer la calidad del servicio y la satisfacción de los usuarios.
        </p>

    </div>

    <div class="footer">2</div>
    <div class="bvs">BVS</div>

</section>

<section class="page">
    <div class="title">Resumen del Mes {{ $nombreMes }}</div>

    <div class="dashboard-card">

        <div class="header-row">
            <div class="header-left">
                <h1>INCIDENTES Y REQUERIMIENTOS</h1>
                <p>Resumen de atenciones registradas en el periodo</p>

                <div class="line-colors">
                    <span class="blue"></span>
                    <span class="green"></span>
                    <span class="orange"></span>
                </div>
            </div>

            <div class="total-box">
                <div class="total-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div>
                    <span>Total de atenciones</span>
                    <strong>{{ $total }}</strong>
                    <small>en el período</small>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-title">CANTIDAD</div>

            <div class="chart-row">
                <canvas id="resumenChart" width="900" height="420"></canvas>

                <div class="legend-box">
                    <div class="legend-item">
                        <b class="dot blue"></b>
                        Incidente<br>
                        <strong>{{ $incidentes }}</strong>
                        ({{ round(($incidentes / max($total, 1)) * 100) }}%)
                    </div>

                    <div class="legend-item">
                        <b class="dot navy"></b>
                        Requerimiento<br>
                        <strong>{{ $requerimientos }}</strong>
                        ({{ round(($requerimientos / max($total, 1)) * 100) }}%)
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-row">
            <div class="percent-box">
                <div class="percent-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <strong>{{ round(($incidentes / max($total, 1)) * 100) }}%</strong>
                    <span>Incidentes</span>
                </div>
            </div>

            <div class="percent-box">
                <div class="percent-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <strong>{{ round(($requerimientos / max($total, 1)) * 100) }}%</strong>
                    <span>Requerimientos</span>
                </div>
            </div>

            <div class="message">
                <div class="message-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    Seguimos trabajando para brindarte<br>
                    respuestas oportunas y efectivas
                </div>
            </div>
        </div>

    </div>

    <div class="footer">3</div>
    <div class="bvs">BVS</div>
</section>

<section class="page">
    <div class="slide-header">
        <div class="icon-circle">
            <i class="fa-solid fa-chart-column"></i>
        </div>

        <div>
            <h1>INCIDENCIA POR ÁREA</h1>
            <p>Distribución de tickets registrados por área</p>
            <div class="line-colors">
                <span class="blue"></span>
                <span class="green"></span>
                <span class="orange"></span>
            </div>
        </div>
    </div>

    <div class="content-row">
        <div class="chart-card">
            <div class="chart-label">TOTAL DE TICKETS POR ÁREA</div>
            <canvas id="incidenciaAreaChart" width="900" height="420"></canvas>
        </div>

        <div class="side-cards">
            <div class="info-card blue-border">
                <div class="info-icon blue-bg">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <h3>TOTAL DE TICKET</h3>
                    <strong>{{ $totalIncidencias }}</strong>
                </div>
            </div>

            <div class="info-card green-border">
                <div class="info-icon green-bg">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h3>TOTAL DE HORA<br>DE ATENCIÓN</h3>
                    <strong class="green-text">{{ $totalHorasIncidencia }} horas</strong>
                </div>
            </div>

            <div class="info-card orange-border">
                <div class="info-icon orange-bg">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <div>
                    <h3>PROMEDIO TOTAL<br>HORAS DE ATENCIÓN</h3>
                    <strong class="orange-text">{{ $promedioHorasIncidencia }} horas</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-note">
        <div>
            <i class="fa-solid fa-chart-line"></i>
            <span>
                {{ $areaTopIncidencia  }} concentra la mayor cantidad de tickets, representando el
                <b>{{ $porcentajeAreaTopIncidencia }}%</b> del total.
            </span>
        </div>

        <div>
            <i class="fa-solid fa-users"></i>
            <span>
                Seguimos trabajando para optimizar la atención y brindar un servicio cada vez más eficiente.
            </span>
        </div>
    </div>

    <div class="footer">4</div>
    <div class="bvs">BVS</div>
</section>

<section class="page">
    <div class="slide-header">
        <div class="icon-circle">
            <i class="fa-solid fa-chart-pie"></i>
        </div>

        <div>
            <h1>INCIDENCIA POR TIPO</h1>
            <p>Distribución de tickets registrados por tipo</p>
            <div class="line-colors">
                <span class="blue"></span>
                <span class="green"></span>
                <span class="orange"></span>
            </div>
        </div>
    </div>

    <div class="content-row">
        <div class="chart-card">
            <div class="chart-label">TOTAL DE TICKETS POR TIPO</div>
            <canvas id="incidenciaTipoChart" width="900" height="420"></canvas>
        </div>

        <div class="side-cards">
            <div class="info-card blue-border">
                <div class="info-icon blue-bg">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <h3>TOTAL DE TICKET</h3>
                    <strong>{{ $totalIncidencias }}</strong>
                </div>
            </div>

            <div class="info-card green-border">
                <div class="info-icon green-bg">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <h3>TIPO DOMINANTE</h3>
                    <strong class="green-text">{{ $tipoTopIncidencia }}</strong>
                    <p>{{ $porcentajeTipoTopIncidencia }}% del total</p>
                </div>
            </div>

            <div class="info-card orange-border">
                <div class="info-icon orange-bg">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <div>
                    <h3>CANTIDAD TOP</h3>
                    <strong class="orange-text">{{ $cantidadTipoTopIncidencia }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-note">
        <div>
            <i class="fa-solid fa-chart-line"></i>
            <span>
                {{ $tipoTopIncidencia }} concentra la mayor cantidad de tickets,
                representando el <b>{{ $porcentajeTipoTopIncidencia }}%</b> del total.
            </span>
        </div>

        <div>
            <i class="fa-solid fa-users"></i>
            <span>
                Seguimos trabajando para optimizar la atención y brindar un servicio cada vez más eficiente.
            </span>
        </div>
    </div>

    <div class="footer">5</div>
    <div class="bvs">BVS</div>
</section>

<section class="page">
    <div class="slide-header">
        <div class="icon-circle">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>
            <h1>USUARIOS CON MÁS INCIDENCIAS</h1>
            <p>Distribución de tickets registrados por usuario</p>
            <div class="line-colors">
                <span class="blue"></span>
                <span class="green"></span>
                <span class="orange"></span>
            </div>
        </div>
    </div>

    <div class="content-row">
        <div class="chart-card">
            <div class="chart-label">TOTAL DE TICKETS POR USUARIO</div>
            <canvas id="usuariosIncidenciaChart" width="900" height="420"></canvas>
        </div>

        <div class="side-cards">
            <div class="info-card blue-border">
                <div class="info-icon blue-bg">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <h3>TOTAL DE TICKET</h3>
                    <strong>{{ $totalIncidencias }}</strong>
                </div>
            </div>

            <div class="info-card green-border">
                <div class="info-icon green-bg">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h3>TOTAL USUARIOS</h3>
                    <strong class="green-text">{{ $totalUsuariosIncidencia }}</strong>
                </div>
            </div>

            <div class="info-card orange-border">
                <div class="info-icon orange-bg">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <h3>USUARIO TOP<br>(mayor tickets)</h3>
                    <strong class="orange-text">{{ $usuarioTopIncidencia }}</strong>
                    <p>{{ $cantidadUsuarioTopIncidencia }} tickets — {{ $porcentajeUsuarioTopIncidencia }}% del total</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-note">
        <div>
            <i class="fa-solid fa-chart-line"></i>
            <span>
                {{ $usuarioTopIncidencia }} concentra la mayor cantidad de tickets con
                <b>{{ $cantidadUsuarioTopIncidencia }}</b> tickets
                (<b>{{ $porcentajeUsuarioTopIncidencia }}%</b>).
            </span>
        </div>

        <div>
            <i class="fa-solid fa-users"></i>
            <span>
                Seguimos trabajando para optimizar la atención y brindar un servicio cada vez más eficiente.
            </span>
        </div>
    </div>

    <div class="footer">6</div>
    <div class="bvs">BVS</div>
</section>

<section class="page">
    <div class="slide-header">
        <div class="icon-circle">
            <i class="fa-solid fa-building"></i>
        </div>

        <div>
            <h1>REQUERIMIENTO POR ÁREA</h1>
            <p>Distribución de tickets registrados por área</p>

            <div class="line-colors">
                <span class="blue"></span>
                <span class="green"></span>
                <span class="orange"></span>
            </div>
        </div>
    </div>

    <div class="content-row">
        <div class="chart-card">
            <div class="chart-label">TOTAL DE TICKETS POR ÁREA</div>
            <canvas id="requerimientoAreaChart" width="900" height="420"></canvas>
        </div>

        <div class="side-cards">

            <div class="info-card blue-border">
                <div class="info-icon blue-bg">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <h3>TOTAL DE TICKET</h3>
                    <strong>{{ $totalRequerimientos }}</strong>
                </div>
            </div>

            <div class="info-card green-border">
                <div class="info-icon green-bg">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h3>TOTAL DE HORA DE ATENCIÓN</h3>
                    <strong class="green-text">
                        {{ $totalHorasRequerimiento }} horas
                    </strong>
                </div>
            </div>

            <div class="info-card orange-border">
                <div class="info-icon orange-bg">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <div>
                    <h3>PROMEDIO TOTAL HORAS DE ATENCIÓN</h3>
                    <strong class="orange-text">
                        {{ $promedioHorasRequerimiento }} horas
                    </strong>
                </div>
            </div>

        </div>
    </div>

    <div class="bottom-note">
        <div>
            <i class="fa-solid fa-chart-line"></i>
            <span>
                {{ $areaTopRequerimiento }}
                concentra la mayor cantidad de tickets,
                representando el
                <b>{{ $porcentajeAreaTopRequerimiento }}%</b>
                del total.
            </span>
        </div>

        <div>
            <i class="fa-solid fa-users"></i>
            <span>
                Seguimos trabajando para optimizar la atención y brindar un servicio cada vez más eficiente.
            </span>
        </div>
    </div>

    <div class="footer">7</div>
    <div class="bvs">BVS</div>
</section>

<section class="page">
    <div class="slide-header">
        <div class="icon-circle">
            <i class="fa-solid fa-chart-pie"></i>
        </div>

        <div>
            <h1>REQUERIMIENTO POR TIPO</h1>
            <p>Distribución de tickets registrados por tipo de requerimiento</p>

            <div class="line-colors">
                <span class="blue"></span>
                <span class="green"></span>
                <span class="orange"></span>
            </div>
        </div>
    </div>

    <div class="content-row">
        <div class="chart-card">
            <div class="chart-label">TOTAL DE TICKETS POR TIPO</div>
            <canvas id="requerimientoTipoChart" width="900" height="420"></canvas>
        </div>

        <div class="side-cards">
            <div class="info-card blue-border">
                <div class="info-icon blue-bg">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <h3>TOTAL DE TICKET</h3>
                    <strong>{{ $totalRequerimientos }}</strong>
                </div>
            </div>

            <div class="info-card green-border">
                <div class="info-icon green-bg">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h3>TOTAL DE HORA<br>DE ATENCIÓN</h3>
                    <strong class="green-text">{{ $totalHorasRequerimiento }} horas</strong>
                </div>
            </div>

            <div class="info-card orange-border">
                <div class="info-icon orange-bg">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <div>
                    <h3>PROMEDIO TOTAL<br>HORAS DE ATENCIÓN</h3>
                    <strong class="orange-text">{{ $promedioHorasRequerimiento }} horas</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-note">
        <div>
            <i class="fa-solid fa-chart-line"></i>
            <span>
                {{ $tipoTopRequerimiento }} concentra la mayor cantidad de requerimientos,
                representando el <b>{{ $porcentajeTipoTopRequerimiento }}%</b> del total.
            </span>
        </div>

        <div>
            <i class="fa-solid fa-users"></i>
            <span>
                Seguimos trabajando para optimizar la atención y brindar un servicio cada vez más eficiente.
            </span>
        </div>
    </div>

    <div class="footer">8</div>
    <div class="bvs">BVS</div>
</section>

<section class="page">

    <div class="slide-header">
        <div class="icon-circle">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>
            <h1>USUARIOS CON MÁS REQUERIMIENTOS</h1>
            <p>Distribución de tickets registrados por usuario</p>

            <div class="line-colors">
                <span class="blue"></span>
                <span class="green"></span>
                <span class="orange"></span>
            </div>
        </div>
    </div>

    <div class="content-row">

        <div class="chart-card">
            <div class="chart-label">
                TOTAL DE TICKETS POR USUARIO
            </div>

            <canvas
                id="usuariosRequerimientoChart"
                width="900"
                height="420">
            </canvas>
        </div>

        <div class="side-cards">

            <div class="info-card blue-border">
                <div class="info-icon blue-bg">
                    <i class="fa-solid fa-ticket"></i>
                </div>

                <div>
                    <h3>TOTAL DE TICKET</h3>
                    <strong>{{ $totalRequerimientos }}</strong>
                </div>
            </div>

            <div class="info-card green-border">
                <div class="info-icon green-bg">
                    <i class="fa-solid fa-users"></i>
                </div>

                <div>
                    <h3>TOTAL USUARIOS</h3>
                    <strong class="green-text">
                        {{ $totalUsuariosRequerimiento }}
                    </strong>
                </div>
            </div>

            <div class="info-card orange-border">
                <div class="info-icon orange-bg">
                    <i class="fa-solid fa-star"></i>
                </div>

                <div>
                    <h3>USUARIO TOP<br>(mayor tickets)</h3>

                    <strong class="orange-text">
                        {{ $usuarioTopRequerimiento }}
                    </strong>

                    <p>
                        {{ $cantidadUsuarioTopRequerimiento }}
                        tickets —
                        {{ $porcentajeUsuarioTopRequerimiento }}%
                        del total
                    </p>
                </div>
            </div>

        </div>

    </div>

    <div class="bottom-note">

        <div>
            <i class="fa-solid fa-chart-line"></i>

            <span>
                {{ $usuarioTopRequerimiento }}
                concentra la mayor cantidad de tickets con
                <b>{{ $cantidadUsuarioTopRequerimiento }}</b>
                tickets
                (<b>{{ $porcentajeUsuarioTopRequerimiento }}%</b>).
            </span>
        </div>

        <div>
            <i class="fa-solid fa-users"></i>

            <span>
                Seguimos trabajando para optimizar la atención y brindar un servicio cada vez más eficiente.
            </span>
        </div>

    </div>

    <div class="footer">9</div>
    <div class="bvs">BVS</div>

</section>

<script>
    Chart.defaults.font.family = 'Liberation Sans';
    Chart.defaults.font.weight = 'bold';
    
    const chartColors = @json($chartPalette);

    const dashboardData = [{{ $incidentes }}, {{ $requerimientos }}];

    const valueLabelsPlugin = {
        id: 'valueLabelsPlugin',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            const isHorizontal = chart.options.indexAxis === 'y';

            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);

                meta.data.forEach((bar, index) => {
                    const value = dataset.data[index];

                    ctx.save();
                    ctx.fillStyle = '#0b2344';
                    ctx.font = isHorizontal ? 'bold 16px "Liberation Sans"' : 'bold 30px "Liberation Sans"';
                    ctx.textAlign = isHorizontal ? 'left' : 'center';
                    ctx.textBaseline = 'middle';

                    if (isHorizontal) {
                        ctx.fillText(value, bar.x + 8, bar.y);
                    } else {
                        ctx.fillText(value, bar.x, bar.y - 14);
                    }

                    ctx.restore();
                });
            });
        }
    };

    function getColors(total) {
        return Array.from({ length: total }, (_, index) => chartColors[index % chartColors.length]);
    }

    function createVerticalBarChart(canvasId, labels, data, colors) {
        const canvas = document.getElementById(canvasId);

        if (!canvas) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: colors,
                    borderRadius: 10,
                    barThickness: 90
                }]
            },
            options: {
                responsive: false,
                animation: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#222',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...data, 0) + 10,
                        ticks: {
                            stepSize: 10,
                            color: '#777',
                            font: {
                                size: 13
                            }
                        },
                        grid: {
                            color: '#e5e7eb',
                            borderDash: [5, 5]
                        }
                    }
                }
            },
            plugins: [valueLabelsPlugin]
        });
    }

    function createHorizontalBarChart(canvasId, labels, data, options = {}) {
        const canvas = document.getElementById(canvasId);

        if (!canvas) return;

        const maxValue = Math.max(...data, 0);
        const isUsersChart = options.users === true;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: getColors(data.length),
                    borderRadius: 6,
                    barThickness: options.barThickness ?? (isUsersChart ? 26 : 38)
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: false,
                maintainAspectRatio: false,
                animation: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 55,
                        top: 8,
                        bottom: 8
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        suggestedMax: maxValue + Math.max(2, Math.ceil(maxValue * 0.15)),
                        ticks: {
                            stepSize: options.stepSize ?? 1,
                            color: '#777',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    y: {
                        afterFit(scale) {
                            scale.width = options.labelWidth ?? (isUsersChart ? 118 : 130);
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#0b2344',
                            autoSkip: false,
                            font: {
                                size: options.fontSize ?? (isUsersChart ? 10 : 13),
                                weight: 'bold'
                            }
                        }
                    }
                }
            },
            plugins: [valueLabelsPlugin]
        });
    }

    createVerticalBarChart(
        'resumenChart',
        ['Incidente', 'Requerimiento'],
        dashboardData,
        ['#2f6bff', '#062b6f']
    );

    createHorizontalBarChart(
        'incidenciaAreaChart',
        @json($areasIncidenciaLabels),
        @json($areasIncidenciaData),
        { barThickness: 36, stepSize: 1, labelWidth: 260, fontSize: 15 }
    );

    createHorizontalBarChart(
        'incidenciaTipoChart',
        @json($tiposIncidentesLabels),
        @json($tiposIncidentesData),
        { barThickness: 36, stepSize: 1, labelWidth: 160, fontSize: 15 }
    );

    createHorizontalBarChart(
        'usuariosIncidenciaChart',
        @json($usuariosIncidentesLabels),
        @json($usuariosIncidentesData),
        { users: true, barThickness: 26, stepSize: 1, labelWidth: 180, fontSize: 12 }
    );

    createHorizontalBarChart(
        'requerimientoAreaChart',
        @json($areasRequerimientoLabels),
        @json($areasRequerimientoData),
        { barThickness: 36, stepSize: 5, labelWidth: 260, fontSize: 15 }
    );

    createHorizontalBarChart(
        'requerimientoTipoChart',
        @json($tiposRequerimientoLabels),
        @json($tiposRequerimientoData),
        { barThickness: 28, stepSize: 5, labelWidth: 165, fontSize: 14 }
    );

    createHorizontalBarChart(
        'usuariosRequerimientoChart',
        @json($usuariosRequerimientoLabels),
        @json($usuariosRequerimientoData),
        { users: true, barThickness: 26, stepSize: 2, labelWidth: 180, fontSize: 12 }
    );
</script>



</body>
</html>