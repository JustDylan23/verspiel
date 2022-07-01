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
      <BaseListItem
        v-for="chapter in novel.chapters"
        :key="chapter.id"
        :to="{ name: 'chapter', params: { id: chapter.id } }"
        :created-at="chapter.createdAt"
        icon="bi bi-bookmark"
        small-header
      >
        <template #header>
          Chapter {{ chapter.number }}
          {{ chapter.title ? '- ' + chapter.title : '' }}
        </template>
        <template #description>
          <div
            v-if="!chapter.published"
            class="badge rounded-pill text-bg-primary"
          >
            preview
          </div>
        </template>
      </BaseListItem>
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
import { useHead } from '@vueuse/head';
import BaseListItem from '@/components/list/BaseListItem.vue';

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
