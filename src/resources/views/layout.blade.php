<!doctype html>
<html lang="en">
<head>
    @include('seamless::partials.header')
</head>
<body>
    @include('seamless::partials.sidebar')

    <main>
        @include('seamless::partials.navbar')

        @yield('content')

        @include('seamless::partials.footer')
    </main>
</body>
</html>
