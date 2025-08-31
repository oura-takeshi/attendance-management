<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <img class="header__img-logo" src="{{ asset('storage/images/logo.svg') }}" alt="coachtech">
        <nav class="nav">
            <a href="/admin/attendance/list" class="nav__link">勤怠一覧</a>
            <a href="/admin/staff/list" class="nav__link">スタッフ一覧</a>
            <a href="/stamp_correction_request/list" class="nav__link">申請一覧</a>
            <form class="nav__logout" action="/admin/logout" method="post">
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