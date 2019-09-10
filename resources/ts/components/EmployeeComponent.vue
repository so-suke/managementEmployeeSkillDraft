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

      <skill-component :experiences="languageExperiences" kindName="言語" :skills="languages" @appendSkill="appendSkillLanguage"></skill-component>

      <section class="skill">
        <h3 class="skill__title">言語</h3>
        <ul class="skillColumns">
          <li v-for="languageExperience in languageExperiences" v-bind:key="languageExperience.id">
            <div class="header">
              <p class="languageName">{{ languageExperience.language.name }}</p>
              <img
                class="iconTrash"
                :src="'/images/trash_icon.png'"
                alt="iconTrash"
                @click="deleteExperienceLanguage(languageExperience.id)"
              />
            </div>
            <ul class="skillChooses">
              <li v-for="experiencePeriod in experiencePeriods" v-bind:key="experiencePeriod.id">
                <input
                  type="radio"
                  :id="`experience_period_${experiencePeriod.id}`"
                  :name="`experience_period_${languageExperience.language.name}`"
                  v-bind:value="`${experiencePeriod.id}`"
                  :checked="experiencePeriod.id == languageExperience.experience_period_id"
                />
                <label :for="`experience_period_${experiencePeriod.id}`">{{experiencePeriod.name}}</label>
              </li>
            </ul>
          </li>
        </ul>
        <div class="switch">
          <button v-show="!canAppendSkillLanguage" @click="enableAppendSkillLanguage">+言語を追加する</button>
          <div class="skillAppend" v-show="canAppendSkillLanguage">
            <p class="skillAppend--title">言語の追加</p>
            <div class="skillAppend--form">
              <select name="choosedLanguageId" ref="selectLanguage">
                <option value>言語を選択してください</option>
                <option
                  :value="`${language.id}`"
                  v-for="language in languages"
                  v-bind:key="language.id"
                >{{ language.name }}</option>
              </select>
              <select name="choosedExperiencePeriodId" ref="selectExperiencePeriodLanguage">
                <option value>期間を選択してください</option>
                <option
                  :value="`${experiencePeriod.id}`"
                  v-for="experiencePeriod in experiencePeriods"
                  v-bind:key="experiencePeriod.id"
                >{{ experiencePeriod.name }}</option>
              </select>
              <button @click="appendSkillLanguage">追加</button>
            </div>
          </div>
        </div>
      </section>

      <section class="skill">
        <h3 class="skill__title">フレームワーク</h3>
        <ul class="skillColumns">
          <li
            v-for="frameworkExperience in frameworkExperiences"
            v-bind:key="frameworkExperience.id"
          >
            <div class="header">
              <p class="frameworkName">{{ frameworkExperience.framework.name }}</p>
              <img
                class="iconTrash"
                :src="'/images/trash_icon.png'"
                alt="iconTrash"
                @click="deleteExperienceFramework(frameworkExperience.id)"
              />
            </div>
            <ul class="skillChooses">
              <li v-for="experiencePeriod in experiencePeriods" v-bind:key="experiencePeriod.id">
                <input
                  type="radio"
                  :id="`experience_period_${experiencePeriod.id}`"
                  :name="`experience_period_${frameworkExperience.framework.name}`"
                  v-bind:value="`${experiencePeriod.id}`"
                  :checked="experiencePeriod.id == frameworkExperience.experience_period_id"
                />
                <label :for="`experience_period_${experiencePeriod.id}`">{{experiencePeriod.name}}</label>
              </li>
            </ul>
          </li>
        </ul>
        <div class="switch">
          <button
            v-show="!canAppendSkillFramework"
            @click="enableAppendSkillFramework"
          >+フレームワークを追加する</button>
          <div class="skillAppend" v-show="canAppendSkillFramework">
            <p class="skillAppend--title">フレームワークの追加</p>
            <div class="skillAppend--form">
              <select ref="selectFramework">
                <option value>フレームワークを選択してください</option>
                <option
                  :value="`${framework.id}`"
                  v-for="framework in frameworks"
                  v-bind:key="framework.id"
                >{{ framework.name }}</option>
              </select>
              <select name="choosedExperiencePeriodId" ref="selectExperiencePeriodFramework">
                <option value>期間を選択してください</option>
                <option
                  :value="`${experiencePeriod.id}`"
                  v-for="experiencePeriod in experiencePeriods"
                  v-bind:key="experiencePeriod.id"
                >{{ experiencePeriod.name }}</option>
              </select>
              <button @click="appendSkillFramework">追加</button>
            </div>
          </div>
        </div>
      </section>
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

  toJpMap: any = new Map([
    ["language", "言語"],
    ["framework", "フレームワーク"]
  ]);

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

  private deleteExperience(id: number, url: string) {
    window.axios
      .delete(`/api/languageExperiences/${id}`)
      .then(response => {
        // とりあえずメッセージ見る
        console.log(response);
        this.$notify({
          group: "manipulation",
          type: "success",
          title: "Success Delete Language Expression",
          text: (<any>response).message
        });
        // 最後、全て読み込み直し
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
        `あれ?${this.toJpMap.get(
          experienceSkillKindName
        )}または期間が入力されてない...`
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

  private appendSkillFramework() {
    this.appendSkill(
      this.$refs.selectFramework as HTMLSelectElement,
      this.$refs.selectExperiencePeriodFramework as HTMLSelectElement,
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
