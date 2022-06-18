<template>
  <TransitionGroup
    name="list"
    tag="div"
    class="toast-container top-0 end-0 m-3 position-fixed"
  >
    <div
      v-for="toast of notifications"
      ref="toast"
      :key="toast"
      class="toast show align-items-center"
      :class="'text-bg-' + toast.variant"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div class="d-flex">
        <div class="toast-body">
          {{ toast.message }}
        </div>
        <button
          type="button"
          class="btn-close btn-close-white me-2 m-auto"
          data-bs-dismiss="toast"
          aria-label="Close"
        ></button>
      </div>
    </div>
  </TransitionGroup>
</template>

<script setup>
import { useNotifications } from '@/utils/notification.js';

const { notifications } = useNotifications();
</script>

<style>
.list-move, /* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}
</style>
