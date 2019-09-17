Vue.component('stepPanel', {
  props: [ 'step' ],
  data: function(){
    return {
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
      ]
    }
  },
  template: `
  <li class="c-item">
  <a v-bind:href="'steps/' + step.id + '/detail' | prefixed">
    <div class="c-item__eyecatch"
         v-bind:style="{ backgroundImage: 'url(' + getAbsolutePath(step.image) + ')'} ">
      <span class="c-categoryIcon"
            v-for="category in parsedCategories">
        {{ getCategoryLabel(category) }}
      </span>
    </div>
    <div class="c-item__summary">
      <h3 class="c-thumbnai__summary__title">
        {{step.title}}
      </h3>
      <div class="c-item__summary__info">
        by <span>{{ step.name }}さん</span><br>
        目安達成時間：<span>{{ getEstimateLabel(step.estimate) }}</span>
      </div>
      <!-- <div class="c-item__summary__like">
        <i class="far fa-heart"></i>
      </div> -->
    </div>
  </a>
</li>
  `,
  methods: {
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
    // 絶対パスを返すメソッド
    getAbsolutePath: function(str) {
      const DOMEIN = location.origin;
      return DOMEIN + '/step/' + str;
    }
  },
  computed: {
    // カテゴリー配列がJSON形式の時だけ JSON.parse をかける算出プロパティ
    // my-listコンポーネントを噛ませているマイページで何故かJSON形式になっている。
    parsedCategories: function() {
      var cats = this.step.categories;
      return Array.isArray(cats) ? cats : JSON.parse(cats);
    }
  },
  
});