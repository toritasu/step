@extends('layout')

@section('title', 'STEP編集')

@section('content')
<main class="l-main">

<div class="l-narrow -center">
  <h2 class="c-subtitle">@yield('heading', 'STEPを編集する')</h2>

  <!-- エラーメッセージ表示欄 -->
  @if($errors->any())
    <div style="padding: 10px; background-color: #ff7777; color: #880000; border-radius: 3px;">
      <ul>
        @foreach($errors->all() as $message)
          <li>{{ $message }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- 入力フォーム -->
  <!-- 入力確認モーダルの「この内容で投稿する」ボタン押下で submit する -->
  <form class="c-inputField" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="user_id" value="1">
    <!-- タイトル -->
    <section class="c-inputField__form">
      <h3 class="c-inputField__label -required">STEPのタイトル</h3>
      <p class="c-note -caption">
        40文字以内で入力してください。<br>
        <span class="c-note -caption -highlighted">
          <span id="js-count-title">0</span>/50文字
        </span>
      </p>
      <input name="title" type="text" class="c-inputField__form -name"
             v-bind:value="postedData.title"
             v-on:keyup="countTitle"
             placeholder="例）保険営業マンが年収1000万エンジニアになるまで">
     </section>

    <!-- カテゴリー -->
    <section class="c-inputField__form">
      <h3 class="c-inputField__label -required" for="name">STEPのカテゴリー</h3>
      <p class="c-note -caption">
        該当するカテゴリーを１～３つ選んでください。<br>
        <span class="c-note -caption -highlighted">選択中のカテゴリー：@{{categoriesShow()}}</span>
      </p>
      <label class="c-inputField__form__checklabel"
             v-for="category in categories">
          <input type="checkbox" class="c-inputField__form__checkbox"
            name="categories[]"
            v-model="postedData.categories"
            :value="category.id" :key="category.id"
            v-on:change="limitCheckBox">
            @{{category.label}}
      </label>
    </section>

    <!-- 概要 -->
    <section class="c-inputField__form">
      <h3 class="c-inputField__label">STEPの概要</h3>
      <p class="c-note -caption">
        あなたがこのSTEPを歩め始めた理由や、このSTEPを通して得たもの、<br>
        そして今あなたが手にしているものについて、<br>
        500文字以内で綴ってください。<br>
        <span class="c-note -caption -highlighted">
          <span id="js-count-description">0</span>/500文字
        </span>
      </p>
      <textarea name="description" class="c-inputField__form__textbox"
                v-on:keyup="countDescription"
                placeholder="10年前、僕は運命の相手と出会った。そう、筋トレだ。"
                >@{{ postedData.description }}</textarea>
    </section>

    <!-- 目安達成時間 -->
    <section class="c-inputField__form">
      <h3 class="c-inputField__label">STEPの目安達成時間</h3>
      <p class="c-note -caption">
        このSTEPを達成するのに必要な時間を、次の中から選んでください。
      </p>
      <select name="estimate" v-model="postedData.estimate">
        <option v-for="estimate in estimates"
                v-bind:value="estimate.id">
                @{{estimate.label}}
        </option>
      </select>
     </section>

    <!-- イメージ画像 -->
    <section class="c-inputField__form">
      <h3 class="c-inputField__label" for="intro">STEPのイメージ画像</h3>
      <p class="c-note -caption">
        初期設定では、１つ目に選んだカテゴリーに関連する画像が使用されます。特別な思い入れのある写真や象徴的なイラストがあれば、変更してください。
      </p>
      <label class="c-inputField__form__preview">
        <img class="filePreview c-inputField__form__preview__picture" alt="イメージ画像"
             src="{{ old('image', asset($step->image)) }}">
        <p id="fileName" class="c-inputField__form__preview__text">クリックして画像を選択</p>
        <input type="file" name="image"
               class="c-inputField__form__preview__file"
               v-on:change="onFileChange">
      </label>
    </section>

    <!-- 小STEP -->
    <section class="c-inputField__form">
      <h3 class="c-inputField__label -required">達成までのSTEP</h3>
      <p class="c-note -caption">
      あなたが歩んだ小さなSTEPを最大@{{maxStepNum}}件まで登録できます。
      </p>

      <div class="c-inputField__form -substep"
           v-for="(substep, index) in postedData.substeps">
        <h4 class="c-substep__number">
          STEP@{{index + 1}}
          <i class="far fa-times-circle c-icon -cancel"
             v-if="index > 0"
             v-on:click="delStepForm(index)"></i>
        </h4>
        <div class="c-substep__box">
          <!-- 小STEPのIDを渡すためのhidden input -->
          <input type="hidden"
                 name="substep_orders[]" v-bind:value="index + 1">

          <p class="c-substep__box__index">タイトル：必須（30文字以内）</p>
          <input type="text" class="c-substep__box__input"
                 name="substep_titles[]"
                 v-model="substep.title">
          <p class="c-substep__box__index">概要（140文字以内）</p>
          <textarea class="c-substep__box__input -textarea"
                    name="substep_descriptions[]"
                    v-model="substep.description"
                    >@{{substep.description}}</textarea>
          <p class="c-substep__box__index">参考サイト名（30文字以内）</p>
          <input type="text" class="c-substep__box__input"
                 name="substep_links[]"
                 v-model="substep.link">
           <p class="c-substep__box__index">参考サイトURL（255文字以内）</p>
           <input type="text" class="c-substep__box__input"
                  name="substep_urls[]"
                  v-model="substep.url">
        </div>
      </div>
      <button type="button" class="c-button -indigo -round -block -topMargin"
              v-on:click.prevent="addStepForm"
              v-bind:disabled="isFull">STEPを追加</button>
    </section>
    <!-- ここまで -->

    <button type="button" class="c-button -red -round -topMargin"
            @click="modalShow">
            入力内容を確認する
    </button>

    <!-- 入力確認モーダル -->
    <div class="c-modal">
      <div class="c-modal__window -large">
        <h3 class="c-modal__window__heading">入力内容確認</h3>
        <table class="c-table">
          <tr class="c-table__row">
            <th class="c-table__cell -heading">タイトル</th>
            <td class="c-table__cell -content">@{{postedData.title}}</td>
          </tr>
          <tr class="c-table__row">
            <th class="c-table__cell -heading">カテゴリー</th>
            <td class="c-table__cell -content">@{{categoriesShow()}}</td>
          </tr>
          <tr class="c-table__row">
            <th class="c-table__cell -heading">概要</th>
            <td class="c-table__cell -content">@{{postedData.description}}</td>
          </tr>
          <tr class="c-table__row">
            <th class="c-table__cell -heading">目安達成時間</th>
            <td class="c-table__cell -content">@{{getEstimateLabel(postedData.estimate)}}</td>
          </tr>
          <tr class="c-table__row">
            <th class="c-table__cell -heading">イメージ画像</th>
            <td class="c-table__cell -content">
              <img class="filePreview" style="width: 100%" src="{{ old('image', asset($step->image)) }}" alt="イメージ画像">
            </td>
          </tr>

          <tr class="c-table__row">
            <th class="c-table__cell -heading">達成までのSTEP</th>
            <td class="c-table__cell -content">
              <div class="c-box"
                   v-for="(step, index) in postedData.substeps">
                <p class="c-box__heading">STEP@{{index + 1}} @{{step.title}}</p>
                <p class="c-box__content">
                  @{{step.description}}<br>
                <a class="c-link" :href="step.url">@{{step.link}}</a>
                </td>
              </div>
            <td>
          </tr>
          
        </table>
        <button type="submit" class="c-button -red -block -round">
          @yield('button', 'この内容で投稿する')
        </button>
        <button type="button" class="c-button -indigo -block -round -topMargin"
                v-on:click="modalClose">やりなおす</button>
      </div>
    </div>
    <!-- ここまで -->
  </form>

  <a href="{{ route('mypage', ['id' => Auth::user()->id]) }}" class="c-link -block">マイページへ戻る</a>

</div>



</main>

@endsection