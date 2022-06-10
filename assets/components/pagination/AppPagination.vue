<template>
  <ul class="pagination">
    <li
      class="page-item"
      :class="{ disabled: !hasPrevious }"
      @click="$emit('update:modelValue', 1)"
    >
      <span class="page-link"><i class="bi bi-chevron-double-left"></i></span>
    </li>
    <li
      class="page-item"
      :class="{ disabled: !hasPrevious }"
      @click="previousPage"
    >
      <span class="page-link"><i class="bi bi-chevron-left" /></span>
    </li>
    <li
      v-for="i in range"
      :key="i"
      class="page-item"
      :class="{ active: i === modelValue }"
      @click="$emit('update:modelValue', i)"
    >
      <a class="page-link" href="#">{{ i }}</a>
    </li>
    <li class="page-item" :class="{ disabled: !hasNext }" @click="nextPage">
      <a class="page-link" href="#"><i class="bi bi-chevron-right" /></a>
    </li>
    <li
      class="page-item"
      :class="{ disabled: !hasNext }"
      @click="$emit('update:modelValue', lastPage)"
    >
      <a class="page-link" href="#"><i class="bi bi-chevron-double-right" /></a>
    </li>
  </ul>
</template>

<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:modelValue']);
const props = defineProps({
  modelValue: {
    type: [Number],
    required: true,
  },
  totalRows: {
    type: [Number],
    required: true,
  },
  perPage: {
    type: [Number],
    required: true,
  },
});

const lastPage = computed(() => Math.ceil(props.totalRows / props.perPage));
const hasPrevious = computed(() => props.modelValue > 1);
const hasNext = computed(() => props.modelValue < lastPage.value);

const range = computed(() => {
  const start = Math.max(1, props.modelValue - 2);
  const end = Math.min(lastPage.value, props.modelValue + 2);
  return Array.from({ length: end - start + 1 }, (_, i) => start + i);
});

const previousPage = () => {
  if (hasPrevious.value) {
    emit('update:modelValue', Number(props.modelValue) - 1);
  }
};

const nextPage = () => {
  if (hasNext.value) {
    emit('update:modelValue', Number(props.modelValue) + 1);
  }
};
</script>
