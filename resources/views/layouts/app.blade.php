<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-id" content="{{ auth()->check() ? auth()->user()->id : '' }}">
    <meta name="url" content="{{ asset('/') }}">
    <meta name="auth-name" content="{{ auth()->check() ? ucfirst(auth()->user()->name) : '' }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/emojionearea.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/dark-mode.css') }}"> --}}
    @yield('style')

    <style>
        .message audio {
            width: 285px !important;
            height: 37px !important;
        }

        .js-player {
            width: 100% !important;
            height: 40px !important;
        }

    </style>

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss']) --}}
</head>

<body>
    {{-- <div id="root"></div> --}}
    <div id="app">

        @include('partials.view_media_modal')
        @include('modals.add_friend_modal')
        @include('modals.friend_request_modal')

        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                @auth
                    <a class="navbar-brand" href="javascript:void(0)">
                        {{ Auth::user()?->name }}
                    </a>

                    <a class="mx-2" style="color: {{ request()->routeIs('home') ? 'white' : 'gray' }}"
                        href="{{ route('home') }}">
                        {{ __('Home') }}
                    </a>
                    <a href="{{ route('user.profile') }}"
                        style="color: {{ request()->routeIs('user.profile') ? 'white' : 'gray' }}" class="mx-2">
                        Profile
                    </a>
                    <a class="btn btn-sm btn-danger mx-2" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                @endauth
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>


                            </li> --}}
                        @endguest
                    </ul>
                </div>
                @auth

                    <div>
                        <button type="button" class="btn  position-relative" style="margin-right: 30px" id="request-list">
                            <i class="bi bi-people text-light"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light"
                                id="notification_counter">
                                {{ getNotificationCounter() }}
                            </span>
                        </button>
                    </div>

                    <div>
                        <button class="btn btn-sm btn-primary" id="add_frnd_btn">Add friend</button>
                    </div>
                @endauth
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
    <script src="{{ asset('assets/jquery.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script> --}}
    {{-- <script src="https://unpkg.com/@zegocloud/zego-im-sdk/zego-im-sdk.js"></script> --}}

    <script src="https://unpkg.com/zego-zim-web@2.16.0/index.js"></script>
    <script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>

    <script src="{{ asset('assets/emojionearea.min.js') }}"></script>
    @vite(['resources/js/app.js'])
    @yield('script')
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script src="{{ asset('assets/message.js') }}"></script>
    <script src="{{ asset('assets/zego.js') }}"></script>
    <script src="{{ asset('assets/toastr.js') }}"></script>


</body>

</html>
