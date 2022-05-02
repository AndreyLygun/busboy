<div class="row my-2">
    <div class="col-4">
        <label class="form-label" for="{{$key}}">{{$label}}</label>
    </div>
    <div class="col-8">
        <select name="category_id" class="form-control">
            @foreach($items as $item)
                <option value="{{$item->id}}" {{$selected==$item->id?'selected':''}}>{{$item->fullname}}</option>
            @endforeach
        </select>
    </div>
</div>
