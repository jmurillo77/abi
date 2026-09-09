<x-app-layout>
    <link rel="stylesheet" href="/css/dashboard.css">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container">
                    <div class="card-section" id="card_001">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                {{-- <a href="{{ route('listaPedido.listaPedidos') }}"> --}}
                                <a href="#">
                                    <img src="img\listaPedidos.png" alt="...">
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="card-section" id="card_002">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                {{-- <a href="{{ route('listaPedido.formulario') }}"> --}}
                                <a href="#">
                                    <img src="img\guardarPedido.png" alt="...">
                                </a>
                            </div>
Aqui otro Menu

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
