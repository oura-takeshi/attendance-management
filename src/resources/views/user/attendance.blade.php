@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">
@endsection

@section('content')
<div class="content">
    @switch($user->status)
    @case(1)
    @if($exist_work_time == null)
    <p class="status">勤務外</p>
    @else
    <p class="status">退勤済</p>
    @endif
    @break
    @case(2)
    <p class="status">出勤中</p>
    @break
    @default
    <p class="status">休憩中</p>
    @endswitch
    <p class="date">{{ $now->format('Y年n月j日') }}({{ $day_of_week }})</p>
    <p class="time">{{ $now->format('H:i') }}</p>
    @switch($user->status)
    @case(1)
    @if($exist_work_time == null)
    <form action="/attendance/work" method="post">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button class="form__button-submit" type="submit">出勤</button>
    </form>
    @else
    <p class="comment">お疲れ様でした。</p>
    @endif
    @break
    @case(2)
    <div class="form__outer">
        <form action="/attendance/work" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button class="form__button-submit" type="submit">退勤</button>
        </form>
        <form action="/attendance/break" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button class="form__button-submit--break" type="submit">休憩入</button>
        </form>
    </div>
    @break
    @default
    <form action="/attendance/break" method="post">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button class="form__button-submit--break" type="submit">休憩戻</button>
    </form>
    @endswitch
</div>
@endsection