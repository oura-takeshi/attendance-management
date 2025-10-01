@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/verify_email.css') }}">
@endsection

@section('content')
<div class="content">
    <p class="comment">登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>
    <a class="auth-link" href="https://mailtrap.io/inboxes/あなたのInboxID/messages" target="_blank">認証はこちらから</a>
    <form action="{{ route('verification.send') }}" method="post">
        @csrf
        <button class="resend-button" type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection