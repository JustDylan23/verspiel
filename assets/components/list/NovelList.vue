<template>
  <div class="list-group">
    <RouterLink
      v-for="novel in items"
      :key="novel.id"
      :to="{ name: 'novel', params: { id: novel.id } }"
      class="list-group-item list-group-item-action ps-0 d-flex"
    >
      <div
        class="d-flex align-items-center justify-content-center"
        style="width: 60px"
      >
        <i class="bi bi-book" />
      </div>
      <div class="flex-grow-1 text-decoration-none d-flex flex-column">
        <h5 class="mb-1 text-truncate">
          {{ novel.title }}
        </h5>
        <small class="text-truncate">{{ novel.description }}</small>
      </div>
      <small>{{ formatDateString(novel.createdAt) }}</small>
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
