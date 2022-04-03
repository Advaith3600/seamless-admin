<header class="container px-4 py-6 flex justify-between items-center">
    <div class="uppercase font-bold text-2xl">
        <a href="{{ url('') }}">{{ config('app.name') }}</a>
    </div>

    <div>
        Hey <span class="font-semibold">{{ auth()->user()->name ?? auth()->user()->username }}</span>!
    </div>
</header>
