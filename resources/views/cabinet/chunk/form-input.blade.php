@if(isset($inrow))
    <div class="row {{isset($class)?$class:''}}">
        <div class="col-12 col-md-4">
            <label for="{{$key}}" class="form-label mt-1 mb-0">{{isset($label)?$label:''}}</label>
        </div>
        <div class="col-12 col-md-8">
            <input type="{{$type}}" class="form-control"  id="{{$key}}" name="{{$key}}" value="{{$value}}" {{isset($attributes)?$attributes:''}}>
            @error($key)
                <div class="form-text text-danger">
                    {{$message}}
                </div>
            @enderror
        </div>
    </div>
@else
    <div class="my-3">
        @isset($label)
            <label for="{{$key}}" class="form-label">{{$label}}</label>
        @endisset
        <input type="{{$type}}" class="form-control" id="{{$key}}" placeholder="{{isset($placeholder)?$placeholder:''}}" value="{{$value}}" {{isset($attributes)?$attributes:''}}>
        @error($key)
            <div class="form-text text-danger">
                {{$message}}
            </div>
        @enderror
    </div>
@endif


