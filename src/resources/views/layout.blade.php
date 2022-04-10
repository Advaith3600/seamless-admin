<!doctype html>
<html lang="en">
<head>
    @include('seamless::partials.header')
</head>
<body>
    <section class="fixed top-0 left-0 h-full z-10">
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
