<div class="row my-2">
    <div class="col-8 col-xl-8 col-sm-5">
        <p class="mb-0 fw-bold">{{$dish->name}}</p>
        <p class="small">{{ $dish->description }}</p>
    </div>
    <div class="col-2 col-xl-2 col-sm-4"><img class="img-fluid picture-preview" src="/images/{{session('company_id')}}/{{$dish->photo}}"/></div>
    <div class="col-2 col-xl-2 col-sm-3">
        <p>{{$dish->price}} руб</p>
        <button class="btn btn-primary btn-sm">В корзину</button>
    </div>
</div>
