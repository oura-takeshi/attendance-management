@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/list.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">勤怠一覧</h1>
    <div class="info">
        <a class="info__link" href="/attendance/list/{{ $prev_year }}/{{ $prev_month }}">前月</a>
        <p class="info__current-month">{{ $current_year }}/{{ $current_month }}</p>
        <a class="info__link" href="/attendance/list/{{ $next_year }}/{{ $next_month }}">翌月</a>
    </div>
    <table class="table">
        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>
        @foreach ($dates as $date)
        <tr>
            <th>{{ $date['date']->format('m/d') }}({{ $date['day_of_week'] }})</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @endforeach
    </table>
</div>
@endsection