<nav class="container mx-auto p-4 flex items-center" id="navbar">
    <i data-lucide="menu" class="cursor-pointer md:invisible" id="hamburger"></i>

    <div class="ml-auto"></div>

    <theme-switcher></theme-switcher>

    <div class="ml-4">
        Hey <span class="font-semibold">{{ auth()->user()->name ?? auth()->user()->username }}</span>!
    </div>
</nav>
