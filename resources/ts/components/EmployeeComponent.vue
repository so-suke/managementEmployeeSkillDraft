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
      <div class="skill">
        <h3 class="skill__title">言語</h3>
        <ul class="skillColumns">
          <li v-for="languageExperience in languageExperiences" v-bind:key="languageExperience.id">
            <div class="header">
              <p class="languageName">{{ languageExperience.language.name }}</p>
              <img
                class="iconTrash"
                :src="'/images/trash_icon.png'"
                alt="iconTrash"
                @click="deleteLanguageExperience(languageExperience.id)"
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
              <select name="choosedLanguageID" ref="selectionLanguageID">
                <option value>言語を選択してください</option>
                <option
                  :value="`${language.id}`"
                  v-for="language in languages"
                  v-bind:key="language.id"
                >{{ language.name }}</option>
              </select>
              <select name="choosedExperiencePeriodID" ref="selectionExperiencePeriodID">
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
      </div>
    </div>
  </div>
</template>

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
    }
  }
}
</style>

<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";
import { optionModule } from "../store/module/Option";
import { setTimeout } from "timers";

@Component
export default class ExampleComponent extends Vue {
  csrf: any = (document.querySelector(
    'meta[name="csrf-token"]'
  ) as any).getAttribute("content");
  employees: any[] = [];
  employee: any = {};
  languageExperiences: any[] = [];
  canAppendSkillLanguage: boolean = false;
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

  private enableAppendSkillLanguage() {
    this.canAppendSkillLanguage = true;
  }

  private deleteLanguageExperience(languageExperienceID: number) {
    console.log("delete langu expe" + languageExperienceID);
    this.$notify({
      group: "manipulation",
      title: "Success Delete Language Expression",
      type: "success",
      duration: 15000,
    });
    return;
    window.axios
      .delete(`/api/languageExperiences/${languageExperienceID}`)
      .then(response => {
        // とりあえずメッセージ見る
        console.log(response);
        // 最後、全て読み込み直し
        this.getLanguageExperiences();
      })
      .catch(function(error) {
        console.log(error);
      });
  }

  private appendSkillLanguage() {
    const choosedLanguageID = (this.$refs.selectionLanguageID as any).value;
    const choosedExperiencePeriodID = (this.$refs
      .selectionExperiencePeriodID as any).value;
    console.log(choosedLanguageID);
    // todo tryCatch
    if (choosedLanguageID === "" || choosedExperiencePeriodID === "") {
      console.warn("あれ?言語または期間が入力されてない...");
      return;
    }
    const hasLanguage = this.languageExperiences.some(languageExperience => {
      return (
        parseInt(languageExperience.language.id) === parseInt(choosedLanguageID)
      );
    });
    if (hasLanguage) {
      console.warn("言語が重複しています。");
      return;
    }
    // データを送る,employee_id,language_id,experience_period_id
    const params = new URLSearchParams();
    params.append("employee_id", this.getEmployeeIdFromUrl());
    params.append("language_id", choosedLanguageID);
    params.append("experience_period_id", choosedExperiencePeriodID);
    window.axios
      .post(`/api/languageExperiences`, params)
      .then(response => {
        console.log(response);
        // js側で全て読み込み直し
        this.getLanguageExperiences();
        // 入力を元に戻す。
        (this.$refs.selectionLanguageID as HTMLSelectElement).selectedIndex = 0;
        (this.$refs
          .selectionExperiencePeriodID as HTMLSelectElement).selectedIndex = 0;
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
    const employeeID = this.getEmployeeIdFromUrl();
    window.axios
      .get(`/api/employees/${employeeID}`)
      .then(response => {
        this.employee = response.data.employee;
        this.languageExperiences = response.data.languageExperiences;
      })
      .catch(function(error) {
        console.log(error);
      });
  }

  private getLanguageExperiences(): void {
    const employeeID = this.getEmployeeIdFromUrl();
    window.axios
      .get(`/api/languageExperiences/${employeeID}`)
      .then(response => {
        this.languageExperiences = response.data.languageExperiences;
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
