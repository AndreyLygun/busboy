@extends('cabinet.template.main')
@section('content')

    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#description', // Replace this CSS selector to match the placeholder element for TinyMCE
            plugins: 'code table lists',
            toolbar: 'undo redo | formatselect| bold italic fontname forecolor fontsize | alignleft aligncenter alignright | indent outdent | bullist numlist | table',
            language: 'ru',
            setup: function (editor) {

            }
        });
    </script>

    <form method="post" action="{{route('cabinet.saveSettings')}}" onsubmit="tinyMCE.triggerSave()">
        @csrf
        <h2 class="text-primary fs-4">Информация о ресторане</h2>
        @include('cabinet.chunk.form-input', ['key'=>'name', 'label'=>'Название ресторана', 'type'=>'text', 'value'=>$company->name, 'class'=>"my-2"])
        @include('cabinet.chunk.form-input', ['key'=>'email', 'label'=>'Электронная почта', 'type'=>'email', 'value'=>'ok@busboy.ru', 'class'=>"my-2"])
        <div class="row my-2">
            <div class="col-12 col-md-4">
                <label class="form-label mt-1 mb-0">Адрес сайта</label>
            </div>
            <div class="col-12 col-md-8">
                <a href="http://{{$company->id}}.busboy.test">{{$company->id}}.busboy.ru</a>
            </div>
        </div>

        <h2 class="text-primary fs-4 mt-4">Доставка и самовывоз</h2>
        @include('cabinet.chunk.form-checkbox', ['key'=>'hasDelivery', 'label'=>'Есть доставка', 'value'=>$company->hadDelivery, 'class'=>'mt-2 mb-0'])
        @include('cabinet.chunk.form-textarea', ['key'=>'deliveryTerm', 'label'=>'Условия доставки', 'value'=>$company->deliveryTerm, 'rows'=>'3', 'class'=>$company->hasDelivery?'':'d-none'])
        @include('cabinet.chunk.form-checkbox', ['key'=>'hasPickup', 'label'=>'Есть самовывоз', 'value'=>$company->hadDelivery, 'class'=>"mt-2"])
        @include('cabinet.chunk.form-textarea', ['key'=>'pickupTerm', 'label'=>'Условия самовывоза', 'value'=>$company->deliveryTerm, 'rows'=>'3', 'class'=>$company->hasPickup?'':'d-none'])
        <script>
            document.getElementById('hasDelivery').onchange = function () {
                if (this.checked) {
                    $('#deliveryTerm_container').show(500);
                } else {
                    $('#deliveryTerm_container').hide(500);
                }
            }
        </script>
        @include('cabinet.chunk.form-checkbox', ['key'=>'hasCardPayment', 'label'=>'Есть оплата картами', 'value'=>$company->hasCardPayment, 'class'=>"my-2"])

        <h2 class="text-primary fs-4">Оформление сайта</h2>
        @include('cabinet.chunk.form-image', ['key'=>'image', 'label'=>'Изображение для шапки сайта', 'img'=>'/customers/'. auth()->user()->company_id .'/' . $company->image])

        @include('cabinet.chunk.form-textarea', ['key'=>'description', 'label'=>'Описание ресторана', 'value'=>$company->description, 'class'=>''])
        <input type="submit" class="btn btn-primary mt-4" value="Сохранить">
    </form>
@endsection
