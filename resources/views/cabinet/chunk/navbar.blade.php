<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Busboy.ru</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('cabinet.getSettings') }}">Настройки</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('cabinet.menu') }}">Меню</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('cabinet.places') }}">Столы/QR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('cabinet.staff') }}">Персонал</a>
                </li>
            </ul>
            @guest()
                <a href="#" class="dropdown-item mx-2" data-bs-toggle="modal" data-bs-target="#login">
                    Зарегистрироваться
                </a>
            <a href="#" class="mx-2" data-bs-toggle="modal" data-bs-target="#login">
                Войти
            </a>
            <div class="modal fade" id="login" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Авторизация</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="/login">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" id="email" type="email" name="email" required="required" autofocus="autofocus">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Пароль</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="mb-3 form-check">
                                    <input class="form-check-input" id="remember" type="checkbox"  name="remember">
                                    <label class="form-check-label" for="remember">Запомнить меня</label>
                                </div>
                                <div class="mb-3">
                                    <div class="flex items-center justify-end mt-4">
                                        @if (Route::has('password.request'))
                                            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Войти</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endguest
            @auth()
                {{ auth()->user()->email }}

            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">Выйти</button>
            </form>
            @endauth

        </div>
    </div>
</nav>
