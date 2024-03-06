@php $name = Str::plural(class_basename($type)); @endphp

@extends(config('seamless-admin.layout'))

@section('title', "Delete $name")

@section('header')
    @saSafeVite('src/resources/assets/js/app.js')
@endsection

@section('content')
    <div class="container mx-auto px-4 py-2" id="app">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl sm:text-4xl font-bold">
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
                <sa-button as-child variant="outline">
                    <a href="{{ route('admin.type.index', request()->type) }}">
                        <i data-lucide="arrow-left" class="size-4"></i>
                        Cancel
                    </a>
                </sa-button>
                <form action="{{ route('admin.type.destroy', [request()->type, 'ids' => $ids]) }}" method="post">
                    @csrf
                    @method('delete')
                    <sa-button variant="destructive">
                        <i data-lucide="trash-2" class="size-4"></i>
                        Delete
                    </sa-button>
                </form>
            </div>
        </div>
    </div>
@endsection
