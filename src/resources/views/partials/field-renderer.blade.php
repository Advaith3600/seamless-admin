@php $is_textarea = in_array(strtolower($column->type), ['tinytext', 'text', 'mediumtext', 'longtext']); @endphp

<div class="flex flex-col {{ $is_textarea ? 'md:col-span-2' : '' }} gap-1">
    <sa-label for="{{ $column->field }}">
        {{ $column->field }}<span class="text-red-500 text-sm">@if(!$column->is_null)*@endif</span>
    </sa-label>

    @if($is_textarea)
        <sa-textarea
            rows="6"
            id="{{ $column->field }}"
            name="{{ $column->field }}"
            placeholder="{{ $column->type }}"
            {{ $column->is_null ? '' : 'required' }}
            model-value="{{ old($column->field) ?? $data[$column->field] ?? '' }}"
        />
    @elseif(str_starts_with($column->type, 'enum'))
        <select
            class="input"
            id="{{ $column->field }}"
            name="{{ $column->field }}"
            {{ $column->is_null ? '' : 'required' }}
        >
            @if($column->is_null) <option value="">NULL</option> @endif
            @php preg_match_all("/\\'(\\w+)\\'/", $column->type, $matches); @endphp
            @foreach ($matches[1] as $option)
                <option
                    value="{{ $option }}"
                    {{ (old($column->field) ?? $data[$column->field] ?? '') === $option ? 'selected' : '' }}
                >
                    {{ Str::ucfirst($option) }}
                </option>
            @endforeach
        </select>
    @elseif($column->foreign)
        <foreign-selection
            v-cloak
            type="{{ $column->type }}"
            field="{{ $column->field }}"
            column_name="{{ $column->foreign->column_name }}"
            default="{{ old($column->field) ?? $data[$column->field] ?? '' }}"
            referenced_table_name="{{ $column->foreign->referenced_table_name }}"
            referenced_column_name="{{ $column->foreign->referenced_column_name }}"
            api_endpoint="{{ route('api.admin.type.search_foreign_references', request()->type) }}"></foreign-selection>
    @else
        @php
            $type = 'text';
            if ($column->type === 'date') $type = 'date';
            else if ($column->type === 'time') $type = 'time';
            else if (in_array($column->type, ['datetime', 'timestamp'])) $type = 'datetime-local';
        @endphp
        <sa-input
            step="any"
            type="{{ $type }}"
            id="{{ $column->field }}"
            name="{{ $column->field }}"
            placeholder="{{ $column->type }}"
            {{ $column->is_null ? '' : 'required' }}
            model-value="{{ old($column->field) ?? $data[$column->field] ?? (!isset($data) && in_array($column->field, ['created_at', 'updated_at']) ? now() : '') }}"
        />
    @endif
</div>
