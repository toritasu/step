var item =  {
  template: `
  <li class="c-item">
    <a :href="item.id | link">
      <div class="c-item__eyecatch" :style="{backgroundImage: 'url(' + item.img + ')'}">
        <span class="c-categoryIcon" v-for="category in item.categories">{{getCategory(category)}}</span>
      </div>
      <div class="c-item__summary">
        <h3 class="c-thumbnai__summary__title">
          {{item.name}}
        </h3>
        <div class="c-item__summary__info">
          by<span>{{item.postedBy}}</span>さん<br>
          目安達成時間：<span>{{item.period}}</span>
        </div>
        <div class="c-item__summary__like">
          <span>{{item.NumOfLike}}</span>
          <i class="far fa-heart"></i>
        </div>
      </div>
    </a>
  </li>
  `,
  props: ['item', 'categories'],
  methods: {
    // IDからカテゴリーの名前を取得する（View用）
    getCategory: function(id) {
      id = Number(id) - 1;
      return this.categories[id].name;
    }
  }
}