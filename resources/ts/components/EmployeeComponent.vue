<template>
  <div>
    <notifications group="manipulation" position="top center" />
    <div class="profile" v-show="employee.name_last">
      <p>
        <span>名前:</span>
        <span>{{ employee.name_last }} {{ employee.name_first }}</span>
        <span>({{ employee.name_last_kana }} {{ employee.name_first_kana }})</span>
      </p>
      <p>生年月日: {{ employee.birth_at_formatted }}(満{{ employee.age }}歳)</p>
    </div>

    <hr />

    <div class="skills">
      <h2 class="skills__title">スキル一覧</h2>

      <skill-component
        :experiences="languageExperiences"
        kindName="language"
        kindNameJp="言語"
        :skills="languages"
        @appendSkill="appendSkillLanguage"
        @deleteExperience="deleteExperienceLanguage"
      ></skill-component>

      <skill-component
        :experiences="frameworkExperiences"
        kindName="framework"
        kindNameJp="フレームワーク"
        :skills="frameworks"
        @appendSkill="appendSkillFramework"
        @deleteExperience="deleteExperienceFramework"
      ></skill-component>

    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";
import { optionModule } from "../store/module/Option";
import SkillComponent from "./parts/SkillComponent.vue";

@Component({
  components: {
    SkillComponent
  }
})
export default class ExampleComponent extends Vue {
  csrf: any = (document.querySelector(
    'meta[name="csrf-token"]'
  ) as any).getAttribute("content");
  employees: any[] = [];
  employee: any = {};
  languageExperiences: any[] = [];
  frameworkExperiences: any[] = [];

  skillKind: any = {
    language: {
      en: "language",
      jp: "言語"
    },
    framework: {
      en: "framework",
      jp: "フレームワーク"
    }
  };

  readonly experienceSkillKindNames: any = {
    language: "language",
    framework: "framework"
  };

  // 追加オプションの表示切り替え
  canAppendSkillLanguage: boolean = false;
  canAppendSkillFramework: boolean = false;

  readonly experiencePeriods = [
    { id: 1, name: "半年未満" },
    { id: 2, name: "半年から1年" },
    { id: 3, name: "1年から2年" },
    { id: 4, name: "2年から3年" },
    { id: 5, name: "3年以上" }
  ];
  // @Prop() protected employee!: any;
  @Prop() protected pathIconTrash!: any;
  @Prop() protected languages!: any;
  @Prop() protected frameworks!: any;

  private enableAppendSkillLanguage() {
    this.canAppendSkillLanguage = true;
  }
  private enableAppendSkillFramework() {
    this.canAppendSkillFramework = true;
  }

  private deleteExperience(id: number, pathDelete: string) {
    window.axios
      .delete(pathDelete)
      .then(response => {
        this.$notify({
          group: "manipulation",
          type: "success",
          title: "Success Delete Language Expression",
          text: (<any>response).message
        });
        // 全て再読み込み
        this.getInitDatas();
      })
      .catch(function(error) {
        console.log(error);
      });
  }

  private deleteExperienceLanguage(id: number) {
    this.deleteExperience(id, `/api/languageExperiences/${id}`);
  }
  private deleteExperienceFramework(id: number) {
    this.deleteExperience(id, `/api/frameworkExperiences/${id}`);
  }

  private appendSkill(
    refSelectSkill: HTMLSelectElement,
    refSelectExperiencePeriod: HTMLSelectElement,
    experiences: any[],
    experienceSkillKindName: string,
    storePath: string
  ) {
    const choosedSkillId = refSelectSkill.value;
    const choosedExperiencePeriodId = refSelectExperiencePeriod.value;

    if (choosedSkillId === "" || choosedExperiencePeriodId === "") {
      refSelectSkill.classList.add("error");
      refSelectExperiencePeriod.classList.add("error");
      console.warn(
        `あれ?${
          this.skillKind[experienceSkillKindName].jp
        }または期間が入力されてない...`
      );
      return;
    }

    const hasExperince = experiences.some(experiences => {
      return (
        parseInt(experiences[experienceSkillKindName].id) ===
        parseInt(choosedSkillId)
      );
    });
    if (hasExperince) {
      console.warn("経験が重複しています。");
      return;
    }

    const params = new URLSearchParams();
    params.append("employee_id", this.getEmployeeIdFromUrl());
    params.append(`${experienceSkillKindName}_id`, choosedSkillId);
    params.append("experience_period_id", choosedExperiencePeriodId);
    window.axios
      .post(storePath, params)
      .then(response => {
        // js側で全て読み込み直し
        this.getInitDatas();
        // 入力を元に戻す。
        refSelectSkill.selectedIndex = 0;
        refSelectExperiencePeriod.selectedIndex = 0;
      })
      .catch(function(error) {
        console.log(error);
      });
  }

  private appendSkillLanguage(payload: any) {
    this.appendSkill(
      payload.refSelectSkill as HTMLSelectElement,
      payload.refSelectExperiencePeriod as HTMLSelectElement,
      this.languageExperiences,
      this.experienceSkillKindNames.language,
      `/api/languageExperiences`
    );
  }

  private appendSkillFramework(payload: any) {
    this.appendSkill(
      payload.refSelectSkill as HTMLSelectElement,
      payload.refSelectExperiencePeriod as HTMLSelectElement,
      this.frameworkExperiences,
      this.experienceSkillKindNames.framework,
      `/api/frameworkExperiences`
    );
    return;
    const choosedFrameworkId = (this.$refs.selectFramework as any).value;
    const choosedExperiencePeriodId = (this.$refs
      .selectExperiencePeriodFramework as any).value;
    if (choosedFrameworkId === "" || choosedExperiencePeriodId === "") {
      console.warn("あれ?フレームワークまたは期間が入力されてない...");
      return;
    }
    const hasExperinceFramework = this.frameworkExperiences.some(
      frameworkExperience => {
        return (
          parseInt(frameworkExperience.framework.id) ===
          parseInt(choosedFrameworkId)
        );
      }
    );
    if (hasExperinceFramework) {
      console.warn("フレームワークが重複しています。");
      return;
    }
    const params = new URLSearchParams();
    params.append("employee_id", this.getEmployeeIdFromUrl());
    params.append("framework_id", choosedFrameworkId);
    params.append("experience_period_id", choosedExperiencePeriodId);
    window.axios
      .post(`/api/frameworkExperiences`, params)
      .then(response => {
        console.log(response);
        // js側で全て読み込み直し
        this.getInitDatas();
        // 入力を元に戻す。
        (this.$refs.selectFramework as HTMLSelectElement).selectedIndex = 0;
        (this.$refs
          .selectExperiencePeriodFramework as HTMLSelectElement).selectedIndex = 0;
      })
      .catch(function(error) {
        console.log(error);
      });
  }

  private getEmployeeIdFromUrl(): string {
    return location.href.substring(location.href.lastIndexOf("/") + 1);
  }

  private mounted(): void {
    this.getInitDatas();
  }

  private getInitDatas(): void {
    const employeeId = this.getEmployeeIdFromUrl();
    window.axios
      .get(`/api/employees/${employeeId}`)
      .then(response => {
        this.employee = response.data.employee;
        this.languageExperiences = response.data.languageExperiences;
        this.frameworkExperiences = response.data.frameworkExperiences;
      })
      .catch(function(error) {
        console.log(error);
      });
  }

  get options() {
    return optionModule.options;
  }
}
</script>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";

img.iconTrash {
  width: 20px;
  cursor: pointer;
}

.vue-notification {
  padding: 32px 10px;
}

.skills {
  &__title {
    font-size: 1.75rem;
  }
}

.skill {
  &__title {
    font-size: 1.5rem;
  }
}
ul.skillColumns {
  margin-bottom: 8px;
  li {
    .header {
      display: flex;
      align-items: center;
      .languageName {
        margin-right: 8px;
      }
    }
  }
}

ul.skillChooses {
  li {
    display: inline-block;
  }
}

.skillAppend {
  border: 1px solid $colorBorderGray;
  border-radius: 5px;
  padding: 8px;

  &--selections {
    display: flex;
    select {
      margin-right: 8px;
    }
  }

  &--form {
    display: flex;
    select {
      margin-right: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      &.error {
        border: 1px solid #ff0000;
      }
    }
  }
}
</style>
