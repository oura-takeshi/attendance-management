<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <img class="header__img-logo" src="{{ asset('storage/images/logo.svg') }}" alt="coachtech">
        <nav class="nav">
            <a href="" class="nav__link">勤怠</a>
            <a href="" class="nav__link">勤怠一覧</a>
            <a href="" class="nav__link">申請</a>
            <form class="nav__logout" action="/logout" method="post">
                @csrf
                <button class="nav__logout-button">ログアウト</button>
            </form>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>