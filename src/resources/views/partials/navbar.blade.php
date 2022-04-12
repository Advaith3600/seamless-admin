<nav class="container mx-auto p-4 flex justify-between items-center" id="navbar">
    <i data-feather="menu" class="cursor-pointer md:invisible" id="hamburger"></i>

    <div>
        Hey <span class="font-semibold">{{ auth()->user()->name ?? auth()->user()->username }}</span>!
    </div>
</nav>
