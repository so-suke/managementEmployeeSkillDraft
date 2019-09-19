import { getEmployeeIdFromUrl } from "../util";

const existsSkill = async (path: string) => {
  return window.axios.get(path)
    .then(response => {
      if (response.data.exists) {
        return false;
      } else {
        return true;
      }
    })
    .catch(error => {
      console.warn(error);
      return true;
    });
}

export const exists = {
  language: async (skillId: string) => {
    return existsSkill(`/api/languages/existsLanguage/${getEmployeeIdFromUrl()}/${skillId}`);
  },
  framework: async (skillId: string) => {
    return existsSkill(`/api/frameworks/existsFramework/${getEmployeeIdFromUrl()}/${skillId}`);
  },
  other: async (skillId: string) => {
    return existsSkill(`/api/others/existsOther/${getEmployeeIdFromUrl()}/${skillId}`);
  },
}
