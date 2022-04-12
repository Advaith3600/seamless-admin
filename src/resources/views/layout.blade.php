<!doctype html>
<html lang="en">
<head>
    @include('seamless::partials.header')
</head>
<body>
    <div id="sidebar-backdrop"></div>

    <section id="sidebar-container">
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
