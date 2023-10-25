<!DOCTYPE html>
<html class="blook-null" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }}</title>

  <!-- Loading alpine via CDN. -->
  <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>

<div class="h-[50svh] w-full flex flex-col items-center justify-center">
    <div>
        <h1 class="font-semibold text-3xl">{{ config('blook.title') }}</h1>
        <pre class="text-gray-400 text-xs mt-2">Powered by Blook {{ Raitone\Blook\Blook::VERSION }}</pre>
    </div>
</div>


</body>

</html>