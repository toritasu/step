Vue.component('myList', {
  props: [ 'steps' ],
  created: function() {
    console.log(this.parsedSteps);
  },
  computed: {
    // JSON形式でSTEPリストを渡されるので、JSON.parseをかける
    parsedSteps: function() {
      return JSON.parse(this.steps);
    }
  },
  template: `
  <ul class="c-itemList -left">
    <step-panel v-for="step in parsedSteps"
                v-bind:step="step"
                v-bind:key="step.id">
    </step-panel>
  </ul>
  `
});