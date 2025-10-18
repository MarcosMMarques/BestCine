<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BestCine🍿</title>

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-slate-950 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        @include('layouts.partials.header')

        <main class="flex-grow">
            @yield('content')
        </main>

        @include('layouts.partials.footer')

    </div>

</body>
</html>
