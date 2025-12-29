<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Services\PosterService;

class PosterController extends Controller
{
    protected PosterService $posterService;

    public function __construct(PosterService $posterService)
    {
        $this->posterService = $posterService;
    }

    public function index()
    {
        $requerimientos = $this->posterService->obtenerRequerimientosConRelaciones();
        $recursos = $this->posterService->obtenerRecursos();

        return view('afiches.afiche', array_merge(
            ['requerimientos' => $requerimientos],
            $recursos
        ));
    }

    public function assetsForm()
    {
        $recursos = $this->posterService->obtenerRecursosEscaneados();

        return view('afiches.recursos', $recursos);
    }

    public function assetsUpload(Request $request)
    {
        $request->validate([
            'tipo'    => 'required|in:plantilla,iconG,iconCheck,iconPhone,iconEmail,font',
            'archivo' => 'required|file|max:4096',
        ]);

        $resultado = $this->posterService->subirAsset(
            $request->input('tipo'),
            $request->file('archivo')
        );

        return redirect()
            ->back()
            ->with('success', "Archivo subido correctamente a {$resultado['relativePath']}/{$resultado['filename']}");
    }

    public function assetsDelete(Request $request)
    {
        $request->validate([
            'tipo'     => 'required|in:plantilla,iconG,iconCheck,iconPhone,iconEmail,font',
            'filename' => 'required|string',
        ]);

        $eliminado = $this->posterService->eliminarAsset(
            $request->input('tipo'),
            $request->input('filename')
        );

        if ($eliminado) {
            return back()->with('success', "Archivo eliminado: {$request->input('filename')}");
        }

        return back()->with('error', 'El archivo ya no existe en el servidor.');
    }

    public function show(Request $request, Requerimiento $req, string $template)
    {
        // Si no llega template, usar 'modern' como fallback para evitar 404
        $template = trim($template) !== '' ? $template : 'modern';

        // Preparar recursos desde el request
        $recursos = [
            'iconG'     => $request->input('iconG'),
            'iconCheck' => $request->input('iconCheck'),
            'iconPhone' => $request->input('iconPhone'),
            'iconEmail' => $request->input('iconEmail'),
            'font'      => $request->input('font'),
        ];

        $includeLogo = $request->boolean('logo', true);

        // Generar el poster
        $bg = $this->posterService->generarPoster($req, $template, $recursos, $includeLogo);

        // Determinar formato de salida
        $format = strtolower($request->string('format', 'png'));
        $format = in_array($format, ['png', 'jpg', 'pdf']) ? $format : 'png';

        // Formato PNG o JPG
        if ($format !== 'pdf') {
            $resultado = $this->posterService->codificarImagen($bg, $format);

            if ($request->boolean('preview')) {
                return Response::make($resultado['binary'], 200, [
                    'Content-Type' => $resultado['mime']
                ]);
            }

            return Response::make($resultado['binary'], 200, [
                'Content-Type'        => $resultado['mime'],
                'Content-Disposition' => "attachment; filename=\"poster_{$req->id}.{$resultado['ext']}\"",
            ]);
        }

        $pngBase64 = $this->posterService->imagenABase64($bg);
        $html = <<<HTML
            <html><body style="margin:0;padding:0;">
                <img src="data:image/png;base64,{$pngBase64}" style="width:100%;height:auto;">
            </body></html>
        HTML;

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        $binary = $pdf->output();

        return Response::make($binary, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"poster_{$req->id}.pdf\"",
        ]);
    }
}    