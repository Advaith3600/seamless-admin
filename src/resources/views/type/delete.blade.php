@php $name = Str::plural(class_basename($type)); @endphp

@extends(config('seamless-admin.layout'))

@section('title', "Delete $name")

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold">
                Delete {{ $name }}
            </h2>
        </div>

        <div class="mt-4">
            <p>Are you sure you want to delete the following records?</p>
            <ul class="list-decimal list-inside my-2 ml-4">
                @foreach($ids as $id)
                    <li>
                        <span class="opacity-70">{{ Str::upper((new $type)->getKeyName()) }}</span>:
                        <span class="font-semibold mr-2">{{ $id }}</span>
                        <span>{{ (string) $type::find($id) }}</span>
                    </li>
                @endforeach
            </ul>
            <p>from the `{{ (new $type)->getTable() }}` table. This action is unreversible. So, make sure you know what
                you are doing before proceeding.</p>

            <div class="mt-4 flex justify-end gap-2">
                <a href="{{ route('admin.type.index', request()->type) }}" class="btn grey">
                    <i data-feather="x"></i>
                    Cancel
                </a>
                <button class="btn red" onclick="document.getElementById('del').submit()">
                    <i data-feather="trash-2"></i>
                    Delete
                </button>
            </div>

            <form action="{{ route('admin.type.destroy', [request()->type, 'ids' => $ids]) }}" method="post" id="del">
                @csrf
                @method('delete')
            </form>
        </div>
    </div>
@endsection
