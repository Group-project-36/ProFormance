<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/utils/notifications.js') }}"></script>
    <script src="{{ asset('js/utils/updatetax.js') }}"></script>
    <script src="{{ asset('js/utils/cardValidity.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ProFormance' }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/phone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @yield('styles')
</head>

<body class="kb-body">
    @include('layouts.partials.dropdown-script')

    @include('layouts.partials.header')
    @if (session('success'))
        <div style="background:#d4edda; padding:12px; margin:10px 0; border-radius:4px;">
            {{ session('success') }}
        </div>
    @endif
    @include('layouts.partials.sidebar-all')
    @include('layouts.partials.sidebar-cart')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')
    @include('layouts.partials.svg-sprite')
    @yield('scripts')
</body>
</html>
