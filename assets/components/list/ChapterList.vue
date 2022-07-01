<template>
  <div class="list-group">
    <BaseListItem
      v-for="chapter in items"
      :key="chapter.id"
      :to="{ name: 'chapter', params: { id: chapter.id } }"
      :created-at="chapter.createdAt"
      icon="bi bi-bookmark"
    >
      <template #header>{{ chapter.novel.title }}</template>
      <template #description>
        <div
          v-if="!chapter.published"
          class="badge rounded-pill text-bg-primary"
        >
          preview
        </div>
        Chapter {{ chapter.number }}
        {{ chapter.title ? '- ' + chapter.title : '' }}
      </template>
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
