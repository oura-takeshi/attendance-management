@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/detail.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">勤怠詳細</h1>
    <form class="form" action="/admin/attendance/work" method="post">
        @csrf
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
                            <span>{{$date->format('Y年')}}</span>
                            <span>{{$date->format('n月j日')}}</span>
                        </div>
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">出勤・退勤</th>
                    <td class="table__desc">
                        <div class="table__desc-time">
                            <input class="table__desc-time-input" type="time" name="work_start_time" value="{{ old('work_start_time', $work_start_time) }}">
                            <span>〜</span>
                            <input class="table__desc-time-input" type="time" name="work_end_time" value="{{ old('work_end_time', $work_end_time) }}">
                        </div>
                        <div class="table__error-message">
                            @error('work_start_time')
                            {{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                @foreach ($break_times as $index => $break_time)
                <tr class="table__row">
                    @if ($index === 0)
                    <th class="table__header">休憩</th>
                    @else
                    <th class="table__header">休憩{{ $index + 1 }}</th>
                    @endif
                    <td class="table__desc">
                        <div class="table__desc-time">
                            <input class="table__desc-time-input" type="time" name="break_time[{{ $index }}][start_time]" value="{{ old('break_time.' . $index . '.start_time', $break_time['start_time']) }}">
                            <span>〜</span>
                            <input class="table__desc-time-input" type="time" name="break_time[{{ $index }}][end_time]" value="{{ old('break_time.' . $index . '.end_time', $break_time['end_time']) }}">
                        </div>
                        <div class="table__error-message">
                            @error('break_time.' . $index . '.start_time')
                            {{ $message }}
                            @enderror
                        </div>
                        <div class="table__error-message">
                            @error('break_time.' . $index . '.end_time')
                            {{ $message }}
                            @enderror
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="form__button">
            <button class="form__button-submit">修正</button>
        </div>
    </form>
</div>
@endsection