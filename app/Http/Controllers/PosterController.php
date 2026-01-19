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

class PosterController extends Controller
{
    public function index()
    {
        $requerimientos = Requerimiento::orderByDesc('created_at')->get();

        $cargos = Cargo::forSelect();
        $sucursales = Sucursal::forSelect();

        // (si más adelante quieres mostrar depa/prov/dist)
        $departamentos = Departamento::forSelect();
        $provincias = Provincia::forSelect();
        $distritos = Distrito::forSelect();

        foreach ($requerimientos as $r) {
            $codigoCargo = str_pad($r->cargo_solicitado, 4, '0', STR_PAD_LEFT);
            $codigoSucursal = str_pad($r->sucursal, 2, '0', STR_PAD_LEFT);

            $r->cargo_nombre = $cargos->get($codigoCargo) ?? $r->cargo_solicitado;
            $r->sucursal_nombre = $sucursales->get($codigoSucursal) ?? $r->sucursal;
        }

        // Assets (una sola vez)
        $plantillas  = $this->collectAssets('assets/plantillas');
        $iconosG     = $this->collectAssets('assets/icons/iconG');
        $iconosCheck = $this->collectAssets('assets/icons/iconCheck');
        $iconosPhone = $this->collectAssets('assets/icons/iconPhone');
        $iconosEmail = $this->collectAssets('assets/icons/iconEmail');
        $fonts       = $this->collectAssets('fonts', ['ttf', 'otf']);

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
        if (!is_dir($full)) return collect();

        return collect(File::files($full))
            ->filter(fn($file) => in_array(strtolower($file->getExtension()), $exts, true))
            ->map(function ($file) use ($relativePath) {
                $filename = $file->getFilename();
                $name = Str::title(str_replace(['_', '-'], ' ', Str::beforeLast($filename, '.')));

                return (object)[
                    'filename' => $filename,
                    'name'     => $name,
                    'path'     => $relativePath . '/' . $filename,
                ];
            })
            ->values();
    }

    public function assetsUpload(Request $request)
    {
        $request->validate([
            'tipo'    => 'required|in:plantilla,iconG,iconCheck,iconPhone,iconEmail,font',
            'archivo' => 'required|file|max:4096',
        ]);

        $tipo = $request->input('tipo');
        $file = $request->file('archivo');

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
        if (!is_dir($destPath)) mkdir($destPath, 0775, true);

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = strtolower($file->getClientOriginalExtension());

        $slugName = Str::slug($originalName, '-');
        if ($slugName === '') $slugName = 'archivo';

        $filename = $slugName . '.' . $extension;
        if (file_exists($destPath . DIRECTORY_SEPARATOR . $filename)) {
            $filename = $slugName . '-' . time() . '.' . $extension;
        }

        $fullPath = $destPath . DIRECTORY_SEPARATOR . $filename;

        if ($tipo === 'plantilla') {
            Image::read($file->getRealPath())->cover(1080, 1080)->save($fullPath);
        } else {
            $file->move($destPath, $filename);
        }

        return redirect()->back()->with('success', "Archivo subido correctamente a {$relativePath}/{$filename}");
    }

    private function scanImages(string $relativePath)
    {
        $fullPath = public_path($relativePath);
        if (!is_dir($fullPath)) return collect();

        return collect(File::files($fullPath))->map(function ($file) use ($relativePath) {
            $filename = $file->getFilename();
            $slug = pathinfo($filename, PATHINFO_FILENAME);

            return (object)[
                'slug'     => $slug,
                'name'     => Str::headline($slug),
                'path'     => $relativePath . '/' . $filename,
                'filename' => $filename,
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
        $fonts        = $this->scanImages('fonts');

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

        if (!$relativePath) return back()->with('error', 'Tipo de recurso inválido.');

        $filename = basename($request->input('filename'));
        $fullPath = public_path($relativePath . DIRECTORY_SEPARATOR . $filename);

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return back()->with('success', "Archivo eliminado: {$filename}");
        }

        return back()->with('error', 'El archivo ya no existe en el servidor.');
    }

    // =========================
    // GENERADOR
    // =========================
    public function show(Request $request, Requerimiento $req, string $template)
    {
        $W = 1080;
        $H = 1080;

        // Catálogos
        $cargos = Cargo::forSelect();
        $sucursales = Sucursal::forSelect();

        // Nombres legibles
        $codigoCargo = str_pad($req->cargo_solicitado, 4, '0', STR_PAD_LEFT);
        $codigoSucursal = str_pad($req->sucursal, 2, '0', STR_PAD_LEFT);
        $req->cargo_nombre = $cargos->get($codigoCargo) ?? $req->cargo_solicitado;
        $req->sucursal_nombre = $sucursales->get($codigoSucursal) ?? $req->sucursal;

        // Assets por query
        $iconGPath     = $request->input('iconG')      ?: 'assets/icons/iconG/guardia.png';
        $iconCheckPath = $request->input('iconCheck')  ?: 'assets/icons/iconCheck/icon_check1.png';
        $iconPhonePath = $request->input('iconPhone')  ?: 'assets/icons/iconPhone/icon_phone1.png';
        $iconEmailPath = $request->input('iconEmail')  ?: 'assets/icons/iconEmail/icon_email1.png';
        $fontPath      = $request->input('font')       ?: 'fonts/OpenSans-Regular.ttf';

        $iconGFull     = file_exists(public_path($iconGPath))     ? public_path($iconGPath)     : null;
        $iconCheckFull = file_exists(public_path($iconCheckPath)) ? public_path($iconCheckPath) : null;
        $iconPhoneFull = file_exists(public_path($iconPhonePath)) ? public_path($iconPhonePath) : null;
        $iconMailFull  = file_exists(public_path($iconEmailPath)) ? public_path($iconEmailPath) : null;

        $fontFull = file_exists(public_path($fontPath))
            ? public_path($fontPath)
            : public_path('fonts/OpenSans-Regular.ttf');

        if (!file_exists($fontFull)) abort(500, 'No se encontró ninguna fuente para generar el afiche.');

        // Plantilla base 1080x1080
        $tpl = $this->findPlantillaFile($template);
        $bg  = Image::read($tpl)->cover($W, $H);

        // Colores
        $primary = '#0b3b8c';
        $accent  = '#fcb900';

        // Textos
        $ubicacionTexto = $req->sucursal_nombre ?? $req->ubicacion_servicio ?? 'Ubicación no registrada';
        $ubicacionTexto = preg_replace('/^\s*sucursal\s+/i', '', (string)$ubicacionTexto);
        $ubicacionTexto = preg_replace('/\s+\d+$/', '', (string)$ubicacionTexto);
        $ubicacionTexto = trim((string)$ubicacionTexto);

        $puesto       = strtoupper(trim($req->cargo_nombre ?? $req->cargo_solicitado));
        $notaServicio = empty($req->servicio_acuartelado) ? 'Servicio no acuartelado' : 'Servicio acuartelado';

        $requisitos = $this->buildRequirementLines($req);
        $beneficios = $this->buildBenefitLines($req);

        // Para no amontonar
        $requisitos = $this->limitLines($requisitos, 3, '');
        $beneficios = array_slice($beneficios, 0, 4);

        // Truncados estéticos
        $requisitos = array_map(fn($t) => $this->truncateText($t, 34), $requisitos);
        $beneficios = array_map(fn($t) => $this->truncateText($t, 18), $beneficios);

        // Overlay suave (pulido)
        $bg->place(Image::create($W, $H)->fill('rgba(0,0,0,0.05)'), 'top-left', 0, 0);

        // PANEL (pulido: sombra más suave y panel un poquito más “limpio”)
        $panelX = 70;
        $panelY = 80;
        $panelW = 660;
        $panelH = 920;

        $shadow = $this->roundedRect($panelW, $panelH, 28, 'rgba(0,0,0,0.10)');
        $bg->place($shadow, 'top-left', $panelX + 8, $panelY + 10);

        $panel = $this->roundedRect($panelW, $panelH, 28, 'rgba(255,255,255,0.95)');
        $bg->place($panel, 'top-left', $panelX, $panelY);

        // LOGO
        $logoFile = public_path('assets/solmar_logo2.png');
        if (file_exists($logoFile) && $request->boolean('logo', true)) {
            $logo = Image::read($logoFile)->resize(width: 170);
            $bg->place($logo, 'top-right', 35, 10);
        }

        // BADGE VACANTE (pulido: un toque menos alto)
        $badgeW = 520;
        $badgeH = 66;
        $badge = $this->roundedRect($badgeW, $badgeH, 16, 'rgba(255,255,255,0.97)');
        $badgeX = $panelX + 25;
        $badgeY = $panelY + 20;
        $bg->place($badge, 'top-left', $badgeX, $badgeY);

        $bg->text('VACANTE PARA:', $badgeX + 20, $badgeY + (int)($badgeH / 2), function ($f) use ($fontFull, $primary) {
            $this->fontLeft($f, $fontFull, 24, $primary);
        });
        $bg->text(strtoupper($ubicacionTexto), $badgeX + 210, $badgeY + (int)($badgeH / 2), function ($f) use ($fontFull, $primary) {
            $this->fontLeft($f, $fontFull, 26, $primary);
        });

        // BANNER (pulido: brillo sutil)
        $bannerX = $panelX + 25;
        $bannerY = $panelY + 95;
        $bannerW = $panelW - 50;
        $bannerH = 175;

        $banner = $this->roundedRect($bannerW, $bannerH, 22, 'rgba(11,59,140,0.97)');
        $bg->place($banner, 'top-left', $bannerX, $bannerY);

        // brillo sutil en la mitad superior
        $gloss = $this->roundedRect($bannerW, (int)($bannerH * 0.55), 22, 'rgba(255,255,255,0.06)');
        $bg->place($gloss, 'top-left', $bannerX, $bannerY);

        $this->textShadowLeft($bg, '>>>', $bannerX + 22, $bannerY + 34, $fontFull, 24, $accent, 'rgba(0,0,0,0.28)', 2, 2);

        $titleLines = $this->splitTitle($puesto, 18, 2);
        $titleY = $bannerY + 76;
        foreach ($titleLines as $i => $line) {
            $this->textShadowLeft($bg, strtoupper($line), $bannerX + 22, $titleY + ($i * 60), $fontFull, 56, $accent, 'rgba(0,0,0,0.33)', 3, 3);
        }

        // ICONO PRINCIPAL (pulido: un poco más abajo y a la derecha)
        if ($iconGFull) {
            $halo = $this->roundedRect(430, 430, 215, 'rgba(255,255,255,0.28)');
            // más a la derecha/abajo => offsets más chicos
            $bg->place($halo, 'bottom-right', 25, 75);

            $iconImage = Image::read($iconGFull)->resize(420, 420);
            $bg->place($iconImage, 'bottom-right', 28, 80);
        }

        // REQUISITOS
        $reqLabelY = $bannerY + $bannerH + 70;
        $bg->text('REQUISITOS:', $panelX + 35, $reqLabelY, function ($f) use ($fontFull, $primary) {
            $this->fontLeft($f, $fontFull, 32, $primary);
        });

        $nextY = $this->dibujarRequisitosPills_SOL_CURVAS(
            $bg,
            $requisitos,
            $fontFull,
            $iconCheckFull,
            $reqLabelY + 34,
            $panelX + 35,
            $panelW - 90,
            58,
            $accent
        );

        // BENEFICIOS
        $benLabelY = $nextY + 18;
        $bg->text('BENEFICIOS:', $panelX + 35, $benLabelY, function ($f) use ($fontFull, $primary) {
            $this->fontLeft($f, $fontFull, 32, $primary);
        });

        $nextY = $this->dibujarBeneficiosGrid_SOL_CURVAS(
            $bg,
            $beneficios,
            $fontFull,
            $iconCheckFull,
            $benLabelY + 34,
            $panelX + 35,
            290,
            58,
            16,
            $accent
        );

        // SERVICIO (pulido: un poquito más presente)
        $serviceY = $this->clamp($nextY + 22, $panelY + 50, $panelY + $panelH - 20);
        $bg->text(strtoupper($notaServicio), $panelX + (int)($panelW / 2), $serviceY, function ($f) use ($fontFull, $primary) {
            $f->file($fontFull)->size(24)->color($primary)->align('center')->valign('middle');
        });

        // ========= Salida =========
        $format = strtolower($request->string('format', 'png'));
        $format = in_array($format, ['png', 'jpg', 'pdf']) ? $format : 'png';

        if ($format !== 'pdf') {
            [$encoder, $mime, $ext] = $format === 'jpg'
                ? [new JpegEncoder(quality: 90), 'image/jpeg', 'jpg']
                : [new PngEncoder(), 'image/png', 'png'];

            $binary = $bg->encode($encoder);

            if ($request->boolean('preview')) {
                return Response::make($binary, 200, ['Content-Type' => $mime]);
            }

            return Response::make($binary, 200, [
                'Content-Type'        => $mime,
                'Content-Disposition' => "attachment; filename=\"poster_{$req->id}.{$ext}\"",
            ]);
        }

        // PDF
        $pngBase64 = base64_encode((string)$bg->encode(new PngEncoder()));
        $html = <<<HTML
<html><body style="margin:0;padding:0;">
  <img src="data:image/png;base64,{$pngBase64}" style="width:100%;height:auto;">
</body></html>
HTML;

        $pdf  = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        $binary = $pdf->output();

        return Response::make($binary, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"poster_{$req->id}.pdf\"",
        ]);
    }

    private function findPlantillaFile(string $template): string
    {
        $base = public_path('assets/plantillas/' . $template);
        $exts = ['png', 'jpg', 'jpeg'];

        foreach ($exts as $ext) {
            $candidate = "{$base}.{$ext}";
            if (file_exists($candidate)) return $candidate;
        }

        abort(404, "Plantilla {$template} no encontrada");
    }

    // =========================
    // Texto
    // =========================
    private function fontLeft($f, string $path, int $size, string $color): void
    {
        $f->file($path)->size($size)->color($color)->align('left')->valign('middle');
    }

    private function textShadowLeft($bg, string $text, int $x, int $y, string $font, int $size, string $color, string $shadow = 'rgba(0,0,0,0.25)', int $dx = 2, int $dy = 2): void
    {
        $bg->text($text, $x + $dx, $y + $dy, function ($f) use ($font, $size, $shadow) {
            $this->fontLeft($f, $font, $size, $shadow);
        });

        $bg->text($text, $x, $y, function ($f) use ($font, $size, $color) {
            $this->fontLeft($f, $font, $size, $color);
        });
    }

    private function limitLines(array $lines, int $max, string $suffix = '...'): array
    {
        $lines = array_values(array_filter(array_map('trim', $lines)));
        if (count($lines) <= $max) return $lines;

        $cut = array_slice($lines, 0, $max);

        if ($suffix !== '') {
            $cut[$max - 1] = rtrim($cut[$max - 1], '.') . " {$suffix}";
        }

        return $cut;
    }

    private function splitTitle(string $text, int $maxLen = 18, int $maxLines = 2): array
    {
        $words = preg_split('/\s+/', trim($text));
        $lines = [];
        $current = '';

        foreach ($words as $w) {
            $try = $current === '' ? $w : ($current . ' ' . $w);
            if (mb_strlen($try) <= $maxLen) {
                $current = $try;
            } else {
                $lines[] = $current;
                $current = $w;
                if (count($lines) >= $maxLines - 1) break;
            }
        }

        if ($current !== '' && count($lines) < $maxLines) $lines[] = $current;
        return array_slice($lines, 0, $maxLines);
    }

    private function truncateText(string $text, int $maxChars): string
    {
        $t = trim(preg_replace('/\s+/', ' ', $text));
        if ($maxChars <= 0) return $t;
        if (mb_strlen($t) <= $maxChars) return $t;
        return rtrim(mb_substr($t, 0, $maxChars - 1)) . '…';
    }

    // =========================
    // Datos
    // =========================
    private function buildRequirementLines(Requerimiento $req): array
    {
        return array_values(array_filter([
            $req->nivel_estudios ? 'Estudios: ' . str_replace('_', ' ', $req->nivel_estudios) : null,
            $req->experiencia_minima ? 'Experiencia: ' . str_replace('_', ' ', $req->experiencia_minima) : null,
            ($req->edad_minima || $req->edad_maxima)
                ? 'Edad: ' . ($req->edad_minima ?? '?') . ' - ' . ($req->edad_maxima ?? '?')
                : null,
            $req->requiere_sucamec ? 'SUCAMEC vigente' : null,
            $req->requiere_licencia_conducir ? 'Licencia de conducir' : null,
            $req->requisitos_adicionales ?: null,
        ]));
    }

    private function buildBenefitLines(Requerimiento $req): array
    {
        $beneficios = $req->beneficios ?? [];

        if (is_string($beneficios)) {
            $decoded = json_decode($beneficios, true);
            if (json_last_error() === JSON_ERROR_NONE) $beneficios = $decoded;
        }

        if (!is_array($beneficios)) $beneficios = [];

        return count($beneficios) > 0
            ? array_map('trim', $beneficios)
            : ['Consultas de beneficios durante la entrevista.'];
    }

    // =========================
    // Pills (curvas) + check VERDE (se mantiene)
    // =========================
    private function dibujarRequisitosPills_SOL_CURVAS(
        $bg,
        array $lines,
        string $fontFull,
        ?string $iconCheckFull,
        int $startY,
        int $x,
        int $pillW,
        int $pillH,
        string $accent
    ): int {
        if (empty($lines)) return $startY;

        $check = ($iconCheckFull && file_exists($iconCheckFull))
            ? Image::read($iconCheckFull)->resize(22, 22)
            : null;

        // cache de shapes (más rápido y consistente)
        $pillShadow = $this->roundedRect($pillW, $pillH, 16, 'rgba(0,0,0,0.10)');
        $pillBlue   = $this->roundedRect($pillW, $pillH, 16, 'rgba(11,59,140,0.97)');
        $sqYellow   = $this->roundedRect(40, 40, 10, 'rgba(252,185,0,1)');

        $y = $startY;

        foreach ($lines as $t) {
            $bg->place($pillShadow, 'top-left', $x + 3, $y + 3);
            $bg->place($pillBlue,   'top-left', $x, $y);

            $sqX = $x + 16;
            $sqY = $y + (int)(($pillH - 40) / 2);
            $bg->place($sqYellow, 'top-left', $sqX, $sqY);

            if ($check) {
                $bg->place($check, 'top-left', $sqX + 9, $sqY + 9);
            }

            $bg->text($t, $x + 70, $y + (int)($pillH / 2), function ($f) use ($fontFull) {
                $f->file($fontFull)->size(23)->color('#ffffff')->align('left')->valign('middle');
            });

            $y += ($pillH + 12);
        }

        return $y;
    }

    private function dibujarBeneficiosGrid_SOL_CURVAS(
        $bg,
        array $items,
        string $fontFull,
        ?string $iconCheckFull,
        int $startY,
        int $x,
        int $itemW,
        int $itemH,
        int $gap,
        string $accent
    ): int {
        if (empty($items)) return $startY;

        $check = ($iconCheckFull && file_exists($iconCheckFull))
            ? Image::read($iconCheckFull)->resize(22, 22)
            : null;

        $pillShadow = $this->roundedRect($itemW, $itemH, 16, 'rgba(0,0,0,0.10)');
        $pillBlue   = $this->roundedRect($itemW, $itemH, 16, 'rgba(11,59,140,0.97)');
        $sqYellow   = $this->roundedRect(40, 40, 10, 'rgba(252,185,0,1)');

        $y = $startY;
        $col = 0;

        foreach ($items as $t) {
            $xx = $x + ($col * ($itemW + $gap));

            $bg->place($pillShadow, 'top-left', $xx + 3, $y + 3);
            $bg->place($pillBlue,   'top-left', $xx, $y);

            $sqX = $xx + 14;
            $sqY = $y + (int)(($itemH - 40) / 2);
            $bg->place($sqYellow, 'top-left', $sqX, $sqY);

            if ($check) {
                $bg->place($check, 'top-left', $sqX + 9, $sqY + 9);
            }

            $bg->text($t, $xx + 66, $y + (int)($itemH / 2), function ($f) use ($fontFull) {
                $f->file($fontFull)->size(22)->color('#ffffff')->align('left')->valign('middle');
            });

            $col++;
            if ($col >= 2) {
                $col = 0;
                $y += ($itemH + 12);
            }
        }

        if ($col !== 0) $y += ($itemH + 12);
        return $y;
    }

    // =========================
    // Curvas (GD) + utilidades
    // =========================
    private function parseRgba(string $rgba): array
    {
        if (preg_match('/rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*([0-9.]+)\s*\)/i', $rgba, $m)) {
            $r = (int)$m[1];
            $g = (int)$m[2];
            $b = (int)$m[3];
            $a = (float)$m[4];
            $a = max(0, min(1, $a));
            $gdAlpha = (int) round((1 - $a) * 127);
            return [$r, $g, $b, $gdAlpha];
        }

        if (preg_match('/^#([0-9a-f]{6})$/i', trim($rgba), $m)) {
            $hex = $m[1];
            return [
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2)),
                0
            ];
        }

        return [255, 255, 255, 0];
    }

    private function roundedRect(int $w, int $h, int $radius, string $fillRgba)
    {
        if (!function_exists('imagecreatetruecolor')) {
            return Image::create($w, $h)->fill($fillRgba);
        }

        $radius = max(0, min($radius, (int) floor(min($w, $h) / 2)));

        $img = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);
        imagealphablending($img, true);

        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);

        [$r, $g, $b, $a] = $this->parseRgba($fillRgba);
        $color = imagecolorallocatealpha($img, $r, $g, $b, $a);

        imagefilledrectangle($img, $radius, 0, $w - $radius, $h, $color);
        imagefilledrectangle($img, 0, $radius, $w, $h - $radius, $color);

        imagefilledellipse($img, $radius, $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($img, $w - $radius, $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($img, $radius, $h - $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($img, $w - $radius, $h - $radius, $radius * 2, $radius * 2, $color);

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return Image::read($png);
    }

    private function clamp(int $v, int $min, int $max): int
    {
        return max($min, min($max, $v));
    }

    // =========================
    // (Se mantiene por compatibilidad aunque ya no lo uses en el diseño)
    // =========================
    private function resolveClienteNombre(Requerimiento $req): string
    {
        if (!empty($req->cliente_nombre) && !ctype_digit((string)$req->cliente_nombre)) {
            return trim((string)$req->cliente_nombre);
        }

        $codigo = trim((string)($req->cliente ?? ''));
        if ($codigo === '') return 'Cliente corporativo';

        static $clientesCache = null;

        if ($clientesCache === null) {
            try {
                $clientesCache = collect(DB::connection('sqlsrv')->select('EXEC dbo.SP_LISTAR_CLIENTES'))
                    ->mapWithKeys(function ($item) {
                        $cod = is_string($item->CODIGO_CLIENTE) ? trim($item->CODIGO_CLIENTE) : $item->CODIGO_CLIENTE;
                        return [$cod => $item->NOMBRE_CLIENTE];
                    })
                    ->toArray();
            } catch (\Throwable $e) {
                $clientesCache = [];
            }
        }

        return $clientesCache[$codigo] ?? $codigo;
    }
}
