<template>
  <HomeCarousel />
  <HomeChapterList class="mt-4" />
</template>

<script setup>
import HomeCarousel from '@/views/home/components/HomeCarousel.vue';
import HomeChapterList from '@/views/home/components/HomeChapterList.vue';
import { useHead } from '@vueuse/head';

useHead({
  title: 'Home - Verspiel',
  meta: [
    {
      name: 'description',
      content: 'Verspiel is a web novel site',
    },
  ],
});
</script>
