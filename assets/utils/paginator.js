import { computed, ref, watch } from 'vue';
import { useRouteQuery } from '@vueuse/router';
import { useDebounceFn } from '@vueuse/core';

export const paginate = async (callable, query) => {
  const isLoading = ref(false);
  const items = ref([]);
  const perPage = ref(1);
  const rows = ref(0);

  const getItems = async () => {
    isLoading.value = true;
    const { data } = await callable(
      '?' + new URLSearchParams(query).toString()
    );

    items.value = data.items;
    rows.value = data.rows;
    perPage.value = data.perPage;
    isLoading.value = false;
  };

  watch(query, getItems);

  await getItems();

  return {
    isLoading,
    items,
    perPage,
    rows,
    getItems,
  };
};

export const getQueryParam = (name, def, parse) => {
  const queryItem = useRouteQuery(name, def); // or with a default value
  const actualValue = ref(queryItem.value);
  return computed({
    get() {
      return parse(actualValue.value);
    },
    set(value) {
      actualValue.value = value;
      queryItem.value = value.toString();
    },
  });
};

export const debounceQueryParam = (value) => {
  const actualValue = ref(value);

  const updateValue = useDebounceFn((value) => {
    actualValue.value = value;
  }, 800);

  return computed({
    get() {
      return actualValue.value;
    },
    set(value) {
      updateValue(value);
    },
  });
};
