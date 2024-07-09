<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Carhub</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="text-light" style="background-color: #121212;">
  <main class=" w-100 py-4">
    @yield('content')
  </main>
</body>

</html>