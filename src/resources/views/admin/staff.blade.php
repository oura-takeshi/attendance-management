@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">スタッフ一覧</h1>
    @if ($users->isEmpty())
    <p>ユーザーはまだ登録されていません</p>
    @else
    <table class="table">
        <tr class="table__row">
            <th class="table__header">名前</th>
            <th class="table__header">メールアドレス</th>
            <th class="table__header">月次勤怠</th>
        </tr>
        @foreach ($users as $user)
        <tr class="table__row">
            <td class="table__desc">{{ $user->name }}</td>
            <td class="table__desc">{{ $user->email }}</td>
            <td class="table__desc">
                <a class="table__list-link" href="/admin/attendance/staff/{{ $user->id }}">詳細</a>
            </td>
        </tr>
        @endforeach
    </table>
    @endif
</div>
@endsection