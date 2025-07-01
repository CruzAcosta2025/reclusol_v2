<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\JpegEncoder;

class PosterController extends Controller
{
    public function index()
    {
        $requerimientos = Requerimiento::orderByDesc('created_at')->get();
        return view('afiches.afiche', compact('requerimientos'));
    }

    public function show(Request $request, Requerimiento $req, string $template)
    {
        // Recoger íconos y fuente (con valores por defecto)
        $iconGPath     = $request->string('iconG',    'assets/images/guardia.png');          // Ícono grande principal
        $iconCheckPath = $request->string('iconCheck', 'assets/icons/icon_check1.png');        // Ícono de check (requisitos)
        $iconPhonePath = $request->string('iconPhone', 'assets/icons/icon_phone1.png');        // Ícono de teléfono (footer)
        $iconEmailPath  = $request->string('iconEmail', 'assets/icons/icon_email1.png');        // Ícono de email (footer)
        $fontPath      = $request->string('font',     'fonts/OpenSans-Regular.ttf');          // Fuente

        // Validar existencia de archivos (evita errores 500)
        $iconGFull     = public_path($iconGPath);
        abort_unless(file_exists($iconGFull), 500, "Ícono principal no encontrado: {$iconGPath}");

        $iconCheckFull = public_path($iconCheckPath);
        abort_unless(file_exists($iconCheckFull), 500, "Ícono de check no encontrado: {$iconCheckPath}");

        $iconPhoneFull = public_path($iconPhonePath);
        abort_unless(file_exists($iconPhoneFull), 500, "Ícono de teléfono no encontrado: {$iconPhonePath}");

        $iconMailFull  = public_path($iconEmailPath);
        abort_unless(file_exists($iconMailFull), 500, "Ícono de email no encontrado: {$iconEmailPath}");

        $fontFull      = public_path($fontPath);
        abort_unless(file_exists($fontFull), 500, "Fuente no encontrada: {$fontPath}");

        // Cargar la plantilla
        $tpl = public_path("assets/plantillas/{$template}.png");
        abort_unless(file_exists($tpl), 404, "Plantilla {$template} no encontrada");
        $bg = Image::read($tpl); // lienzo base 1080×1080

        // Logo opcional
        if ($request->boolean('logo', true)) {
            $logoFile = public_path('assets/solmar_logo2.png');
            if (file_exists($logoFile)) {
                $logo = Image::read($logoFile)->resize(width: 180);
                $bg->place($logo, 'top-left', 90, 210);
            }
        }

        // Cabeceras y bloques principales
        $bg->place(
            Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(1080, 100),
            'top-left',
            0,
            0
        );
        $bg->text(
            'OPORTUNIDAD LABORAL',
            540,
            50,
            fn($f) => $this->font($f, $fontFull, 48, '#FDFEFE')
        );

        $bg->place(
            Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(400, 60),
            'top-left',
            0,
            140
        );
        $bg->text(
            "{$req->cliente} – {$req->sucursal}",
            200,
            170,
            fn($f) => $this->font($f, $fontFull, 36, '#FFFFFF')
        );

        $bg->place(
            Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(480, 80),
            'top-left',
            300,
            225
        );
        $bg->text(
            'SOLMAR SECURITY',
            540,
            265,
            fn($f) => $this->font($f, $fontFull, 48, '#FDFEFE')
        );

        // Cargo
        $bg->text(
            "REQUIERE {$req->cargo_solicitado}",
            600,
            350,
            fn($f) => $this->font($f, $fontFull, 38, '#ccd1d1')
        );

        // Imagen principal de icono (guardia, supervisor, etc)
        $iconImage = Image::read($iconGFull)->resize(300, 300);
        $bg->place($iconImage, 'top-left', 30, 390);

        // Título requisitos
        $bg->text(
            'Requisitos:',
            520,
            400,
            fn($f) => $this->font($f, $fontFull, 35, '#f4d03f')
        );

        // Dibujar requisitos, pasando el ícono de check y la fuente seleccionada
        $this->dibujarRequisitos($bg, $req, $fontFull, $iconCheckFull);

        // Pie de contacto con íconos de teléfono y email
        $this->dibujarFooter($bg, $fontFull, $iconPhoneFull, $iconMailFull);

        // Formato de salida (png, jpg, pdf)
        $format = strtolower($request->string('format', 'png'));
        $format = in_array($format, ['png', 'jpg', 'pdf']) ? $format : 'png';

        if ($format !== 'pdf') {
            [$encoder, $mime, $ext] = $format === 'jpg'
                ? [new JpegEncoder(quality: 90), 'image/jpeg', 'jpg']
                : [new PngEncoder(),             'image/png',  'png'];

            $binary = $bg->encode($encoder);

            if ($request->boolean('preview')) {
                return Response::make($binary, 200, ['Content-Type' => $mime]);
            }

            return Response::make(
                $binary,
                200,
                [
                    'Content-Type'        => $mime,
                    'Content-Disposition' => "attachment; filename=\"poster_{$req->id}.{$ext}\"",
                ]
            );
        }

        // PDF (se arma como PNG embebido en un HTML)
        $pngBase64 = base64_encode((string) $bg->encode(new PngEncoder()));
        $html = <<<HTML
            <html><body style="margin:0;padding:0;">
                <img src="data:image/png;base64,{$pngBase64}" style="width:100%;height:auto;">
            </body></html>
        HTML;

        $pdf  = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        $binary = $pdf->output();

        return Response::make(
            $binary,
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"poster_{$req->id}.pdf\"",
            ]
        );
    }

    // Helper para formato de texto
    private function font($f, string $path, int $size, string $color): void
    {
        $f->file($path)
            ->size($size)
            ->color($color)
            ->align('center')
            ->valign('middle');
    }

    // Dibuja la lista de requisitos usando el icono de check y la fuente elegida
    private function dibujarRequisitos($bg, Requerimiento $req, string $fontFull, string $iconCheckFull): void
    {
        $lines = [
            "Estudios mínimos: {$req->nivel_estudios}",
            "Vacantes: {$req->cantidad_requerida}",
            "Fecha límite: " . ($req->fecha_limite?->format('d/m/Y') ?? 'No definida'),
            "Edad: {$req->edad_minima} - {$req->edad_maxima}",
            "Extras: {$req->requisitos_adicionales}",
        ];

        $icon = Image::read($iconCheckFull)->resize(32, 32); // Ícono de check
        $y = 450;
        $iconX = 420;
        $textX = 480;
        $lineHeight = 50;

        foreach ($lines as $text) {
            $bg->place($icon, 'top-left', $iconX, $y);
            $bg->text($text, $textX, $y + 5, function ($f) use ($fontFull) {
                $f->file($fontFull)
                    ->size(30)
                    ->color('#F7F9F9')
                    ->align('left')
                    ->valign('top');
            });
            $y += $lineHeight;
        }

        // Nota final
        $bg->text(
            'NOTA: SERVICIO NO ACUARTELADO',
            540,
            $y + 100,
            fn($f) => $this->font($f, $fontFull, 28, '#F7F9F9')
        );
    }

    // Pie de página con contacto (ahora recibe íconos de teléfono y mail)
    private function dibujarFooter($bg, string $fontFull, string $iconPhoneFull, string $iconEmailFull): void
    {
        $footer = Image::read(public_path('assets/rectangles/text-bg-blue.png'))
            ->resize(1080, 180);
        $bg->place($footer, 'bottom', 0);

        $phone = Image::read($iconPhoneFull)->resize(30, 30);
        $mail  = Image::read($iconEmailFull)->resize(30, 30);

        $bg->place($phone, 'top-left', 410, 985);
        $bg->place($mail,  'top-left', 280, 1028);

        $bg->text(
            'Interesados comunicarse a:',
            520,
            950,
            fn($f) => $this->font($f, $fontFull, 30, '#F7F9F9')
        );
        $bg->text(
            '946343555',
            520,
            1000,
            fn($f) => $this->font($f, $fontFull, 26, '#F7F9F9')
        );
        $bg->text(
            'informes@gruposolmar.com.pe',
            520,
            1040,
            fn($f) => $this->font($f, $fontFull, 26, '#F7F9F9')
        );
    }
}
