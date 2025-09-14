@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">{{ $input_date->format('Y年n月j日') }}の勤怠</h1>
    <div class="info">
        <a class="info__day-link prev-day-arrow" href="/admin/attendance/list/{{ $prev_day_date }}">前日</a>
        <p class="info__current-day calendar">{{ $input_date->format('Y/m/d') }}</p>
        <a class="info__day-link next-day-arrow" href="/admin/attendance/list/{{ $next_day_date }}">翌日</a>
    </div>
    @if (count($users) > 0)
    <div class="table">
        <table class="table__inner">
            <tr class="table__row">
                <th class="table__header">名前</th>
                <th class="table__header">出勤</th>
                <th class="table__header">退勤</th>
                <th class="table__header">休憩</th>
                <th class="table__header">合計</th>
                <th class="table__header">詳細</th>
            </tr>
            @foreach ($users as $user)
            <tr class="table__row">
                <td class="table__desc">{{ $user['user_name'] }}</td>
                @if ($user['work_start_time'])
                <td class="table__desc">{{ $user['work_start_time']->format('H:i') }}</td>
                @else
                <td class="table__desc"></td>
                @endif
                @if ($user['work_end_time'])
                <td class="table__desc">{{ $user['work_end_time']->format('H:i') }}</td>
                @else
                <td class="table__desc"></td>
                @endif
                @if ($user['total_break_time'])
                <td class="table__desc">{{ $user['total_break_time'] }}</td>
                @else
                <td class="table__desc"></td>
                @endif
                <td class="table__desc">{{ $user['actual_work_time'] }}</td>
                <td class="table__desc">
                    <a class="table__detail-link" href="/attendance/{{ $user['attendance_day_id'] }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @else
    <div class="comment">
        <p class="comment__inner">*この日の出勤者はいません。</p>
    </div>
    @endif
</div>
@endsection