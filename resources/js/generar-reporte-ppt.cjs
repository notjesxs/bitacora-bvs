const pptxgen = require("pptxgenjs");
const fs = require("fs");
const path = require("path");

if (!process.argv[2]) {
    throw new Error("Falta el archivo JSON de entrada.");
}

if (!process.argv[3]) {
    throw new Error("Falta la ruta de salida del PPTX.");
}

const data = JSON.parse(fs.readFileSync(process.argv[2], "utf8"));
const output = process.argv[3];

const pptx = new pptxgen();

pptx.layout = "LAYOUT_WIDE";
pptx.author = "Sistema de Bitácoras BVS";
pptx.subject = "Informe Soporte Software";
pptx.title = "Informe del Servicio de Soporte Software";

const COLORS = {
    BLUE: "3366FF",
    DARK_BLUE: "002060",
    TITLE_BLUE: "0070C0",
    TEXT: "595959",
    BLACK: "000000",
    WHITE: "FFFFFF",
    GRID: "D9D9D9",
    TABLE_HEADER: "D9E2F3",
    TABLE_BORDER: "BFBFBF",
};

const FONT = "Arial";
const SLIDE_W = 13.333;
const SLIDE_H = 7.5;

function safeRows(rows) {
    return Array.isArray(rows) ? rows : [];
}

function getMesAnio() {
    const partes = String(data.nombreMes || "").split(" ");
    return {
        mes: partes[0] || "",
        anio: partes[1] || "",
    };
}

function getAxisMax(values) {
    const max = Math.max(...values.map(v => Number(v) || 0), 1);

    if (max <= 10) return 10;
    if (max <= 20) return 20;

    return Math.ceil((max + 5) / 10) * 10;
}

function addTitleWithBlueBar(slide, title) {
    slide.addShape(pptx.ShapeType.rect, {
        x: 0.18,
        y: 0.32,
        w: 0.12,
        h: 0.5,
        fill: { color: COLORS.BLUE },
        line: { color: COLORS.BLUE },
    });

    slide.addText(title, {
        x: 0.42,
        y: 0.35,
        w: 8,
        h: 0.3,
        fontSize: 16,
        bold: true,
        color: "111111",
        fontFace: FONT,
    });
}

function addFooter(slide, page) {
    slide.addShape(pptx.ShapeType.rect, {
        x: 0,
        y: 6.95,
        w: 0.60,
        h: 0.55,
        fill: { color: COLORS.BLUE },
        line: { color: COLORS.BLUE }
    });

    slide.addText(String(page), {
        x: 0.08,
        y: 7.03,
        w: 0.48,
        h: 0.25,
        fontSize: 9,
        color: COLORS.WHITE,
        bold: true,
        align: "center",
        valign: "mid",
        fontFace: FONT,
        fit: "shrink",
        breakLine: false,
    });
    slide.addText("BVS", {
        x: 12.45,
        y: 7.0,
        w: 0.75,
        h: 0.3,
        fontSize: 18,
        bold: true,
        color: COLORS.BLUE,
        fontFace: FONT,
    });
}

function addBullet(slide, text, y, h = 0.65, fontSize = 18) {
    slide.addText("•", {
        x: 0.65,
        y,
        w: 0.25,
        h: 0.25,
        fontSize: fontSize,
        color: COLORS.BLACK,
        fontFace: FONT,
    });

    slide.addText(text, {
        x: 1.15,
        y,
        w: 11.5,
        h,
        fontSize: fontSize,
        color: COLORS.BLACK,
        fontFace: FONT,
        fit: "shrink",
        breakLine: false,
    });
}

function addIndicadorTable(slide, x, y, totalTickets, totalHoras, promedioHoras) {
    const rows = [
        ["TOTAL DE TICKET", totalTickets],
        ["TOTAL DE HORA DE ATENCION", totalHoras],
        ["PROMEDIO TOTAL HORAS DE ATENCION", promedioHoras],
    ];

    rows.forEach((row, i) => {
        const top = y + i * 0.47;

        slide.addShape(pptx.ShapeType.rect, {
            x,
            y: top,
            w: 3.95,
            h: 0.23,
            fill: { color: COLORS.DARK_BLUE },
            line: { color: COLORS.BLACK, width: 0.7 },
        });

        slide.addText(row[0], {
            x,
            y: top + 0.01,
            w: 3.95,
            h: 0.18,
            fontSize: 10.5,
            color: COLORS.WHITE,
            align: "center",
            valign: "mid",
            fontFace: FONT,
        });

        slide.addShape(pptx.ShapeType.rect, {
            x,
            y: top + 0.23,
            w: 3.95,
            h: 0.23,
            fill: { color: COLORS.WHITE },
            line: { color: COLORS.BLACK, width: 0.7 },
        });

        slide.addText(String(row[1] ?? ""), {
            x,
            y: top + 0.245,
            w: 3.95,
            h: 0.18,
            fontSize: 10.5,
            color: COLORS.BLACK,
            align: "center",
            valign: "mid",
            fontFace: FONT,
        });
    });
}

function addHorizontalBars(slide, title, rows, x, y, w, h, adaptive = false) {
    rows = safeRows(rows);

    slide.addText(title, {
        x: x + 1.3,
        y: y - 0.45,
        w: 4.8,
        h: 0.3,
        fontSize: 15,
        color: COLORS.TEXT,
        align: "center",
        fontFace: FONT,
    });

    if (rows.length === 0) {
        slide.addText("Sin datos disponibles", {
            x,
            y,
            w,
            h: 0.4,
            fontSize: 12,
            color: COLORS.TEXT,
            align: "center",
            fontFace: FONT,
        });
        return;
    }

    const values = rows.map(r => Number(r.total) || 0);
    const max = Math.max(...values, 1);
    const axisMax = Math.ceil((max + 1) / 2) * 2;

    const labelW = 1.25;
    const chartX = x + labelW;
    const chartW = w - labelW - 0.3;

    const count = rows.length;

    const barH = adaptive
        ? count <= 3 ? 0.42 : count <= 5 ? 0.35 : 0.28
        : 0.42;

    const gap = adaptive
        ? count <= 3 ? 0.55 : count <= 5 ? 0.25 : 0.18
        : 0.77;

    const labelFont = adaptive && count > 5 ? 8 : 9.5;

    rows.forEach((r, i) => {
        const rowY = y + i * (barH + gap);
        const total = Number(r.total) || 0;
        const barW = (total / axisMax) * chartW;

        slide.addText(String(r.label ?? ""), {
            x,
            y: rowY + 0.05,
            w: labelW - 0.15,
            h: 0.25,
            fontSize: labelFont,
            color: COLORS.TEXT,
            align: "right",
            fontFace: FONT,
            fit: "shrink",
        });

        slide.addShape(pptx.ShapeType.rect, {
            x: chartX,
            y: rowY,
            w: barW,
            h: barH,
            fill: { color: COLORS.DARK_BLUE },
            line: { color: COLORS.DARK_BLUE },
        });

        slide.addText(String(total), {
            x: chartX + barW + 0.08,
            y: rowY + 0.05,
            w: 0.35,
            h: 0.2,
            fontSize: labelFont,
            color: COLORS.BLACK,
            fontFace: FONT,
        });
    });

    const lastBarY = y + (rows.length - 1) * (barH + gap);
    const axisY = adaptive ? lastBarY + barH + 0.12 : y + h + 0.1;

    slide.addShape(pptx.ShapeType.line, {
        x: chartX,
        y: y - 0.25,
        w: 0,
        h: axisY - y - 0.1,
    });

    for (let i = 0; i <= axisMax; i += 2) {
        const tickX = chartX + (i / axisMax) * chartW;

        slide.addText(String(i), {
            x: tickX - 0.18,
            y: axisY,
            w: 0.6,
            h: 0.25,
            fontSize: 9,
            color: COLORS.TEXT,
            fontFace: FONT,
            align: "center",
        });
    }
}

function addIncidenciasAreaSlide(rows, page) {
    rows = safeRows(rows);

    const slide = pptx.addSlide();

    const graphX = 1.35;
    const graphY = 2.35;
    const graphW = 6.65;
    const graphH = 3.7;

    const { mes } = getMesAnio();

    addTitleWithBlueBar(slide, "Incidencias por área en el mes de " + mes);

    addHorizontalBars(
        slide,
        "INCIDENCIA POR AREA",
        rows,
        graphX,
        graphY,
        graphW,
        graphH,
        false
    );

    const totalTickets = rows.reduce((sum, r) => sum + (Number(r.total) || 0), 0);

    addIndicadorTable(
        slide,
        8.65,
        3.15,
        totalTickets,
        data.totalHorasAtencionIncidentes ?? 0,
        data.promedioHorasAtencionIncidentes ?? "0.0"
    );

    addFooter(slide, page);
}

function addTiposIncidentesSlide(rows, page) {
    rows = safeRows(rows);

    const slide = pptx.addSlide();

    addTitleWithBlueBar(slide, "Tipos de Incidentes");

    addHorizontalBars(
        slide,
        "TIPO DE INCIDENCIA",
        rows,
        2.3,
        2.05,
        7.4,
        4.6,
        true
    );

    slide.addShape(pptx.ShapeType.rect, {
        x: 10.95,
        y: 4.2,
        w: 0.1,
        h: 0.1,
        fill: { color: COLORS.DARK_BLUE },
        line: { color: COLORS.DARK_BLUE },
    });

    slide.addText("Total", {
        x: 11.1,
        y: 4.12,
        w: 0.7,
        h: 0.25,
        fontSize: 10,
        color: COLORS.TEXT,
        fontFace: FONT,
    });

    addFooter(slide, page);
}

function addUsuariosIncidenciasSlide(rows, page) {
    rows = safeRows(rows);

    const slide = pptx.addSlide();

    addTitleWithBlueBar(slide, "Gráfico de usuarios con más incidencias");

    addHorizontalBars(
        slide,
        "TOTAL DE USUARIOS",
        rows,
        2.25,
        2.05, // antes estaba 1.25
        8.9,
        4.6,
        true
    );

    slide.addShape(pptx.ShapeType.rect, {
        x: 11.55,
        y: 4.14,
        w: 0.1,
        h: 0.1,
        fill: { color: COLORS.DARK_BLUE },
        line: { color: COLORS.DARK_BLUE },
    });

    slide.addText("Total", {
        x: 11.7,
        y: 4.06,
        w: 0.65,
        h: 0.25,
        fontSize: 10,
        color: COLORS.TEXT,
        fontFace: FONT,
    });

    addFooter(slide, page);
}

function addRequerimientosAreaSlide(rows, page) {
    rows = safeRows(rows);

    const slide = pptx.addSlide();

    const graphX = 1.35;
    const graphY = 2.35;
    const graphW = 6.65;
    const graphH = 3.7;

    const { mes } = getMesAnio();

    addTitleWithBlueBar(
        slide,
        `Requerimientos solicitados por área en el mes de ${mes}`
    );

    addHorizontalBars(
        slide,
        "REQUERIMIENTO POR AREA",
        rows,
        graphX,
        graphY,
        graphW,
        graphH,
        true
    );

    const totalTickets = rows.reduce(
        (sum, r) => sum + (Number(r.total) || 0),
        0
    );

    addIndicadorTable(
        slide,
        8.65,
        3.15,
        totalTickets,
        data.totalHorasAtencionRequerimientos ?? 0,
        data.promedioHorasAtencionRequerimientos ?? 0
    );

    addFooter(slide, page);
}

function addTiposRequerimientosSlide(rows, page) {
    rows = safeRows(rows);

    const slide = pptx.addSlide();

    addTitleWithBlueBar(slide, "Tipo de Requerimientos");

    addHorizontalBars(
        slide,
        "TIPO DE REQUERIMIENTOS",
        rows,
        2.15,
        1.95,
        8.4,
        4.6,
        true
    );

    slide.addShape(pptx.ShapeType.rect, {
        x: 11.45,
        y: 4.35,
        w: 0.1,
        h: 0.1,
        fill: { color: COLORS.DARK_BLUE },
        line: { color: COLORS.DARK_BLUE },
    });

    slide.addText("Total", {
        x: 11.6,
        y: 4.27,
        w: 0.7,
        h: 0.25,
        fontSize: 10,
        color: COLORS.TEXT,
        fontFace: FONT,
    });

    addFooter(slide, page);
}

function addUsuariosRequerimientosSlide(rows, page) {
    rows = safeRows(rows);

    const slide = pptx.addSlide();

    addTitleWithBlueBar(
        slide,
        "Gráfico de usuarios con más requerimientos"
    );

    addHorizontalBars(
        slide,
        "TOTAL DE USUARIOS",
        rows,
        2.55,
        1.65,
        8.3,
        5.1,
        true
    );

    slide.addShape(pptx.ShapeType.rect, {
        x: 10.75,
        y: 4.18,
        w: 0.1,
        h: 0.1,
        fill: { color: COLORS.DARK_BLUE },
        line: { color: COLORS.DARK_BLUE },
    });

    slide.addText("Total", {
        x: 10.9,
        y: 4.1,
        w: 0.7,
        h: 0.25,
        fontSize: 10,
        color: COLORS.TEXT,
        fontFace: FONT,
    });

    addFooter(slide, page);
}

function addPortadaSlide(page) {
    const slide = pptx.addSlide();
    const portadaPath = path.join(process.cwd(), "images", "portada-bvs.png");

    if (fs.existsSync(portadaPath)) {
        slide.addImage({
            path: portadaPath,
            x: 0,
            y: 0,
            w: SLIDE_W,
            h: SLIDE_H,
        });
    } else {
        slide.background = { color: COLORS.DARK_BLUE };
    }

    const { mes, anio } = getMesAnio();

    slide.addText(
        `Informe  del Servicio de\nSoporte software Incidencias y\nrequerimientos\nMes de ${mes} ${anio}.`,
        {
            x: 0.35,
            y: 5.25,
            w: 4.9,
            h: 1.25,
            fontSize: 21,
            color: COLORS.WHITE,
            fontFace: FONT,
            align: "center",
            valign: "mid",
            fit: "shrink",
            breakLine: false,
        }
    );
}

function addObjetivoSlide(page) {
    const slide = pptx.addSlide();
    const { mes } = getMesAnio();

    slide.addText("Objetivo:", {
        x: 0.65,
        y: 0.45,
        w: 4,
        h: 0.35,
        fontSize: 20,
        bold: true,
        color: COLORS.TITLE_BLUE,
        fontFace: FONT,
    });

    addBullet(
        slide,
        `Presentar los resultados del servicio de soporte software correspondientes al mes de ${mes}, destacando la\ncalidad del servicio y la continuidad operativa asegurada por nuestro equipo de 04 especialistas.`,
        1.15,
        0.7
    );

    addBullet(
        slide,
        "El informe detalla las atenciones realizadas por los especialistas, quienes operaron de manera ininterrumpida\ndurante los 03 turnos establecidos, garantizando la cobertura del servicio.",
        2.25,
        0.7
    );

    addBullet(
        slide,
        "Asimismo, incluye un resumen consolidado de incidentes y requerimientos atendidos, permitiendo analizar el\nvolumen total de atenciones gestionadas durante el período evaluado.",
        3.35,
        0.7
    );

    addBullet(
        slide,
        "Se presentan los tiempos de respuesta y de resolución como indicadores clave de desempeño (KPI) del servicio.",
        4.45,
        0.35
    );

    slide.addText(
        "Finalmente, se identifican las áreas con mayor número de reportes, los tipos de incidentes más recurrentes y los\nusuarios con mayor volumen de incidencias, facilitando el análisis de patrones de uso y la identificación de\noportunidades de mejora continua.",
        {
            x: 0.65,
            y: 5.25,
            w: 12.1,
            h: 0.9,
            fontSize: 18,
            color: COLORS.BLACK,
            fontFace: FONT,
            fit: "shrink",
            breakLine: false,
        }
    );

    addFooter(slide, page);
}

function addResumenMesSlide(page) {
    const slide = pptx.addSlide();

    addTitleWithBlueBar(slide, "Resumen del Mes " + data.nombreMes);

    const chartW = 9.2;
    const chartH = 5.6;
    const chartX = (SLIDE_W - chartW) / 2;

    const resumenValues = [
        Number(data.incidentes) || 0,
        Number(data.requerimientos) || 0,
    ];

    slide.addChart(
        pptx.ChartType.bar,
        [
            {
                name: "Total",
                labels: ["Incidente", "Requerimiento"],
                values: resumenValues,
            },
        ],
        {
            x: chartX,
            y: 0.95,
            w: chartW,
            h: chartH,

            showTitle: false,
            showLegend: true,
            legendPos: "r",

            showValue: true,
            dataLabelPosition: "outEnd",

            showCatAxis: false,
            showValAxis: false,

            valAxisMinVal: 0,
            valAxisMaxVal: getAxisMax(resumenValues),
            valAxisMajorUnit: 10,

            showMajorGridLines: false,
            showMinorGridLines: false,

            valAxisLineColor: COLORS.WHITE,
            valAxisLineTransparency: 100,

            catAxisLineColor: COLORS.WHITE,
            catAxisLineTransparency: 100,

            valGridLine: {
                color: COLORS.WHITE,
                transparency: 100,
            },

            gapWidthPct: 420,

            chartColors: ["4F75BD", "17377A"],

            valAxisLabelFontSize: 8,
            catAxisLabelFontSize: 8,
        }
    );

    addFooter(slide, page);
}

function addAccionesRecomendacionesSlide(page) {
    const slide = pptx.addSlide();

    addTitleWithBlueBar(
        slide,
        "Acciones y Recomendaciones."
    );

    const pag10Path = path.join(process.cwd(), "images", "pag10.png");

    if (fs.existsSync(pag10Path)) {
        slide.addImage({
            path: pag10Path,
            x: 1.0,
            y: 1.15,
            w: 11.0,
            h: 5.63
        });
    }

    addFooter(slide, page);
}

function addAccionesRecomendaciones2Slide(page) {
    const slide = pptx.addSlide();

    addTitleWithBlueBar(
        slide,
        "Acciones y Recomendaciones."
    );

    const pag11Path = path.join(process.cwd(), "images", "pag11.png");

    if (fs.existsSync(pag11Path)) {
        slide.addImage({
            path: pag11Path,
            x: 1.0,
            y: 1.15,
            w: 11.0,
            h: 5.63
        });
    }

    addFooter(slide, page);
}

function addConclusionesSlide(page) {
    const slide = pptx.addSlide();
    const { mes } = getMesAnio();

    slide.addText("•", {
        x: 0.65,
        y: 0.78,
        w: 0.2,
        h: 0.3,
        fontSize: 18,
        color: COLORS.BLACK,
        fontFace: FONT,
    });

    slide.addText("Conclusiones.", {
        x: 0.92,
        y: 0.80,
        w: 4,
        h: 0.3,
        fontSize: 20,
        bold: true,
        color: COLORS.BLACK,
        fontFace: FONT,
    });

    slide.addText(
        `El servicio de soporte software proporcionado durante el mes de ${mes} se caracterizó por la\n` +
        `continuidad operativa, la calidad de la atención y la eficiencia en la resolución de incidentes y\n` +
        `requerimientos.`,
        {
            x: 0.65,
            y: 1.60,
            w: 11.9,
            h: 0.85,
            fontSize: 20,
            color: COLORS.BLACK,
            fontFace: FONT,
            breakLine: false,
            fit: "shrink",
        }
    );

    slide.addText(
        "El equipo de cuatro especialistas demostró un alto nivel de compromiso y profesionalismo,\n" +
        "garantizando la satisfacción de los usuarios y el correcto funcionamiento del software.",
        {
            x: 0.65,
            y: 2.70,
            w: 11.9,
            h: 0.65,
            fontSize: 20,
            color: COLORS.BLACK,
            fontFace: FONT,
            breakLine: false,
            fit: "shrink",
        }
    );

    slide.addText(
        "Los KPI del servicio se mantuvieron dentro de los rangos esperados, lo que indica un buen\n" +
        "desempeño general.",
        {
            x: 0.65,
            y: 3.80,
            w: 11.9,
            h: 0.65,
            fontSize: 20,
            color: COLORS.BLACK,
            fontFace: FONT,
            breakLine: false,
            fit: "shrink",
        }
    );

    slide.addText(
        "Sin embargo, se identificaron algunas áreas de mejora que se abordarán en los próximos meses a\n" +
        "través de la implementación de acciones específicas.",
        {
            x: 0.65,
            y: 4.60,
            w: 11.9,
            h: 0.65,
            fontSize: 20,
            color: COLORS.BLACK,
            fontFace: FONT,
            breakLine: false,
            fit: "shrink",
        }
    );

    slide.addText(
        "En resumen, el servicio de soporte software cumplió con los objetivos establecidos y contribuyó de\n" +
        "manera significativa a la continuidad operativa del Puerto de Paracas.",
        {
            x: 0.65,
            y: 5.70,
            w: 11.9,
            h: 0.65,
            fontSize: 20,
            color: COLORS.BLACK,
            fontFace: FONT,
            breakLine: false,
            fit: "shrink",
        }
    );

    addFooter(slide, page);
}


function addMejoraContinuaSlide(page) {
    const slide = pptx.addSlide();

    slide.addText("Acciones de Mejora continua", {
        x: 0.65,
        y: 0.35,
        w: 4.5,
        h: 0.35,
        fontSize: 20,
        bold: true,
        color: "0070C0",
        fontFace: FONT,
    });

    slide.addText(
        "Con base en los resultados del análisis de los\n" +
        "incidentes,\n" +
        "requerimientos y KPI, se han identificado las\n" +
        "siguientes\n" +
        "acciones de mejora continua:",
        {
            x: 0.65,
            y: 0.75,
            w: 5.3,
            h: 1.4,
            fontSize: 16,
            color: COLORS.BLACK,
            fontFace: FONT,
        }
    );

    addBullet(
        slide,
        "Implementar un programa de capacitación para los\nusuarios sobre el uso del módulo del software que\npresenta un mayor número de incidentes.",
        2.35,
        0.8,
        15
    );

    addBullet(
        slide,
        "Optimizar los procesos de soporte para reducir el tiempo\nde respuesta y resolución de los incidentes.",
        3.25,
        0.7,
        15
    );

    addBullet(
        slide,
        "Desarrollar una base de conocimientos con preguntas\nfrecuentes y soluciones a problemas comunes para\nmejorar la tasa de resolución en el primer contacto.",
        4.05,
        0.95,
        15
    );

    slide.addText(
        "Estas acciones se implementarán en los próximos meses con\n" +
        "el objetivo de mejorar la calidad del servicio de soporte\n" +
        "software y la satisfacción del cliente.",
        {
            x: 0.65,
            y: 5.35,
            w: 6.4,
            h: 0.85,
            fontSize: 18,
            color: COLORS.BLACK,
            fontFace: FONT,
            breakLine: false,
        }
    );

    const pag13Path = path.join(process.cwd(), "images", "pag13.png");

    if (fs.existsSync(pag13Path)) {
        slide.addImage({
            path: pag13Path,
            x: 7.9,
            y: 1.6,
            w: 4.2,
            h: 3.4
        });
    } else {
        console.log("No existe imagen:", pag13Path);
    }

    addFooter(slide, page);
}

function addGraciasSlide(page) {
    const slide = pptx.addSlide();

    const graciasPath = path.join(process.cwd(), "images", "gracias.png");

    if (fs.existsSync(graciasPath)) {
        slide.addImage({
            path: graciasPath,
            x: 0,
            y: 0,
            w: SLIDE_W,
            h: SLIDE_H
        });
    }

    slide.addText("Muchas gracias ¡¡", {
        x: 0.55,
        y: 1.65,
        w: 4.2,
        h: 0.5,
        fontSize: 24,
        color: COLORS.WHITE,
        fontFace: FONT,
        bold: false,
        align: "left",
        valign: "mid"
    });
}

async function main() {
    let page = 1;

    addPortadaSlide(page++);
    addObjetivoSlide(page++);
    addResumenMesSlide(page++);

    addIncidenciasAreaSlide(data.incidenciasPorArea, page++);
    addTiposIncidentesSlide(data.tiposIncidentes, page++);
    addUsuariosIncidenciasSlide(data.usuariosIncidentes, page++);
    addRequerimientosAreaSlide(data.requerimientosPorArea, page++);
    addTiposRequerimientosSlide(data.tiposRequerimientos, page++);
    addUsuariosRequerimientosSlide(data.usuariosRequerimientos, page++);
    addAccionesRecomendacionesSlide(page++);
    addAccionesRecomendaciones2Slide(page++);
    addConclusionesSlide(page++);
    addMejoraContinuaSlide(page++);
    addGraciasSlide(page++);

    await pptx.writeFile({ fileName: output });
}

main().catch(error => {
    console.error("Error generando PPT:", error);
    process.exit(1);
});