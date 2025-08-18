@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/list.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">勤怠一覧</h1>
    <div class="info">
        <a class="info__month-link prev-month-arrow" href="/attendance/list/{{ $prev_year }}/{{ $prev_month }}">前月</a>
        <p class="info__current-month calendar">{{ $current_year }}/{{ $current_month }}</p>
        <a class="info__month-link next-month-arrow" href="/attendance/list/{{ $next_year }}/{{ $next_month }}">翌月</a>
    </div>
    <div class="table">
        <table class="table__inner">
            <tr class="table__row">
                <th class="table__header">日付</th>
                <th class="table__header">出勤</th>
                <th class="table__header">退勤</th>
                <th class="table__header">休憩</th>
                <th class="table__header">合計</th>
                <th class="table__header">詳細</th>
            </tr>
            @foreach ($dates as $date)
            <tr class="table__row">
                <td class="table__desc">{{ $date['date']->format('m/d') }}({{ $date['day_of_week'] }})</td>
                @if($date['work_start_time'])
                <td class="table__desc">{{ $date['work_start_time']->format('H:i') }}</td>
                @else
                <td class="table__desc"></td>
                @endif
                @if($date['work_end_time'])
                <td class="table__desc">{{ $date['work_end_time']->format('H:i') }}</td>
                @else
                <td class="table__desc"></td>
                @endif
                @if($date['total_break_time'])
                <td class="table__desc">{{ $date['total_break_time']->format('G:i') }}</td>
                @else
                <td class="table__desc"></td>
                @endif
                @if($date['actual_work_time'])
                <td class="table__desc">{{ $date['actual_work_time']->format('G:i') }}</td>
                @else
                <td class="table__desc"></td>
                @endif
                <td class="table__desc">
                    <a class="table__detail-link" href="/attendance/{{ $date['date']->format('Y/m/d') }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection