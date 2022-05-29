<template>
  <div class="list-group">
    <RouterLink
      v-for="chapter in items"
      :key="chapter.id"
      :to="{ name: 'chapter', params: { id: chapter.id } }"
      class="list-group-item list-group-item-action ps-0 d-flex"
    >
      <div
        class="d-flex align-items-center justify-content-center"
        style="width: 60px"
      >
        <i class="bi bi-bookmark" />
      </div>
      <div class="flex-grow-1 text-decoration-none d-flex flex-column">
        <div class="fs-5">
          {{ chapter.novel.title }}
        </div>
        <div>Chapter {{ chapter.number }}</div>
      </div>
      <small>{{ formatDateString(chapter.createdAt) }}</small>
    </RouterLink>
  </div>
</template>

<script setup>
import { formatDateString } from '@/utils/date';

defineProps({
  items: {
    type: Array,
    required: true,
  },
});
</script>
