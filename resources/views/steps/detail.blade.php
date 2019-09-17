@extends('layout')

@section('title', 'STEP詳細')

@section('content')
<main class="l-main">
  <section class="l-content">

    <section class="p-stepDetail">
      <div class="p-stepDetail__categories">
        @foreach($categories as $category)
        <span class="c-categoryIcon">
          <!-- TODO: アクセサだとなぜかundefinedになる -->
          {{ App\Step::CATEGORIES[$category]['label'] }} 
        </span>
        @endforeach
      </div>
      <h2 class="c-subtitle">{{ $step->title }}</h2>

      <img class="p-stepDetail__image" src="{{ asset($step->image) }}">
      <div class="p-stepDetail__author">
        <p class="p-stepDetail__author__heading">このSTEPを歩んだ人</p>
        <a class="p-stepDetail__author__link" href="">
          <img class="c-avater" src="{{ asset($poster->avater) }}" alt="">
          {{ $poster->name }}さん
        </a>
      </div>
      <p class="p-stepDetail__description">
        {{ $step->description}}
      </p>
      <h3 class="c-heading -yellow">私が歩んだ {{ count($substeps) }} のSTEP</h3>
      <p class="p-stepDetail__description -center">
        目安達成時間：{{ $step->estimate_label}}
      </p>
      <ul class="p-stepDetail__steps">

        @foreach($substeps as $substep)
        <li class="p-stepDetail__steps__item
          @if( $challenge )
            @if( $substep->order < $challenge->progress ) 
             -clear
            @endif
          @endif">
          <label for="{{ $substep->id }}"
                 class="p-stepDetail__steps__item__title">
            {{ $substep->order }}. {{ $substep->title }}
            <i class="p-stepDetail__steps__item__title__arrow fas fa-arrow-circle-down"></i>
          </label>
          <!-- CSSセレクタとラジオボタンを利用したアコーディオン -->
          <input type="checkbox" id="{{ $substep->id }}"
                 class="p-stepDetail__steps__item__checkbox"
                 @if( $challenge && $substep->order === $challenge->progress) checked="checked" @endif>
                 <!-- 進行中の小STEPはデフォルトで開いておく -->
          <div class="p-stepDetail__steps__item__detail">
            <div class="p-stepDetail__steps__item__detail__content">
              <p class="p-stepDetail__steps__item__detail__content__text">{{ $substep->description }}</p>
              <a class="c-link" href="{{ $substep->url }}" target="blank">{{ $substep->link }}</a>
            </div>

            <!-- 投稿者ではない＆挑戦している場合のみ「次へ進む」ボタンが出現 -->
            @if( Auth::check() )
              @if( Auth::user()->id !== $poster->id && $challenge )
                @if( $substep->order === $challenge->progress )
                <button class="c-button -red -round -block"
                        v-on:click="clearSubstep({{$substep->order}})">
                  @if( $substep->order === $substepCount)
                    目標達成!!
                  @else
                    次へ進む
                  @endif
                </button>
                @elseif( $substep->order < $challenge->progress )
                <button class="c-button -blue -round -block" style="background-color: #00ced1">
                  クリア！
                </button>
                @endif
              @endif
            @endif

          </div>
        </li>
        @endforeach
        <!-- 達成したSTEPなら「GOAL」が「達成しました」に -->
        @if (isset($challenge) && $challenge->status === 2)
          <li class="p-stepDetail__steps__item -goal"
              style="background-color: #00ced1; color:#fff; border:none">達成しました!!</li>
        @else
          <li class="p-stepDetail__steps__item -goal">GOAL</li>
        @endif
      </ul>

      <!-- ログイン中のみ各種ボタンが出現 -->
      @if( Auth::check() )
        <!-- 自分が投稿したSTEPなら「編集する」が出現 -->
        @if( Auth::user()->id === $poster->id )
          <a href="{{ route('steps.edit', ['id' => $step->id]) }}">
            <button type="button" class="c-button -indigo -round -block">
              編集する
            </button>
          </a>
        @elseif(isset($challenge))
          <!-- 達成したSTEPなら「達成しました」マークが出現 -->
          @if ($challenge->status === 2)
            <p class="p-stepDetail__closing -block -center" style="color: #00ced1;">
              {{ $challenge->created_at->format('Y/n/j G:i') }} 挑戦開始<br>
              {{ $challenge->updated_at->format('Y/n/j G:i') }} 目標達成!!
            </p>
            <a href="https://twitter.com/intent/tweet?url={{request()->fullUrl()}}&text=【{{$step->title}}】を達成しました！｜『STEP』"
               target="blank_">
              <button class="c-button -twitter -block" style="font-size: .75rem;">
                <i class="fab fa-twitter"></i>Twitterで目標達成を報告する
              </button>
            </a>
          <!-- 挑戦中のSTEPなら「挑戦をやめる」ボタンが出現 -->
          @else
            <p class="p-stepDetail__closing -block -center">
              このSTEPに挑戦中です！コツコツ頑張りましょう！
            </p>
            <p class="p-stepDetail__closing -block -center">
              {{ $challenge->created_at->format('Y年n月j日 G時i分') }} 挑戦開始
            </p>
            <button type="button" class="c-button -round -block"
                    style="background-color: #a9a9a9"
                    v-on:click="ajaxAbortChallenge()">
                    挑戦をやめる
            </button>
          @endif
        @else
          <!-- 自分が投稿したSTEPでなく挑戦中でもなければ「挑戦する」ボタンが出現 -->
          <p class="p-stepDetail__closing -block -center">【{{ $step->title }}】に</p>
          <button type="button" class="c-button -red -round -block"
                  v-on:click="ajaxGoChallenge()">
                  挑戦する
          </button>
        @endif
      @endif


    </section>

    <a class="c-link -block -center" href="{{ route('steps.list') }}">STEP一覧へ戻る</a>
  </section>

  <!-- 挑戦決定後＆中断後モーダル -->
  <section class="c-modal">
    <div class="c-modal__window">
      <h3 class="c-modal__window__heading">{{ $step->title }}</h3>
      <!-- 挑戦を始めた時 -->
      @if(!$challenge)
        <p class="c-modal__window__text">の挑戦を始めました！</p>
        <p class="c-modal__window__text">【マイページ】の【挑戦中のSTEP】から、進捗の確認と記録ができるようになりました。１つのSTEPが終わったら【クリア】を押すことで次のSTEPへ進めます。<br></p>
        <p class="c-modal__window__text">１歩ずつ歩んでいけば夢は必ずかないます！</p>
        <p class="c-modal__window__text -share">
        <a href="https://twitter.com/intent/tweet?url={{request()->fullUrl()}}&text=【{{$step->title}}】の挑戦を始めました！｜『STEP』"
           target="blank_">
          <button class="c-button -twitter -block" style="font-size: .75rem;">
            <i class="fab fa-twitter"></i>Twitterで挑戦開始を宣言する
          </button>
        </a>
        </p>
        <a href="{{ route('steps.detail', ['id' => $step->id]) }}">
        <button class="c-button -blue -round">閉じる</button>
        </a>
      <!-- 挑戦をやめた時 -->
      @else
        <p class="c-modal__window__text">の挑戦をやめました…</p>
        <p class="c-modal__window__text">自分の目的に合ったSTEPを探してみましょう！</p>
        <a href="{{ route('steps.list') }}">
        <button class="c-button -blue -round">STEP一覧へ戻る</button>
        </a>
      @endif


    </div>
  </section>
</main>
@endsection