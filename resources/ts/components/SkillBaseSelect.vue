<template>
  <select
    name="select"
    ref="select"
    :data-what-select-jp="whatSelectJp"
    v-model="selected"
  >
    <option value>{{ whatSelectJp }}を選択してください</option>
    <option :value="`${item.id}`" v-for="item in items" v-bind:key="item.id">{{ item.name }}</option>
  </select>
</template>

<script lang="ts">
import { Component, Prop, Emit, Vue, Watch } from "vue-property-decorator";

@Component({})
export default class SkillBaseSelect extends Vue {
  selected: string = "";
  @Prop() private whatSelectJp!: string;
  @Prop() private items!: any[];

  public getSelectId(): string {
    return (this.$refs.select as HTMLSelectElement).value;
  }
}
</script>

<style lang="scss" scoped>
select {
  position: relative;
  width: 100%;
  margin-bottom: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 8px;
}
</style>
