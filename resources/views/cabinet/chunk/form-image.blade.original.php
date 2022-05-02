<div class="form-group" style="position: relative;">
    <div class="col-form-label">{{$label}}</div>
    <div style="position: relative; width: 100%; bottom:0px; border: 1px solid gray;"
        sty-le="position: relative; width: 100%; bottom:0px; border: 1px solid gray;">
        <label id="label_{{$key}}" for="{{$key}}" class="image-input-label small" style="background-image: url('{{$img}}')"></label>
        <input type="hidden" id="{{$key}}_clear" name="{{$key}}_clear" value="0">
        <input type="file" accept="image/*" class="form-control-file" id="{{$key}}" name="{{$key}}">
        <div style="position: absolute; padding: 10px; left: 10px; bottom: 10px; padding: 5px 10px; visibility: visible">
            <label class="btn btn-secondary" for="{{$key}}" style="">Загрузить...</label>
            <button id="clear_{{$key}}_btn" class="btn btn-secondary" onclick="clearFile(this); return false;" style="">Очистить</button>
        </div>
    </div>
</div>

<script>
    function fileChanged() {
        const file = {{$key}}.files[0]
        if (file) {
            src = URL.createObjectURL(file)
            label_{{$key}}.style.backgroundImage='url('+src+')';
            $('#{{$key}}_clear').val(0);
            $("#clear_{{$key}}").show(500);
        }
    }

    function clearFile(btn) {
        {{$key}}.value = '';
        $("clear_{{$key}}_btn").hide(500);
        label_{{$key}}.style.backgroundImage='none';
        $('#{{$key}}_clear').val(1);
    }

    {{$key}}.onchange = fileChanged;
</script>
