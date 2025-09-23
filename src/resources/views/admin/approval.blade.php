@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/approval.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">勤怠詳細</h1>
    <div class="table">
        <table class="table__inner">
            <tr class="table__row">
                <th class="table__header">名前</th>
                <td class="table__desc">
                    <div class="table__desc-name">{{ $user_name }}</div>
                </td>
            </tr>
            <tr class="table__row">
                <th class="table__header">日付</th>
                <td class="table__desc">
                    <div class="table__desc-date">
                        <span>{{ $date->format('Y年') }}</span>
                        <span>{{ $date->format('n月j日') }}</span>
                    </div>
                </td>
            </tr>
            <tr class="table__row">
                <th class="table__header">出勤・退勤</th>
                <td class="table__desc">
                    @if ($work_start_time || $work_end_time)
                    <div class="table__desc-time">
                        <span>{{ $work_start_time }}</span>
                        <span>〜</span>
                        <span>{{ $work_end_time }}</span>
                    </div>
                    @endif
                </td>
            </tr>
            @foreach ($break_time_requests as $index => $break_time_request)
            <tr class="table__row">
                @if ($index === 0)
                <th class="table__header">休憩</th>
                @else
                <th class="table__header">休憩{{ $index + 1 }}</th>
                @endif
                <td class="table__desc">
                    @if ($break_time_request['start_time'] || $break_time_request['end_time'])
                    <div class="table__desc-time">
                        <span>{{ $break_time_request['start_time'] }}</span>
                        <span>〜</span>
                        <span>{{ $break_time_request['end_time'] }}</span>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="table__row">
                <th class="table__header">備考</th>
                <td class="table__desc">
                    <div class="table__desc-reason">
                        <p class="table__desc-reason-content">{{ $reason }}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="button">
        @if ($approval === 1)
        <a class="button__link" href="/admin/attendance/approve/{{ $work_time_request_id }}">承認</a>
        @else
        <p class="button__para">承認済み</p>
        @endif
    </div>
</div>
@endsection