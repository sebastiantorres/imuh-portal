<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <div class="container">
    @yield('content')
  </div>
</body>
</html>
