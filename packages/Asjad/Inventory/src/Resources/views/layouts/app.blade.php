<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Mini Inventory') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">

    <!-- Vue mount point -->
    <div id="app" class="container mx-auto py-10">
        @yield('content')
    </div>

    @stack('scripts')
    <!-- ✅ Mount Vue after all components are registered -->
    <script>
        window.addEventListener('load', () => {
            if (window.app && typeof window.app.mount === 'function') {
                window.app.mount('#app');
            }
        });
    </script>

</body>

</html>
