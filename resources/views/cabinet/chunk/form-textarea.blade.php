
<div id="{{$key}}_container" class="{{isset($class)?$class:''}}">
    <label for="{{$key}}" class="col-form-label">{{$label}}</label>
    <textarea type="text" class="form-control" name="{{$key}}" id="{{$key}}" @isset($rows) rows="{{$rows}}"@endif>{{$value}}</textarea>
</div>
