<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MagangApp') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-green-50">

    <div class="flex items-center justify-center min-h-screen px-4">

        <div
            class="w-full max-w-md p-6 bg-white border border-green-200 shadow-lg rounded-2xl"
        >

            {{-- INI WAJIB ADA --}}
            {{ $slot }}

        </div>

    </div>

</body>
</html>
