<template>
  <div class="list-group">
    <BaseListItem
      v-for="novel in items"
      :key="novel.id"
      :to="{ name: 'novel', params: { id: novel.id } }"
      :created-at="novel.createdAt"
      icon="bi bi-book"
    >
      <template #header>{{ novel.title }}</template>
      <template #description>{{ novel.shortDescription }}</template>
    </BaseListItem>
  </div>
</template>

<script setup>
import BaseListItem from '@/components/list/BaseListItem.vue';

defineProps({
  items: {
    type: Array,
    required: true,
  },
});
</script>
