<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
        <script src="/vendor/bootstrap/js/bootstrap.js"></script>
        <script src="/vendor/vue/vue.js"></script>

    </head>
    <body>
    <div   id="app">
        <div class="container">
            <div id="dish_12" class="row my-2">
                <div class="col-6" id>
                    <span id="dish_name_12">Первое</span><br/>
                    <span id="dish_option_12" class="small">Суп</span>
                </div>
                <div class="col-4" >
                    <span id="dish_price_12" class="fw-bold">100</span>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary" @click="addItem(12)" >+</button>
                </div>
            </div>
            <div id="dish_13" class="row my-2">
                <div class="col-6" id>
                    <span id="dish_name_13">Второе</span><br/>
                    <span id="dish_option_13" class="small">Котлеты</span>
                </div>
                <div class="col-4" >
                    <span id="dish_price_13" class="fw-bold">50</span>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary" @click="addItem(13)" >+</button>
                </div>
            </div>
            <div id="dish_14" class="row my-2">
                <div class="col-6" id>
                    <span id="dish_name_14">Первое</span><br/>
                    <span id="dish_option_14" class="small">Суп</span>
                </div>
                <div class="col-4" >
                    <span id="dish_price_14" class="fw-bold">100</span>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary" @click="addItem(14)" >+</button>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Заказ</h2>
                    <p v-if="order.length==0">
                        Добавьте блюда в заказ
                    </p>
                    <p v-else>
                        @{{ orderSumm }} руб, @{{ order.length }} блюд
                    </p>
                </div>
            </div>
            <div class="row my-2" v-for="(item, index) in order">
                <div class="col-6">
                    <span>@{{item.name}}</span><br>
                    <span class="small">@{{item.option}}</span>
                </div>
                <div class="col-4">
                    @{{item.price}}
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary" @click="removeItem(index)">-</button>
                </div>
            </div>
        </div>
    </div>
    </body>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            order: []
        },
        computed: {
            orderSumm: function() {
                return this.order.reduce((sum, item) => sum + parseFloat(item.price), 0);
            },
        },
        created() {
            if (order = localStorage.getItem('order')) {
                this.order = JSON.parse(order)
            } else this.order = [];
        },
        methods : {
            addItem(id) {
                name = document.getElementById('dish_name_'+id).innerHTML;
                option = document.getElementById('dish_option_'+id).innerHTML;
                price = document.getElementById('dish_price_'+id).innerHTML;
                this.order.push({'name': name, 'option': option, 'price': price});
                window.localStorage.setItem('order', JSON.stringify(this.order));
            },
            removeItem(index) {
                app.order.splice(index, 1);
                window.localStorage.setItem('order', JSON.stringify(this.order));
            },
            test(delta, event) {
                alert(event.currentTarget.dataset.price);
            }
        }
    })
</script>
</html>
