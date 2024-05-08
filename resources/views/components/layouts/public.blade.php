<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    {{ seo()->render() }}

    @stack('head')

    @livewireStyles
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="">
    {{ $slot }}

    @livewireScriptConfig
    @filamentScripts
    @stack('scripts')
  </body>
</html>
