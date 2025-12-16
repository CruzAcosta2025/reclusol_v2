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
                title: '¡Registro Completado!',
                html: @json($success),
                width: 550,
                heightAuto: true,
                padding: '2rem',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Aceptar',
                background: '#ffffff',
                didOpen: (modal) => {
                    modal.querySelector('.swal2-title').style.color = '#059669';
                    modal.querySelector('.swal2-html-container').style.color = '#374151';
                    modal.querySelector('.swal2-html-container').style.fontSize = '1rem';
                    modal.querySelector('.swal2-html-container').style.lineHeight = '1.5';
                }
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'error',
                title: 'Error en la Validación',
                width: 550,
                heightAuto: true,
                padding: '2rem',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Reintentar',
                background: '#ffffff',
                didOpen: (modal) => {
                    modal.querySelector('.swal2-title').style.color = '#dc2626';
                    modal.querySelector('.swal2-html-container').style.color = '#374151';
                    modal.querySelector('.swal2-html-container').style.fontSize = '0.95rem';
                    modal.querySelector('.swal2-html-container').style.lineHeight = '1.6';
                }
            });
        });
    </script>
@endif
