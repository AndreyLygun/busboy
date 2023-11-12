<div class="row my-2">
    <div class="col-4">
        <p class="mb-0 fw-bold">{{$dish->name}}</p>
    </div>
    <div class="col-4">
        <p class="small">{{ $dish->description }}</p>
    </div>
    <div class="col-2">
        <img class="img-fluid picture-preview" src="/images/{{session('company_id')}}/{{$dish->photo}}"/>
    </div>
    <div class="col-2">
        <button class="btn btn-primary btn-block">
            {{$dish->price}}&nbsp;руб
        </button>
    </div>
</div>
