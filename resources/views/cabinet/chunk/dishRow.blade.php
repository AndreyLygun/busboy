<div class="dish-card" id="{{ $dish->id }}" data-id="{{ $dish->id }}" data-price="{{ $dish->price }}"  data-name="[[+pagetitle:htmlent]]">
    <div class="row">
        <div class="col-5">
            <div class="float-start h-100 mx-3 sorthandle"><i class="bi-list"></i></div>
            <p class="fw-bold mb-1 line-2" title="{{ $dish->fullname }}">
                <a href="{{ route('menu.editdish', $dish->id) }}">{{ $dish->fullname }}</a>
            </p>
            <p class="mb-1 pt-0 small">{{ $dish->shortname }}&nbsp;</p>
            <p class="small">
                Доступно:
                @if($dish->hall==1) Зал @endif
                @if($dish->piсkup==1) Самовывоз @endif
                @if($dish->delivery==1) Доставка @endif
            </p>
        </div>
        <div class="col-2 text-right">
            @if($dish->photo != '')
                <div class="mb-3">
                    <img class="img-fluid img-thumbnail" src="/customers/{{ $company_id . '/img/' . $dish->photo }}" alt="{{ $dish->fullname }}">
                </div>
            @endif
        </div>
        <div class="col-2">
            <span class="small line-3">{{ $dish->description }}</span></br>
        </div>

        <div class="col-3">
            <div class="float-end h-100 mx-3">
                <input type="checkbox" name="selected" value="{{ $dish->id }}" class="form-check-input mt-1">
            </div>

            <span class="small fd-bold">Цена: {{ $dish->price }}</span> руб.</br>
            <span class="small">Вес: {{ $dish->size }}</span></br>
            <span class="small">КБЖУ: {{ $dish->kbju }}</span></p>
        </div>
    </div>
    <hr class="mt-0">
</div>
