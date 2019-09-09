<template>
  <ul class="employees">
    <li v-for="employee in employees" :key="employee.id">
      <p :class="`employee employee--${employee.gender}`">
        <a :href="`employees/${employee.id}`">
          <span class="name_last">{{ employee.name_last }} {{ employee.name_first }}</span>
          <span class="name_last_kana">{{ employee.name_last_kana }} {{ employee.name_first_kana }}</span>
        </a>
      </p>
    </li>
  </ul>
</template>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";
.employees {
  width: 40%;
  margin: 0 auto;
  li {
    cursor: pointer;
    &:hover {
      background-color: $colorUserHoverBg;
    }
  }
}
.employee {
  margin-bottom: 8px;
  border: 1.5px solid;
  border-radius: 5px;
  padding: 8px;
  &--male {
    border-color: $colorBorderMale;
  }
  &--female {
    border-color: $colorBorderFemale;
  }
  span {
    display: inline-block;
    &.name_last {
      width: 120px;
    }
    &.name_last_kana {
      width: 200px;
    }
  }
}
</style>

<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";
import { optionModule } from "../store/module/Option";

@Component
export default class ExampleComponent extends Vue {
  employees: any[] = [];

  private mounted(): void {
    window.axios
      .get("api/employees")
      .then(res => {
        console.log(res.data);
        this.employees = res.data;
      })
      .catch(err => {
        console.log(err);
      });
  }

  get options() {
    return optionModule.options;
  }
}
</script>
