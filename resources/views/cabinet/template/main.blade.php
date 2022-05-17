<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Busboy.ru</title>


    <link href="/css/tabler.min.css?1652048814" rel="stylesheet"/>
    <link href="/css/tabler-flags.min.css?1652048814" rel="stylesheet"/>
    <link href="/css/tabler-payments.min.css?1652048814" rel="stylesheet"/>
    <link href="/css/tabler-vendors.min.css?1652048814" rel="stylesheet"/>
    <li-nk href="/css/demo.min.css?1652048814" rel="stylesheet"/>


    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style2.css" rel="stylesheet">

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/script.js"></script>



</head>
<body>
    @include('cabinet.chunk.navbar')
    <div class="container">
        <div class="raw">
            <div class="col-12 py-5 ps-2">
                @yield('content')
            </div>
        </div>
    </div>
    <div id="loader" class="loader"></div>
    <div class="container fixed-bottom">
        <div id="liveToast" class="toast mb-4 ml-4" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: lightgoldenrodyellow">
            <div class="toast-body" id="toast-message">
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <script>
            showMsg("{{session('message')}}")
        </script>
    @endif

</body>
</html>
