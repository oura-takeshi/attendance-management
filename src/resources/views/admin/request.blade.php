@extends('admin.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/request.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="heading">申請一覧</h1>
    <div class="info">
        <p class="info__pending">承認待ち</p>
        <a class="info__approved" href="/stamp_correction_request/list/?page=approved">承認済み</a>
    </div>
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
            <tr class="table__row">
                <td class="table__desc"></td>
                <td class="table__desc"></td>
                <td class="table__desc"></td>
                <td class="table__desc"></td>
                <td class="table__desc"></td>
                <td class="table__desc"></td>
            </tr>
        </table>
    </div>
</div>
@endsection