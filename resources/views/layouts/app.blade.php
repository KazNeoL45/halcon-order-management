<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Halcon </title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Bootstrap & FontAwesome for legacy views -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            <div class="flex">
                <!-- Sidebar -->
                <aside class="w-64 bg-white border-r">
                    <nav class="mt-6 space-y-1">
                        <x-responsive-nav-link class="text-black" :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link class="text-black" :href="route('orders.index')"
                        :active="request()->routeIs('orders.*')">
                            {{ __('Orders') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link class="text-black" :href="route('products.index')"
                        :active="request()->routeIs('products.*')">
                            {{ __('Products') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link class="text-black" :href="route('clients.index')"
                        :active="request()->routeIs('clients.*')">
                            {{ __('Clients') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link class="text-black" :href="route('users.index')"
                            :active="request()->routeIs('users.*')">
                            {{ __('Users') }}
                        </x-responsive-nav-link>
                    </nav>
                </aside>

                <!-- Main Content -->
                <div class="flex-1">
                    <main class="p-6 text-gray-900">
                        @hasSection('content')
                            @yield('content')
                        @else
                            {{ $slot }}
                        @endif
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
