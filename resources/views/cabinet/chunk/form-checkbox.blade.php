<div class="form-check {{isset($class)?$class:''}}">
    <input type="hidden" name="{{$key}}" value="0">
    <input type="checkbox" name="{{$key}}" id="{{$key}}" class="form-check-input" {{isset($checked)?'checked':''}} value="1">
    <label for="{{$key}}" class="form-check-label">{{$label}}</label>
</div>
