<footer id="footer" class="py-2 flex mx-auto items-center gap-2">
    <a href="https://github.com/advaith3600/seamless-admin" target="_blank" class="flex">Seamless Admin</a>
    <span class="bg-gray-800 rounded-full w-1 h-1"></span>
    <span>Version: {{ SeamlessAdmin::getPackageVersion() }}</span>
</footer>

@yield('footer')

<script>
    window.addEventListener('load', () => {
        if ('lucide' in window)
            lucide.createIcons();

        document.getElementById('hamburger')?.addEventListener('click', () => {
            document.getElementById('sidebar-container')?.classList?.add('active');
            document.getElementById('sidebar-backdrop')?.classList?.add('active');
        });

        document.getElementById('sidebar-backdrop')?.addEventListener('click', () => {
            document.getElementById('sidebar-container')?.classList?.remove('active');
            document.getElementById('sidebar-backdrop')?.classList?.remove('active');
        })
    });

    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    } else if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
</script>
