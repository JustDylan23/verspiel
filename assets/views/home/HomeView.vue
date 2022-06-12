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
      content:
        'Verspiel is your next platform for reading web novels. Sign up now for access to commenting and join the community. Don’t forget to join the Discord for discussions.',
    },
  ],
});
</script>
