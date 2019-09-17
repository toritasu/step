@extends('layout')

@section('title', 'プロフィール編集')

@section('content')
<main class="l-main">

<section class="l-narrow -center">
  <h2 class="c-subtitle">プロフィール編集</h2>

  <!-- エラーメッセージ表示欄 -->
  @if($errors->any())
    <div style="padding: 10px; background-color: #ff7777; color: #880000; border-radius: 3px;">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form class="c-inputField" method="post"
        enctype="multipart/form-data">
    @csrf
    
    <label class="c-inputField__label" for="pic">アバター画像</label>
    <img class="filePreview c-avater -large" alt="アバター画像"
         src="{{ asset($user->avater) }}">
    <input name="avater" type="file" class="c-inputField__form -pic"
           v-on:change="onFileChange">

    <label class="c-inputField__label" for="name">ニックネーム</label>
    <input name="name" type="text" class="c-inputField__form -name"
           value="{{ old('name', $user->name) }}">

    <label class="c-inputField__label" for="email">メールアドレス</label>
    <input name="email" type="email" class="c-inputField__form -email"
           value="{{ old('email', $user->email) }}">

    <label class="c-inputField__label" for="intro">自己紹介</label>
    <textarea name="introduction"
              class="c-inputField__form -textbox">{{ old('introduction', $user->introduction) }}</textarea>

    <button type="submit" class="c-button -indigo -round -topMargin">編集する</button>
  </form>

  <a href="{{ route('mypage', ['id' => Auth::user()->id]) }}"
    class="c-link -block">マイページへ戻る</a>

</section>

</main>
@endsection