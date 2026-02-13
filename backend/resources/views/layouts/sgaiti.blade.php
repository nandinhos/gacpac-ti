<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SGTI-GAC') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar para Desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-white border-r border-gray-200">
                <div class="flex items-center flex-shrink-0 px-4">
                    <x-application-logo class="w-auto h-8 text-fab-blue" />
                    <span class="ml-2 text-lg font-semibold text-gray-900">SGTI-GAC</span>
                </div>
                <div class="flex flex-col flex-grow mt-8">
                    <nav class="flex-1 px-2 space-y-1">
                        @php
                            $navItems = [
                                ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2z'],
                                ['name' => 'Ativos', 'route' => 'assets.index', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                ['name' => 'Cautelas', 'route' => 'custody.index', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                                ['name' => 'Inventário', 'route' => 'inventory.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                ['name' => 'Setores', 'route' => 'sectors.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                ['name' => 'Usuários', 'route' => 'users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'],
                                // ['name' => 'Relatórios', 'route' => 'reports.index', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                           class="flex items-center px-2 py-2 text-sm font-medium rounded-md group transition-colors {{ request()->routeIs($item['route'] . '*') ? 'bg-blue-50 text-fab-blue' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs($item['route'] . '*') ? 'text-fab-blue' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['name'] }}
                        </a>
                        @endforeach
                </nav>
            </div>
        </div>
    </div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex md:hidden" style="display: none;">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
            
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex flex-col flex-1 w-full max-w-xs bg-white">
                <div class="absolute top-0 right-0 pt-2 -mr-12">
                    <button type="button" class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                        <span class="sr-only">Fechar sidebar</span>
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <x-application-logo class="w-auto h-8 text-fab-blue" />
                        <span class="ml-2 text-lg font-semibold text-gray-900">SGTI-GAC</span>
                    </div>
                    <nav class="mt-5 px-2 space-y-1">
                        @foreach($navItems as $item)
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                           class="flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs($item['route'] . '*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="flex-shrink-0 w-6 h-6 mr-3 {{ request()->routeIs($item['route'] . '*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['name'] }}
                        </a>
                        @endforeach
                    </nav>
                </div>
            </div>
            <div class="flex-shrink-0 w-14"></div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 md:pl-64">
            <!-- Top navigation -->
            <div class="sticky top-0 z-10 flex flex-shrink-0 h-16 bg-white shadow">
                <button type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Abrir sidebar</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex justify-between flex-1 px-4">
                    <div class="flex items-center flex-1">
                        @isset($header)
                            <div class="text-xl font-semibold text-gray-800 leading-tight">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    <div class="flex items-center ml-4 md:ml-6">
                        <!-- Profile dropdown -->
                        <div class="relative ml-3">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button type="button" class="flex items-center max-w-xs text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <span class="sr-only">Menu do usuário</span>
                                        <div class="flex items-center justify-center w-8 h-8 font-medium text-gray-700 bg-gray-300 rounded-full text-sm">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <span class="hidden ml-2 font-medium text-gray-700 md:block">{{ Auth::user()->name }}</span>
                                        <svg class="hidden w-5 h-5 ml-1 text-gray-400 md:block" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Perfil') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Sair') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto">
                <div class="py-6">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
    @stack('scripts')
</body>
</html>
