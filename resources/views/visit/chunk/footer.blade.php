<div id="loader" class="loader"></div>
{{ session()->flash('success') }}
<div class="container fixed-bottom">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: lightgoldenrodyellow">
        <div class="toast-body" id="toast-message">
        </div>
    </div>
    <div class="row">
        <div class="col  px-0">
            <div class="btn-group" role="group" style="width:100%; border-top: 5px solid white">
                <a href="{{ route('visit.about') }}" class="btn btn-primary btn-lg" style="border-radius: 0px"><small>О ресторане</small></a>
                <a href="{{ route('visit.menu') }}" class="btn btn-primary btn-lg" style="border-radius: 0px"><small>Меню</small></a>
                <a href="{{ route('visit.cart') }}" class="btn btn-primary btn-lg" style="border-radius: 0px"><small>Корзина</small></a>
                <a href="{{ route('visit.waiter') }}" class="btn btn-primary btn-lg" style="border-radius: 0px"><small>Официант</small></a>
            </div>
        </div>
    </div>
</div>
