<x-app-layout>
    <div class="container">
        <h2 class="mb-4">Listado de Postulantes en Proceso</h2>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>DNI</th>
                    <th>Edad</th>
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Fecha de postulación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($postulantes as $postulante)
                <tr>
                    <td>{{ $postulante->id }}</td>
                    <td>{{ $postulante->nombres }} {{ $postulante->apellidos }}</td>
                    <td>{{ $postulante->dni }}</td>
                    <td>{{ $postulante->edad }}</td>
                    <td>{{ $postulante->departamento_nombre ?? $postulante->departamento }}</td>
                    <td>{{ $postulante->cargo_nombre ?? $postulante->cargo }}</td>
                    <td>{{ \Carbon\Carbon::parse($postulante->fecha_postula)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('entrevistas.evaluar', $postulante->id) }}" class="btn btn-primary btn-sm">
                            Entrevistar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No hay postulantes en proceso.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $postulantes->links() }} {{-- paginación --}}
    </div>
</x-app-layout>