@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cyan-400 via-cyan-900 to-cyan-700 py-8">
    {{-- Botón volver --}}
    <a href="{{ route('dashboard') }}"
        class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-medium">Volver al Dashboard</span>
    </a>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        {{-- Encabezado --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800">Evaluar Postulante</h1>
            <p class="text-gray-600 mt-1">Evalue al postulante</p>
        </div>
    </div>
</div>

@endsection