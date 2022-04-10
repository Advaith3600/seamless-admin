<header class="container mx-auto p-4 flex justify-end items-center" id="navbar">
    <div>
        Hey <span class="font-semibold">{{ auth()->user()->name ?? auth()->user()->username }}</span>!
    </div>
</header>
