<template>
  <div>
    <section class="skill">
      <div class="skill__title">
        <h3 class="text">{{ skillKind.ja }}</h3>
        <button class="ghostButton ghostButton--blue" @click="showAppendModal(skillKind)">
          <font-awesome-icon icon="plus" />
        </button>
      </div>
      <p v-show="experiences.length === 0">{{ messages.noSkillsRegisteredYet }}</p>
      <ul class="skills">
        <li v-for="experience in experiences" v-bind:key="experience.id">
          <p class="skillName">{{ experience[skillKind.en].name }}</p>
          <ul class="experiencePeriods">
            <li
              :class="{'noActive': !isMatchPeriod(experiencePeriod.id, experience.experience_period_id)}"
              v-for="experiencePeriod in experiencePeriods"
              v-bind:key="experiencePeriod.id"
            >
              <input
                type="radio"
                :id="`experience_period_${experiencePeriod.id}`"
                :name="`experience_period_${experience[skillKind.en].name}`"
                v-bind:value="`${experiencePeriod.id}`"
                :checked="isMatchPeriod(experiencePeriod.id, experience.experience_period_id)"
              />
              <label :for="`experience_period_${experiencePeriod.id}`">{{experiencePeriod.name}}</label>
            </li>
          </ul>
          <ul class="skillBtns">
            <li>
              <button
                class="ghostButton ghostButton--blue"
                @click="showUpdateExperiencePeriodModal(skillKind, experience)"
              >
                <img src="/images/edit_calendar_icon.png" alt />
              </button>
            </li>
            <li>
              <button
                class="ghostButton ghostButton--blue"
                @click="deleteExperience(skillKind.en, experience.id)"
              >
                <img src="/images/trash_icon.png" alt />
              </button>
            </li>
          </ul>
        </li>
      </ul>
    </section>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Emit, Vue, Watch } from "vue-property-decorator";
import ModalComponent from "@components/ModalComponent.vue";
import { noSkillsRegisteredYet } from "../myresources/messages";
import SkillBaseSelect from "./SkillBaseSelect.vue";
import { getEmployeeIdFromUrl } from "../util";
import { ValidationObserver, ValidationProvider } from "vee-validate";

@Component({
  components: {
    ValidationObserver,
    ValidationProvider,
    ModalComponent,
    SkillBaseSelect
  }
})
export default class SkillComponent extends Vue {
  readonly experiencePeriods = [
    { id: 1, name: "半年未満" },
    { id: 2, name: "半年から1年" },
    { id: 3, name: "1年から2年" },
    { id: 4, name: "2年から3年" },
    { id: 5, name: "3年以上" }
  ];

  readonly messages: { noSkillsRegisteredYet: string } = {
    noSkillsRegisteredYet
  };

  @Prop() private experiences!: any;
  @Prop() private skillKind!: any;

  private isMatchPeriod(
    periodIdEmployeeSelf: string,
    periodIdOneOfAll: string
  ) {
    return periodIdEmployeeSelf === periodIdOneOfAll;
  }

  @Emit("showModalClick")
  private showAppendModal(skillKind: any) {
    return skillKind;
  }

  @Emit("updateExperiencePeriodClick")
  private showUpdateExperiencePeriodModal(skillKind: any, experience: any) {
    return { skillKind, experience };
  }
  @Emit("deleteClick")
  private deleteExperience(skillKindEn: string, experienceId: number) {
    return { skillKindEn, experienceId };
  }
}
</script>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";

.skill {
  &__title {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 1.5rem;
    .text {
      margin-right: 8px;
    }
  }
}

ul.skillBtns {
  display: flex;
  li {
    &:not(:last-child) {
      margin-right: 8px;
    }
  }
}

ul.skills {
  margin-bottom: 8px;
  li {
    display: flex;
    align-items: center;
    margin-bottom: 0.25rem;
    .skillName {
      width: 200px;
      margin-right: 8px;
    }
  }
}

ul.experiencePeriods {
  margin-right: 1rem;
  li {
    pointer-events: none;
    display: inline-block;
    &.noActive {
      opacity: 0.5;
    }
  }
}
</style>
