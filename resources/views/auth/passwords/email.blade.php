@extends('layout')

@section('title', 'パスワード再設定メールの送信')

@section('content')
  <main class="l-main -center">
    <section class="l-narrow -center">
      <h2 class="c-subtitle">パスワード再発行</h2>
      <p class="c-note">
        ご登録されているメールアドレスあてに、パスワード再発行用のリンクを送信します。
      </p>
      @if (session('status'))
        <div role="alert">
          {{ session('status') }}
        </div>
      @endif

      <form action="{{ route('password.email') }}" method="POST"
            class="c-inputField -box">
      @csrf
        <label for="email" class="c-inputField__label">メールアドレス</label>
        <input name="email" id="email" type="text" 
              class="c-inputField__form -email">

        <button type="submit" class="c-button -blue -round -topMargin">送信する</button>
      </form>

      <a href="{{ route('home') }}" class="c-link -block -topMargin">TOPへ戻る</a>

    </section>
  </main>
@endsection
