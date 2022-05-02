@extends('cabinet.template.main')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="mt-3 ms-1">Редактируем блюдо</h2>
    <form action="{{route('menu.updatedish', ['dish'=>$dish->id])}}" method="post" name="dishForm" id="dishForm" enctype="multipart/form-data">
        @csrf
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="dish-main-props" data-bs-toggle="tab" data-bs-target="#tab-pane-main" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Основные</button>
                <button class="nav-link" id="dish-additional-props" data-bs-toggle="tab" data-bs-target="#tab-pane-additional" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Дополнительные</button>
            </div>
        </nav>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active pt-2" id="tab-pane-main" role="tabpanel" aria-labelledby="dish-main-props">
                @include('cabinet.chunk.form-checkbox', ['key'=>'hide', 'label'=>'Стоп-лист', 'value'=>$dish->hide])
                @include('cabinet.chunk.form-input', ['key'=>'fullname', 'label'=>'Название блюда', 'type'=>'text', 'value'=>$dish->fullname])
                @include('cabinet.chunk.form-input', ['key'=>'shortname', 'label'=>'Краткое название блюда', 'type'=>'text', 'value'=>$dish->shortname])
                @include('cabinet.chunk.form-select', ['key'=>'shortname', 'label'=>'Категория', 'items'=>$categories, 'selected'=>$dish->category_id])
                <div class="row">
                    <div class="col-4 py-2">
                        <div class="form-check-label">Доступно</div>
                    </div>
                    <div class="col-auto">
                        @include('cabinet.chunk.form-checkbox', ['key'=>'pickup', 'label'=>'для самовывоза', 'value'=>$dish->pickup])
                    </div>
                    <div class="col-auto">
                        @include('cabinet.chunk.form-checkbox', ['key'=>'delivery', 'label'=>'для доставки', 'value'=>$dish->delivery])
                    </div>
                </div>
                @include('cabinet.chunk.form-input', ['key'=>'price', 'label'=>'Цена в зале', 'type'=>'number', 'value'=>$dish->price])
                @include('cabinet.chunk.form-input', ['key'=>'out_price', 'label'=>'Цена в доставке', 'type'=>'number', 'value'=>$dish->out_price])

                <div class="row">

                    <div class="col-6">
                        @include('cabinet.chunk.form-textarea', ['key'=>'description', 'label'=>'Описание', 'value'=>$dish->description])
                    </div>

                    <div class="col-6">
                        @include('cabinet.chunk.form-image', ['key'=>'photo', 'label'=>'Фотография', 'img'=>'/customers/'. auth()->user()->company_id.'/img/'.$dish->photo])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade pt-2" id="tab-pane-additional" role="tabpanel" aria-labelledby="dish-main-props">
                @include('cabinet.chunk.form-input', ['key'=>'article', 'label'=>'Артикул', 'type'=>'text', 'value'=>$dish->article])
                @include('cabinet.chunk.form-textarea', ['key'=>'options', 'label'=>'Ценовые модификаторы', 'value'=>$dish->options])
                @include('cabinet.chunk.form-input', ['key'=>'size', 'label'=>'Размер/объем порции', 'type'=>'text', 'value'=>$dish->size])
                @include('cabinet.chunk.form-input', ['key'=>'kbju', 'label'=>'КБЖУ', 'type'=>'text', 'value'=>$dish->kbju])
            </div>
        </div>
        <div class="my-3">
            <input type="submit" role="button" class="btn btn-primary mb-2 mr-5" value="Сохранить">
            <a role="button" class="btn btn-primary mb-2" href="{{route('cabinet.menu')}}">Закрыть</a>
        </div>
    </form>
@endsection



