<template>
  <div>
    <h3>Latest chapter updates:</h3>
    <ChapterList :items="latestChapters" class="mt-2" />
    <RouterLink
      :to="{ name: 'browse', params: { type: 'chapters' } }"
      class="mt-2 d-block"
    >
      View all <i class="bi bi-chevron-right"></i>
    </RouterLink>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import axios from 'axios';
import ChapterList from '@/components/list/ChapterList.vue';

const latestChapters = reactive(
  await axios.get('/api/chapters/latest').then(({ data }) => data)
);
</script>
