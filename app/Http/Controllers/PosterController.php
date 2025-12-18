<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\Cargo;
use Illuminate\Support\Str;
use App\Models\Sucursal;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosterController extends Controller
{

    public function index()
    {
        // Traer requerimientos
        $requerimientos = Requerimiento::orderByDesc('created_at')->get();

        $cargos = Cargo::forSelect();
        $sucursales = Sucursal::forSelect();

        $departamentos = Departamento::forSelect();
        $provincias = Provincia::forSelect();
        $distritos = Distrito::forSelect();

        foreach ($requerimientos as $r) {
            $codigoCargo = str_pad($r->cargo_solicitado, 4, '0', STR_PAD_LEFT);
            $codigoSucursal = str_pad($r->sucursal, 2, '0', STR_PAD_LEFT);

            // $codigoDepartamento = ltrim((string)$r->departamento, '0');
            // $codigoProvincia = ltrim((string)$r->provincia, '0');
            //$codigoDistrito = ltrim((string)$r->distrito, '0');

            $r->cargo_nombre = $cargos->get($codigoCargo) ?? $r->cargo_solicitado;
            $r->sucursal_nombre = $sucursales->get($codigoSucursal) ?? $r->sucursal;
            //$r->departamento_nombre = $departamentos->get($codigoDepartamento)?->DEPA_DESCRIPCION ?? $r->departamento;
            //$r->provincia_nombre = $provincias->get($codigoProvincia)?->PROVI_DESCRIPCION ?? $r->provincia;
            //$r->distrito_nombre = $distritos->get($codigoDistrito)?->DIST_DESCRIPCION ?? $r->distrito;
            // ================== RECURSOS DINÁMICOS ==================
            // OJO: revisa que estos paths coincidan con lo que usas en tu AssetsController
            $plantillas  = $this->collectAssets('assets/plantillas');          // fondos 1080x1080
            $iconosG     = $this->collectAssets('assets/icons/iconG');         // íconos principales
            $iconosCheck = $this->collectAssets('assets/icons/iconCheck');         // checks
            $iconosPhone = $this->collectAssets('assets/icons/iconPhone');         // teléfonos
            $iconosEmail = $this->collectAssets('assets/icons/iconEmail');         // emails
            $fonts       = $this->collectAssets('fonts', ['ttf', 'otf']);      // fuentes
        }
        return view('afiches.afiche', [
            'requerimientos' => $requerimientos,
            'plantillas'     => $plantillas,
            'iconosG'        => $iconosG,
            'iconosCheck'    => $iconosCheck,
            'iconosPhone'    => $iconosPhone,
            'iconosEmail'    => $iconosEmail,
            'fonts'          => $fonts,
        ]);
    }

    private function collectAssets(string $relativePath, array $exts = ['png', 'jpg', 'jpeg'])
    {
        $full = public_path($relativePath);

        if (!is_dir($full)) {
            return collect();
        }

        return collect(File::files($full))
            ->filter(function ($file) use ($exts) {
                return in_array(strtolower($file->getExtension()), $exts, true);
            })
            ->map(function ($file) use ($relativePath) {
                $filename = $file->getFilename();

                // Nombre bonito para mostrar en la UI
                $name = Str::title(
                    str_replace(
                        ['_', '-'],
                        ' ',
                        Str::beforeLast($filename, '.')
                    )
                );

                return (object) [
                    'filename' => $filename,
                    'name'     => $name,
                    'path'     => $relativePath . '/' . $filename,
                ];
            })
            ->values();
    }

    // Procesa el formulario y guarda el archivo en la carpeta correspondiente
    public function assetsUpload(Request $request)
    {
        $request->validate([
            'tipo'    => 'required|in:plantilla,iconG,iconCheck,iconPhone,iconEmail,font',
            'archivo' => 'required|file|max:4096', // 4 MB
        ]);

        $tipo = $request->input('tipo');
        $file = $request->file('archivo');

        // Carpeta destino según el tipo
        $relativePath = match ($tipo) {
            'plantilla'  => 'assets/plantillas',
            'iconG'      => 'assets/icons/iconG',
            'iconCheck'  => 'assets/icons/iconCheck',
            'iconPhone'  => 'assets/icons/iconPhone',
            'iconEmail'  => 'assets/icons/iconEmail',
            'font'       => 'fonts',
            default      => 'assets/otros',
        };

        $destPath = public_path($relativePath);

        if (!is_dir($destPath)) {
            mkdir($destPath, 0775, true);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = strtolower($file->getClientOriginalExtension()); // ← minúsculas

        $slugName = Str::slug($originalName, '-');
        if ($slugName === '') {
            $slugName = 'archivo';
        }

        $filename = $slugName . '.' . $extension;

        if (file_exists($destPath . DIRECTORY_SEPARATOR . $filename)) {
            $filename = $slugName . '-' . time() . '.' . $extension;
        }

        $fullPath = $destPath . DIRECTORY_SEPARATOR . $filename;

        // 👉 Solo PLANTILLAS a 1080x1080
        if ($tipo === 'plantilla') {
            $image = Image::read($file->getRealPath())
                ->cover(1080, 1080);   // recorta/ajusta a 1080x1080 sin deformar

            $image->save($fullPath);
        } else {
            // Íconos y fuentes se guardan tal cual
            $file->move($destPath, $filename);
        }

        return redirect()
            ->back()
            ->with('success', "Archivo subido correctamente a {$relativePath}/{$filename}");
    }


    private function scanImages(string $relativePath)
    {
        $fullPath = public_path($relativePath);

        if (!is_dir($fullPath)) {
            return collect();
        }

        return collect(File::files($fullPath))->map(function ($file) use ($relativePath) {
            $filename = $file->getFilename();                     // ej. modern.png
            $slug     = pathinfo($filename, PATHINFO_FILENAME);   // modern

            return (object) [
                'slug'     => $slug,
                'name'     => Str::headline($slug),               // "modern" -> "Modern"
                'path'     => $relativePath . '/' . $filename,    // assets/plantillas/modern.png
                'filename' => $filename,                          // solo el nombre del archivo
            ];
        });
    }

    public function assetsForm()
    {
        $plantillas   = $this->scanImages('assets/plantillas');
        $iconosG      = $this->scanImages('assets/icons/iconG');
        $iconosCheck  = $this->scanImages('assets/icons/iconCheck');
        $iconosPhone  = $this->scanImages('assets/icons/iconPhone');
        $iconosEmail  = $this->scanImages('assets/icons/iconEmail');
        $fonts        = $this->scanImages('fonts'); // aquí serán .ttf/.otf

        return view('afiches.recursos', compact(
            'plantillas',
            'iconosG',
            'iconosCheck',
            'iconosPhone',
            'iconosEmail',
            'fonts'
        ));
    }

    public function assetsDelete(Request $request)
    {
        $request->validate([
            'tipo'     => 'required|in:plantilla,iconG,iconCheck,iconPhone,iconEmail,font',
            'filename' => 'required|string',
        ]);

        $tipo = $request->input('tipo');

        $relativePath = match ($tipo) {
            'plantilla'  => 'assets/plantillas',
            'iconG'      => 'assets/icons/iconG',
            'iconCheck'  => 'assets/icons/iconCheck',
            'iconPhone'  => 'assets/icons/iconPhone',
            'iconEmail'  => 'assets/icons/iconEmail',
            'font'       => 'fonts',
            default      => null,
        };

        if (!$relativePath) {
            return back()->with('error', 'Tipo de recurso inválido.');
        }

        // Seguridad: nos quedamos solo con el nombre, sin rutas raras
        $filename = basename($request->input('filename'));
        $fullPath = public_path($relativePath . DIRECTORY_SEPARATOR . $filename);

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return back()->with('success', "Archivo eliminado: {$filename}");
        }

        return back()->with('error', 'El archivo ya no existe en el servidor.');
    }


    public function show(Request $request, Requerimiento $req, string $template)
    {

        // Cargar catálogos
        $cargos = Cargo::forSelect();
        $sucursales = Sucursal::forSelect();

        // Mapear nombres legibles
        $codigoCargo = str_pad($req->cargo_solicitado, 4, '0', STR_PAD_LEFT);
        $codigoSucursal = str_pad($req->sucursal, 2, '0', STR_PAD_LEFT);

        $codigoNivelEducativo = (string)$req->nivel_estudios;
        $codigoDepartamento = ltrim((string)$req->departamento, '0');
        $codigoProvincia = ltrim((string)$req->provincia, '0');
        $codigoDistrito = ltrim((string)$req->distrito, '0');

        $req->cargo_nombre = $cargos->get($codigoCargo) ?? $req->cargo_solicitado;
        $req->sucursal_nombre = $sucursales->get($codigoSucursal) ?? $req->sucursal;

        //$req->cargo_nombre = $cargos->get($codigoCargo)?->DESC_CARGO ?? $req->cargo_solicitado;
        //$req->sucursal_nombre = $sucursales->get($codigoSucursal)?->SUCU_DESCRIPCION ?? $req->sucursal;

        // Rutas relativas que llegan por query (desde el JS)
        $iconGPath     = $request->input('iconG');      // ej: assets/icons/iconG/guardia.png
        $iconCheckPath = $request->input('iconCheck');  // ej: assets/icons/iconCheck/icon_check1.png
        $iconPhonePath = $request->input('iconPhone');  // ej: assets/icons/iconPhone/icon_phone1.png
        $iconEmailPath = $request->input('iconEmail');  // ej: assets/icons/iconEmail/icon_email1.png
        $fontPath      = $request->input('font');       // ej: fonts/OpenSans-Regular.ttf

        // Fallbacks si no viene nada en el querystring
        $iconGPath     = $iconGPath     ?: 'assets/icons/iconG/guardia.png';
        $iconCheckPath = $iconCheckPath ?: 'assets/icons/iconCheck/icon_check1.png';
        $iconPhonePath = $iconPhonePath ?: 'assets/icons/iconPhone/icon_phone1.png';
        $iconEmailPath = $iconEmailPath ?: 'assets/icons/iconEmail/icon_email1.png';
        $fontPath      = $fontPath      ?: 'fonts/OpenSans-Regular.ttf';

        // Resolver a rutas absolutas y, si no existen, simplemente no usamos ese ícono
        $iconGFull     = file_exists(public_path($iconGPath))     ? public_path($iconGPath)     : null;
        $iconCheckFull = file_exists(public_path($iconCheckPath)) ? public_path($iconCheckPath) : null;
        $iconPhoneFull = file_exists(public_path($iconPhonePath)) ? public_path($iconPhonePath) : null;
        $iconMailFull  = file_exists(public_path($iconEmailPath)) ? public_path($iconEmailPath) : null;

        // La fuente sí es obligatoria → fallback + abort si ni la de fallback existe
        $fontFull = file_exists(public_path($fontPath))
            ? public_path($fontPath)
            : public_path('fonts/OpenSans-Regular.ttf');

        if (!file_exists($fontFull)) {
            abort(500, 'No se encontró ninguna fuente para generar el afiche.');
        }


        // Cargar la plantilla
        //$tpl = public_path("assets/plantillas/{$template}.png");
        //abort_unless(file_exists($tpl), 404, "Plantilla {$template} no encontrada");
        //$bg = Image::read($tpl); // lienzo base 1080×1080

        $tpl = $this->findPlantillaFile($template);
        $bg = Image::read($tpl); // lienzo base

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
            "{$req->nombre_provincia} – {$req->nombre_distrito}",
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

        /*
        $bg->text(
            'SOLMAR SECURITY',
            540,
            265,
            fn($f) => $this->font($f, $fontFull, 48, '#FDFEFE')
        );
        */

        // Cargo
        $bg->text(
            "{$req->cargo_nombre}",
            620,
            350,
            fn($f) => $this->font($f, $fontFull, 38, '#000909ff')
        );

        // Imagen principal de icono (guardia, supervisor, etc)
        if ($iconGFull) {
            $iconImage = Image::read($iconGFull)->resize(300, 300);
            $bg->place($iconImage, 'top-left', 30, 390);
        }

        // Título requisitos
        $bg->text(
            'Requisitos:',
            510,
            400,
            fn($f) => $this->font($f, $fontFull, 35, '#f4d03f')
        );

        // Dibujar requisitos, pasando el ícono de check y la fuente seleccionada
        $this->dibujarRequisitos($bg, $req, $fontFull, $iconCheckFull);
        $this->dibujarFooter($bg, $fontFull, $iconPhoneFull, $iconMailFull);

        // Pie de contacto con íconos de teléfono y email
        //$this->dibujarFooter($bg, $fontFull, $iconPhoneFull, $iconMailFull);

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

    private function findPlantillaFile(string $template): string
    {
        $base = public_path('assets/plantillas/' . $template);
        $exts = ['png', 'jpg', 'jpeg'];

        foreach ($exts as $ext) {
            $candidate = "{$base}.{$ext}";
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        abort(404, "Plantilla {$template} no encontrada");
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
    private function dibujarRequisitos($bg, Requerimiento $req, string $fontFull, ?string $iconCheckFull): void
    {
        $lines = [
            "Estudios mínimos: {$req->nivel_estudios}",   // ojo, aquí era nivel_estudio
        ];

        $icon = $iconCheckFull && file_exists($iconCheckFull)
            ? Image::read($iconCheckFull)->resize(32, 32)
            : null;

        $y          = 450;
        $iconX      = 420;
        $textX      = 480;
        $lineHeight = 50;

        foreach ($lines as $text) {
            if ($icon) {
                $bg->place($icon, 'top-left', $iconX, $y);
            }

            $bg->text($text, $textX, $y + 5, function ($f) use ($fontFull) {
                $f->file($fontFull)
                    ->size(30)
                    ->color('#F7F9F9')
                    ->align('left')
                    ->valign('top');
            });

            $y += $lineHeight;
        }

        $bg->text(
            'NOTA: SERVICIO NO ACUARTELADO',
            540,
            $y + 100,
            fn($f) => $this->font($f, $fontFull, 28, '#F7F9F9')
        );
    }


    // Pie de página con contacto (ahora recibe íconos de teléfono y mail)
    private function dibujarFooter($bg, string $fontFull, ?string $iconPhoneFull, ?string $iconEmailFull): void
    {
        $footer = Image::read(public_path('assets/rectangles/text-bg-blue.png'))
            ->resize(1080, 180);
        $bg->place($footer, 'bottom', 0);

        if ($iconPhoneFull && file_exists($iconPhoneFull)) {
            $phone = Image::read($iconPhoneFull)->resize(30, 30);
            $bg->place($phone, 'top-left', 410, 985);
        }

        if ($iconEmailFull && file_exists($iconEmailFull)) {
            $mail = Image::read($iconEmailFull)->resize(30, 30);
            $bg->place($mail, 'top-left', 280, 1028);
        }

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
