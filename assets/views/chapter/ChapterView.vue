<template>
  <AppBreadcrumb
    :nav="[
      { name: 'Home', to: { name: 'home' } },
      {
        name: chapter.novel.title,
        to: { name: 'novel', params: { id: chapter.novel.id } },
      },
      {
        name: 'Chapter ' + chapter.number + ' - ' + chapter.title,
        to: '',
      },
    ]"
  />
  <div
    ref="header"
    class="d-flex align-items-center mb-4 sticky-top py-2 bg-dark border-primary"
    style="top: -1px"
    :class="{ 'border-bottom': isTop }"
    itemscope
    itemtype="http://schema.org/Chapter"
  >
    <h3
      class="reader-header flex-grow-1 me-2 text-truncate mb-0"
      :class="{ 'fs-6 top': isTop }"
    >
      Chapter {{ chapter.number }}
      <span itemprop="name">
        {{ chapter.title ? '- ' + chapter.title : '' }}
      </span>
    </h3>
    <ReaderSettings
      v-model:width="settings.width"
      v-model:font-size="settings.fontSize"
      v-model:line-height="settings.lineHeight"
      :is-top="isTop"
    />
  </div>
  <ChapterSelector :chapter="chapter" :chapters="chapters" />
  <div class="card flex-grow-1 align-items-center">
    <div
      class="card-body reader text-break"
      :style="{
        width: settings.width + '%',
        fontSize: settings.fontSize + 'rem',
        lineHeight: settings.lineHeight,
      }"
      v-html="chapter.content"
    ></div>
  </div>
  <ChapterSelector :chapter="chapter" :chapters="chapters" />
  <Suspense>
    <CommentSection :comment-section="chapter.commentSection.id" />
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
import { onMounted, reactive, ref } from 'vue';
import { useRoute } from 'vue-router';
import AppBreadcrumb from '@/components/breadcrumb/AppBreadcrumb.vue';
import ChapterSelector from '@/views/chapter/components/ChapterSelector.vue';
import ReaderSettings from '@/views/chapter/components/ReaderSettings.vue';
import { useLocalStorage } from '@vueuse/core';
import CommentSection from '@/components/comment/CommentSection.vue';
import { useHead } from '@vueuse/head';

const route = useRoute();

const chapter = reactive(
  await axios.get('/api/chapters/' + route.params.id).then(({ data }) => data)
);

const chapters = reactive(
  await axios
    .get('/api/novels/' + chapter.novel.id + '/chapters')
    .then(({ data }) => data)
);

const settings = useLocalStorage('reader_settings', {
  width: 100,
  fontSize: 1,
  lineHeight: 1.5,
});
const header = ref();
const isTop = ref(false);
onMounted(() => {
  const observer = new IntersectionObserver(
    ([e]) => {
      isTop.value = e.intersectionRatio < 1;
    },
    { threshold: [1] }
  );
  observer.observe(header.value);
});
useHead({
  title:
    'Chapter ' + chapter.number + ' - ' + chapter.novel.title + ' - Verspiel',
  meta: [
    {
      name: 'description',
      content: chapter.novel.shortDescription,
    },
  ],
});
</script>

<style>
.reader {
  transition: all 0.2s ease;
}

.reader-header.top {
  transition: all 0.3s ease;
}
</style>
