@extends('layout')

@section('title', 'パスワード再設定')

@section('content')
  <main class="l-main -center">
    <section class="l-narrow -center">
      <h2 class="c-subtitle">パスワード再発行</h2>
      <p class="c-note">
        新しいパスワードを入力してください。
      </p>

      @if($errors->any())
        <div style="padding: 10px; background-color: #ff7777; color: #880000; border-radius: 3px;">
      @foreach($errors->all() as $message)
        <p>{{ $message }}</p>
      @endforeach
        </div>
      @endif
      
      <form action="{{ route('password.update') }}" method="post"
            class="c-inputField -box">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label class="c-inputField__label" for="email">メールアドレス</label>
        <input id="email" type="email" name="email"
              　class="c-inputField__form -email"
              　value="{{ $email ?? old('email') }}"
              　required autocomplete="email" autofocus>

        <label class="c-inputField__label" for="password">新しいパスワード</label>
        <input id="password" type="password" name="password"
               class="c-inputField__form -password"
               required autocomplete="new-password">

        <label class="c-inputField__label" for="password-confirm">新しいパスワード（確認）</label>
        <input id="password-confirm" type="password" name="password_confirmation"
               class="c-inputField__form -password"
               required autocomplete="new-password">

        <button type="submit" class="c-button -blue -round -topMargin">送信する</button>
      </form>

      <a href="{{ route('home') }}" class="c-link -block -topMargin">TOPへ戻る</a>

    </section>
  </main>
@endsection