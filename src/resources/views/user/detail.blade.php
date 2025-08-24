@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/detail.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">勤怠詳細</h1>
    <form class="form" action="">
        <div class="table">
            <table class="table__inner">
                <tr class="table__row">
                    <th class="table__header">名前</th>
                    <td class="table__desc">西&emsp;伶奈</td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">日付</th>
                    <td class="table__desc">
                        <span>2023年</span>
                        <span>6月1日</span>
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">出勤・退勤</th>
                    <td class="table__desc">
                        <input type="time">
                        <span>~</span>
                        <input type="time">
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">休憩</th>
                    <td class="table__desc">
                        <input type="time">
                        <span>~</span>
                        <input type="time">
                    </td>
                </tr>
                <tr class="table__row">
                    <th class="table__header">備考</th>
                    <td class="table__desc">
                        <textarea name="" id=""></textarea>
                    </td>
                </tr>
            </table>
        </div>
        <div class="form__button">
            <button class="form__button-submit">修正</button>
        </div>
    </form>
</div>
@endsection