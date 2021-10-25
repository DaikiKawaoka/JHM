<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/siderbar.css') }}" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" />

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'KBC就活管理アプリ') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('register_confirm') }}">{{ __('ユーザ作成') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    求人 <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if (!Auth::user()->is_teacher())
                                        <a class="dropdown-item" href="{{ route('entries.index') }}">
                                            エントリー済み会社一覧
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('companies.index') }}">
                                        求人一覧
                                    </a>
                                    <a class="dropdown-item" href="{{ route('companies.create') }}">
                                        求人登録
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @if (Auth::user()->is_teacher())
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        生徒 <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('workspaces.showMember') }}">
                                            生徒一覧
                                        </a>
                                        <a class="dropdown-item" href="{{ route('users.create') }}">
                                            生徒登録
                                        </a>

                                    </div>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.edit', Auth::user()->id) }}">
                                        {{ __('プロフィール編集') }}
                                    </a>

                                    <form method="post" name="logout_form" action="{{ route('auth_logout') }}">
                                        <a href="javascript:logout_form.submit()"
                                            class="dropdown-item">{{ __('ログアウト') }}</a>
                                        @csrf
                                    </form>

                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @guest
                @yield('content')
            @else
                @if (Auth::user()->is_teacher())
                    <div class="main_container">

                        <siderbar :items="{{ json_encode(Auth::user()->getTaughtClasses()) }}"></siderbar>
                        <!-- @foreach (Auth::user()->getTaughtClasses() as $class)
                                    <div class="card m-3">
                                        <div class="card-body @if (session('workspace_id') == $class->id) alert-secondary @endif">
                                            <a
                                                href="{{ route('workspaces.change', $class->id) }}">{{ __($class->class_name) }}</a>
                                        </div>
                                    </div>
                                @endforeach -->
                        <div class="side2">
                            @yield('content')
                        </div>
                    </div>
                @else
                    @yield('content')
                @endif
            @endguest
        </main>
    </div>
</body>

</html>
