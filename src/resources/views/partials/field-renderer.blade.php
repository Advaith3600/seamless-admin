@php $is_textarea = in_array($column->Type, ['tinytext', 'text', 'mediumtext', 'longtext']); @endphp

<div class="flex flex-col {{ $is_textarea ? 'col-span-2' : '' }}">
    <label
        for="{{ $column->Field }}"
        class="mb-1 font-semibold"
    >
        {{ str($column->Field)->ucfirst() }}
    </label>

    @if($is_textarea)
        <textarea
            rows="6"
            class="input"
            id="{{ $column->Field }}"
            name="{{ $column->Field }}"
            placeholder="{{ $column->Type }}"
            {{ $column->Null === 'NO' ? 'required' : '' }}
        >{{ old($column->Field) ?? $data[$column->Field] ?? '' }}</textarea>
    @elseif(str_starts_with($column->Type, 'enum'))
        <select class="input" id="{{ $column->Field }}" name="{{ $column->Field }}">
            @if($column->Null === 'YES') <option value="">NULL</option> @endif
            @php preg_match_all("/\\'(\\w+)\\'/", $column->Type, $matches); @endphp
            @foreach ($matches[1] as $option)
                <option
                    value="{{ $option }}"
                    {{ (old($column->Field) ?? $data[$column->Field] ?? '') === $option ? 'selected' : '' }}
                >
                    {{ str($option)->ucfirst() }}
                </option>
            @endforeach
        </select>
    @else
        @php
            $type = 'text';
            if ($column->Type === 'date') $type = 'date';
            else if ($column->Type === 'time') $type = 'time';
            else if ($column->Type === 'datetime') $type = 'datetime-local';
        @endphp
        <input
            class="input"
            type="{{ $type }}"
            id="{{ $column->Field }}"
            name="{{ $column->Field }}"
            placeholder="{{ $column->Type }}"
            value="{{ old($column->Field) ?? $data[$column->Field] ?? '' }}"
        />
    @endif
</div>
