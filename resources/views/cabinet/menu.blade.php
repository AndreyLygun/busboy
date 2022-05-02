@extends('cabinet.template.main')
@section('content')
    <h1>Меню</h1>

    <form>
        <div class="py-3 sticky-top bg-gray-800">
            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle me-2" data-bs-toggle="dropdown">
                Обмен с Excel
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('menu.export') }}" class="dropdown-item">Скачать меню</a>
                <label for="excel-import" class="dropdown-item">Импорт меню...</label>
                <input type="file" class="d-none" id="excel-import" name="excel-import" accept=".xls,.xlsx" onchange="document.forms.importexcel.submit();">
            </div>

            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle me-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Добавить
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a href="#" class="dropdown-item">Раздел...</a>
                <a href="{{route('menu.adddish')}}" class="dropdown-item">Блюдо...</a>
            </div>

            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Действия
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <input class="btn" formaction="/cabinet/" formmethod="post" type="submit" value="Удалить..." />
                <a href="#" class="dropdown-item">Удалить</a>
                <a href="#" class="dropdown-item">Добавить в стоп-лист</a>
                <a href="#" class="dropdown-item">Убрать из стоп-листа</a>
            </div>
        </div>
        <div id="category-list">
            @foreach($menu as $category)
                <div class="row" id="{{$category->id}}">
                    <div class="col-12 category-card">
                        <div class="row ">
                            <div class="col-12 bg-light py-1">
                                <div class="float-start me-2 sorthandle"><i class="bi-list"></i></div>
                                <h3 class="float-start fs-5 mouse-pointer"  onclick="$('#dishes_{{ $category->id}}').slideToggle(500);">
                                    {{$category->fullname}}
                                </h3>
                                <div class="float-end h-100 mx-3">
                                    <input type="checkbox"  value="{{ $category->id }}" class="form-check-input  align-middle"
                                           onchange="$('#dishes_{{ $category->id}} input:checkbox').prop('checked', $(this).prop('checked'));">
                                </div>
                            </div>

                        </div>
                        <div id="dishes_{{ $category->id}}" class="dish-list" style="display: none">
                            @foreach($category->dishes as $dish)
                                @include('cabinet.chunk.dishRow')
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $('#category-list, .dish-list').sortable({
            handle: ".sorthandle",
            opacity: 0.5,
            axis: "y",
            revert: 200,
            stop: function() {
                sortedIDs = $(this).sortable( "toArray" );
                $.post(
                    '{{route('menu.changeorder')}}',
                    {ids: sortedIDs},
                    function(data) {
                        showMsg(data.msg);
                    }
                )
            }
        });
    </script>

@endsection
