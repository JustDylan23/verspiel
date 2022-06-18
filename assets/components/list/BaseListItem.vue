<template>
  <RouterLink
    class="list-group-item list-group-item-action ps-0 d-flex"
    :to="to"
  >
    <div
      class="d-flex align-items-center justify-content-center"
      style="min-width: 60px"
    >
      <i :class="icon" />
    </div>
    <div class="flex-grow-1" style="min-width: 0">
      <div class="d-flex">
        <div class="fs-5 flex-grow-1 text-truncate me-2">
          <slot name="header" />
        </div>
        <small class="text-nowrap">
          {{ formatDateString(createdAt) }}
        </small>
      </div>
      <div class="text-truncate">
        <slot name="description" />
      </div>
    </div>
  </RouterLink>
</template>

<script setup>
import { formatDateString } from '@/utils/date';

defineProps({
  icon: {
    type: String,
    require: true,
  },
  to: {
    type: Object,
    require: true,
  },
  createdAt: {
    type: String,
    require: true,
  },
});
</script>
