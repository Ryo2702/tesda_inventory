<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Authentication portal')">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Authentication') — {{ config('app.name') }}</title>
    @fonts

</head>
<body class="text-black min-h-screen flex items-center justify-center font-sans selection:bg-indigo-500/30">
    <!-- Clean, minimal background without gradients -->
    <div class="fixed inset-0 pointer-events-none z-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 40px 40px;"></div>

    <div class="relative z-10 w-full max-w-[440px] p-6 mx-auto @yield('container_class')">
        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
