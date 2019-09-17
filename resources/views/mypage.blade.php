@extends('layout')

@section('title', 'マイページ')

@section('content')
<main class="l-main -clearfix">
  <section class="l-content">
    <!-- サイドバー -->
    <aside class="l-sidebar p-profile">
      <table class="p-profile__table">
        <tr>
          <td class="p-profile__table__cell">
            <img class="c-avater -large -border" src="{{ asset($user->avater) }}" alt="">
          </td>
        </tr>
        <tr>
          <th class="p-profile__table__cell -heading">ニックネーム</th>
          <td class="p-profile__table__cell">{{ $user->name }}</td>
        </tr>
        <tr>
          <th class="p-profile__table__cell -heading">メールアドレス</th>
          <td class="p-profile__table__cell">{{ $user->email }}</td>
        </tr>
        <tr>
          <th class="p-profile__table__cell -heading">自己紹介</th>
          <td class="p-profile__table__cell">{{ $user->introduction }}</td>
        </tr>
        <td class="p-profile__table__cell">
          <a href="{{ route('profile.edit', ['id' => $user->id]) }}">
            <button class="c-button -indigo -round -block">編集する</button>
          </a>
        </td>
        <td class="p-profile__table__cell">
          <form id="logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="c-button -glay -round -block"
                  type="submit">ログアウト</button>
        </td>
      </form>
      </table>

</aside>
    <!-- 投稿したSTEP＆挑戦中のSTEP -->
    <article class="l-article p-myList">
      <div class="p-selection">

        <h2 class="c-subtitle">投稿したSTEP <span>{{ count($steps_by_user) }}</span>件</h2>
        <!-- 投稿したSTEP一覧用のコンポーネント -->
        <my-list steps="{{ $steps_by_user }}"></my-list>

        <h2 class="c-subtitle">挑戦中のSTEP <span>{{ count($steps_challenges) }}</span>件</h2>
        <!-- 投稿したSTEP一覧用のコンポーネント -->
        <my-list steps="{{ $steps_challenges }}"></my-list>

        <h2 class="c-subtitle">達成したSTEP <span>{{ count($steps_achived) }}</span>件</h2>
        <!-- 投稿したSTEP一覧用のコンポーネント -->
        <my-list steps="{{ $steps_achived }}"></my-list>

      </div>
    </article>

  </section>
</main>
@endsection

@section('posting_form')
  <!-- 投稿ボタン -->
  @if(Auth::check())
  <a class="c-posting" href="{{ route('steps.create') }}">
      <div class="c-posting__content">
          <i class="c-posting__content__icon fas fa-pen-fancy"></i>
          <p class="c-posting__content__text">STEPを投稿</p>
      </div>
  </a>
  @endif
@endsection