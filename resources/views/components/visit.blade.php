<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME') }}</title>

    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Styles -->
    <style>

    </style>

</head>
<body>
    <div class="container">
        <div class="row">
            <div clas="col">
                <h1>{{ env('APP_NAME') }}</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div clas="col">
                {{ $slot }}
            </div>
        </div>
    </div>
    <div class="container fixed-bottom">
        <div class="row">
            <div clas="col">
                <div class="btn-group btn-group-lg w-100">
                    <a href="/menu/" class="btn btn-primary" {{}}>Меню</a></button>
                    <a href="/cart/" class="btn btn-primary">Корзина</a></button>
                    <a href="/waiter/" class="btn btn-primary">Официант</a></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
