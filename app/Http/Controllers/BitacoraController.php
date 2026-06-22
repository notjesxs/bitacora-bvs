<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        $usuarios = User::orderBy('name')->get();

        $meses = Bitacora::selectRaw("TO_CHAR(fecha_registro, 'YYYY-MM') as mes")
            ->distinct()
            ->orderBy('mes', 'desc')
            ->pluck('mes');

        return view('bitacoras.index', compact('bitacoras', 'usuarios', 'meses'));

    }

    public function create()
    {
        return view('bitacoras.create');
    }

    public function store(Request $request)
    {
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

        $data['user_id'] = Auth::id();

        Bitacora::create($data);

        return redirect()->route('bitacoras.index')
            ->with('success', 'Bitácora registrada correctamente.');
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

        $bitacora->update($data);

        return redirect()->route('bitacoras.index')
            ->with('success', 'Bitácora actualizada correctamente.');
    }

    public function destroy(Bitacora $bitacora)
    {
        abort_if(
            auth()->id() !== $bitacora->user_id,
            403
        );

        $bitacora->delete();

        return redirect()->route('bitacoras.index')
            ->with('success', 'Bitácora eliminada correctamente.');
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'mes' => 'required'
        ]);

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
        $sheet->setCellValue('A2', 'Mes: ' . $request->mes . ' | Generado: ' . now()->format('d/m/Y H:i'));

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

        $filename = 'bitacora-' . $request->mes . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}