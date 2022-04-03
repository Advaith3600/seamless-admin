<meta charset="UTF-8" />
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<title>@yield('title', 'Admin') | {{ config('app.name') }}</title>

{{--importing font--}}
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
{{--custom css--}}
<link href="{{ asset('seamless-admin/css/tailwind.css') }}" rel="stylesheet" />
<link href="{{ asset('seamless-admin/css/app.css') }}" rel="stylesheet" />


{{--scripts--}}
<script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js" defer></script>
<script src="{{ asset('seamless-admin/js/app.js') }}" defer></script>
