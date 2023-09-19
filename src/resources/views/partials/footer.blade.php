<footer id="footer" class="py-2 flex mx-auto items-center gap-2">
    <a href="https://github.com/advaith3600/seamless-admin" target="_blank" class="flex">Seamless Admin</a>
    <span class="bg-gray-800 rounded-full w-1 h-1"></span>
    <span>Version: {{ SeamlessAdmin::getPackageVersion() }}</span>
</footer>

@yield('footer')
