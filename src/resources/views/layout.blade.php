<!doctype html>
<html lang="en">
<head>
    @include('seamless::partials.header')
</head>
<body>
    <div class="uppercase font-bold text-2xl p-4" id="logo">
        <a href="{{ url('') }}">{{ config('app.name') }}</a>
    </div>

    @include('seamless::partials.navbar')

    @include('seamless::partials.sidebar')

    <main id="main">
        @yield('content')

        @include('seamless::partials.footer')
    </main>
</body>
</html>
