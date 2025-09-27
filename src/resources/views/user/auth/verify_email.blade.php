@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/verify_email.css') }}">
@endsection

@section('content')
<div class="content">
    <p class="">登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>
    <a href="">認証はこちらから</a>
    <form action="{{ route('verification.send') }}" method="post">
        @csrf
        <button type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection