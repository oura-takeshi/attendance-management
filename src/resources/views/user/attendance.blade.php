@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">
@endsection

@section('content')
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
@endsection