<?php

namespace App\Http\Requests\Auth;

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
