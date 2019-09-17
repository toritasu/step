@extends('layout')

@section('title', 'STEP一覧')

@section('content')
  <main class="l-main">
    <!-- STEP一覧コンポーネント -->
    <section class="l-content">
      <!-- 検索フォーム -->
      <div class="p-searchForm">
        <h2 class="p-searchForm__title">挑戦したいSTEPを探しましょう</h2>
        <form class="p-searchForm__forms">
        <label class="p-searchForm__forms__form">
          カテゴリー<br>
          <select class="c-selectbox"
                  v-model.number="categoryFilter">
            <option value="" selected>すべて</option>
            <option v-for="category in categories"
                    v-bind:value="category.id">@{{category.label}}</option>
          </select>
        </label>
        <label class="p-searchForm__forms__form">
          並び替え<br>
          <select class="c-selectbox"
                  v-model.number="sortOrder">
            <option value="1">投稿日が新しい順</option>
            <option value="2">投稿日が古い順</option>
          </select>
        </label>
      </div>
    </form>

    <div class="p-selection">
      <h2 class="c-subtitle">投稿されたSTEP <span>@{{numOfFilteredList}}</span>件</h2>
      <ul class="c-itemList">

        <!-- STEP１件 コンポーネント -->
        <step-panel v-for="step in filteredList"
                    v-bind:step="step" v-bind:key="step.id">
        </step-panel>

      </ul>

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