<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'usuario'   => ['required', 'string'],
            'contrasena' => ['required', 'string'],
        ];
    }


    /*
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Si NO podemos autenticar (credenciales inválidas) -> contar intento y lanzar excepción
        if (! Auth::attempt([
            'usuario' => $this->input('usuario'),
            'password' => $this->input('contrasena'),
        ], $this->boolean('remember'))) {
            // Aumenta el contador de intentos (decay por defecto: 60s, puedes pasar otro valor si quieres)
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'usuario' => trans('auth.failed'),
            ]);
        }

        // Si llegamos aquí es porque la autenticación fue exitosa -> limpiar contador
        RateLimiter::clear($this->throttleKey());
    }

    */

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $usuario    = $this->input('usuario');
        $contrasena = $this->input('contrasena');

        // 1️⃣ Verificar si el usuario existe y está inhabilitado
        $user = User::where('usuario', $usuario)->first();

        if ($user && ! $user->habilitado) {
            // Cuenta como intento fallido para el rate limiter
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'usuario' => 'Tu usuario ha sido inhabilitado. Contacta con el administrador.',
            ]);
        }

        // 2️⃣ Intentar autenticación SOLO si está habilitado (o no existe)
        if (! Auth::attempt([
            'usuario'  => $usuario,
            'password' => $contrasena,
            'habilitado' => 1, // doble seguro: sólo usuarios habilitados
        ], $this->boolean('remember'))) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'usuario' => trans('auth.failed'),
            ]);
        }

        // 3️⃣ Si todo OK, limpiar contador de intentos
        RateLimiter::clear($this->throttleKey());
    }


    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'usuario' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        // Uso input() para mayor compatibilidad
        return Str::transliterate(Str::lower($this->input('usuario')) . '|' . $this->ip());
    }
}
