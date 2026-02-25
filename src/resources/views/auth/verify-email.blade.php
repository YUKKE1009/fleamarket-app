@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="verify-page">
    <p class="verify-message">
        <strong>登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。</strong>
    </p>

    {{-- FN012-3: 認証サイト（MailHog）へのリンクボタン --}}
    <div class="verify-action">
        <a href="http://localhost:8025" target="_blank" class="verify-btn">
            認証はこちらから
        </a>
    </div>

    {{-- FN013: 認証メール再送ボタン --}}
    <form class="resend-form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-link-button">
            認証メールを再送する
        </button>
    </form>

    @if (session('message'))
    <p class="resend-success">
        {{ session('message') }}
    </p>
    @endif
</div>
@endsection