<!doctype html>
<html lang="en">
<head>
    @include('seamless::partials.header')
</head>
<body class="bg-background text-foreground">
    <div id="sidebar-backdrop"></div>

    <section id="sidebar-container" class="dark bg-card text-card-foreground">
        @include('seamless::partials.logo')

        @include('seamless::partials.sidebar')
    </section>

    <main id="main">
        @include('seamless::partials.navbar')

        @yield('content')

        @include('seamless::partials.footer')
    </main>
</body>
</html>
