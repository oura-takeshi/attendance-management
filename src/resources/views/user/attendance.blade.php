@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">
@endsection

@section('content')
<div class="content">
    @switch($user->status)
    @case(1)
    <p class="status">勤務外</p>
    @break
    @case(2)
    <p class="status">出勤中</p>
    @break
    @default
    <p class="status">休憩中</p>
    @endswitch
    <p class="date">{{ $now->format('Y年m月d日') }}({{ $day_of_week }})</p>
    <p class="time">{{ $now->format('H:i') }}</p>
    @switch($user->status)
    @case(1)
    <div class="form__outer">
        <form class="form" action="/attendance/work" method="post">
            @csrf
            <input type="text" name="user_id" value="{{ $user->id }}">
            <button class="form__button-submit" type="submit">出勤</button>
        </form>
    </div>
    @break
    @case(2)
    <div class="form__outer flex">
        <form class="form" action="/attendance/work" method="post">
            @csrf
            <input type="text" name="user_id" value="{{ $user->id }}">
            <button class="form__button-submit" type="submit">退勤</button>
        </form>
        <form class="form" action="/attendance/break" method="post">
            @csrf
            <input type="text" name="user_id" value="{{ $user->id }}">
            <button class="form__button-submit--break" type="submit">休憩入</button>
        </form>
    </div>
    @break
    @default
    <div class="form__outer">
        <form class="form" action="/attendance/break" method="post">
            @csrf
            <input type="text" name="user_id" value="{{ $user->id }}">
            <button class="form__button-submit--break" type="submit">休憩戻</button>
        </form>
    </div>
    @endswitch
</div>
@endsection