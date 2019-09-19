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

<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";
import { optionModule } from "../store/module/Option";
import { async } from "q";

@Component
export default class ExampleComponent extends Vue {
  employees: any[] = [];

  private mounted(): void {
    this.fetchData();
  }

  private fetchData() {
    window.axios
      .get(`/api/employees`)
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

  @Watch("currentPage")
  onCurrentPageChanged(value: any) {
    this.fetchData();
  }
}
</script>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";
.myContainer {
  display: flex;
  flex-direction: column;
  align-items: center;
}
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
