@once
    @push('scripts')
        <script>
            function alignOptions(id) {
                select = document.getElementById('option_'+id)
                target = document.getElementById('dish_'+id);
                price = parseFloat(target.dataset.baseprice) + parseFloat(select.value);
                optionName = select.options[select.selectedIndex].text;
                target.dataset.price = price;
                target.dataset.optionname = optionName;
                $("#dish_"+id+" .dish-price").html(price)
                $('#dish_'+id+ ' .option-value').html("Варианты: " + optionName);
            }

        </script>
    @endpush
@endonce


<div class="dish_card" id="dish_{{$dish->id}}" data-id="{{$dish->id}}"
     data-baseprice="{{$dish->price}}" data-price="{{$dish->price}}" data-name="{{$dish->fullname}}" data-optionname="">
    <hr>
    <div class="row">
        <div class="col-7 mouse-pointer"  data-bs-toggle="modal" data-bs-target="#modal_{{$dish->id}}">
            <p class="line-2 fw-bold mb-1 mt-0">{{$dish->fullname}}</p>
            <div class="dish-description small">
                {{$dish->description}}
            </div>
        </div>
        <div class="col-5 text-right">
            @isset($dish->photo)
                <div  class="mouse-pointer" data-bs-toggle="modal" data-bs-target="#modal_{{$dish->id}}">
                    <img class="mt-n1 img-fluid img-thumbnail" src="/customers/demo/img/{{$dish->photo}}" alt="{{$dish->fullname}}">
                </div>
            @endisset
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-7 mouse-pointer"  data-bs-toggle="modal" data-bs-target="#modal_{{$dish->id}}">
            <div class="mt-2 small fw-bold text-primary option-value">

            </div>
        </div>
        <div class="col-5 text-right">
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-primary btn-block btn-sm mb-0" onclick="add2cart({{$dish->id}})">
                    <i class="bi bi-cart"></i>
                    <span class="dish-price">{{$dish->price}}</span> руб.
                </button>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modal_{{$dish->id}}" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{$dish->fullname}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @isset($dish->photo)
                        <img src="/customers/demo/img/{{$dish->photo}}" class="img-fluid">
                    @endisset
                    <div>{{$dish->description}}</div>
                    <div class="my-3 pr-4 dish_options">
                        @include('visit.chunk.dish_options')
                    </div>

                    @isset($dish->volume) <div>Размер порции: {{$dish->volume}}</div> @endisset
                    @isset($dish->kbju) <div>Пищевая ценность: {{$dish->kbju}}</div>@endisset
                </div>
                <div class="modal-footer">
                    <div class="col text-left pl-0">
                        <button type="button" class="btn btn-outline-primary btn-block" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                    <div class="col text-right" >
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-block"  onclick="add2cart({{$dish->id}})">
                                <span  class="p-0 align-top"></span>
                                <span class="dish-price">{{$dish->price}}</span>  руб.
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


