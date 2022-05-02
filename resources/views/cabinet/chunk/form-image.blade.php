<div class="form-group">
    <div class="col-form-label">
        {{$label}}
        <a href="#""><label class="ms-3" for="{{$key}}" style="">Загрузить...</label></a>
        <a href="#" id="clear_{{$key}}_btn" class="ms-3" onclick="clearFile(this); return false;" style="">Очистить</a>
    </div>
    <div class="ratio ratio-21x9" style="position: relative; width: 100%; height:100%; bottom:0px; border: 1px solid gray;">
        <label id="label_{{$key}}" for="{{$key}}" style="background-image: url('{{$img}}'); background-position: center; background-repeat: no-repeat; background-size: contain"></label>
        <input type="hidden" id="{{$key}}_clear" name="{{$key}}_clear" value="0">
        <input type="file" accept="image/*" class="form-control-file" id="{{$key}}" name="{{$key}}" onchange="fileChanged()">
        <div style="position: absolute; padding: 10px; left: 10px; bottom: 10px; padding: 5px 10px; visibility: visible">
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
            $("#clear_{{$key}}_btn").show(500);
        }
    }

    function clearFile(btn) {
        {{$key}}.value = '';
        $("#clear_{{$key}}_btn").hide(500);
        label_{{$key}}.style.backgroundImage='none';
        $('#{{$key}}_clear').val(1);
    }
</script>
