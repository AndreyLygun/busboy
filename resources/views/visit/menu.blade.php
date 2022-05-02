@extends('visit.template.main')
@section('content')
    <h1 class="fs-2">Меню для посетителей</h1>

    <div id="menu-sections" class="container sticky-top px-0" style="background: white">
        <div class="row ">
            <div class="col-12 px-1"  id="category_list">
                <ul class="nav nav-pills" role="tablist">
                    @foreach($menu as $category)
                        <li class="nav-item ">
                            <a class="nav-link py-0 px-2 small" href="#cat_{{$category->id}}" role="tab"><span class="small text-nowrap">{{ $category->fullname }}</span></a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row" data-bs-spy="scroll" data-bs-target="#category_list" data-bs-offset="4" >
            @foreach($menu as $category)
                <div class="col-12" id="cat_{{$category->id}}">
                    <h2 class="fs-3 text-secondary" >{{ $category->fullname }}</h2>
                </div>
                @each('visit.chunk.dishRow', $category->dishes, 'dish')
            @endforeach
    </div>

<script>
    function add2cart(id) {
        showWait(1);
        html_id = '#dish_'+id;
        el = document.querySelector(html_id);
        name = el.dataset.name;
        price = el.dataset.price;
        option = el.dataset.optionname
        $.get('/carthandler.html',
            `action=add2cart&name=${name}&option=${option}&price=${price}`,
            function (data) {
                if (data['status']==1)
                    showMsg(data['msg']);
                else showMsg("Извините, произошла ошибка. Обратитесь, пожалуйста, к официанту");
            }
        ).fail(function() {
            showMsg("Извините, произошла ошибка. Обратитесь, пожалуйста, к официанту");
        });
        showWait(0);
    }
</script>



@endsection
