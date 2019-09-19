<template>
  <div>
    <notifications classes="myNotifications" group="manipulation" position="top center" />
    <notifications group="select" position="top right" />

    <ValidationObserver v-slot="{ validate }">
      <modal-component ref="appendModal" @close="resetForm">
        <h1 slot="header">{{ modal.append.skillKind.ja }}を追加します</h1>

        <div class="skillForm" slot="body">
          <ValidationProvider
            :name="modal.append.skillKind.ja"
            :rules="modal.append.skillRules"
            v-slot="{ errors }"
          >
            <div ref="formColumn" class="formColumn" v-bind:class="{ error: errors.length > 0 }">
              <select
                name="selectSkill"
                ref="selectSkill"
                :data-what-select-jp="modal.append.skillKind.ja"
                v-model="selectedSkill"
              >
                <option value>{{ modal.append.skillKind.ja }}を選択してください</option>
                <option
                  :value="`${item.id}`"
                  v-for="item in modal.append.skills"
                  v-bind:key="item.id"
                >{{ item.name }}</option>
              </select>
              <p class="errorMessage">{{ errors[0] }}</p>
            </div>
          </ValidationProvider>

          <ValidationProvider name="期間" rules="requiredSelect" v-slot="{ errors }">
            <div ref="formColumn" class="formColumn" v-bind:class="{ error: errors.length > 0 }">
              <select
                name="selectExperiencePeriod"
                ref="selectExperiencePeriod"
                data-what-select-jp="期間"
                v-model="selectedExperiencePeriod"
              >
                <option value>期間を選択してください</option>
                <option
                  :value="`${item.id}`"
                  v-for="item in experiencePeriods"
                  v-bind:key="item.id"
                >{{ item.name }}</option>
              </select>
              <p class="errorMessage">{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>

        <div slot="footer">
          <button
            class="ghostButton ghostButton--blue"
            @click="appendSkillExperience(validate, modal.append.skillKind.en)"
          >追加</button>
        </div>
      </modal-component>
    </ValidationObserver>

    <ValidationObserver v-slot="{ validate }">
      <modal-component ref="updateExperiencePeriodModal">
        <h1 slot="header">{{ modal.updateExperiencePeriod.skillNameJa }}の経験期間を更新します</h1>

        <div class="skillForm" slot="body">
          <ValidationProvider name="期間" rules="requiredSelect" v-slot="{ errors }">
            <div ref="formColumn" class="formColumn" v-bind:class="{ error: errors.length > 0 }">
              <select
                name="selectExperiencePeriodUpdate"
                ref="selectExperiencePeriodUpdate"
                data-what-select-jp="期間"
                v-model="selectExperiencePeriodUpdate"
              >
                <option value>期間を選択してください</option>
                <option
                  :value="`${item.id}`"
                  v-for="item in experiencePeriods"
                  v-bind:key="item.id"
                >{{ item.name }}</option>
              </select>
              <p class="errorMessage">{{ errors[0] }}</p>
            </div>
          </ValidationProvider>
        </div>

        <div slot="footer">
          <button class="ghostButton ghostButton--blue" @click="updateExperiencePeriod(validate)">更新</button>
        </div>
      </modal-component>
    </ValidationObserver>

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
        :skillKind="skillKind.language"
        @showModalClick="showAppendModal"
        @updateExperiencePeriodClick="showUpdateExperiencePeriodModal"
        @deleteClick="deleteExperience"
      ></skill-component>

      <skill-component
        :experiences="frameworkExperiences"
        :skillKind="skillKind.framework"
        @showModalClick="showAppendModal"
        @updateExperiencePeriodClick="showUpdateExperiencePeriodModal"
        @deleteClick="deleteExperience"
      ></skill-component>

      <skill-component
        :experiences="otherExperiences"
        :skillKind="skillKind.other"
        @showModalClick="showAppendModal"
        @updateExperiencePeriodClick="showUpdateExperiencePeriodModal"
        @deleteClick="deleteExperience"
      ></skill-component>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from "vue-property-decorator";
import { optionModule } from "../store/module/Option";
import SkillComponent from "../components/SkillComponent.vue";
import SkillBaseSelect from "../components/SkillBaseSelect.vue";
import ModalComponent from "../components/ModalComponent.vue";
import { ValidationObserver, ValidationProvider } from "vee-validate";
import { getEmployeeIdFromUrl, capitalize } from "../util";

@Component({
  components: {
    ModalComponent,
    SkillComponent,
    SkillBaseSelect,
    ValidationObserver,
    ValidationProvider
  }
})
export default class ExampleComponent extends Vue {
  csrf: any = (document.querySelector(
    'meta[name="csrf-token"]'
  ) as any).getAttribute("content");
  value: string = "";
  employees: any[] = [];
  employee: any = {};
  languageExperiences: any[] = [];
  frameworkExperiences: any[] = [];
  otherExperiences: any[] = [];
  modal: any = {
    append: {
      skillKind: {},
      skills: [],
      skillRules: ""
    },
    updateExperiencePeriod: {
      skillKind: {},
      skillNameJa: "",
      skillExperienceId: ""
    }
  };

  readonly skillKind: any = {
    language: {
      en: "language",
      ja: "言語",
    },
    framework: {
      en: "framework",
      ja: "フレームワーク",
    },
    other: {
      en: "other",
      ja: "その他",
    }
  };

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
  @Prop() protected others!: any;

  selectedSkill: string = "";
  selectedExperiencePeriod: string = "";
  selectExperiencePeriodUpdate: string = "";

  //todo
  skillRules: string = "requiredSelect|uniqueSkillLanguage";

  private mounted(): void {
    this.init();
  }

  private confirmDelete() {
    return confirm("本当に削除しますか？");
  }

  private resetForm() {
    this.selectedSkill = "";
    this.selectedExperiencePeriod = "";
  }

  private get experiencedLanguages() {
    return this.languageExperiences.map(languageExperience => {
      return languageExperience.language;
    });
  }

  private getLanguageExperienceId(
    experienceSkillKindName: string,
    skillId: string
  ): string {
    return this.languageExperiences.find(languageExperience => {
      return (
        languageExperience[experienceSkillKindName].id === parseInt(skillId)
      );
    }).id;
  }

  private init(): void {
    const employeeId = getEmployeeIdFromUrl();
    window.axios
      .get(`/api/employees/${employeeId}`)
      .then(response => {
        this.employee = response.data.employee;
        this.languageExperiences = response.data.languageExperiences;
        this.frameworkExperiences = response.data.frameworkExperiences;
        this.otherExperiences = response.data.otherExperiences;
      })
      .catch(function(error) {
        console.log(error);
      });
  }

  private appendSkillExperience(validate: any, skillKindName: string) {
    validate().then((valid: any) => {
      if (valid === false) return;
      const skillId = (this.$refs.selectSkill as HTMLSelectElement).value;
      const experiencePeriodId = (this.$refs
        .selectExperiencePeriod as HTMLSelectElement).value;

      const params = new URLSearchParams();
      params.append("employee_id", getEmployeeIdFromUrl());
      params.append(`${skillKindName}_id`, skillId);
      params.append("experience_period_id", experiencePeriodId);
      window.axios
        .post(`/api/${skillKindName}Experiences`, params)
        .then(response => {
          this.init();
          (this.$refs.appendModal as ModalComponent).close();
        })
        .catch(function(error) {
          console.warn(error);
        });
    });
  }

  private updateExperiencePeriod(validate: any) {
    validate().then((valid: any) => {
      if (valid === false) return;
      const params = new URLSearchParams();
      params.append(
        "experience_period_id",
        (this.$refs.selectExperiencePeriodUpdate as HTMLSelectElement).value
      );
      window.axios
        .put(
          `/api/${this.modal.updateExperiencePeriod.skillKind.en}Experiences/${
            this.modal.updateExperiencePeriod.skillExperienceId
          }`,
          params
        )
        .then(response => {
          this.init();
          (this.$refs.updateExperiencePeriodModal as ModalComponent).close();
        })
        .catch(function(error) {
          console.warn(error);
        });
    });
  }

  private showAppendModal(skillKind: any) {
    this.modal.append.skillKind = skillKind;
    this.modal.append.skillRules = `requiredSelect|uniqueSkill${capitalize(skillKind.en)}`;
    if (skillKind.en === this.skillKind.language.en) {
      this.modal.append.skills = this.languages;
    } else if (skillKind.en === this.skillKind.framework.en) {
      this.modal.append.skills = this.frameworks;
    } else if (skillKind.en === this.skillKind.other.en) {
      this.modal.append.skills = this.others;
    }
    (this.$refs.appendModal as ModalComponent).show();
  }

  private showUpdateExperiencePeriodModal(payload: any) {
    this.modal.updateExperiencePeriod.skillNameJa =
      payload.experience[payload.skillKind.en].name;
    this.modal.updateExperiencePeriod.skillKind = payload.skillKind;
    this.modal.updateExperiencePeriod.skillExperienceId = payload.experience.id;
    (this.$refs.updateExperiencePeriodModal as ModalComponent).show();
  }

  private deleteExperience(payload: any) {
    const isPermit = confirm("本当に削除してもよろしいでしょうか");
    if (isPermit === false) return;
    window.axios
      .delete(`/api/${payload.skillKindEn}Experiences/${payload.experienceId}`)
      .then(response => {
        this.init();
      })
      .catch(function(error) {
        console.warn(error);
      });
  }
}
</script>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";

.skillForm {
  .formColumn {
    &.error {
      select {
        border: 1px solid #ff0000;
      }
      .errorMessage {
        display: block;
        color: #ff0000;
        font-size: 0.75rem;
      }
    }
    select {
      width: 100%;
      margin-bottom: 4px;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 8px;
    }
  }
}
</style>
