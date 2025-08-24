<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">

<body>
    <header class="header">
        <img class="header__img-logo" src="{{ asset('storage/images/logo.svg') }}" alt="coachtech">
        <nav class="nav">
            @if($exist_work_time && $exist_work_end_time)
            <a href="/attendance/list" class="nav__link clock-out">今月の出勤一覧</a>
            <a href="" class="nav__link clock-out">申請一覧</a>
            @else
            <a href="/attendance" class="nav__link">勤怠</a>
            <a href="/attendance/list" class="nav__link">勤怠一覧</a>
            <a href="" class="nav__link">申請</a>
            @endif
            <form class="nav__logout" action="/logout" method="post">
                @csrf
                <button class="nav__logout-button">ログアウト</button>
            </form>
        </nav>
    </header>

    <main>
        <div class="content">
            @if(!$exist_work_time)
            <p class="status">勤務外</p>
            @elseif(!$exist_work_end_time && (!$exist_break_time || $exist_break_end_time))
            <p class="status">出勤中</p>
            @elseif(!$exist_work_end_time && !$exist_break_end_time)
            <p class="status">休憩中</p>
            @else
            <p class="status">退勤済</p>
            @endif
            <p class="date">{{ $now->format('Y年n月j日') }}({{ $day_of_week }})</p>
            <p class="time">{{ $now->format('H:i') }}</p>
            @if(!$exist_work_time)
            <a class="work-link" href="/attendance/work">出勤</a>
            @elseif(!$exist_work_end_time && (!$exist_break_time || $exist_break_end_time))
            <div class="links__outer">
                <a class="work-link" href="/attendance/work">退勤</a>
                <a class="break-link" href="/attendance/break">休憩入</a>
            </div>
            @elseif(!$exist_work_end_time && (!$exist_break_time || !$exist_break_end_time))
            <a class="break-link" href="/attendance/break">休憩戻</a>
            @else
            <p class="comment">お疲れ様でした。</p>
            @endif
        </div>
    </main>
</body>

</html>



@section('content')

@endsection