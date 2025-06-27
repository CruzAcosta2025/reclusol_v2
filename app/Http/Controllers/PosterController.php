<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use  \Illuminate\Support\Carbon;

class PosterController extends Controller
{
    public function index()
    {
        $requerimientos = Requerimiento::orderByDesc('created_at')->get();
        return view('afiches.afiche', compact('requerimientos'));
    }

    public function show(Request $request, Requerimiento $req, string $template)
    {
        logger("Template recibido: '{$template}'");

        // 1. Validar que la plantilla exista
        $templatePath = public_path("assets/plantillas/{$template}.png");
        if (!file_exists($templatePath)) {
            abort(404, "Plantilla no encontrada: {$template}.png");
        }

        // 2. Leer fondo
        $bg = Image::read($templatePath);

        // 3. Logo (opcional)
        if ($request->boolean('logo', true)) {
            $logoPath = public_path('assets/solmar_logo2.png');
            if (file_exists($logoPath)) {
                $logo = Image::read($logoPath)->resize(width: 180);
                $bg->place($logo, 'top-left', 90, 210);
            }
        }

        $fontPath = public_path('fonts/OpenSans-Bold.ttf');

        $bgRect = Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(1080, 100);
        $bg->place($bgRect, 140); // centrado arriba

        // Cabecera principal
        $bg->text("OPORTUNIDAD LABORAL", 540, 50, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(48);
            $font->color('#fdfefe');
            $font->align('center');
            $font->valign('middle');
        });

        $clienteBg = Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(400, 60);
        $bg->place($clienteBg, 'top-left', 0, 120); // 110 porque el texto está en 150, y queremos centrarlo verticalmente


        $bg->text("{$req->cliente} - {$req->sucursal}", 200, 150, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(36);
            $font->color('#FFFFFF');
            $font->align('center');
            $font->valign('middle');
        });

        $solmarBg = Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(480, 80);
        $bg->place($solmarBg, 'top-left', 300, 200); // 110 porque el texto está en 150, y queremos centrarlo verticalmente

        $bg->text("SOLMAR SECURITY", 540, 240, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(48);
            $font->color('#fdfefe');
            $font->align('center');
            $font->valign('middle');
        });


        $bg->text("SE REQUIERE {$req->cargo_solicitado}", 585, 330, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(38);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        $bg->text("Requisitos", 470, 400, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(35);
            $font->color('#fdfefe');
            $font->align('center');
            $font->valign('middle');
        });

        $yStart = 450;
        $lineHeight = 52;

        $requisitos = [
            "Estudios mínimos: {$req->nivel_estudios}",
            "Vacantes: {$req->cantidad_requerida}",
            "Fecha límite: " . ($req->fecha_limite ? $req->fecha_limite->format('d/m/Y') : 'No definida'),
            "Edad: {$req->edad_minima} - {$req->edad_maxima}",
            "Extras: {$req->requisitos_adicionales}",
        ];

        $checkIcon = Image::read(public_path('assets/icons/check.png'))->resize(32, 32);

        foreach ($requisitos as $i => $linea) {
            $y = $yStart + ($i * $lineHeight);

            // Coloca el ícono
            $bg->place($checkIcon, 'top-left', 380, $y);

            // Coloca el texto
            $bg->text($linea, 420, $y + 27, function ($font) use ($fontPath) {
                $font->file($fontPath);
                $font->size(30);
                $font->color('#f7f9f9');
                $font->align('left');
            });
        }

        $bg->text("NOTA: SERVICIO NO ACUARTELADO", 540, $yStart + (count($requisitos) * $lineHeight) + 100, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(28);
            $font->color('#f7f9f9');
            $font->align('center');
        });

        

        $tallRect = Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(1080, 180);
        $bg->place($tallRect, 'bottom', 0); 

        // Icono de teléfono
        $phoneIcon = Image::read(public_path('assets/icons/phone.png'))->resize(30, 30);
        $bg->place($phoneIcon, 'top-left', 410, 975); // ajusta la posición a tu plantilla

        // Icono de email
        $emailIcon = Image::read(public_path('assets/icons/email.png'))->resize(30, 30);
        $bg->place($emailIcon, 'top-left', 280, 1010);

        $bg->text("Interesados comunicarse a:", 520, 950, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(30);
            $font->color('#f7f9f9');
            $font->align('center');
        });

        // Texto de contacto
        $bg->text("946343555", 520, 1000, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(26);
            $font->color('#f7f9f9');
            $font->align('center');
        });

        $bg->text("informes@gruposolmar.com.pe", 520, 1030, function ($font) use ($fontPath) {
            $font->file($fontPath);
            $font->size(26);
            $font->color('#f7f9f9');
            $font->align('center');
        });

        // 6. Generar PNG
        $png = (string) $bg->toPng();

        // 7. Mostrar preview o descargar
        if ($request->boolean('preview')) {
            return response($png, 200)->header('Content-Type', 'image/png');
        }

        $filename = 'poster_' . $req->id . '.png';
        return response($png, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }
}
