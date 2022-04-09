<header class="container p-4 flex justify-end items-center">
    <div>
        Hey <span class="font-semibold">{{ auth()->user()->name ?? auth()->user()->username }}</span>!
    </div>
</header>
