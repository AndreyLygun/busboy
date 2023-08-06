<div class="accordion" id="accordionBook">
    @foreach($menu['categories'] as $category)
        <div class="accordion-item">
            <h2 class="accordion-header" id="{{'categoryTitle_'.$category['id'] }} data-category={{$category['id'] }}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#category_{{ $category['id'] }}" aria-expanded="true" aria-controls="collapseOne">
                    {{ $category['name'] }}
                </button>
            </h2>
            <div id="{{ 'category_'.$category['id'] }}" class="accordion-collapse collapse" aria-labelledby="category-{{ $category['id'] }}" data-bs-parent="#accordionBook">
                <div class="accordion-body">
                    <div class="col-12">
                        {{
                            \Orchid\Screen\Actions\Link::make('Добавить блюдо')->icon('plus')->route('platform.dish', ['category'=>$category['id']])
                        }}
                    </div>
                    <div class="col-12">
                        @foreach($category['dishes'] as $dish)
                            <div class="dish row" id="{{$dish['id']}}">
                                <hr>
                                <div class="col-8 col-md-5">
                                    <table border=0>
                                        <tr>
                                            <td valign='top' class='sorthandle' title="В пределах одного раздела можно менять порядок блюд мышкой">

                                            </td>
                                            <td valign='top'>
                                                <div class="mx-2">
                                                    <a href="{{ route('platform.dish', ['id'=>$dish['id']])  }}" title="Редактировать блюдо">{{ $dish['name'] }}</a>
                                                </div>
                                                <div class="mx-2 small text-secondary">
                                                    #{{ $dish['id'] }}
                                                    {{ $dish['shortname'] }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="d-none d-md-block col-2">
                                    <img src='/images/{{ session('company_id') }}/{{ $dish['photo'] }}' title="{{ $dish['photo'] }}" class='img-fluid'>
                                </div>
                                <div class="d-none d-md-block col-2">
                                    <span class="small text-secondary line-3 compressed-line-height" id="content_[[+id]]">{{ $dish['description'] }}</span>
                                </div>
                                <div class="col-3 col-md-2">
                                    <span id="price_[[+id]]">{{ $dish['price'] }}</span> <span class='small font-weight-bold'></span> руб.<br>
                                    <span class="small text-secondary line-1" id="volume_[[+id]]">{{ $dish['size'] }} </span>
                                    <span class="small text-secondary line-1" id="kbju_[[+id]]">{{ $dish['kbju'] }} </span>
                                </div>
                                <div class="col-1">
                                    {{ \Orchid\Screen\Actions\Button::make('удалить')->icon('trash')->confirm('Удалить блюдо?')->method('deleteDish', ['id'=>$dish['id']]) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<script src="/js/jquery.sortable.min.js"></script>
<script>
    function saveOrder() {
        alert('Yes!');
    }
   $('#accordionBook').sortable({bind: 'saveOrder'});
</script>
