@extends('cabinet.template.main')
@section('content')
    @csrf
    <h1>Регистрируем нового пользователя!!!</h1>
    <form method="post" action="{{ route('registerUser') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="form-text text-danger">{{ $message }}</div>
            @else
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" value="123{{ old('password') }}">
            @error('password')
            <div class="form-text text-danger">
                {{ $message }}
            </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection


