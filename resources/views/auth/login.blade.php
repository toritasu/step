@extends('layout')

@section('title', 'ログイン')

@section('content')
<main class="l-main -center">

<section class="l-narrow -center">
  <h2 class="c-subtitle">ログイン</h2>
  <p class="c-note">
    パスワードを忘れた方は<a class="c-link" href="{{ route('password.request') }}">こちら</a>から再発行できます。
  </p>

  @if($errors->any())
  <div style="padding: 10px; background-color: #ff7777; color: #880000; border-radius: 3px;">
      @foreach($errors->all() as $message)
        <p>{{ $message }}</p>
      @endforeach
    </div>
  @endif

  <form class="c-inputField -box" method="post">
  @csrf
    <label class="c-inputField__label" for="email">メールアドレス</label>
    <input name="email" type="email" class="c-inputField__form -email">

    <label class="c-inputField__label" for="pass">パスワード</label>
    <input name="password" type="password" class="c-inputField__form -pass">

    <button class="c-button -blue -round -topMargin">ログインする</button>
  </form>

  <a href="index.html" class="c-link -block -topMargin">TOPへ戻る</a>

</section>

</main>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('/js/footerFixed.js') }}"></script>
@endsection