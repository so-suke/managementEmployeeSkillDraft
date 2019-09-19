<template>
  <div class="skillAppend">
    <div class="skillAppend--form">
      <skill-base-select
        ref="formColumnSkill"
        :rules="skillRules"
        :items="skills"
        :whatSelectJp="kindName.jp"
      ></skill-base-select>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Emit, Vue, Watch } from "vue-property-decorator";
import SkillBaseSelect from "./SkillBaseSelect.vue";
import { ValidationProvider } from "vee-validate";

@Component({
  components: {
    SkillBaseSelect,
    ValidationProvider
  }
})
export default class SkillFormDelete extends Vue {
  readonly experiencePeriods = [
    { id: 1, name: "半年未満" },
    { id: 2, name: "半年から1年" },
    { id: 3, name: "1年から2年" },
    { id: 4, name: "2年から3年" },
    { id: 5, name: "3年以上" }
  ];

  refFormColumns: SkillBaseSelect[] = [];
  @Prop() private kindName!: { en: string; jp: string };
  @Prop() private skills!: any;
  @Prop() private skillRules!: string;

  private mounted() {
    this.refFormColumns = [
      this.$refs.formColumnSkill as SkillBaseSelect,
      this.$refs.formColumnExperiencePeriods as SkillBaseSelect
    ];
  }

  public getSkillId(): string {
    return (this.$refs.formColumnSkill as SkillBaseSelect).getSelectId();
  }
}
</script>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";

.skillAppend {
  &--selections {
    display: flex;
    select {
      margin-right: 8px;
    }
  }

  &--form {
    .formColumn {
      display: flex;
      &.error {
        select {
          border: 1px solid #ff0000;
        }
        .errorMessage {
          display: block;
        }
      }
      select {
        margin-right: 8px;
        margin-bottom: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
      }
    }
  }
}
</style>
