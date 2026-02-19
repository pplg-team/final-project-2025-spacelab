<meta charset="utf-8" />
{{-- buat lebih besar skalanya --}}
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>
    {{ ($title ?? config('app.name')) . (isset($description) ? ' | ' . $description : '') }}
</title>
{{-- Favicon LIGHT --}}
<link 
    rel="icon" 
    href="{{ asset('assets/images/logo/favicon/dark/favicon-dark.ico') }}" 
    media="(prefers-color-scheme: light)"
    sizes="any"
>

<link 
    rel="icon" 
    href="{{ asset('assets/images/logo/favicon/dark/favicon-dark.svg') }}"
    media="(prefers-color-scheme: light)"
    type="image/svg+xml"
>

<link 
    rel="apple-touch-icon" 
    href="{{ asset('assets/images/logo/favicon/dark/apple-touch-icon-dark.png') }}"
    media="(prefers-color-scheme: light)"
>

<link 
    rel="manifest" 
    href="{{ asset('assets/images/logo/favicon/dark/site.webmanifest') }}"
    media="(prefers-color-scheme: light)"
>


{{-- Favicon DARK --}}
<link 
    rel="icon" 
    href="{{ asset('assets/images/logo/favicon/light/favicon-light.ico') }}" 
    media="(prefers-color-scheme: dark)"
    sizes="any"
>

<link 
    rel="icon" 
    href="{{ asset('assets/images/logo/favicon/light/favicon-light.svg') }}"
    media="(prefers-color-scheme: dark)"
    type="image/svg+xml"
>

<link 
    rel="apple-touch-icon" 
    href="{{ asset('assets/images/logo/favicon/light/apple-touch-icon-light.png') }}"
    media="(prefers-color-scheme: dark)"
>

<link 
    rel="manifest" 
    href="{{ asset('assets/images/logo/favicon/light/site.webmanifest') }}"
    media="(prefers-color-scheme: dark)"
>


<meta name="description" content="{{ $description ?? 'SpaceLab is an all-in-one academic schedule and facility management system designed to streamline school operations and enhance productivity.' }}">
<meta name="keywords" content="SpaceLab, Academic Schedule, Facility Management, School Management System, Education Software, Timetable Management, Resource Scheduling">
<meta name="author" content="SpaceLab Team">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#ffffff">

<!-- Open Graph / Facebook -->
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? 'SpaceLab is an all-in-one academic schedule and facility management system designed to streamline school operations and enhance productivity.' }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('og-image.png') }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $description ?? 'SpaceLab is an all-in-one academic schedule and facility management system designed to streamline school operations and enhance productivity.' }}">
<meta name="twitter:image" content="{{ asset('og-image.png') }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" /> 

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Assets -->
@vite(['resources/css/app.css', 'resources/js/app.js'])