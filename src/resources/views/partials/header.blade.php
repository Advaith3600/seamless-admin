<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<title>@yield('title', 'Admin') | {{ config('app.name') }}</title>

<link rel="icon" href="{{ asset('favicon.svg') }}" />
<link rel="alternate icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}" />
{{--importing font--}}
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
{{--custom css--}}
@vite('src/resources/assets/scss/app.scss', 'seamless-admin')
<script src="https://unpkg.com/lucide@latest" defer></script>

@yield('header')
