<?php

namespace App\Services;

use App\Models\Requerimiento;
use App\Models\Cargo;
use App\Models\Sucursal;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\JpegEncoder;

class PosterService
{
    protected CargoService $cargoService;
    protected RequerimientoService $requerimientoService;

    public function __construct(CargoService $cargoService, RequerimientoService $requerimientoService)
    {
        $this->cargoService = $cargoService;
        $this->requerimientoService = $requerimientoService;
    }

    public function obtenerRequerimientosConRelaciones()
    {
        $requerimientos = Requerimiento::orderByDesc('created_at')->get();

        $cargos = Cargo::forSelect();
        $sucursales = Sucursal::forSelect();
        $departamentos = Departamento::forSelect();
        $provincias = Provincia::forSelect();
        $distritos = Distrito::forSelect();

        foreach ($requerimientos as $r) {
            $codigoCargo = str_pad($r->cargo_solicitado, 4, '0', STR_PAD_LEFT);
            $codigoSucursal = str_pad($r->sucursal, 2, '0', STR_PAD_LEFT);

            $r->cargo_nombre = $cargos->get($codigoCargo) ?? $r->cargo_solicitado;
            $r->sucursal_nombre = $sucursales->get($codigoSucursal) ?? $r->sucursal;
        }

        return $requerimientos;
    }

    public function obtenerRecursos()
    {
        return [
            'plantillas'  => $this->collectAssets('assets/plantillas'),
            'iconosG'     => $this->collectAssets('assets/icons/iconG'),
            'iconosCheck' => $this->collectAssets('assets/icons/iconCheck'),
            'iconosPhone' => $this->collectAssets('assets/icons/iconPhone'),
            'iconosEmail' => $this->collectAssets('assets/icons/iconEmail'),
            'fonts'       => $this->collectAssets('fonts', ['ttf', 'otf']),
        ];
    }

    public function collectAssets(string $relativePath, array $exts = ['png', 'jpg', 'jpeg'])
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

    public function scanImages(string $relativePath)
    {
        $fullPath = public_path($relativePath);

        if (!is_dir($fullPath)) {
            return collect();
        }

        return collect(File::files($fullPath))->map(function ($file) use ($relativePath) {
            $filename = $file->getFilename();
            $slug     = pathinfo($filename, PATHINFO_FILENAME);

            return (object) [
                'slug'     => $slug,
                'name'     => Str::headline($slug),
                'path'     => $relativePath . '/' . $filename,
                'filename' => $filename,
            ];
        });
    }

    public function obtenerRecursosEscaneados()
    {
        return [
            'plantillas'  => $this->scanImages('assets/plantillas'),
            'iconosG'     => $this->scanImages('assets/icons/iconG'),
            'iconosCheck' => $this->scanImages('assets/icons/iconCheck'),
            'iconosPhone' => $this->scanImages('assets/icons/iconPhone'),
            'iconosEmail' => $this->scanImages('assets/icons/iconEmail'),
            'fonts'       => $this->scanImages('fonts'),
        ];
    }

    public function subirAsset(string $tipo, $file)
    {
        $relativePath = $this->obtenerRutaPorTipo($tipo);
        $destPath = public_path($relativePath);

        if (!is_dir($destPath)) {
            mkdir($destPath, 0775, true);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = strtolower($file->getClientOriginalExtension());

        $slugName = Str::slug($originalName, '-');
        if ($slugName === '') {
            $slugName = 'archivo';
        }

        $filename = $slugName . '.' . $extension;

        if (file_exists($destPath . DIRECTORY_SEPARATOR . $filename)) {
            $filename = $slugName . '-' . time() . '.' . $extension;
        }

        $fullPath = $destPath . DIRECTORY_SEPARATOR . $filename;

        // Solo PLANTILLAS a 1080x1080
        if ($tipo === 'plantilla') {
            $image = Image::read($file->getRealPath())
                ->cover(1080, 1080);

            $image->save($fullPath);
        } else {
            // Íconos y fuentes se guardan tal cual
            $file->move($destPath, $filename);
        }

        return [
            'relativePath' => $relativePath,
            'filename'     => $filename,
        ];
    }

    public function eliminarAsset(string $tipo, string $filename)
    {
        $relativePath = $this->obtenerRutaPorTipo($tipo);
        
        if (!$relativePath) {
            return false;
        }

        $filename = basename($filename);
        $fullPath = public_path($relativePath . DIRECTORY_SEPARATOR . $filename);

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return true;
        }

        return false;
    }

    public function generarPoster(
        Requerimiento $req,
        string $template,
        array $recursos,
        bool $includeLogo = true
    ) {
        // Cargar catálogos
        $cargos = Cargo::forSelect();
        $sucursales = Sucursal::forSelect();
        $departamentos = Departamento::forSelect();
        $provincias = Provincia::forSelect();
        $distritos = Distrito::forSelect();

        // Mapear nombres legibles para cargo y sucursal
        $codigoCargo = str_pad($req->cargo_solicitado, 4, '0', STR_PAD_LEFT);
        $codigoSucursal = str_pad($req->sucursal, 2, '0', STR_PAD_LEFT);

        $req->cargo_nombre = $cargos->get($codigoCargo) ?? $req->cargo_solicitado;
        $req->sucursal_nombre = $sucursales->get($codigoSucursal) ?? $req->sucursal;

        // Mapear nombres legibles para provincia y distrito
        $codigoProvincia = ltrim((string)$req->provincia, '0');
        $codigoDistrito = ltrim((string)$req->distrito, '0');

        $req->nombre_provincia = $provincias->get($codigoProvincia) ?? $req->provincia;
        $req->nombre_distrito = $distritos->get($codigoDistrito) ?? $req->distrito;

        // Procesar rutas de recursos con fallbacks
        $recursos = $this->procesarRecursos($recursos);

        // Cargar plantilla
        $tpl = $this->findPlantillaFile($template);
        $bg = Image::read($tpl);

        // Logo opcional
        if ($includeLogo) {
            $this->agregarLogo($bg);
        }

        // Dibujar elementos del poster
        $this->dibujarCabecera($bg, $recursos['fontFull']);
        $this->dibujarUbicacion($bg, $req, $recursos['fontFull']);
        $this->dibujarSeccionSolmar($bg, $recursos['fontFull']);
        $this->dibujarCargo($bg, $req, $recursos['fontFull']);
        $this->dibujarIconoPrincipal($bg, $recursos['iconGFull']);
        $this->dibujarTituloRequisitos($bg, $recursos['fontFull']);
        $this->dibujarRequisitos($bg, $req, $recursos['fontFull'], $recursos['iconCheckFull']);
        $this->dibujarFooter($bg, $recursos['fontFull'], $recursos['iconPhoneFull'], $recursos['iconMailFull']);

        return $bg;
    }

    private function procesarRecursos(array $recursos)
    {
        $iconGPath     = $recursos['iconG']     ?? 'assets/icons/iconG/guardia.png';
        $iconCheckPath = $recursos['iconCheck'] ?? 'assets/icons/iconCheck/icon_check1.png';
        $iconPhonePath = $recursos['iconPhone'] ?? 'assets/icons/iconPhone/icon_phone1.png';
        $iconEmailPath = $recursos['iconEmail'] ?? 'assets/icons/iconEmail/icon_email1.png';
        $fontPath      = $recursos['font']      ?? 'fonts/OpenSans-Regular.ttf';

        $iconGFull     = file_exists(public_path($iconGPath))     ? public_path($iconGPath)     : null;
        $iconCheckFull = file_exists(public_path($iconCheckPath)) ? public_path($iconCheckPath) : null;
        $iconPhoneFull = file_exists(public_path($iconPhonePath)) ? public_path($iconPhonePath) : null;
        $iconMailFull  = file_exists(public_path($iconEmailPath)) ? public_path($iconEmailPath) : null;

        $fontFull = file_exists(public_path($fontPath))
            ? public_path($fontPath)
            : public_path('fonts/OpenSans-Regular.ttf');

        if (!file_exists($fontFull)) {
            abort(500, 'No se encontró ninguna fuente para generar el afiche.');
        }

        return [
            'iconGFull'     => $iconGFull,
            'iconCheckFull' => $iconCheckFull,
            'iconPhoneFull' => $iconPhoneFull,
            'iconMailFull'  => $iconMailFull,
            'fontFull'      => $fontFull,
        ];
    }

    public function findPlantillaFile(string $template): string
    {
        $base = public_path('assets/plantillas/' . $template);
        $exts = ['png', 'jpg', 'jpeg'];

        foreach ($exts as $ext) {
            $candidate = "{$base}.{$ext}";
            if (file_exists($candidate)) {
                return $candidate;
            }
        }
        // Fallback a una plantilla por defecto para no romper la generación
        foreach ($exts as $ext) {
            $fallback = public_path("assets/plantillas/modern.{$ext}");
            if (file_exists($fallback)) {
                return $fallback;
            }
        }

        abort(404, "Plantilla {$template} no encontrada");
    }

    public function codificarImagen($bg, string $format)
    {
        if ($format === 'jpg') {
            return [
                'binary' => $bg->encode(new JpegEncoder(quality: 90)),
                'mime'   => 'image/jpeg',
                'ext'    => 'jpg',
            ];
        }

        return [
            'binary' => $bg->encode(new PngEncoder()),
            'mime'   => 'image/png',
            'ext'    => 'png',
        ];
    }

    public function imagenABase64($bg)
    {
        return base64_encode((string) $bg->encode(new PngEncoder()));
    }

    // ==================== MÉTODOS DE DIBUJO ====================

    private function agregarLogo($bg)
    {
        $logoFile = public_path('assets/solmar_logo2.png');
        if (file_exists($logoFile)) {
            $logo = Image::read($logoFile)->resize(width: 180);
            $bg->place($logo, 'top-left', 90, 210);
        }
    }

    private function dibujarCabecera($bg, string $fontFull)
    {
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
    }

    private function dibujarUbicacion($bg, Requerimiento $req, string $fontFull)
    {
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
    }

    private function dibujarSeccionSolmar($bg, string $fontFull)
    {
        $bg->place(
            Image::read(public_path('assets/rectangles/text-bg-blue.png'))->resize(480, 80),
            'top-left',
            300,
            225
        );
    }

    private function dibujarCargo($bg, Requerimiento $req, string $fontFull)
    {
        $bg->text(
            "{$req->cargo_nombre}",
            620,
            350,
            fn($f) => $this->font($f, $fontFull, 38, '#000909ff')
        );
    }

    private function dibujarIconoPrincipal($bg, ?string $iconGFull)
    {
        if ($iconGFull) {
            $iconImage = Image::read($iconGFull)->resize(300, 300);
            $bg->place($iconImage, 'top-left', 30, 390);
        }
    }

    private function dibujarTituloRequisitos($bg, string $fontFull)
    {
        $bg->text(
            'Requisitos:',
            510,
            400,
            fn($f) => $this->font($f, $fontFull, 35, '#f4d03f')
        );
    }

    private function dibujarRequisitos($bg, Requerimiento $req, string $fontFull, ?string $iconCheckFull)
    {
        $lines = [
            "Estudios mínimos: {$req->nivel_estudios}",
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

    private function dibujarFooter($bg, string $fontFull, ?string $iconPhoneFull, ?string $iconEmailFull)
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

    private function font($f, string $path, int $size, string $color): void
    {
        $f->file($path)
            ->size($size)
            ->color($color)
            ->align('center')
            ->valign('middle');
    }

    private function obtenerRutaPorTipo(string $tipo): ?string
    {
        return match ($tipo) {
            'plantilla'  => 'assets/plantillas',
            'iconG'      => 'assets/icons/iconG',
            'iconCheck'  => 'assets/icons/iconCheck',
            'iconPhone'  => 'assets/icons/iconPhone',
            'iconEmail'  => 'assets/icons/iconEmail',
            'font'       => 'fonts',
            default      => null,
        };
    }
}