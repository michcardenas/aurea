@extends('layouts.app')

@section('title', 'Pedido confirmado | Nuvion Glass')

@section('content')
    <section class="py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 mx-auto bg-success/10 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m4.5 12.75 6 6 9-13.5"/>
                </svg>
            </div>
            <h1 class="mt-6 font-brand text-3xl font-bold text-text-dark">¡Pedido confirmado!</h1>
            <p class="mt-4 text-gray-700">Gracias por tu compra. Recibirás un correo de confirmación con los detalles de tu pedido.</p>
            <a href="{{ route('home') }}" class="mt-8 inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                Volver al inicio
            </a>
        </div>
    </section>
@endsection
