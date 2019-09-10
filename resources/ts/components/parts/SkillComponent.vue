<template>
  <section class="skill">
    <h3 class="skill__title">{{ kindNameJp }}</h3>
    <ul class="skillColumns">
      <li v-for="experience in experiences" v-bind:key="experience.id">
        <div class="header">
          <p class="skillName">{{ experience[kindName].name }}</p>
          <img
            class="iconTrash"
            :src="'/images/trash_icon.png'"
            alt="iconTrash"
            @click="deleteExperience(experience.id)"
          />
        </div>
        <ul class="skillChooses">
          <li v-for="experiencePeriod in experiencePeriods" v-bind:key="experiencePeriod.id">
            <input
              type="radio"
              :id="`experience_period_${experiencePeriod.id}`"
              :name="`experience_period_${experience[kindName].name}`"
              v-bind:value="`${experiencePeriod.id}`"
              :checked="experiencePeriod.id == experience.experience_period_id"
            />
            <label :for="`experience_period_${experiencePeriod.id}`">{{experiencePeriod.name}}</label>
          </li>
        </ul>
      </li>
    </ul>
    <div class="switch">
      <button v-show="!canAppendSkill" @click="enableAppendSkill">+{{ kindNameJp }}を追加する</button>
      <div class="skillAppend" v-show="canAppendSkill">
        <p class="skillAppend--title">{{ kindNameJp }}の追加</p>
        <div class="skillAppend--form">
          <select ref="selectSkill">
            <option value>{{ kindNameJp }}を選択してください</option>
            <option
              :value="`${skill.id}`"
              v-for="skill in skills"
              v-bind:key="skill.id"
            >{{ skill.name }}</option>
          </select>
          <select ref="selectExperiencePeriod">
            <option value>期間を選択してください</option>
            <option
              :value="`${experiencePeriod.id}`"
              v-for="experiencePeriod in experiencePeriods"
              v-bind:key="experiencePeriod.id"
            >{{ experiencePeriod.name }}</option>
          </select>
          <button @click="appendSkill">追加</button>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";
</style>

<script lang="ts">
import { Component, Prop, Emit, Vue, Watch } from "vue-property-decorator";

@Component
export default class SkillComponent extends Vue {
  readonly experiencePeriods = [
    { id: 1, name: "半年未満" },
    { id: 2, name: "半年から1年" },
    { id: 3, name: "1年から2年" },
    { id: 4, name: "2年から3年" },
    { id: 5, name: "3年以上" }
  ];

  canAppendSkill: boolean = false;

  @Prop() private experiences!: any;
  @Prop() private kindName!: string;
  @Prop() private kindNameJp!: string;
  @Prop() private skills!: any;

  private enableAppendSkill() {
    this.canAppendSkill = true;
  }

  @Emit("appendSkill")
  public appendSkill() {
    return {
      refSelectSkill: this.$refs.selectSkill,
      refSelectExperiencePeriod: this.$refs.selectExperiencePeriod
    };
  }
  @Emit("deleteExperience")
  public deleteExperience(id: number) {
    return id;
  }
}
</script>

<style lang="scss" scoped>
@import "~@/variables/_variables.scss";

img.iconTrash {
  width: 20px;
  cursor: pointer;
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
      .skillName {
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
