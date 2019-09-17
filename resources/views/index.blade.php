@extends('layout')

@section('title', 'あなたの人生のSTEPを共有しよう')

@section('content')
<!-- ヒーローバナー -->
<main class="l-main">
    <section class="l-hero">
      <div class="p-hero">
        <div class="p-hero__title">
          <h1 class="p-hero__title__siteName">STEP</h1>
          <p class="p-hero__title__message">あなたの人生のSTEPを共有しよう</p>
        </div>
      </div>
    </section>

    <!-- イントロダクション -->
    <section class="l-content p-content">
      <p class="p-intro">
        プログラミングや英語などを学ぶのにも、<br>
        人それぞれ「これが良かった」という「順番」と「内容」があります。<br>
        人それぞれの「この順番でこういったものを学んでいったのが良かった」という「STEP」を投稿し、<br>
        他の人はそれを観ながらその「STEP」を元に学習を進めていけるサービスです。
      </p>

      <!-- 人気の高いSTEP -->
      <div class="p-selection">
        <!-- <h2 class="c-subtitle">みんなが挑戦しているSTEP</h2>
        <ul class="c-itemList">
        <li class="c-item" v-for="step in steps">
          <a v-bind:href="'detail' + step.id">
            <div class="c-item__eyecatch"
                 v-bind:style="{backgroundImage: 'url(../step/' + step.image + ')'}">
              <span class="c-categoryIcon">@{{ step.category1 }}</span>
            </div>
            <div class="c-item__summary">
              <h3 class="c-thumbnai__summary__title">
                @{{step.title}}
              </h3>
              <div class="c-item__summary__info">
                by<span>@{{step.user_id}}</span>さん<br>
                目安達成時間：<span>@{{step.estimate}}</span>
              </div>
              <div class="c-item__summary__like">
                <i class="far fa-heart"></i>
              </div>
            </div>
          </a>
        </li>
      </ul> -->
        <a href="{{ route('steps.list') }}">
          <button class="c-button -round -lead">投稿されたSTEPを見る</button>
        </a>
      </div>

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