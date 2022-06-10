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
        style="min-width: 60px"
      >
        <i class="bi bi-bookmark" />
      </div>
      <div class="flex-grow-1" style="min-width: 0">
        <div class="d-flex">
          <div class="fs-5 flex-grow-1 text-truncate">
            {{ chapter.novel.title }} asdfasdfasdfaSF
          </div>
          <small class="text-nowrap">
            {{ formatDateString(chapter.createdAt) }}
          </small>
        </div>
        <div class="text-truncate">
          Chapter {{ chapter.number }}
          {{ chapter.title ? '- ' + chapter.title : '' }}
        </div>
      </div>
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
