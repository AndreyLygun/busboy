<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Busboy.ru</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/bootstrap.bundle.min.js"></script>
</head>
<body>

{{ session()->get('success') }}
    <div class="container">
        @include('promo.chunk.navbar')
    </div>
    <div class="container">
        <div class="raw">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>
