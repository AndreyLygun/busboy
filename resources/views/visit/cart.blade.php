@extends('visit.template.main')
@section('content')
    <h1 class="fs-2">Корзина</h1>
    @if(count($cart))
        <table class="table" id="cart">
            @foreach($cart as $i=>$dish)
                <tr id="cart_{{$i}}" class='cartItem' data-price="{{$dish['price']}}">
                    <td width="60%">{{ $dish['name'] }} @isset($dish['option'])({{$dish['option']}})@endif</td>
                    <td width="30%">{{ $dish['price'] }} руб</td>
                    <td width="10%"><a href="#" onclick="removeCartItem('{{$i}}'); return false;">x</a></td>
                </tr>
            @endforeach
            <tr class="fw-bold"><td>Итого</td><td><span id="cartSum"></span> руб</td></tr>
        </table>
        <button class="btn btn-primary" onclick="sendOrder();">Отправить заказ</button>
    @else
        <p>Сейчас корзина пуста. Чтобы сделать заказ, добавьте блюда в корзину из нашего <a href="{{ route('visit.menu') }}">меню</a></p>
    @endif



    @if(count($ordered))
    <h1 class="fs-2">Было заказано</h1>
    <table class="table" id="ordered">
        @foreach($ordered as $i=>$dish)
            <tr ="ordered_{{$i}}" class='orderedItem' data-price={{$dish['price']}}>
                <td>{{ $dish['name'] }}</td>
                <td>{{ $dish['price'] }} руб</td>
            </tr>
        @endforeach
        <tr class="fw-bold"><td >Итого</td><td><span id="orderedSum"></span> руб</td></tr>
    </table>
    @endif

    <script>
        function calcCart() {
            summ = 0;
            $('#cart .cartItem').each(function () {
                summ += parseFloat(this.dataset.price);
            })
            $('#cartSum').html(summ);
            summ = 0;
            $('#ordered .orderedItem').each(function () {
                summ += parseFloat(this.dataset.price);
            })
            $('#orderedSum').html(summ);

        }

        function removeCartItem(key) {
            $.get(
                '/carthandler.html',
                `action=removeCartItem&key=${key}`,
                function () {
                    $('#cart_'+key).hide(
                        1000,
                        function() {
                            $(this).remove();
                            calcCart();
                        }

                    )
                }
                )
        }
        calcCart();
    </script>

@endsection



