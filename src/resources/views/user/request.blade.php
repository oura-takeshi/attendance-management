@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/request.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">申請一覧</h1>
    <div class="info">
        @if ($param !== "approved")
        <p class="info__pending para">承認待ち</p>
        <a class="info__approved link" href="/stamp_correction_request/list/?page=approved">承認済み</a>
        @else
        <a class="info__pending link" href="/stamp_correction_request/list">承認待ち</a>
        <p class="info__approved para">承認済み</p>
        @endif
    </div>
    @if (count($input_requests) > 0)
    <div class="table">
        <table class="table__inner">
            <tr class="table__row">
                <th class="table__header">状態</th>
                <th class="table__header">名前</th>
                <th class="table__header">対象日時</th>
                <th class="table__header">申請理由</th>
                <th class="table__header">申請日時</th>
                <th class="table__header">詳細</th>
            </tr>
            @foreach ($input_requests as $request)
            <tr class="table__row">
                @if ($request->approval === 1)
                <td class="table__desc">承認待ち</td>
                @else
                <td class="table__desc">承認済み</td>
                @endif
                <td class="table__desc text">{{ $request->attendanceDay->user->name }}</td>
                <td class="table__desc date">{{ $request->attendanceDay->date->format('Y/m/d') }}</td>
                <td class="table__desc text">{{ $request->reason }}</td>
                <td class="table__desc date">{{ $request->created_at->format('Y/m/d') }}</td>
                <td class="table__desc">
                    <a class="table__detail-link" href="/attendance/{{ $request->attendanceDay->id }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @else
    <div class="comment">
        @if ($param !== "approved")
        <p class="comment__inner">*承認待ちの申請はまだありません。</p>
        @else
        <p class="comment__inner">*承認済みの申請はまだありません。</p>
        @endif
    </div>
    @endif
</div>
@endsection