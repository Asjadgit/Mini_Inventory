<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Efficient inventory management system">

    <title>{{ config('app.name', 'Mini Inventory') }} - @yield('title', 'Dashboard')</title>

    <!-- Preload critical resources -->
    {{-- <link rel="preload" href="{{ vite_asset('resources/css/app.css') }}" as="style">
    <link rel="preload" href="{{ vite_asset('resources/js/app.js') }}" as="script"> --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    @stack('head')
</head>

<body class="bg-gray-50 text-gray-900 min-h-full flex flex-col">
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded z-50">
        Skip to main content
    </a>

    <!-- Optional header section -->
    @hasSection('header')
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="container mx-auto px-4 py-4">
                @yield('header')
            </div>
        </header>
    @endif

    <!-- Main content wrapper -->
    <main id="main-content" class="flex-grow container mx-auto px-4 py-8">
        <!-- Vue mount point -->
        <div id="app" class="min-h-[50vh]">
            @yield('content')
        </div>
    </main>

    <!-- Optional footer section -->
    @hasSection('footer')
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="container mx-auto px-4 py-6">
                @yield('footer')
            </div>
        </footer>
    @endif

    <!-- Loading state for better UX -->
    <div id="app-loading" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-300">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
            <p class="text-gray-600">Loading {{ config('app.name', 'Mini Inventory') }}...</p>
        </div>
    </div>

    @stack('scripts')

    <!-- Enhanced Vue mounting with error handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading indicator when Vue app is ready
            const hideLoadingIndicator = () => {
                const loadingElement = document.getElementById('app-loading');
                if (loadingElement) {
                    loadingElement.style.opacity = '0';
                    setTimeout(() => {
                        loadingElement.style.display = 'none';
                    }, 300);
                }
            };

            // Mount Vue app with error handling
            const mountVueApp = () => {
                if (window.app && typeof window.app.mount === 'function') {
                    try {
                        window.app.mount('#app');
                        console.log('Vue app mounted successfully');
                        hideLoadingIndicator();
                    } catch (error) {
                        console.error('Failed to mount Vue app:', error);
                        // Show error message to user
                        document.getElementById('app').innerHTML = `
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                <h2 class="text-lg font-medium text-red-800 mb-2">Application Error</h2>
                                <p class="text-red-600">Failed to load the application. Please refresh the page.</p>
                                <button onclick="window.location.reload()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                    Reload Page
                                </button>
                            </div>
                        `;
                        hideLoadingIndicator();
                    }
                } else {
                    // Retry mounting after a short delay if app isn't available yet
                    setTimeout(mountVueApp, 100);
                }
            };

            // Start mounting process
            mountVueApp();

            // Fallback: hide loading indicator after 10 seconds
            setTimeout(hideLoadingIndicator, 10000);
        });
    </script>
</body>

</html>
