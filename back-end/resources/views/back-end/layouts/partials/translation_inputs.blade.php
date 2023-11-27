@php
$config_langs = config('constants.langs');
@endphp


<table class="table hide" @if(!empty($type))id="translation_table_{{$type}}" @else id="translation_table" @endif>
    <tbody>
        @foreach ($config_langs as $key => $lang)
            <tr>

                <td> <input class="form-control translations" type="text" name="translations[{{ $key }}][{{ $attribute }}]"
                        value="@if (!empty($translations[$key][$attribute])) {{ $translations[$key][$attribute] }} @endif"
                        placeholder="{{ $lang['full_name'] }}"></td>
            </tr>
        @endforeach
    </tbody>
</table>
