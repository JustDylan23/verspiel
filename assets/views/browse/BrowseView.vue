<template>
  <div>
    <p class="text-white-50">
      Browsing {{ $route.params.type === 'novels' ? 'novels' : 'chapters' }}
    </p>
    <div class="mb-2 position-relative">
      <input
        ref="searchField"
        v-model="query.title"
        class="app-search form-control me-2 ps-5"
        type="search"
        placeholder="Search"
        aria-label="Search"
      />
      <i class="bi bi-search position-absolute left-0 top-0 mt-2 ms-3"></i>
    </div>
    <component :is="resultComponent" :items="items" class="mb-3 flex-grow-1" />
    <p v-if="items.length === 0" class="text-white-50 fs-5">
      No results found!
    </p>
    <AppPagination
      v-if="!isLoading && perPage < rows"
      v-model="query.page"
      :total-rows="rows"
      :per-page="perPage"
    />
  </div>
</template>

<script setup>
import axios from 'axios';
import AppPagination from '@/components/pagination/AppPagination.vue';
import NovelList from '@/components/list/NovelList.vue';
import ChapterList from '@/components/list/ChapterList.vue';
import {
  debounceQueryParam,
  getQueryParam,
  paginate,
} from '@/utils/paginator.js';
import { reactive, ref } from 'vue';
import { onStartTyping } from '@vueuse/core';
import { useRoute } from 'vue-router';

const route = useRoute();
const resultComponent =
  route.params.type === 'novels' ? NovelList : ChapterList;

const searchField = ref();
onStartTyping(() => {
  if (!searchField.value?.active) {
    searchField.value.focus();
    searchField.value.scrollIntoView();
  }
});

const query = reactive({
  page: getQueryParam('page', 1, (v) => parseInt(v, 10) || 1),
  title: debounceQueryParam(route.query.search || ''),
});

const getNovels = async (query) =>
  axios.get('/api/' + route.params.type + query);

const { isLoading, items, perPage, rows } = await paginate(getNovels, query);
</script>
