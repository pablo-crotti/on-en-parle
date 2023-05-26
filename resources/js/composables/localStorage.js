import { ref, watch } from "vue";

export function useLocalStorage(key, defaultValue = null) {
  let data = localStorage.getItem(key);

  if (data === null) {
    data = defaultValue;
    localStorage.setItem(key, JSON.stringify(data));
  } else {
    data = JSON.parse(data);
  }

  const value = ref(data);

  watch(value, () => {
    localStorage.setItem(key, JSON.stringify(value.value));
  }, { deep: true });

  return {value};
}