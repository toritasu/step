<!DOCTYPE html>
<html lang="ja">
  <head>
    <title>STEP | @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=M+PLUS+1p&display=swap">
    <script type="text/javascript" src="{{ asset('/js/jquery-3.4.1.min.js') }}"></script>
  </head>

  <body>
    <div id="app" v-cloak>

      <!-- ヘッダー -->
      <header class="l-header">
    <div class="l-header__contents p-header">
      <div class="l-header__contents__left">
        <a href="{{ route('home') }}">
          <img class="p-header__logo" src="{{ asset('images/logo.png') }}" alt="">
        </a>
      </div>
      <div class="l-header__contents__right p-header__menu">
        <!-- ログイン中はマイページへのリンクを表示 -->
        @if(Auth::check())
          <a class="p-header__menu__link" href="{{ route('steps.list') }}">STEP一覧</a>
          <a class="p-header__menu__link" href="{{ route('mypage', Auth::user()->id ) }}">
            <img class="c-avater" src="{{ asset(Auth::user()->avater) }}" alt="">マイページ
          </a>
        <!-- 未ログインは新規会員登録とログインボタンを設置 -->
        @else
          <a href="{{ route('register') }}">
            <button class="c-button -red">新規登録</button>
          </a>
          <a href="{{ route('login') }}">
            <button class="c-button -blue">ログイン</button>
          </a>
        @endif
      </div>
    </div>

  <!-- フラッシュメッセージ -->
  @if (session('message'))
    <div class="c-flashModal">
        {{ session('message') }}
    </div>
  @endif

  </header>

    <!-- 各ページのコンテンツ -->
    @yield('content')

    @yield('posting_form')

    <!-- フッター -->
    <footer id="footer" class="l-footer">
        <div class="p-footer">
            © 2019 Toritasu
        </div>
    </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script src="https://jp.vuejs.org/js/vue.js"></script>
    <script src="{{ asset('js/step-panel.js') }}"></script>
    <script src="{{ asset('/js/my-list.js') }}"></script>
    <script src="{{ asset('/js/main.js') }}"></script>
  </body>
</html>