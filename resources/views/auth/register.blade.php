@extends('layout')

@section('title', '会員登録')

@section('content')
<main class="l-main -center">

<section class="l-narrow -center">
  <h2 class="c-subtitle">新規会員登録</h2>
  <p class="c-note">
    会員登録すると、<br>
    あなたが歩んだSTEPを共有したり、<br>
    誰かが歩んだSTEPに挑戦することができます。<br>
    既にアカウントお持ちの方は<a class="c-link" href="login.html">ログイン</a>してください。
  </p>

  @if($errors->any())
  <div style="padding: 10px; background-color: #ff7777; color: #880000; border-radius: 3px;">
      @foreach($errors->all() as $message)
        <p>{{ $message }}</p>
      @endforeach
    </div>
  @endif

  <form action="{{ route('register') }}" method="post" class="c-inputField -box" >
  @csrf
    <label class="c-inputField__label" for="name">ニックネーム</label>
    <input name="name" type="text" class="c-inputField__form -name">

    <label class="c-inputField__label" for="email">メールアドレス</label>
    <input name="email" type="email" class="c-inputField__form -email">

    <label class="c-inputField__label" for="pass">パスワード</label>
    <input name="password" type="password" class="c-inputField__form -pass">

    <label class="c-inputField__label" for="pass">パスワード（確認）</label>
    <input name="password_confirmation" type="password" class="c-inputField__form -pass">

    <button type="submit" class="c-button -red -round -topMargin">登録する</button>
  </form>

  <a href="index.html" class="c-link -block -topMargin">TOPへ戻る</a>

</section>

</main>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('/js/footerFixed.js') }}"></script>
@endsection