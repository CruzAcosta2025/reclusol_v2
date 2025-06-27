<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;   // <-- facade correcto v3
use Intervention\Image\Geometry\Factories\RectangleFactory; // solo si quieres tip-hint


class AficheController extends Controller
{
    //public function mostrar () {
      //  return view('afiches.afiche');
   // }
    public function generar(Request $request)
    {
        // --- 0) Datos dinámicos --------------------------------------------
        $puesto = $request->get('puesto', 'Secretaria');
        $sede   = $request->get('sede',   'Arequipa');
        $fecha  = $request->get('fecha',  '2025-07-10');

        // --- 1) Fondo y logo -----------------------------------------------
        $afiche = Image::read(public_path('assets/fondo11.png'));
        $logo   = Image::read(public_path('assets/logo.png'))->resize(width: 180);
        $afiche->place($logo, 'top-left', 40, 40);

        // --- 2) Overlay semitransparente detrás del texto ------------------
        $afiche->drawRectangle(
    230,       // x  ↖︎ esquina superior-izquierda
    160,       // y
    function (RectangleFactory $rectangle) {
        $rectangle->size(720, 260);          // ancho, alto
        $rectangle->background('#00000080'); // negro alfa ≈50 %
        // $rectangle->border('#ffffff', 2); // (opcional) borde blanco 2 px
    }
);

        // --- 3) Texto: coordenadas base ------------------------------------
        $x = 260;        // un poco a la derecha del overlay
        $y = 200;
        $line = 80;      // salto entre líneas

        // Puesto
        $afiche->text("Puesto: $puesto", $x, $y, function ($font) {
            $font->size(56)->color('#ffffff')
                 ->filename(public_path('fonts/OpenSans-Bold.ttf'));
        });

        // Sede
        $afiche->text("Sede: $sede", $x, $y + $line, function ($font) {
            $font->size(48)->color('#ffffff')
                 ->filename(public_path('fonts/OpenSans-Regular.ttf'));
        });

        // Fecha límite
        $afiche->text("Fecha límite: $fecha", $x, $y + 2 * $line, function ($font) {
            $font->size(48)->color('#ffd100')
                 ->filename(public_path('fonts/OpenSans-SemiBold.ttf'));
        });

        // --- 4) Entregar PNG al navegador ----------------------------------
        return response((string) $afiche->toPng(), 200)
               ->header('Content-Type', 'image/png');
    }
}

