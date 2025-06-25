{{-- resources/views/components/alerts.blade.php --}}
@once
    {{--  Carga SweetAlert2 una sola vez --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endonce

@props([
    'success' => session('success'),
    'errors'  => $errors,
])

@if ($success)
    {{--  Ejecuta cuando el DOM ya está listo y la librería cargada --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: @json($success),
                 width: 500,          // ancho exacto — 500 px
                 heightAuto: true,    // (por defecto) ajusta alto al contenido
                 padding: '2rem',     // espacio interior (≈ 32 px)
                confirmButtonColor: '#3085d6',
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'error',
                title: 'Corrige los campos',
                 width: 500,          // ancho exacto — 500 px
                 heightAuto: true,    // (por defecto) ajusta alto al contenido
                 padding: '2rem',     // espacio interior (≈ 32 px)
        
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif
