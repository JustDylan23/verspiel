<template>
  <AppBreadcrumb :nav="nav" />
  <div itemscope itemtype="https://schema.org/Book">
    <h1 itemprop="name">{{ novel.title }}</h1>
    <div itemprop="about" v-html="novel.description" />
    <h4>Chapters:</h4>
    <p v-if="novel.chapters.length === 0" class="text-white-50 fs-5">
      No chapters added yet!
    </p>
    <div class="list-group" itemprop="hasPart">
      <RouterLink
        v-for="chapter in novel.chapters"
        :key="chapter.id"
        class="list-group-item list-group-item-action d-flex ps-0"
        :to="{ name: 'chapter', params: { id: chapter.id } }"
      >
        <div
          class="d-flex align-items-center justify-content-center"
          style="width: 60px"
        >
          <i class="bi bi-bookmark" />
        </div>
        <div>
          <div>
            Chapter {{ chapter.number }}
            {{ chapter.title ? '- ' + chapter.title : '' }}
          </div>
          <small>{{ formatDateString(chapter.createdAt) }}</small>
        </div>
      </RouterLink>
    </div>
  </div>
  <Suspense>
    <CommentSection :comment-section="novel.commentSection.id" />
    <template #fallback>
      <div class="mt-4">
        <span class="spinner-border spinner-border-sm"></span>
        Loading comment section...
      </div>
    </template>
  </Suspense>
</template>

<script setup>
import axios from 'axios';
import { reactive } from 'vue';
import { useRoute } from 'vue-router';
import AppBreadcrumb from '@/components/breadcrumb/AppBreadcrumb.vue';
import CommentSection from '@/components/comment/CommentSection.vue';
import { formatDateString } from '@/utils/date';
import { useHead } from '@vueuse/head';

const route = useRoute();

const novel = reactive(
  await axios.get('/api/novels/' + route.params.id).then(({ data }) => data)
);

const nav = [
  { name: 'Home', to: { name: 'home' } },
  { name: novel.title, to: '' },
];

useHead({
  title: novel.title + ' - ' + 'Verspiel',
  meta: [
    {
      name: 'description',
      content: novel.shortDescription,
    },
  ],
});
</script>
