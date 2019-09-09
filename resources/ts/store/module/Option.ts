import { Module, VuexModule, Action, Mutation, getModule } from 'vuex-module-decorators';
import store from '../index';

interface OptionType {
  id: number;
  value: string;
  label: string;
}

@Module({ dynamic: true, store, name: 'option', namespaced: true })
class Option extends VuexModule {
  public id: number = 0;
  public options: OptionType[] = [
    {
      id: 0,
      value: "Option_0",
      label: "Option_0"
    },
    {
      id: 1,
      value: "Option_1",
      label: "Option_1"
    },
    {
      id: 2,
      value: "Option_2",
      label: "Option_2"
    },
  ];

  @Mutation
  public addOption(option: OptionType) {
    this.options.push(option);
    this.id++;
  }
  public deleteOption(index: number) {
    this.options.splice(index, 1);
  }
}

export const optionModule = getModule(Option);
