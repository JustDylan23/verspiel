<template>
  <h3 class="mt-5">Comment section</h3>
  <Suspense>
    <CommentList :comment-section="commentSection" />
    <template #fallback>
      <div class="mt-4">
        <span class="spinner-border spinner-border-sm"></span>
        Loading comment section...
      </div>
    </template>
  </Suspense>
</template>

<script setup>
import CommentList from '@/components/comment/CommentList.vue';
import { onBeforeUnmount } from 'vue';
import replyBus from '@/components/comment/replyBus.js';

defineProps({
  commentSection: {
    type: Number,
    required: true,
  },
});

onBeforeUnmount(() => {
  replyBus.all.clear();
});
</script>
