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
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center overflow-hidden relative font-sans">
    <!-- Background effects -->
    <div class="fixed rounded-full blur-[80px] opacity-40 pointer-events-none z-0 w-[500px] h-[500px] bg-[radial-gradient(circle,rgba(99,102,241,0.3),transparent_70%)] -top-[15%] -left-[10%] animate-[pulse_20s_ease-in-out_infinite]"></div>
    <div class="fixed rounded-full blur-[80px] opacity-40 pointer-events-none z-0 w-[400px] h-[400px] bg-[radial-gradient(circle,rgba(139,92,246,0.25),transparent_70%)] -bottom-[10%] -right-[5%] animate-[pulse_20s_ease-in-out_infinite_reverse]"></div>
    <div class="fixed rounded-full blur-[80px] opacity-40 pointer-events-none z-0 w-[300px] h-[300px] bg-[radial-gradient(circle,rgba(59,130,246,0.2),transparent_70%)] top-[50%] left-[60%] animate-[pulse_15s_ease-in-out_infinite]"></div>
    
    <div class="fixed inset-0 pointer-events-none z-0" style="background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 60px 60px;"></div>

    <div class="relative z-10 w-full max-w-[440px] p-6 mx-auto @yield('container_class')">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[20px] p-8 sm:p-10 relative overflow-hidden transition-all duration-500 opacity-0 translate-y-5 animate-card-enter @yield('card_class')">
            <!-- Top accent line -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[60%] h-[2px] bg-gradient-to-r from-transparent via-indigo-500 to-violet-500 rounded-sm"></div>
            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
