<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Process\Process;
use Spatie\Browsershot\Browsershot;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $query = Bitacora::with('encargado');

        if ($request->filled('encargado_id')) {
            $query->where('user_id', $request->encargado_id);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_registro', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_registro', '<=', $request->fecha_fin);
        }

        $bitacoras = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $usuarios = User::where('id', '!=', 8)
        ->orderBy('name')
        ->get();

        $meses = Bitacora::selectRaw("TO_CHAR(fecha_registro, 'YYYY-MM') as mes")
            ->distinct()
            ->orderBy('mes', 'desc')
            ->pluck('mes');

        return view('bitacoras.index', compact('bitacoras', 'usuarios', 'meses'));

    }

    public function create()
    {
        abort_if(auth()->id() == 8, 403);
        return view('bitacoras.create');
    }

    public function store(Request $request)
    {
        abort_if(auth()->id() == 8, 403);
        
        $data = $request->validate([
            'tipo_caso' => 'required|string|max:255',
            'proceso' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
            'solucion' => 'nullable|string',
            'fecha_registro' => 'required|date',
            'tiempo_resolucion' => 'nullable|integer',
            'estado' => 'required|string',
            'area' => 'nullable|string|max:255',
            'personal' => 'nullable|string|max:255',
        ]);

        $data['personal'] = !empty($data['personal'])
            ? mb_convert_case(mb_strtolower(trim($data['personal']), 'UTF-8'), MB_CASE_TITLE, 'UTF-8')
            : null;

        $data['user_id'] = Auth::id();

        Bitacora::create($data);
        return redirect()->route('bitacoras.index')
            ->with('success', 'Caso registrado correctamente.');
    }

    public function edit(Bitacora $bitacora)
    {
        abort_if(
            auth()->id() !== $bitacora->user_id,
            403
        );

        return view('bitacoras.edit', compact('bitacora'));
    }

    public function update(Request $request, Bitacora $bitacora)
    {

        abort_if(
            auth()->id() !== $bitacora->user_id,
            403
        );

        $data = $request->validate([
            'tipo_caso' => 'required|string|max:255',
            'proceso' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|string',
            'solucion' => 'nullable|string',
            'fecha_registro' => 'nullable|date',
            'tiempo_resolucion' => 'nullable|integer',
            'estado' => 'required|string',
            'area' => 'nullable|string|max:255',
            'personal' => 'nullable|string|max:255',
        ]);

        if ($request->estado === 'CERRADO' || $request->estado === 'RESUELTO') {
            $data['fecha_cierre'] = now();
        }

        $data['personal'] = !empty($data['personal'])
        ? mb_convert_case(mb_strtolower(trim($data['personal']), 'UTF-8'), MB_CASE_TITLE, 'UTF-8')
        : null;

        $bitacora->update($data);

        return redirect()->route('bitacoras.index')
            ->with('success', 'Caso actualizado correctamente.');
    }

    public function destroy(Bitacora $bitacora)
    {
        abort_if(
            auth()->id() !== $bitacora->user_id,
            403
        );

        $bitacora->delete();

        return redirect()->route('bitacoras.index')
            ->with('success', 'Caso eliminado correctamente.');
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'mes' => 'required'
        ]);

        $mes = $request->mes;
        $nombreMes = $this->nombreMes($mes);

        $bitacoras = Bitacora::with('encargado')
            ->whereRaw("TO_CHAR(fecha_registro, 'YYYY-MM') = ?", [$request->mes])
            ->orderBy('fecha_registro', 'desc')
            ->get();

        if ($bitacoras->isEmpty()) {
            return redirect()
                ->route('bitacoras.index')
                ->with('error', 'No existen casos registrados para el mes seleccionado.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Bitácora');

        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'REPORTE DE BITÁCORA BVS');

        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue(
            'A2',
            'Mes: ' . $request->mes . ' | Generado: ' . now()->timezone('America/Lima')->format('d/m/Y H:i')
        );

        $headers = [
            'TIPO CASO',
            'PROCESO',
            'DESCRIPCIÓN',
            'PRIORIDAD',
            'SOLUCIÓN',
            'ENCARGADO',
            'FECHA REGISTRO',
            'TIEMPO DE SOLUCIÓN',
            'ESTADO',
            'ÁREA',
            'PERSONAL'
        ];

        $sheet->fromArray($headers, null, 'A4');

        $row = 5;

        foreach ($bitacoras as $b) {
            $sheet->fromArray([
                $b->tipo_caso,
                $b->proceso,
                $b->descripcion,
                $b->prioridad,
                $b->solucion,
                $b->encargado?->name,
                $b->fecha_registro?->format('d/m/Y'),
                $b->tiempo_resolucion,
                $b->estado,
                $b->area,
                $b->personal,
            ], null, 'A' . $row);

            if ($b->prioridad === 'ALTA' || $b->prioridad === 'CRITICA') {
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '991B1B']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2']
                    ],
                ]);
            }

            if ($b->prioridad === 'MEDIA') {
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '1D4ED8']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DBEAFE']
                    ],
                ]);
            }

            if ($b->prioridad === 'BAJA') {
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '166534']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DCFCE7']
                    ],
                ]);
            }

            if ($b->estado === 'RESUELTO' || $b->estado === 'CERRADO') {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '166534']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DCFCE7']
                    ],
                ]);
            }

            if ($b->estado === 'PENDIENTE') {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '991B1B']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2']
                    ],
                ]);
            }

            if ($b->estado === 'EN_PROCESO') {
                $sheet->getStyle("I{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '92400E']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7']
                    ],
                ]);
            }

            $row++;
        }

        $lastRow = $row - 1;

        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ]);

        $sheet->getStyle('A2:K2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => '475569']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
        ]);

        $sheet->getStyle('A4:K4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1D4ED8']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
        ]);

        $sheet->getStyle("A4:K{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1']
                ]
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
        ]);

        $sheet->getStyle("A5:K{$lastRow}")->applyFromArray([
            'font' => [
                'size' => 10,
                'color' => ['rgb' => '0F172A']
            ],
        ]);

        $sheet->getStyle("A5:A{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle("B5:B{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle("C5:C{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("D5:D{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle("E5:E{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle("F5:F{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("G5:I{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle("J5:J{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle("K5:K{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(4)->setRowHeight(24);

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(16);
        $sheet->getColumnDimension('E')->setWidth(50);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(16);
        $sheet->getColumnDimension('J')->setWidth(22);
        $sheet->getColumnDimension('K')->setWidth(25);

        $sheet->freezePane('A5');
        $sheet->setAutoFilter("A4:K{$lastRow}");

        $filename = 'BITACORA ' . $nombreMes . ' - INFORME.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function generarPpt(Request $request)
    {
        $request->validate([
            'mes' => 'required'
        ]);

        $mes = $request->mes;
        $nombreMes = $this->nombreMes($mes);

        $base = Bitacora::whereRaw("TO_CHAR(fecha_registro, 'YYYY-MM') = ?", [$mes]);

        $incidentes = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['incid%'])
            ->count();

        $requerimientos = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['requer%'])
            ->count();
        
        $horasIncidentes = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['incid%'])
            ->sum('tiempo_resolucion');

        $horasRequerimientos = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['requer%'])
            ->sum('tiempo_resolucion');

        $promedioIncidentes = $incidentes > 0
            ? round($horasIncidentes / $incidentes, 1)
            : 0;

        $promedioRequerimientos = $requerimientos > 0
            ? round($horasRequerimientos / $requerimientos, 1)
            : 0;

        if ($incidentes == 0 && $requerimientos == 0) {
            return redirect()
                ->route('bitacoras.index')
                ->with('error', 'No existen casos para el mes seleccionado.');
        }

        $data = [
            'mes' => $mes,
            'nombreMes' => $nombreMes,
            'incidentes' => $incidentes,
            'requerimientos' => $requerimientos,

            'incidenciasPorArea' => $this->formatPptData(
                $this->agruparCasos($mes, 'incid%', 'area')
            ),

            'tiposIncidentes' => $this->formatPptData(
                $this->agruparCasos($mes, 'incid%', 'proceso')
            ),

            'usuariosIncidentes' => $this->formatPptData(
                $this->agruparCasos($mes, 'incid%', 'personal', 10)
            ),

            'requerimientosPorArea' => $this->formatPptData(
                $this->agruparCasos($mes, 'requer%', 'area')
            ),

            'tiposRequerimientos' => $this->formatPptData(
                $this->agruparCasos($mes, 'requer%', 'proceso')
            ),

            'usuariosRequerimientos' => $this->formatPptData(
                $this->agruparCasos($mes, 'requer%', 'personal', 12)
            ),
            'totalHorasAtencionIncidentes' => $horasIncidentes,
            'promedioHorasAtencionIncidentes' => $promedioIncidentes,

            'totalHorasAtencionRequerimientos' => $horasRequerimientos,
            'promedioHorasAtencionRequerimientos' => $promedioRequerimientos,
        ];

        $jsonPath = storage_path('app/reporte_soporte_' . uniqid() . '.json');
        $pptPath = storage_path('app/Informe-Soporte-' . $mes . '.pptx');

        file_put_contents($jsonPath, json_encode($data, JSON_UNESCAPED_UNICODE));

        $process = new Process([
            'node',
            base_path('resources/js/generar-reporte-ppt.cjs'),
            $jsonPath,
            $pptPath
        ]);

        $process->setTimeout(120);
        $process->run();

        @unlink($jsonPath);

        if (!$process->isSuccessful()) {
            return redirect()
                ->route('bitacoras.index')
                ->with('error', 'Error generando PPT: ' . $process->getErrorOutput());
        }

        return response()
            ->download($pptPath, 'FOR-SER-12_INFORME SOPORTE SOFTWARE -V.1.0 ' . $nombreMes . '.pptx')
            ->deleteFileAfterSend(true);
    }

    private function formatPptData($collection)
    {
        return $collection->map(function ($total, $label) {
            return [
                'label' => (string) $label,
                'total' => (int) $total,
            ];
        })->values()->toArray();
    }

    private function nombreMes($mes)
    {
        [$anio, $numeroMes] = explode('-', $mes);

        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        return ($meses[$numeroMes] ?? $numeroMes) . ' ' . $anio;
    }

    private function agruparCasos($mes, $tipo, $campo, $limit = null)
    {
        $query = Bitacora::whereRaw("TO_CHAR(fecha_registro, 'YYYY-MM') = ?", [$mes])
            ->whereRaw("LOWER(tipo_caso) LIKE ?", [$tipo])
            ->selectRaw("COALESCE(NULLIF(TRIM({$campo}), ''), 'SIN DATO') as label, COUNT(*) as total")
            ->groupByRaw("COALESCE(NULLIF(TRIM({$campo}), ''), 'SIN DATO')")
            ->orderByDesc('total');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->pluck('total', 'label');
    }

    public function generarPdf(Request $request)
    {
        $request->validate([
            'mes' => 'required'
        ]);

        $mes = $request->mes;
        $nombreMes = $this->nombreMes($mes);

        $base = Bitacora::whereRaw("TO_CHAR(fecha_registro, 'YYYY-MM') = ?", [$mes]);

        $incidentes = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['incid%'])
            ->count();

        $requerimientos = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['requer%'])
            ->count();

        if ($incidentes == 0 && $requerimientos == 0) {
            return redirect()
                ->route('bitacoras.index')
                ->with('error', 'No existen casos para el mes seleccionado.');
        }

        $horasIncidentes = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['incid%'])
            ->sum('tiempo_resolucion');

        $horasRequerimientos = (clone $base)
            ->whereRaw("LOWER(tipo_caso) LIKE ?", ['requer%'])
            ->sum('tiempo_resolucion');

        $incidenciasPorAreaRaw = $this->agruparCasos($mes, 'incid%', 'area');
        $requerimientosPorAreaRaw = $this->agruparCasos($mes, 'requer%', 'area');

        $incidenciasPorArea = $this->formatPptData($incidenciasPorAreaRaw);
        $requerimientosPorArea = $this->formatPptData($requerimientosPorAreaRaw);

        $areaTopIncidencia = $incidenciasPorAreaRaw->keys()->first() ?? 'SIN DATO';
        $cantidadTopIncidencia = $incidenciasPorAreaRaw->first() ?? 0;

        $porcentajeAreaTopIncidencia = $incidentes > 0
            ? round(($cantidadTopIncidencia / $incidentes) * 100, 1)
            : 0;

        $promedioHorasIncidencia = $incidentes > 0
            ? round($horasIncidentes / $incidentes, 2)
            : 0;

        $tiposIncidentesRaw = $this->agruparCasos($mes, 'incid%', 'proceso');
        $tiposIncidentes = $this->formatPptData($tiposIncidentesRaw);

        $tipoTopIncidencia = $tiposIncidentesRaw->keys()->first() ?? 'SIN DATO';
        $cantidadTipoTopIncidencia = $tiposIncidentesRaw->first() ?? 0;

        $porcentajeTipoTopIncidencia = $incidentes > 0
            ? round(($cantidadTipoTopIncidencia / $incidentes) * 100, 1)
            : 0;

        $usuariosIncidentesRaw = $this->agruparCasos($mes, 'incid%', 'personal', 10);
        $usuariosIncidentes = $this->formatPptData($usuariosIncidentesRaw);

        $usuarioTopIncidencia = $usuariosIncidentesRaw->keys()->first() ?? 'SIN DATO';
        $cantidadUsuarioTopIncidencia = $usuariosIncidentesRaw->first() ?? 0;

        $porcentajeUsuarioTopIncidencia = $incidentes > 0
            ? round(($cantidadUsuarioTopIncidencia / $incidentes) * 100, 1)
            : 0;

        $areaTopRequerimiento = $requerimientosPorAreaRaw->keys()->first() ?? 'SIN DATO';
        $cantidadAreaTopRequerimiento = $requerimientosPorAreaRaw->first() ?? 0;

        $porcentajeAreaTopRequerimiento = $requerimientos > 0
            ? round(($cantidadAreaTopRequerimiento / $requerimientos) * 100, 1)
            : 0;

        $promedioHorasRequerimiento = $requerimientos > 0
            ? round($horasRequerimientos / $requerimientos, 2)
            : 0;

        $tiposRequerimientosRaw = $this->agruparCasos($mes, 'requer%', 'proceso');
        $tiposRequerimientos = $this->formatPptData($tiposRequerimientosRaw);

        $tipoTopRequerimiento = $tiposRequerimientosRaw->keys()->first() ?? 'SIN DATO';
        $cantidadTipoTopRequerimiento = $tiposRequerimientosRaw->first() ?? 0;

        $porcentajeTipoTopRequerimiento = $requerimientos > 0
            ? round(($cantidadTipoTopRequerimiento / $requerimientos) * 100, 1)
            : 0;
        $usuariosRequerimientosRaw = $this->agruparCasos($mes, 'requer%', 'personal', 15);
        $usuariosRequerimientos = $this->formatPptData($usuariosRequerimientosRaw);

        $usuarioTopRequerimiento = $usuariosRequerimientosRaw->keys()->first() ?? 'SIN DATO';
        $cantidadUsuarioTopRequerimiento = $usuariosRequerimientosRaw->first() ?? 0;

        $porcentajeUsuarioTopRequerimiento = $requerimientos > 0
            ? round(($cantidadUsuarioTopRequerimiento / $requerimientos) * 100, 1)
            : 0;

        $data = [
            'mes' => $mes,
            'nombreMes' => $nombreMes,

            'incidentes' => $incidentes,
            'requerimientos' => $requerimientos,
            'total' => $incidentes + $requerimientos,

            'horasIncidentes' => round($horasIncidentes, 2),
            'horasRequerimientos' => round($horasRequerimientos, 2),

            'incidenciasPorArea' => $incidenciasPorArea,
            'requerimientosPorArea' => $requerimientosPorArea,

            'areasIncidenciaLabels' => collect($incidenciasPorArea)->pluck('label')->toArray(),
            'areasIncidenciaData' => collect($incidenciasPorArea)->pluck('total')->toArray(),

            'totalIncidencias' => $incidentes,
            'totalHorasIncidencia' => round($horasIncidentes, 2),
            'promedioHorasIncidencia' => $promedioHorasIncidencia,

            'areaTopIncidencia' => $areaTopIncidencia,
            'cantidadTopIncidencia' => $cantidadTopIncidencia,
            'porcentajeAreaTopIncidencia' => $porcentajeAreaTopIncidencia,

            'tiposIncidentesLabels' => collect($tiposIncidentes)->pluck('label')->toArray(),
            'tiposIncidentesData' => collect($tiposIncidentes)->pluck('total')->toArray(),

            'tipoTopIncidencia' => $tipoTopIncidencia,
            'cantidadTipoTopIncidencia' => $cantidadTipoTopIncidencia,
            'porcentajeTipoTopIncidencia' => $porcentajeTipoTopIncidencia,

            'usuariosIncidentesLabels' => collect($usuariosIncidentes)->pluck('label')->toArray(),
            'usuariosIncidentesData' => collect($usuariosIncidentes)->pluck('total')->toArray(),

            'usuarioTopIncidencia' => $usuarioTopIncidencia,
            'totalUsuariosIncidencia' => count($usuariosIncidentes),
            'cantidadUsuarioTopIncidencia' => $cantidadUsuarioTopIncidencia,
            'porcentajeUsuarioTopIncidencia' => $porcentajeUsuarioTopIncidencia,

            'areasRequerimientoLabels' => collect($requerimientosPorArea)->pluck('label')->toArray(),
            'areasRequerimientoData' => collect($requerimientosPorArea)->pluck('total')->toArray(),

            'totalRequerimientos' => $requerimientos,
            'totalHorasRequerimiento' => round($horasRequerimientos, 2),
            'promedioHorasRequerimiento' => $promedioHorasRequerimiento,

            'areaTopRequerimiento' => $areaTopRequerimiento,
            'cantidadAreaTopRequerimiento' => $cantidadAreaTopRequerimiento,
            'porcentajeAreaTopRequerimiento' => $porcentajeAreaTopRequerimiento,

            'tiposRequerimientos' => $tiposRequerimientos,
            'tiposRequerimientoLabels' => collect($tiposRequerimientos)->pluck('label')->toArray(),
            'tiposRequerimientoData' => collect($tiposRequerimientos)->pluck('total')->toArray(),

            'tipoTopRequerimiento' => $tipoTopRequerimiento,
            'cantidadTipoTopRequerimiento' => $cantidadTipoTopRequerimiento,
            'porcentajeTipoTopRequerimiento' => $porcentajeTipoTopRequerimiento,

            'usuariosRequerimientos' => $usuariosRequerimientos,
            'usuariosRequerimientoLabels' => collect($usuariosRequerimientos)->pluck('label')->toArray(),
            'usuariosRequerimientoData' => collect($usuariosRequerimientos)->pluck('total')->toArray(),
            'totalUsuariosRequerimiento' => count($usuariosRequerimientos),
            'usuarioTopRequerimiento' => $usuarioTopRequerimiento,
            'cantidadUsuarioTopRequerimiento' => $cantidadUsuarioTopRequerimiento,
            'porcentajeUsuarioTopRequerimiento' => $porcentajeUsuarioTopRequerimiento,
        ];

        $html = view('reportes.soporte-pdf', $data)->render();

        $pdfPath = storage_path('app/Informe-Soporte-' . $mes . '.pdf');

        Browsershot::html($html)
            ->setChromePath(config('services.browser.path')) // Ajusta la ruta según tu sistema operativo
            ->noSandbox()
            ->paperSize(1600, 900, 'px')
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->savePdf($pdfPath);

        return response()
            ->download($pdfPath, 'FOR-SER-12_INFORME SOPORTE SOFTWARE -V.1.0 ' . $nombreMes . '.pdf')
            ->deleteFileAfterSend(true);
    }
    
}