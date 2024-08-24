<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-head.tinymce-config />
</head>

<body>
    @auth

        <nav class="navbar navbar-expand-lg bg_nav border_custom shadow z-3 d-flex flex-column mb-5">
            <div class="container">
                <p>Dashboard</p>
                <p>Benvenuto: {{ Auth::user()->email }}</p>
                <button class="navbar-toggler p-0 border-0 " type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="container d-block d-lg-none">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        @if (Auth::user()->name == 'Admin')
                            <x-admin-links />
                        @else
                            <x-user-links />
                        @endif
                    </div>
                </div>
            </div>
            <div class="container-fluid d-none d-lg-block">
                @if (Auth::user()->name == 'Admin')
                    <x-admin-links />
                @else
                    <x-user-links />
                @endif
            </div>
        </nav>
        <div class="container-fluid mb-5">
            <x-display-error />
            <x-display-message />
            <x-display-success />
            {{ $slot }}
        </div>
    @endauth
    @guest
        {{ $slot }}
    @endguest
</body>

</html>
