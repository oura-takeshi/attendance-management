@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/login.css') }}">
@endsection

@section('content')
@if(session('message'))
<div class="alert">{{ session('message') }}</div>
@endif
<div class="content">
    <h1 class="form__heading">ログイン</h1>
    <form class="form" action="/login" method="post" novalidate>
        @csrf
        <div class="form__group">
            <label class="form__label" for="email">メールアドレス</label>
            <div class="form__group-content">
                <div class="form__input-text">
                    <input type="email" name="email" id="email" value="{{ old('email') }}">
                </div>
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <label class="form__label" for="password">パスワード</label>
            <div class="form__group-content">
                <div class="form__input-text">
                    <input type="password" name="password" id="password">
                </div>
                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <button class="form__button-submit" type="submit">ログインする</button>
    </form>
    <a class="register-link" href="/register">会員登録はこちら</a>
</div>
@endsection