<template>
  <div class="d-flex my-4">
    <div class="flex-grow-1 pe-3">
      <select
        class="form-select form-select-sm rounded-5"
        style="max-width: 300px"
        @change="
          $router.push({ name: 'chapter', params: { id: $event.target.value } })
        "
      >
        <option
          v-for="chap in chapters"
          :key="chap.id"
          :value="chap.id"
          :selected="chap.id == $route.params.id"
          class="text-bg-dark"
        >
          Chapter {{ chap.number }} {{ chap.title ? '- ' + chap.title : '' }}
        </option>
      </select>
    </div>
    <div class="d-flex">
      <RouterLink
        class="btn rounded-5 btn-sm d-flex justify-content-center me-3"
        :class="
          chapter.previousChapter !== null ? 'btn-primary' : 'btn-dark-grey'
        "
        :to="previousChapterLink"
      >
        <i class="bi bi-chevron-left" /> <span class="pe-1">Prev</span>
      </RouterLink>
      <RouterLink
        class="btn rounded-5 btn-sm d-flex justify-content-center"
        :class="chapter.nextChapter !== null ? 'btn-primary' : 'btn-dark-grey'"
        :to="nextChapterLink"
      >
        <span class="ps-1">Next</span> <i class="bi bi-chevron-right" />
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  chapter: {
    type: Object,
    required: true,
  },
  chapters: {
    type: Array,
    required: true,
  },
});

const previousChapterLink = computed(() => {
  return props.chapter.previousChapter !== null
    ? {
        name: 'chapter',
        params: { id: props.chapter.previousChapter },
      }
    : '';
});

const nextChapterLink = computed(() => {
  return props.chapter.nextChapter !== null
    ? {
        name: 'chapter',
        params: { id: props.chapter.nextChapter },
      }
    : '';
});
</script>
