<table>

@foreach($dish->options() as $section)
    <table>
        <tr>
            <td>
                <label class="me-2" for="option_{{$dish->id}}">{{$section["label"]}}</label>
            </td>
            <td>
                <select name="option_{{$dish->id}}" id="option_{{$dish->id}}" class="form-control dish_options my-2"
                        onchange="alignOptions({{$dish->id}})">
                    @foreach($section["items"] as $item)
                        <option value="{{$item["value"]}}">{{$item["name"]}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>

@endforeach

</table>
