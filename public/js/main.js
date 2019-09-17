// Laravel環境でvue devtoolsをONにする設定
Vue.config.devtools = true;

// グローバルフィルタ
// 参照したファイルの絶対パスを返す（assetが使用できない箇所用）
Vue.filter('prefixed', function(str){
  const DOMEIN = location.origin;
  return DOMEIN + '/step/' + str;
})

// このアプリケーションにおける Vue.js の役割
// 1. STEPのサムネイル
// 1-1. 表示や並び替えに必要なデータを取得
// 1-2. 検索フォームによるフィルタリング
// 2. 投稿フォーム
// 2-1. 確認画面モーダルの表示
// 2-2. 確認画面モーダルに必要なデータの保持（v-model）
// 2-3. 小STEPフィールドの追加・削除
// 2-4. イメージ画像のライブプレビュー
// 2-5. inputの文字数カウント
// 2-6. checkboxのチェック数制限

new Vue({
  el: '#app',
  created() {
    this.getAjaxList();
    this.getAjaxUser();
    this.getAjaxDetail();
  },
  // =========================
  // データ
  // =========================
  data: {
    // Ajaxで取得した生のSTEPコレクション（ユーザー情報も結合されている）
    steps: [],
    // =========================
    // 定数
    // =========================
    // カテゴリーリスト
    categories: [
      {id: 1, label: 'プログラミング', img: 'images/programming.jpeg'},
      {id: 2, label: '語学', img: 'images/conversation.jpeg'},
      {id: 3, label: 'トレーニング', img: 'images/training.jpg'},
      {id: 4, label: 'スポーツ', img: 'images/sport.jpeg'},
      {id: 5, label: 'イラスト・漫画', img: 'images/drawing.jpg'},
      {id: 6, label: '就職・転職', img: 'images/recruit.jpg'},
      {id: 7, label: '資格・試験', img: 'images/exam.jpeg'},
      {id: 8, label: '恋愛・婚活', img: 'images/love.jpeg'},
      {id: 9, label: 'その他', img: 'images/stepdefault.jpg'}
    ],
    // 目安達成時間リスト
    estimates: [
      { id: 1, label: '～数時間' },
      { id: 2, label: '～１日' },
      { id: 3, label: '～１週間' },
      { id: 4, label: '～１ヵ月' },
      { id: 5, label: '～３ヵ月' },
      { id: 6, label: '～６ヵ月' },
      { id: 7, label: '～１年' },
      { id: 8, label: '～２年' },
      { id: 9, label: '２年以上'}
    ],
    // =========================
    // 検索フォーム用データ
    // =========================
    // ログイン中ユーザーのID
    loggedInUser: '',
    // カテゴリーフィルターで指定されたカテゴリーID
    categoryFilter: '',
    // 「あなたのSTEP」チェックフラグ
    isMineChecked: '',
    // 「挑戦中のSTEP」チェックフラグ
    isChallengedChecked: '',
    // ソートの種類
    sortOrder: 1,
    // =========================
    // 投稿フォーム用データ
    // ========================= 
    // 小STEPの最大数
    maxStepNum: 10,
    postedData: {
      // タイトルの入力内容
      title: '',
      // 選択中のカテゴリー
      categories: [],
      // 概要の入力内容
      description: '',
      // 小STEPが最大数に達したかどうか
      isFull: false,
      // イメージ画像
      image: '',
      // 目安達成時間（デフォルトは数時間：ソートに使うためIDで管理）
      estimate: 1,
      // アップロードされた画像
      image: null,
      // 小STEPデータ（初期値は空のオブジェクト１つ）
      substeps: [
        { id: 1, title: '', description: '', url: '', link: ''}
      ],
    },
  },
  // =========================
  // メソッド
  // =========================
  methods: {
    // =========================
    // Ajax通信(GET) 
    // =========================
    // DBからデータを取得
    getAjaxList: function(){
      var self = this;
      var url = '/step/ajax/steps';
      axios.get(url).then(function(response){
        self.steps = response.data;
        console.log(self.steps);
      });
    },
    getAjaxUser: function() {
      // ログイン中のユーザーのIDを取得し、
      // 自分が投稿したSTEPにのみ isMine という補足プロパティを追加する
      // ※「あなたのSTEP」で絞り込み検索するために使用
      var url = '/step/ajax/user';
      axios.get(url).then(function(response){
        self.loggedInUser = response.data;
      });
    },
    // STEP１件の情報を取得する
    // 編集ページのデータをVueで取り扱いやすくするため。
    getAjaxDetail: function() {
      var self = this;
      var id = this.getId();
      var url = '/step/ajax/' + id + '/detail';
      axios.get(url).then(function(response){
        console.log(response.data);
        step = response.data.step;
        substeps = response.data.substeps;
        self.postedData.title = step.title;
        self.postedData.categories = step.categories;
        self.postedData.description = step.description;
        self.postedData.estimate = step.estimate;
        self.postedData.image = step.image;
        for(var i = 0; i < substeps.length; i++ ){
          self.postedData.substeps[i] = substeps[i];
        }
      });
    },
    // =========================
    // 汎用メソッド
    // =========================
    // モーダルを表示
    modalShow: function(flg) {
      $('.c-modal').addClass('show');
    },
    // モーダルを閉じる
    modalClose: function() {
      $('.c-modal').removeClass('show');
    },
    // IDからカテゴリーのラベルを取得する（View用）
    getCategoryLabel: function(id) {
      id = Number(id) - 1;
      return this.categories[id].label;
    },
    // IDから目安見積もり時間のラベルを取得する（View用）
    getEstimateLabel: function(id) {
      id = Number(id) - 1;
      return this.estimates[id].label;
    },
    // 小ステップの数を算出する
    numOfsubsteps: function(step) {
      if(step.substeps) {
        return step.substeps.length;
      } else {
        return 0;
      }
    },
    // =========================
    // 投稿フォーム関連
    // =========================
    // カテゴリーのチェックを３つまでに制限する
    limitCheckBox: function() {
      var numOfChecked = $('.c-inputField__form__checkbox:checked').length;
      console.log(numOfChecked);
      var $unchecked = $('.c-inputField__form__checkbox').not(':checked');
      if(numOfChecked >= 3) {
        $unchecked.attr('disabled', true);
      } else {
        $unchecked.attr('disabled', false);
      }
    },
    // チェックボックスでon/offされたカテゴリーを配列に格納
    changeCategories: function(e) {
      var categories = this.postedData.categories
      var addedCat = e.target.value;
      categories.push(addedCat);
      for(var i=0; i < categories.length; i++) {
        if(categories[i] == addedCat) {
          categories.splice(i ,1);
        }
      }
      this.limitCheckBox;
    },
    // 選択したカテゴリーを名前で羅列
    categoriesShow: function() {
      var catArr = [];
      var cats = this.postedData.categories;
      for(var i=0; i < cats.length; i++){
        var id = cats[i] - 1;
        catArr.push(this.categories[id].label);
      }
      return catArr.join('、');
    },
    // テキストエリアの文字数カウント
    countTitle: function(e) {
      this.postedData.title = e.target.value;
      $('#js-count-title').text(this.postedData.title.length);
    },
    countDescription: function(e) {
      this.postedData.description = e.target.value;
      $('#js-count-description').text(this.postedData.description.length);
    },
    // 小STEPの入力欄を追加
    addStepForm: function() {
      var newId = this.postedData.substeps.length + 1;
      // console.log('新しいID：' + newId);
      this.postedData.substeps.push({id: newId, title: '', detail: '', url: '', link: ''});
    },
    // 小STEPの入力欄を削除
    delStepForm: function(id) {
      delete this.postedData.substeps.splice(id, 1);
    },
    // ライブプレビュー
    onFileChange: function(e) {
      var file = e.target.files[0];
      var src = this.postedData.image;
      const READER = new FileReader();
      // console.log(file.type);
      READER.onload = function(e) {
        src = e.target.result;
        console.log(src);
        $('.filePreview').attr('src', src);
        $('#fileName').text(file.name);
      };
      READER.readAsDataURL(file);
    },
    // =========================
    // 検索フォーム関連
    // =========================
    sortOrderChanged: function(event) {
      this.sortOrder = event.target.value;
    },
    categoryFilterChanged: function(event) {
      this.categoryFilter = event.target.value;
    },
    isMineChanged: function() {
      this.isMineChecked = !this.isMineChecked;
    },
    isChallengedChanged: function() {
      this.isChallengedChecked = !this.isChallengedChecked;
    },
    isMatch: function(category) {
      return category === this.categoryFilter;
    },
    // =========================
    // STEP挑戦
    // =========================
    // Ajax通信でSTEPと挑戦者を紐付ける
    ajaxGoChallenge: function() {
      var self = this;
      var id = this.getId();
      var url = '/step/ajax/' + id + '/challenge/start';
      axios.get(url).then(function(response){
        console.log(response.data);
        // 処理が終了したらモーダルを表示
        self.modalShow(response.data);
      });
    },
    // Ajax通信でSTEPと挑戦者を紐付けを解く
    ajaxAbortChallenge: function() {
      var self = this;
      var id = this.getId();
      var url = '/step/ajax/' + id + '/challenge/abort';
      axios.get(url).then(function(response){
        console.log(response.data);
        // 処理が終了したらモーダルを表示
        self.modalShow(response.data);
      });
    },
    // =========================
    // 小STEPクリア
    // =========================
    // Ajax通信で小STEPと挑戦者を紐付ける
    clearSubstep: function(substep_order) {
      console.log(substep_order);
      var id = this.getId();
      var url = '/step/ajax/' + id + '/substep/'+ substep_order + '/clear';
      axios.get(url).then(function(response){
        // url()->previous()によって同じURLが返ってくるので、リダイレクト
        location.href = response.data.url;
        // ゴールした時はお祝いモーダルを表示
        if(response.data.goal_flg) {
          console.log('おめでとう！');
        }
      });
    },
    congratulations: function() {
      console.log('おめでとう！！');
    },
    // =========================
    // STEP詳細ページのURLからIDを取得する関数
    // =========================
    getId: function() {
      var url = window.location.href;
      var array = url.split('/');
      var pos = array.length - 2;
      return array[pos];
    },
  },
  // =========================
  // 算出プロパティ
  // =========================
  computed: {
    // 【検索フォームの処理を反映させたSTEPリスト】
    filteredList: function() {
      // フィルター後のSTEPを格納する新しい配列
      var newList = [];
      // i番目のSTEPが表示対象かどうかを判定する
      for (var i = 0; i < this.steps.length; i++) {
        // 表示対象化を判定するフラグを宣言
        var isShow = true;
        // 1. 選択されたカテゴリーに該当するもの以外を除外
        if (this.categoryFilter) {
          if (!this.steps[i].categories.find(this.isMatch)) {
            isShow = false;
          }
        }
        // 2. 「あなたのSTEP」にチェックがある場合は、自分のSTEP以外を除外
        if (this.isMineChecked) {
          if (this.steps[i].user_id !== this.loggedInUser) {
            isShow = false;
          }
        }
        // 3. 「挑戦中のSTEP」にチェックがある場合は、挑戦中のSTEP以外を除外
        if (this.isChallengedChecked) {
          if (!this.steps[i].isChallenged) {
            isShow = false;
          }
        }
        // 表示対象のSTEPだけを新しい配列に追加する
        if (isShow) {
          newList.push(this.steps[i]);
        }
      }
      // 新しい配列を並び替える
      switch (this.sortOrder) {
        case 1: // 投稿日が新しい順
          newList.sort(function(a,b) {
            if(a.created_at > b.created_at) {
              return 1;
            } else {
              return -1;
            }
          });
          break;
        case 2: // 投稿日が古い順
          break;
        case 3: // 挑戦者が多い順
          break;
        case 4: // 挑戦者が少ない順
          break;
      }
      // フィルター後の怪人リストを返す
      return newList;
    },
    // 【フィルター後のSTEPリストの件数】
    numOfFilteredList: function() {
      return this.filteredList.length;
    },
    // STEPの件数
    numOfItems: function() {
      return this.steps.length;
    },
    // 小STEPが最大件数に達したらフラグをONにする
    isFull: function() {
      if(this.postedData.substeps.length >= this.maxStepNum) {
        return true;
      } else {
        return false;
      }
    },
    // カテゴリーに応じたイメージ画像の初期設定
    selectedImage: function() {
      if(this.postedData.categories.length !== 0) {
        var first = this.postedData.categories[0] - 1;
        this.postedData.image = this.categories[first].img;
      } else {
        this.postedData.image = 'images/stepdefault.jpg';
      }
      return this.postedData.image;
    }
  }
});